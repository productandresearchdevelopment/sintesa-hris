<?php

namespace App\Controllers\Admins\FileManagers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Systems\User;
use App\Libraries\FileUpload;
use App\Libraries\Notifier;
use App\Libraries\Query;
use App\Models\FileManager as ModelsFileManager;
use App\Models\Organization;
use App\SystemModels\Auth\User as AuthUser;
use App\SystemModels\Globals\Upload as ModelsUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class FileManager extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $parentFolder = ModelsFileManager::find(1);
        $parentorganization = Organization::find(1);
        $organizations = Organization::all();
        $users = AuthUser::all();
        $params = [
            'user' => $user,
            'users' => $users,
            'parentFolder' => $parentFolder,
            'folder' => ModelsFileManager::where('type', 1)->get(),
            'parentorganization' => $parentorganization,
            'organization' => $organizations
        ];

        if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
            $view = isMobile() ? '_front.filemanager.mobile' : '_front.filemanager.index';
            return view($view, $params);
        } else {
            $view = isMobile() ? '_front.filemanager.mobile' : '_bak.filemanager.main';
            return view($view, $params);
        }
    }

    // public function data(Request $request)
    // {
    //     $query = ModelsFileManager::with([
    //         'file',
    //         'parent',
    //         'childs',
    //         'organizations',
    //         'users',
    //         'createdBy',
    //         'updatedBy',
    //         'deletedBy',
    //     ])->where('type', 2);

    //     $query->addSelect([
    //         'size' => DB::table('uploads')
    //             ->select('size')
    //             ->whereColumn('uploads.id', 'iq_filemanager.file_id')
    //             ->limit(1),
    //     ]);

    //     $query->addSelect([
    //         'folder_name' => DB::table('iq_filemanager as parent')
    //             ->select('name')
    //             ->whereColumn('parent.id', 'iq_filemanager.parent_id')
    //             ->limit(1)
    //     ]);

    //     $searchFields = [
    //         'name',
    //         'path',
    //         'extension',
    //     ];

    //     if ($folder = $request->input('filter-folder')) {
    //         if ($folder = ModelsFileManager::find($folder)) {
    //             if ($request->input('only-current-folder')) {
    //                 $query->where('parent_id', $folder->id);
    //             } else {
    //                 $query->where('path', 'LIKE', $folder->path . '%');
    //             }
    //         }
    //     }

    //     $trash = $request->input('trash');

    //     if ($trash === null || $trash === '' || $trash === 'all trash') {
    //         $query->withTrashed();
    //     } elseif ($trash == 1) {
    //         $query->withoutTrashed();
    //     } elseif ($trash == 2) {
    //         $query->onlyTrashed();
    //     }

    //     if ($fileType = $request->input('file-type')) {
    //         $this->applyFileTypeFilter($query, $fileType);
    //     }

    //     // dd(Query::open($query, $searchFields));

    //     return Query::open($query, $searchFields);
    // }

    public function data(Request $request)
    {
        $query = ModelsFileManager::with([
            'file',
            'parent',
            'childs',
            'organizations',
            'users',
            'createdBy',
            'updatedBy',
            'deletedBy',
        ])->where('type', 2); // Hanya ambil tipe file

        $query->addSelect([
            'size' => DB::table('uploads')
                ->select('size')
                ->whereColumn('uploads.id', 'iq_filemanager.file_id')
                ->limit(1),
        ]);

        $query->addSelect([
            'folder_name' => DB::table('iq_filemanager as parent')
                ->select('name')
                ->whereColumn('parent.id', 'iq_filemanager.parent_id')
                ->limit(1),
        ]);

        $searchFields = ['name', 'path', 'extension'];

        if ($folder = $request->input('filter-folder')) {
            if ($folder = ModelsFileManager::find($folder)) {
                $query->where('path', 'LIKE', $folder->path . '%');
            }
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($fileType = $request->input('sort-extension')) {
            $query->where('extension', $fileType);
        }

        // Trash Filter
        $trash = $request->input('trash');
        if ($trash === null || $trash === '' || $trash === 'all trash') {
            $query->withTrashed();
        } elseif ($trash == 1) {
            $query->withoutTrashed();
        } elseif ($trash == 2) {
            $query->onlyTrashed();
        }
        $query->orderBy('created_at', 'desc');

        return Query::open($query, $searchFields);
    }


    public function dataOrganizations(Request $request, $filemanagerId = null)
    {
        if ($filemanagerId) {
            $data = $this->treeFiles($filemanagerId);
            return response()->json($data);
        }
    }

    public function dataUsers(Request $request, $filemanagerId = null)
    {
        if ($filemanagerId) {
            $data = ModelsFileManager::where('id', $filemanagerId)->first();
            return response()->json($data->users);
        }
    }

    protected function applyFileTypeFilter($query, $fileType)
    {
        $fileExtensions = [];

        switch ($fileType) {
            case 'document':
                $fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
                break;
            case 'image':
                $fileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                break;
            case 'video':
                $fileExtensions = ['mp4', 'avi', 'mov', 'mkv'];
                break;
            case 'audio':
                $fileExtensions = ['mp3', 'wav', 'ogg'];
                break;
            case 'archive':
                $fileExtensions = ['zip', 'rar', '7z'];
                break;
            case 'link':
                $query->whereNotNull('link');
                return;
        }

        if (!empty($fileExtensions)) {
            $query->whereIn('extension', $fileExtensions);
        }
    }


    public function get(Request $request, $id = null)
    {
        return ModelsFileManager::where('id', $id)->where('type', 2)->with('createdBy', 'updatedBy', 'deletedBy')->first();
    }

    public function pushFile(Request $request)
    {
        if (!$file = FileUpload::upload('file', 'filemanager')) {
            return ['success' => false, 'message' => 'File not found'];
        } else if (!$folder = ModelsFileManager::find($request->input('folder_id'))) {
            return ['success' => false, 'message' => 'Folder not found'];
        } else {
            $file = ModelsUpload::find($file);

            $data = ModelsFileManager::create([
                'file_id' => $file->id,
                'parent_id' => $folder->id,
                'name' => $file->filename_origin,
                'extension' => $file->extension,
                'type_file' => $file->type,
                'type' => 2,
                'path_file' => $file->filename,
                'description' => $request->description,
            ]);

            if ($request->filled('file_name')) {
                $this->setName(new Request([
                    'id' => $data->id,
                    'name' => $request->input('file_name')
                ]));
            }
            $this->setPathFolder($data);

            $user = auth()->user();
            $data->users()->attach($user->id);
            $data->organizations()->attach($user->organization_id);

            return ['success' => true, 'message' => 'Success...'];
        }

        return ['success' => false, 'message' => 'File not found'];
    }

    public function pushLink(Request $request)
    {
        $validatedData = $request->validate([
            'folder_id' => 'required|exists:iq_filemanager,id',
            'name' => 'required|string',
            'link' => 'required|string',
        ]);

        $folder = ModelsFileManager::find($validatedData['folder_id']);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'folder not found',
            ], 400);
        }

        $data = ModelsFileManager::create([
            'parent_id' => $request->input('folder_id'),
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'type_file' => 'link',
            'type' => 2,
            'category' => 'iq_filemanager',
            'watermark' => $request->user()->email
        ]);

        $this->setPathFolder($data);

        return response()->json([
            'success' => true,
            'message' => 'Link uploaded successfully',
            'data' => $data
        ], 200);
    }

    public function updateFile(Request $request)
    {
        $validatedData = $request->validate([
            'folder_id' => 'required|exists:iq_filemanager,id',
            'file_ids' => 'required|string',
        ]);

        $fileIds = explode(',', $validatedData['file_ids']);

        foreach ($fileIds as $id) {
            $upload = ModelsFileManager::find($id);
            if ($upload) {
                $upload->parent_id = $validatedData['folder_id'];
                $upload->save();

                $this->setPathFolder($upload);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Files updated successfully',
        ], 200);
    }

    public function updateLink(Request $request, $id)
    {
        $validatedData = $request->validate([
            'folder_id' => 'required|exists:iq_filemanager,id',
            'name' => 'required|string',
            'link' => 'required|string',
        ]);


        $upload = ModelsFileManager::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $upload->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Link updated successfully',
            'data' => $upload,
        ], 200);
    }

    public function updateOrganizations(Request $request, $id)
    {
        $input  = $request->all();
        $organizations  = json_decode($input['organizations']);
        $data   = ModelsFileManager::find($id);

        $data->organizations()->sync($organizations);

        return response()->json(['success' => true, 'message' => 'Success!!!']);
    }

    public function setOrganization(Request $request, $filemanagerId)
    {
        $input  = $request->all();
        $organization  = $input['organization'];
        $auth = $input['auth'];

        if ($filemanagerId && $organization) {
            $data   = ModelsFileManager::find($filemanagerId);
            $data->organizations()->detach([$organization]);
            if ($auth) $data->organizations()->attach([$organization]);
            return response()->json(['success' => true, 'message' => 'Success!!!']);
        }
        return response()->json(['success' => false, 'message' => 'Organization Not Found']);
    }

    public function setUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileManagerId' => 'required|exists:iq_filemanager,id',
            'userId' => 'required|exists:auth_user,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fileManager = ModelsFileManager::find($request->fileManagerId);

        if ($fileManager->users()->where('user_id', $request->userId)->exists()) {
            return response()->json(['message' => 'User is already added to this file manager.'], 200);
        }

        $fileManager->users()->attach($request->userId);
        Notifier::send($request->userId, 'New File Manager', 'filemanager', 'New File Manager');

        return response()->json(['message' => 'User added successfully.']);
    }


    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileManagerId' => 'required|exists:iq_filemanager,id',
            'userId' => 'required|exists:auth_user,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fileManager = ModelsFileManager::find($request->fileManagerId);

        $fileManager->users()->detach($request->userId);

        return response()->json(['message' => 'User removed successfully.']);
    }

    public function setName(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:iq_filemanager,id',
            'name' => 'required|string',
        ]);

        $upload = ModelsFileManager::find($validatedData['id']);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $upload->update(['name' => $validatedData['name']]);

        return response()->json([
            'success' => true,
            'message' => 'File updated successfully',
            'data' => $upload,
        ], 200);
    }


    public function setTag(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:iq_filemanager,id',
            'tag' => 'required|string',
        ]);

        $upload = ModelsFileManager::find($validatedData['id']);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $upload->update(['tag' => $validatedData['tag']]);

        return response()->json([
            'success' => true,
            'message' => 'File updated successfully',
            'data' => $upload,
        ], 200);
    }

    public function setDesc(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:iq_filemanager,id',
            'description' => 'required|string',
        ]);

        $upload = ModelsFileManager::find($validatedData['id']);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $upload->update(['description' => $validatedData['description']]);

        return response()->json([
            'success' => true,
            'message' => 'File updated successfully',
            'data' => $upload,
        ], 200);
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::where('id', $id)->withTrashed()->first()) {
                    if ($upload = ModelsUpload::where('id', $rec->file_id)->first()) {
                        $upload->delete();
                    }
                    $rec->delete();
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Data Soft-delete Successfully'
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::withTrashed()->where('id', $id)->first()) {
                    $rec->restore();

                    if ($upload = ModelsUpload::withTrashed()->where('id', $rec->file_id)->first()) {
                        $upload->restore();
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => "Data restored successfully",
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "No data provided or invalid format.",
        ], 400);
    }


    public function forceDelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::withTrashed()->where('id', $id)->first()) {

                    $rec->forceDelete();
                    FileUpload::removeFileById($rec->file_id);
                }
            }
            return response()->json([
                'success' => true,
                'message' => "Data deleted successfully",
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "No data provided or invalid format.",
        ], 400);
    }

    // FOLDER CONTROLLER -----------------------------------------------------------------------------------------------

    // public function dataFolder(Request $request)
    // {
    //     $node = $request->input('node');
    //     $level = $request->input('level');
    //     $selected = ModelsFileManager::find($request->input('selected'));
    //     $selectedPath = $selected ? explode('/', $selected->path) : [];

    //     $filter = $request->input('filter');

    //     if ($filter === 'trash') {
    //         $query = ModelsFileManager::onlyTrashed()->where('type', 1)->orderBy('name');
    //     } else {
    //         $query = $node ? ModelsFileManager::where('parent_id', $node) : ModelsFileManager::whereNull('parent_id');

    //         if ($filter === 'active') {
    //             $query->whereNull('deleted_at');
    //         } elseif ($filter === 'all trash') {
    //             $query->withTrashed();
    //         }

    //         $query->orderBy('name')->where('type', 1);
    //     }

    //     $result = $query->get();

    //     foreach ($result as $row) {
    //         $row->text = $row->name;
    //         $row->icon = asset('images/icons/folder.png');

    //         if ($filter === 'trash') {
    //             $row->parent_id = null;
    //         }

    //         if ($filter === 'active') {
    //             $allChildren = ModelsFileManager::where('parent_id', $row->id)->get();
    //         } else {
    //             $allChildren = $row->childs;
    //         }

    //         $hasChildren = $allChildren->where('type', 1)->count();

    //         foreach ($selectedPath as $r) {
    //             if ($r == $row->id) {
    //                 if ($hasChildren) {
    //                     $row->expanded = true;
    //                 }
    //             }
    //         }

    //         $row->leaf = !$hasChildren;
    //     }

    //     return $result;
    // }

    public function dataFolder(Request $request)
    {
        $node = $request->input('node'); // ID parent node
        $level = $request->input('level'); // Level folder yang diminta
        $selected = ModelsFileManager::find($request->input('selected'));
        $selectedPath = $selected ? explode('/', $selected->path) : [];

        $filter = $request->input('filter');

        if ($filter === 'trash') {
            $query = ModelsFileManager::onlyTrashed()->where('type', 1)->orderBy('name');
        } else {
            // Jika ada parameter level, tambahkan ke filter query
            if ($level !== null) {
                $query = ModelsFileManager::where('level', $level);
            } else {
                $query = $node
                    ? ModelsFileManager::where('parent_id', $node)
                    : ModelsFileManager::whereNull('parent_id');
            }

            if ($filter === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($filter === 'all trash') {
                $query->withTrashed();
            }

            $query->orderBy('name')->where('type', 1);
        }

        $result = $query->get();

        foreach ($result as $row) {
            $row->text = $row->name;
            $row->icon = asset('images/icons/folder.png');

            if ($filter === 'trash') {
                $row->parent_id = null;
            }

            // Periksa apakah ada anak untuk menentukan status leaf
            $allChildren = ($filter === 'active')
                ? ModelsFileManager::where('parent_id', $row->id)->get()
                : $row->childs;

            $hasChildren = $allChildren->where('type', 1)->count();

            foreach ($selectedPath as $r) {
                if ($r == $row->id && $hasChildren) {
                    $row->expanded = true;
                }
            }

            $row->leaf = !$hasChildren;
        }

        return response()->json($result);
    }




    public function pushFolder(Request $request, $id = null)
    {
        // dd($request->all());
        if ($parent = $request->input('parent_id')) {
            $input = [
                'parent_id' => $parent,
                'name' => $request->input('name'),
                'type' => 1
            ];

            if ($id) {
                if ($data = ModelsFileManager::find($id)) {
                    $data->update($input);

                    $files = ModelsFileManager::where('parent_id', $data->id)->where('type', 2)->get();

                    foreach ($files as $file) {
                        $upload = ModelsFileManager::find($file->id);
                        if ($upload) {
                            $upload->parent_id = $data->id;
                            $upload->save();

                            $this->setPathFolder($upload);
                        }
                    }
                } else {
                    return ['success' => false, 'message' => 'No Update Data'];
                }
            } else $data = ModelsFileManager::create($input);

            $this->setPathFolder($data);

            $data->leaf = false;
            $data->icon = asset('images/icons/folder.png');

            return [
                'success' => true,
                'message' => 'Success!!!',
                'data' => $data
            ];
        } else abort('500');
    }

    public function deleteFolder(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::where('id', $id)->where('type', 1)->withTrashed()->first()) {
                    ModelsFileManager::where('parent_id', $id)->where('type', 2)->delete();
                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function restoreFolder(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::where('id', $id)->where('type', 1)->withTrashed()->first()) {
                    ModelsFileManager::where('parent_id', $id)->where('type', 2)->withTrashed()->restore();
                    $rec->restore();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function forceDeleteFolder(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = ModelsFileManager::where('id', $id)->withTrashed()->first()) {
                    $rec->forcedelete();
                }
            }
            return response()->json(['success' => true, 'message' => 'File forced deleted successfully'], 200);
        }
        return response()->json(['success' => false, 'message' => 'File cannot be force deleted'], 400);
    }

    private function setPathFolder($data)
    {
        $parent = $data->parent_id;
        $path = '/' . $data->id;
        $level = 1;
        while ($parent) {
            $path = '/' . $parent . $path;
            $parent = ModelsFileManager::find($parent)->parent_id;
            $level++;
        }
        $path = $path . '/';
        $data->update(['path' => $path, 'level' => $level]);
        return $path;
    }

    public function downloadFile(Request $request, $id)
    {
        $ufile = ModelsFileManager::find($id);

        if (!$ufile) {
            return abort(404, 'File not found.');
        }

        $uploadFile = ModelsUpload::find($ufile->file_id);

        if (!$uploadFile) {
            return abort(404, 'File not found on server.');
        }

        $fullPath = $uploadFile->filename;
        $absolutePath = public_path('storage/uploads/' . $fullPath);

        if (!file_exists($absolutePath)) {
            return abort(404, 'File not found on server.');
        }

        $filename = pathinfo($ufile->name, PATHINFO_EXTENSION) ? $ufile->name : $ufile->name . '.' . $uploadFile->extension;

        return response()->download($absolutePath, $filename);
    }

    public function downloadMultipleFiles(Request $request)
    {
        if ($data = json_decode($request->data)) {
            $zip = new ZipArchive();
            $zipFileName = 'files_' . time() . '.zip';
            $tempPath = storage_path('app/public/temp/' . $zipFileName);

            if (!file_exists(storage_path('app/public/temp'))) {
                mkdir(storage_path('app/public/temp'), 0775, true);
            }

            if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                foreach ($data as $id) {
                    $file = ModelsFileManager::find($id);

                    if ($file) {
                        $uploadFile = ModelsUpload::find($file->file_id);
                        if ($uploadFile) {
                            $filePath = public_path('storage/uploads/' . $uploadFile->filename);
                            if (file_exists($filePath) && is_file($filePath)) {
                                $fileNameInZip = pathinfo($file->name, PATHINFO_EXTENSION)
                                    ? $file->name
                                    : $file->name . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

                                $zip->addFile($filePath, $fileNameInZip);
                            } else {
                                Log::warning("File not found or is a directory: $filePath");
                            }
                        } else {
                            Log::warning("Associated file not found in ModelsUpload for file ID: {$file->file_id}");
                        }
                    }
                }
                $zip->close();
            } else {
                return response()->json(['error' => 'Could not create ZIP file.'], 500);
            }
            return response()->json(['url' => route('filemanager.downloadZip', ['filename' => $zipFileName])]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function downloadZip($filename)
    {
        $filePath = storage_path('app/public/temp/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        return abort(404);
    }

    private function treeFiles($filemanagerId, $parent = null)
    {
        $result = Organization::where('parent_id', $parent)
            ->orderBy('name')
            ->get();
        $filemanagers = ModelsFileManager::find($filemanagerId);

        foreach ($result as $row) {
            $row->children = $this->treeFiles($filemanagers->id, $row->id);
            $row->leaf = (count($row->children)) ? false : true;
            $row->checked = $row->hasFilemanager($filemanagers->id);
            $row->icon = asset('images/icons/' . ($row->type->icon ?? 'home') . '.png');
            $row->home = ($filemanagers->home == $row->id);
        }

        return $result;
    }
}
