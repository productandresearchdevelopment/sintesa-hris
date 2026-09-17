<?php

namespace App\Controllers\Admins\Bulletins;

use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use App\Libraries\Query;
use App\Models\Bulletins\Bulletin as BulletinModel;
use App\Models\Bulletins\BulletinCategory as BulletinModelCategory;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bulletin extends Controller
{
    private function prepareBulletinIndexParams(Request $request): array
    {
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer', 'administrator']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user->organization)->company_id ?? optional($user)->company_id;

        $catQuery = BulletinModelCategory::query();
        if (!$isSuperUser && $userCompany) {
            $catQuery->where(function ($q) use ($userCompany) {
                $q->whereHas('organizations', function ($orgQ) use ($userCompany) {
                    $orgQ->where('company_id', $userCompany);
                })
                    ->orWhereHas('createdBy.employee', function ($empQ) use ($userCompany) {
                        $empQ->where('company_id', $userCompany);
                    })
                    ->orWhere(function ($subQ) {
                        $subQ->doesntHave('organizations')
                            ->where(function ($authorQ) {
                                $authorQ->whereNull('created_by')
                                    ->orWhereHas('createdBy.role', function ($roleQ) {
                                        $roleQ->whereIn('id', [1, 10, 11]);
                                    });
                            });
                    });
            });
        }

        $categories = $catQuery->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name
            ];
        });

        return [
            'category' => $categories,
            'user' => $user
        ];
    }

    public function index(Request $request): View
    {
        $params = $this->prepareBulletinIndexParams($request);
        $user = $params['user'];

        $view = in_array($user?->role?->name, ['DEVELOPER', 'SUPERADMIN', 'ADMINISTRATOR', 'HRGA'])
            ? '_bak.bulletin.main'
            : '_front.bulletin.index';

        return view($view, $params);
    }

    public function index_mobile(Request $request): View
    {
        $params = $this->prepareBulletinIndexParams($request);
        return view('_front.bulletin.mobile', $params);
    }


    public function view(Request $request, $id = null)
    {
        if ($data = BulletinModel::find($id)) {
            $user = $request->user();
            $author = User::find($data->created_by);
            if ($author) {
                $data->author = [
                    'id' => $author->id,
                    'name' => $author->name,
                    'photo' => $author->photo ? route('file', $author->photo->id) : null
                ];
            } else {
                $data->author = null;
            }

            $params = [
                'user' => $user,
                'data' => $data
            ];

            if (isMobile()) {
                $params['id'] = $id;
                return view('_front.bulletin.detail-mobile', $params);
            } else {
                return view('_bak.bulletin.detail', $params);
            }
        }

        abort('404');
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer', 'administrator']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user->organization)->company_id ?? optional($user)->company_id;

        $searchFields = ['title', 'description'];

        $query = BulletinModel::with([
            'category',
            'category.organizations',
            'cover_image',
            'created_by'
        ]);

        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereHas('category.organizations', function ($orgQ) use ($userCompany) {
                    $orgQ->where('company_id', $userCompany);
                })
                    ->orWhereHas('created_by.employee', function ($empQ) use ($userCompany) {
                        $empQ->where('company_id', $userCompany);
                    })
                    ->orWhere(function ($subQ) {
                        $subQ->whereHas('category', function ($catQ) {
                            $catQ->doesntHave('organizations')
                                ->where(function ($authorQ) {
                                    $authorQ->whereNull('created_by')
                                        ->orWhereHas('createdBy.role', function ($roleQ) {
                                            $roleQ->whereIn('id', [1, 10, 11]);
                                        });
                                });
                        });
                    });
            });
        }

        if ($request->has('category') && !is_null($request->category)) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('pinned') && !is_null($request->pinned) && $request->pinned == 1) {
            $query->where('is_pinned', 1);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $query->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc');

        $result = Query::open($query, $searchFields);

        foreach ($result['data'] as $item) {
            if (isset($item->created_by) && $item->created_by) {
                $photoId = $item->cover_image_id ? $item->cover_image->id : null;
                if ($photoId) {
                    $item['cover'] = route('file', $photoId);
                } else {
                    $item['cover'] = null;
                }
            } else {
                $item['cover'] = null;
            }
        }

        return response()->json($result);
    }
    public function create(Request $request)
    {
        $rules = [
            'title'       => 'required|string',
            'category_id' => 'required|integer|exists:iq_bulletin_category,id',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'is_pinned'   => 'nullable|boolean',
        ];

        if ($request->hasFile('file')) {
            $rules['file'] = 'nullable|mimes:jpeg,jpg,png,svg,gif,webp|max:5120';
        }

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => 'Judul harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.integer' => 'ID kategori tidak valid.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'file.mimes' => 'Format file harus berupa jpeg, jpg, png, svg, gif, atau webp.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
            'content.required' => 'Konten harus diisi.',
            'content.string' => 'Konten harus berupa teks.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                'errors' => $validator->errors()
            ], 422);
        }

        $cover = null;

        if ($request->hasFile('file')) {
            $cover = FileUpload::upload('file', 'cover-bulletin');

            if (!$cover) {
                return response()->json(['success' => false, 'message' => 'Cover image upload failed']);
            }
        }

        $file = $cover ? Upload::find($cover) : null;

        $description = $this->extractDescription($request->input('content'), $request->input('description'));

        $bulletin = BulletinModel::create([
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'cover_image_id' => $file ? $file->id : null,
            'content' => $request->input('content'),
            'description' => $description,
            'is_pinned' => $request->input('is_pinned') ? 1 : 0,
        ]);

        return response()->json(['success' => true, 'data' => $bulletin, 'message' => 'Success create bulletin']);
    }

    public function edit(Request $request)
    {
        $rules = [
            'id'          => 'required|integer|exists:iq_bulletin,id',
            'title'       => 'required|string',
            'category_id' => 'required|integer|exists:iq_bulletin_category,id',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'is_pinned'   => 'nullable|boolean',
        ];

        if ($request->hasFile('file')) {
            $rules['file'] = 'nullable|mimes:jpeg,jpg,png,svg,gif,webp|max:5120';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $bulletin = BulletinModel::find($request->input('id'));
        if (!$bulletin) {
            return response()->json([
                'success' => false,
                'message' => 'Bulletin not found',
            ], 404);
        }

        $description = $this->extractDescription(
            $request->input('content'),
            $request->input('description')
        );

        $updateData = [
            'title'       => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'content'     => $request->input('content'),
            'description' => $description,
            'is_pinned'   => $request->input('is_pinned'),
        ];

        if ($request->hasFile('file')) {
            if ($bulletin->cover_image_id) {
                FileUpload::removeFileById([$bulletin->cover_image_id]);
            }

            $cover = FileUpload::upload('file', 'cover-bulletin');
            if (!$cover) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cover image upload failed',
                ]);
            }

            $newFile = Upload::find($cover);
            if (!$newFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload file not found',
                ]);
            }

            $updateData['cover_image_id'] = $newFile->id;
        }

        $bulletin->update($updateData);

        return response()->json([
            'success' => true,
            'data'    => $bulletin,
            'message' => 'Bulletin updated successfully',
        ]);
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = BulletinModel::where('id', $id)->withTrashed()->first()) {
                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    // Fungsi restore untuk mengembalikan data yang dihapus secara soft delete
    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = BulletinModel::withTrashed()->find($id)) {
                    $rec->restore();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function forcedelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = BulletinModel::where('id', $id)->withTrashed()->first()) {
                    $rec->forcedelete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }

        return ['success' => false, 'message' => 'No Data!'];
    }


    public function get(Request $request, $id)
    {
        $rules = ['id' => 'required|integer|exists:iq_bulletin,id'];
        $validator = Validator::make(['id' => $id], $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $bulletin = BulletinModel::with('category')->find($id);
        if (!$bulletin) {
            return response()->json(['error' => 'Bulletin not found'], 404);
        }

        return response()->json(['data' => $bulletin, 'success' => true]);
    }

    public function setIsPin(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        foreach ($data as $item) {
            $id = $item['id'];
            $isPinned = $item['is_pinned'];

            BulletinModel::where('id', $id)->update([
                'is_pinned' => $isPinned,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bulletin pin status updated.',
        ]);
    }


    private function extractDescription($content, $inputDescription = null)
    {
        if (!is_null($inputDescription) && strlen(trim($inputDescription)) > 0) {
            return $inputDescription;
        }

        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = str_replace("\xc2\xa0", ' ', $content);
        $content = preg_replace('/\s+/', ' ', $content);

        $content = preg_replace('/<(?!span|p)(.*?)>/', '', $content);

        preg_match_all('/<(span|p)>(.*?)<\/\1>/', $content, $matches);
        $paragraphs = array_filter(array_map('trim', $matches[2]));

        $description = isset($paragraphs[0]) ? $paragraphs[0] : '';
        if (strlen($description) < 100 && isset($paragraphs[1])) {
            $description .= ' ' . $paragraphs[1];
        }

        if (strlen(trim($description)) == 0) {
            $description = strip_tags($content);
        }

        return substr($description, 0, 100) . '...';
    }
}
