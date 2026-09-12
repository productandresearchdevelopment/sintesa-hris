<?php

namespace App\Controllers\Front\Chatbots;

use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Helpdesks\Helpdesk;
use App\Models\Helpdesks\HelpdeskAnswer;
use App\Models\Helpdesks\HelpdeskCategory;
use App\Models\Helpdesks\HelpdeskFile;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload as Uploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Chatbot extends Controller
{
    private $intents = [
        'create' => [
            'buatkan data ticket',
            'buat ticket',
            'create ticket',
            'bikin ticket',
            'tambah ticket',
            'add ticket',
            'buatkan saya ticket',
            'buatkan tiket',
            'buat tiket',
            'create tiket',
            'bikin tiket',
            'tambah tiket',
            'add tiket',
            'buatkan saya tiket'
        ],
        'update' => [
            'mengubah data ticket',
            'ubah ticket',
            'update ticket',
            'edit ticket',
            'perbarui ticket',
            'edit ticket saya',
            'mengubah data tiket',
            'ubah tiket',
            'update tiket',
            'edit tiket',
            'perbarui tiket',
            'edit tiket saya',
            'ganti ticket',
            'ganti tiket',
            'ubah data ticket',
            'ubah data tiket',
            'modifikasi ticket',
            'modifikasi tiket'
        ],
        'close' => [
            'menghapus data ticket',
            'hapus ticket',
            'close ticket',
            'remove ticket',
            'hapus ticket saya',
            'menghapus data tiket',
            'hapus tiket',
            'close tiket',
            'remove tiket',
            'hapus tiket saya',
            'delete ticket',
            'delete tiket',
            'tutup ticket',
            'tutup tiket',
            'hilangkan ticket',
            'hilangkan tiket'
        ]
    ];

    public function handleChat(Request $request)
    {
        $message = strtolower($request->input('message'));
        $session = Session::get('chat_stage', []);

        if (Session::get('chat_stage.action') === 'finalize') {
            if (stripos($message, 'ya') !== false) {
                Session::forget('chat_stage');
                return response()->json([
                    'reply' => 'Apa yang kau perlukan hari ini?',
                    'type' => 'default'
                ]);
            } elseif (stripos($message, 'tidak') !== false) {
                Session::forget('chat_stage');
                return response()->json([
                    'reply' => 'Terima kasih atas kunjungan Anda! Jika butuh bantuan lagi, jangan ragu untuk bertanya.',
                    'type' => 'close'
                ]);
            }
        }

        if (empty($session)) {
            foreach ($this->intents as $action => $phrases) {
                foreach ($phrases as $phrase) {
                    if (stripos($message, $phrase) !== false) {
                        Session::put('chat_stage', ['action' => $action, 'step' => 1]);
                        if ($action === 'update' || $action === 'close') {
                            return $this->handleTicketSelection($action);
                        } else {
                            return response()->json(['reply' => $this->getInitialResponse($action)]);
                        }
                    }
                }
            }
            return $this->askHuggingFace($message);
        }

        switch ($session['action']) {
            case 'create':
                return $this->handleCreateTicket($message, $request, $session);
            case 'update':
                return $this->handleUpdateTicket($message, $request, $session);
            case 'close':
                return $this->handleDeleteTicket($message, $request, $session);
        }

        return $this->askHuggingFace($message);
    }

    private function getInitialResponse($action)
    {
        switch ($action) {
            case 'create':
                return 'Saya akan membuatkan data ticket. Tolong beri tahu judul ticketnya.';
            case 'update':
                return $this->handleTicketSelection($action);
            case 'close':
                return $this->handleTicketSelection($action);
        }
    }

    private function handleTicketSelection($action)
    {
        $user = auth()->user();
        $tickets = Helpdesk::where('created_by', $user->id)->get(['id', 'title']);

        if ($tickets->isEmpty()) {
            Session::forget(['chat_stage', 'chat_data']);
            Session::put('chat_stage', ['action' => 'finalize']);

            return [
                'reply' => "Anda tidak memiliki tiket untuk {$action}.",
                'data' => [],
                'type' => 'view'
            ];
        }

        Session::put('chat_data.tickets', $tickets);

        return [
            'reply' => "Berikut adalah daftar tiket Anda. Silakan pilih ID tiket yang ingin Anda {$action}.",
            'data' => $tickets,
            'type' => 'view'
        ];
    }


    private function handleCreateTicket($message, $request, $session)
    {
        switch ($session['step']) {
            case 1:
                Session::put('chat_stage.step', 2);
                Session::put('chat_data.title', $message);
                return response()->json([
                    'reply' => 'Terima kasih. Apa isi pesan ticket ini?',
                    'type' => 'default',
                ]);

            case 2:
                Session::put('chat_stage.step', 3);
                Session::put('chat_data.message', $message);
                return response()->json([
                    'reply' => 'Silakan pilih kategori untuk ticket ini:',
                    'type' => 'category',
                    'categories' => HelpdeskCategory::all()->pluck('name')->toArray(),
                ]);

            case 3:
                $categoriesLowerCase = HelpdeskCategory::all()->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                $categories = HelpdeskCategory::all()->pluck('name')->toArray();

                if (in_array($message, $categoriesLowerCase)) {
                    $selectedCategory = HelpdeskCategory::where('name', $message)->first();
                    Session::put('chat_data.category', $selectedCategory->id);
                    Session::put('chat_stage.step', 4);

                    return response()->json([
                        'reply' => 'Apakah ada yang ingin Anda mention? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default',
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Kategori tidak valid. Silakan pilih ulang kategori untuk ticket ini.',
                        'type' => 'category',
                        'categories' => $categories
                    ]);
                }

            case 4:
                if (stripos($message, 'ya') !== false) {
                    $users = User::all()->pluck('name')->toArray();
                    Session::put('chat_stage.step', 5);
                    return response()->json([
                        'reply' => 'Berikut daftar user:',
                        'type' => 'mention',
                        'users' => $users,
                    ]);
                } elseif (stripos($message, 'tidak') !== false) {
                    Session::put('chat_stage.step', 6);
                    return response()->json([
                        'reply' => 'Apakah ada file yang ingin Anda kirim? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default',
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Saya tidak mengerti jawaban Anda. Apakah ada yang ingin Anda mention? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default',
                    ]);
                }

            case 5:
                $usersLowerCase = User::all()->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                $users = User::all()->pluck('name')->toArray();

                $mentions = array_map('trim', explode(',', strtolower($message)));
                $invalidUsers = array_diff($mentions, $usersLowerCase);

                if (empty($invalidUsers)) {
                    $mentionedUsers = User::whereIn(DB::raw('LOWER(name)'), $mentions)->pluck('id')->toArray();

                    Session::put('chat_data.mentions', $mentionedUsers);
                    Session::put('chat_stage.step', 6);

                    return response()->json([
                        'reply' => 'Apakah ada file yang ingin Anda kirim? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default',
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Pengguna dengan nama: ' . implode(', ', $invalidUsers) . ' tidak valid. Silakan pilih ulang pengguna untuk ticket ini.',
                        'type' => 'mention',
                        'users' => $users,
                    ]);
                }

            case 6:
                if (stripos($message, 'ya') !== false) {
                    Session::put('chat_stage.step', 7);
                    return response()->json([
                        'reply' => 'Silakan unggah file Anda.',
                        'type' => 'file',
                    ]);
                } elseif (stripos($message, 'tidak') !== false) {
                    return $this->finalizeCreate($request);
                } else {
                    return response()->json([
                        'error' => 'Saya tidak mengerti jawaban Anda. Apakah ada file yang ingin Anda kirim? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default',
                    ]);
                }

            case 7:
                if ($request->hasFile('files')) {
                    $files = $request->file('files');
                    $uploadedFiles = [];

                    if (count($files) > 4) {
                        return response()->json([
                            'error' => 'Ups!, File terlalu banyak. Maksimal 4 file yang dapat diunggah. Silakan unggah ulang.',
                            'type' => 'file',
                        ]);
                    }

                    $uploadedFiles = FileUpload::upload('files', 'chatbot');

                    Session::put('chat_data.files', $uploadedFiles);
                    return $this->finalizeCreate($request);
                } else {
                    return response()->json([
                        'error' => 'Tidak ada file yang terdeteksi. Silakan unggah ulang.',
                        'type' => 'file',
                    ]);
                }
        }
    }

    private function finalizeCreate($request)
    {
        $data = Session::get('chat_data');

        $ticket = Helpdesk::create([
            'title' => $data['title'],
            'category_id' => $data['category'],
            'message' => $data['message'],
            'status' => 'pending'
        ]);

        if (!empty($data['files'])) {
            foreach ($data['files'] as $file) {
                $uploadedFile = Uploads::find($file);
                if ($uploadedFile) {
                    HelpdeskFile::create([
                        'upload_id' => $uploadedFile->id,
                        'helpdesk_id' => $ticket->id
                    ]);
                } else {
                    throw new \Exception('Uploaded file not found');
                }
            }
        }

        if (!empty($data['mentions'])) {
            $ticket->user_mentions()->sync($data['mentions']);
        }

        Session::forget(['chat_stage', 'chat_data']);
        Session::put('chat_stage', ['action' => 'finalize']);

        return response()->json([
            'reply' => "Ticket berhasil dibuat dengan ID {$ticket->id}. Apakah ada yang bisa saya bantu lagi? (Ketik 'Ya' atau 'Tidak')",
            'type' => 'finalize'
        ]);
    }

    private function handleUpdateTicket($message, $request, $session)
    {
        switch ($session['step']) {
            case 1:
                $ticket = Helpdesk::with(['category', 'uploads', 'user_mentions'])->where('id', $message)->first();

                if ($ticket) {
                    Session::put('chat_data.ticket', $ticket);
                    Session::put('chat_stage.step', 2);

                    return response()->json([
                        'reply' => "Berikut data tiket Anda:",
                        'data' => [
                            'title' => $ticket->title,
                            'message' => $ticket->message,
                            'category' => $ticket->category ? $ticket->category->name : 'Tidak ada kategori',
                            'mentions' => $ticket->user_mentions->isNotEmpty()
                                ? $ticket->user_mentions->pluck('name')->implode(', ')
                                : 'Tidak ada mention',
                            'files' => $ticket->uploads->isNotEmpty()
                                ? $ticket->uploads->pluck('filename_origin')->implode(', ')
                                : 'Tidak ada file'
                        ],
                        'type' => 'view'
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Ticket tidak ditemukan. Silakan masukkan ID tiket yang valid.',
                        'type' => 'default'
                    ]);
                }

            case 2:
                if (stripos($message, 'ya') !== false) {
                    Session::put('chat_stage.step', 3);
                    return response()->json([
                        'reply' => 'Baik. Apa judul baru untuk tiket ini?',
                        'type' => 'default'
                    ]);
                } elseif (stripos($message, 'tidak') !== false) {
                    Session::forget(['chat_stage', 'chat_data']);
                    return response()->json([
                        'reply' => 'Baik, tiket tidak diperbarui. Apa yang ingin Anda lakukan selanjutnya?',
                        'type' => 'default'
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Saya tidak mengerti jawaban Anda. Ketik "Ya" untuk lanjut atau "Tidak" untuk membatalkan.',
                        'type' => 'default'
                    ]);
                }

            case 3:
                Session::put('chat_data.update.title', $message);
                Session::put('chat_stage.step', 4);
                return response()->json([
                    'reply' => 'Apa isi pesan baru untuk tiket ini?',
                    'type' => 'default'
                ]);

            case 4:
                Session::put('chat_data.update.message', $message);
                Session::put('chat_stage.step', 5);
                return response()->json([
                    'reply' => 'Silakan pilih kategori baru untuk tiket ini:',
                    'type' => 'category',
                    'categories' => HelpdeskCategory::all()->pluck('name')->toArray()
                ]);

            case 5:
                $categories = HelpdeskCategory::all()->pluck('name')->map('strtolower')->toArray();
                if (in_array(strtolower($message), $categories)) {
                    $category = HelpdeskCategory::whereRaw('LOWER(name) = ?', [strtolower($message)])->first();
                    Session::put('chat_data.update.category', $category->id);
                    Session::put('chat_stage.step', 6);
                    return response()->json([
                        'reply' => 'Apakah ada yang ingin Anda mention? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default'
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Kategori tidak valid. Silakan pilih kategori yang tersedia.',
                        'type' => 'category',
                        'categories' => HelpdeskCategory::all()->pluck('name')->toArray()
                    ]);
                }

            case 6:
                if (stripos($message, 'ya') !== false) {
                    $users = User::all()->pluck('name')->toArray();
                    Session::put('chat_stage.step', 7);
                    return response()->json([
                        'reply' => 'Berikut daftar user yang dapat Anda mention:',
                        'type' => 'mention',
                        'users' => $users
                    ]);
                } elseif (stripos($message, 'tidak') !== false) {
                    Session::put('chat_stage.step', 8);
                    return response()->json([
                        'reply' => 'Apakah ada file yang ingin Anda perbarui? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default'
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Jawaban tidak dimengerti. Ketik "Ya" atau "Tidak".',
                        'type' => 'default'
                    ]);
                }

            case 7:
                $users = User::all()->pluck('name')->map('strtolower')->toArray();
                $mentions = array_map('trim', explode(',', strtolower($message)));
                $invalid = array_diff($mentions, $users);

                if (empty($invalid)) {
                    $mentionedUsers = User::whereIn(DB::raw('LOWER(name)'), $mentions)->pluck('id')->toArray();
                    Session::put('chat_data.update.mentions', $mentionedUsers);
                    Session::put('chat_stage.step', 8);
                    return response()->json([
                        'reply' => 'Apakah ada file yang ingin Anda perbarui? (Ketik "Ya" atau "Tidak")',
                        'type' => 'default'
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Pengguna berikut tidak valid: ' . implode(', ', $invalid),
                        'type' => 'mention',
                        'users' => User::all()->pluck('name')->toArray()
                    ]);
                }

            case 8:
                if (stripos($message, 'ya') !== false) {
                    Session::put('chat_stage.step', 9);
                    return response()->json([
                        'reply' => 'Silakan unggah file baru Anda.',
                        'type' => 'file'
                    ]);
                } else {
                    return $this->finalizeUpdate();
                }

            case 9:
                if ($request->hasFile('files')) {
                    $files = FileUpload::upload('files', 'chatbot');
                    Session::put('chat_data.update.files', $files);
                    return $this->finalizeUpdate();
                } else {
                    return response()->json([
                        'error' => 'Tidak ada file terdeteksi. Silakan unggah ulang.',
                        'type' => 'file'
                    ]);
                }
        }
    }

    private function finalizeUpdate()
    {
        $data = Session::get('chat_data');
        $updates = $data['update'];
        $ticket = $data['ticket'];

        $ticket->update([
            'title' => $updates['title'] ?? $ticket->title,
            'message' => $updates['message'] ?? $ticket->message,
            'category_id' => $updates['category'] ?? $ticket->category_id,
            'status' => $ticket->status != 'pending' ? $ticket->status : 'pending'
        ]);

        if (!empty($updates['files'])) {
            HelpdeskFile::where('helpdesk_id', $ticket->id)->delete();

            foreach ($updates['files'] as $file) {
                HelpdeskFile::create([
                    'helpdesk_id' => $ticket->id,
                    'upload_id' => $file
                ]);
            }
        }

        if (!empty($updates['mentions'])) {
            $ticket->user_mentions()->sync($updates['mentions']);
        }

        Session::forget(['chat_stage', 'chat_data']);
        Session::put('chat_stage', ['action' => 'finalize']);

        return response()->json([
            'reply' => "Ticket berhasil diperbarui. Apakah ada yang bisa saya bantu lagi? (Ketik 'Ya' atau 'Tidak')",
            'type' => 'finalize'
        ]);
    }

    private function handleDeleteTicket($message, $request, $session)
    {
        switch ($session['step']) {
            case 1:
                $ticket = Helpdesk::with(['category', 'uploads', 'user_mentions'])->find($message);

                if ($ticket) {
                    Session::put('chat_data.ticket', $ticket);
                    Session::put('chat_stage.step', 2);

                    return response()->json([
                        'reply' => "Berikut data tiket Anda:",
                        'data' => [
                            'title' => $ticket->title,
                            'message' => $ticket->message,
                            'category' => $ticket->category ? $ticket->category->name : 'Tidak ada kategori',
                            'mentions' => $ticket->user_mentions->isNotEmpty()
                                ? $ticket->user_mentions->pluck('name')->implode(', ')
                                : 'Tidak ada mention',
                            'files' => $ticket->uploads->isNotEmpty()
                                ? $ticket->uploads->pluck('filename_origin')->implode(', ')
                                : 'Tidak ada file'
                        ],
                        'type' => 'view'
                    ]);
                }

                return response()->json([
                    'error' => 'Ticket tidak ditemukan. Silakan masukkan ID tiket yang benar.',
                    'type' => 'default',
                ]);

            case 2:
                if (stripos($message, 'ya') !== false) {
                    $ticket = Session::get('chat_data.ticket');

                    $ticket->closed_at = now();
                    $ticket->status = 'closed';
                    $ticket->save();

                    Session::forget(['chat_stage', 'chat_data']);
                    Session::put('chat_stage', ['action' => 'finalize']);

                    return response()->json([
                        'reply' => "Ticket berhasil dihapus. Apakah ada yang bisa saya bantu lagi? (Ketik 'Ya' atau 'Tidak')",
                        'type' => 'default',
                    ]);
                } elseif (stripos($message, 'tidak') !== false) {
                    Session::forget(['chat_stage', 'chat_data']);
                    return response()->json([
                        'reply' => 'Baik, tiket tidak jadi dihapus. Apa yang Anda perlukan hari ini?',
                        'type' => 'default',
                    ]);
                }

                return response()->json([
                    'error' => 'Jawaban tidak valid. Apakah Anda yakin ingin menghapus tiket ini? (Ketik "Ya" atau "Tidak")',
                    'type' => 'default',
                ]);
        }
    }


    public function resetSession()
    {
        Session::forget(['chat_stage', 'chat_data']);
        return response()->json([
            'message' => 'Sesi chat telah direset.',
            'status' => 'success'
        ], 200);
    }

    private function askHuggingFace($message)
    {
        $apiKey = env('HUGGING_FACE_API_KEY');
        $endpoint = 'https://api-inference.huggingface.co/models/google/gemma-1.1-2b-it/v1/chat/completions';

        $data = [
            'model' => 'google/gemma-2-2b-it',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message,
                ]
            ],
            'max_tokens' => 500,
            'stream' => false,
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post($endpoint, $data);

        if ($response->successful()) {
            $responseData = $response->json();
            $reply = $responseData['choices'][0]['message']['content'] ?? 'Tidak ada balasan dari model.';
        } else {
            $reply = 'Terjadi kesalahan saat menghubungi model.';
        }

        return response()->json(['reply' => $reply]);
    }
}
