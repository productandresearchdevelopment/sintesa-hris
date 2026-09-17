<?php

namespace App\Controllers\Admins\Helpdesks;

use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use App\Libraries\Notifier;
use App\Models\Helpdesks\Helpdesk;
use App\Models\Helpdesks\HelpdeskAnswer as Mod;
use App\Models\Helpdesks\HelpdeskAnswerFile;
use App\SystemModels\Globals\Upload as Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HelpdeskAnswer extends Controller
{
    public function getAllByHelpdeskId(Request $request, $id = null)
    {
        return response()->json([
            'data' => Mod::where('helpdesk_id', $id)->with('user_mentions', 'uploads', 'createdBy', 'updatedBy', 'deletedBy')->get(),
            'message' => 'Data fetched successfully',
            'success' => true
        ], 200);
    }

    public function get(Request $request, $id = null)
    {
        return Mod::where('id', $id)->with('helpdesk', 'user_mentions', 'uploads', 'createdBy', 'updatedBy', 'deletedBy')->first();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'helpdesk_id' => 'required|exists:iq_helpdesk,id',
            'message' => 'required|string',
            'files.*' => 'nullable|file',
            'user_mentions' => 'nullable',
        ], [
            'helpdesk_id.exists' => 'Helpdesk not found',
            'helpdesk_id.required' => 'Helpdesk is required',
            'message.required' => 'Message is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $userMentions = null;

        if ($request->filled('user_mentions')) {
            $userMentions = json_decode($request->input('user_mentions'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Invalid user_mentions format', 'success' => false], 422);
            }
        }

        DB::beginTransaction();
        try {
            $data = $request->only(['helpdesk_id', 'message']);
            $answer = Mod::create($data);

            $helpdesk = Helpdesk::find($answer->helpdesk_id);
            $helpdesk->last_answer_id = $answer->id;
            $helpdesk->status = 'discussed';
            $helpdesk->save();

            if ($request->hasFile('files')) {
                $fileIds = FileUpload::upload('files', 'helpdesk-answer');

                if ($fileIds) {
                    foreach ($fileIds as $fileId) {
                        $uploadedFile = Uploads::find($fileId);

                        if ($uploadedFile) {
                            HelpdeskAnswerFile::create([
                                'upload_id' => $uploadedFile->id,
                                'helpdesk_answer_id' => $answer->id
                            ]);
                        } else {
                            throw new \Exception('Uploaded file not found');
                        }
                    }
                } else {
                    throw new \Exception('File upload failed');
                }
            }

            if ($userMentions) {
                $answer->user_mentions()->sync($userMentions);
                Notifier::sendToMany($userMentions, 'New helpdesk answer', 'helpdesk_answer', 'You have a new helpdesk answer');
            }

            DB::commit();
            return response()->json([
                'data' => $answer,
                'message' => "Data created successfully",
                'success' => true
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create data: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function update(Request $request, int|string $id)
    {
        $validator = Validator::make($request->all(), [
            'helpdesk_id' => 'required|exists:iq_helpdesk,id',
            'message' => 'required|string',
            'existing_files.*' => 'nullable|exists:uploads,id',
            'files.*' => 'nullable|file',
            'user_mentions' => 'nullable',
        ], [
            'helpdesk_id.exists' => 'Helpdesk not found',
            'helpdesk_id.required' => 'Helpdesk is required',
            'message.required' => 'Message is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $answer = Mod::find($id);

        if (!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }

        DB::beginTransaction();
        try {
            if ($request->has('helpdesk_id')) {
                $answer->helpdesk_id = $request->input('helpdesk_id');
            }

            if ($request->has('message')) {
                $answer->message = $request->input('message');
            }

            $answer->save();

            $existingFileIds = $request->input('existing_files', []);

            if (is_string($existingFileIds)) {
                $existingFileIds = json_decode($existingFileIds, true);
            }

            if (!is_array($existingFileIds)) {
                $existingFileIds = [];
            }

            $currentFileIds = HelpdeskAnswerFile::where('helpdesk_answer_id', $answer->id)->pluck('upload_id')->toArray();

            $filesToKeep = array_intersect($currentFileIds, $existingFileIds);
            $filesToRemove = array_diff($currentFileIds, $filesToKeep);

            if (!empty($filesToRemove)) {
                HelpdeskAnswerFile::whereIn('upload_id', $filesToRemove)->forceDelete();
                FileUpload::removeFileById($filesToRemove);
            }

            if ($request->hasFile('files')) {
                $fileIds = FileUpload::upload('files', 'helpdesk-answer');

                if ($fileIds) {
                    foreach ($fileIds as $fileId) {
                        $uploadedFile = Uploads::find($fileId);

                        if ($uploadedFile) {
                            HelpdeskAnswerFile::create([
                                'helpdesk_answer_id' => $answer->id,
                                'upload_id' => $uploadedFile->id,
                            ]);
                        } else {
                            throw new \Exception('Uploaded file not found');
                        }
                    }
                } else {
                    throw new \Exception('File upload failed');
                }
            }

            if ($request->filled('user_mentions')) {
                $userMentions = json_decode($request->input('user_mentions'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid user_mentions format');
                }

                $answer->user_mentions()->sync($userMentions);
            } else {
                $answer->user_mentions()->detach();
            }

            DB::commit();
            return response()->json(['data' => $answer, 'message' => "Data updated successfully", 'success' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_answer_id' => 'required|exists:iq_helpdesk_answer,id',
            'soft_delete' => 'nullable|boolean',
        ], [
            'last_answer_id.exists' => 'Helpdesk answer not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $answer = Mod::find($request->input('last_answer_id'));
        if (!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }

        $helpdesk = Helpdesk::find($answer->helpdesk_id);
        if (!$helpdesk) {
            return response()->json([
                'success' => false,
                'message' => 'Helpdesk not found.'
            ], 404);
        }

        $softDelete = $request->input('soft_delete', false);
        DB::beginTransaction();

        try {
            $remainingAnswers = Mod::where('helpdesk_id', $helpdesk->id)
                ->where('id', '!=', $answer->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $helpdesk->last_answer_id = $remainingAnswers ? $remainingAnswers->id : null;
            $helpdesk->save();

            $files = HelpdeskAnswerFile::where('helpdesk_answer_id', $answer->id)
                ->get(['helpdesk_answer_id', 'upload_id']);

            $uploadIds = $files->pluck('upload_id')->toArray();

            if ($softDelete) {
                if ($files->isNotEmpty()) {
                    HelpdeskAnswerFile::whereIn('upload_id', $uploadIds)
                        ->where('helpdesk_answer_id', $answer->id)
                        ->delete();
                }

                $answer->delete();
            } else {
                if ($files->isNotEmpty()) {
                    HelpdeskAnswerFile::whereIn('upload_id', $uploadIds)
                        ->where('helpdesk_answer_id', $answer->id)
                        ->forceDelete();
                }

                if (!empty($uploadIds)) {
                    FileUpload::removeFileById($uploadIds);
                }

                $answer->forceDelete();
                $answer->user_mentions()->detach();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete data: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
