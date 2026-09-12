<?php

namespace App\Controllers\Admins\Helpdesks;

use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use App\Libraries\Notifier;
use App\Libraries\Query;
use App\Models\City;
use App\Models\Company;
use App\Models\Division;
use App\Models\Employees\Employee;
use App\Models\GlobalData;
use App\Models\Helpdesks\HelpdeskCategory;
use App\Models\Helpdesks\Helpdesk as Mod;
use App\Models\Helpdesks\HelpdeskAnswer;
use App\Models\Helpdesks\HelpdeskAnswerFile;
use App\Models\Helpdesks\HelpdeskFile;
use App\Models\Leave;
use App\Models\Organization;
use App\Models\Placement;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload as Uploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Helpdesk extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = [
            'user' => $user,
            'employees' => Employee::all(),
            'leaves' => Leave::all(),
            'helpdesks' => Mod::all(),
            'helpdesk_categories' => HelpdeskCategory::all(),
            'users' => User::all(),
            'organizations' => Organization::all(),
            'divisions' => Division::all(),
            'companies' => Company::all(),
            'placements' => Placement::all(),
            'genders' => GlobalData::where('group', 'gender')->get(),
            'maritals' => GlobalData::where('group', 'marital')->get(),
            'religions' => GlobalData::where('group', 'religion')->get(),
            'banks' => GlobalData::where('group', 'bank')->get(),
            'emergency_relations' => GlobalData::where('group', 'emergency_relation')->get(),
            'cities' => City::select('id', 'city')->get(),
            'provinces' => City::select('id', 'province')->get()
        ];

        // if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
        // $view = isMobile() ? '_front.helpdesk.mobile' : '_front.helpdesk.index';
        $view = isMobile() ? '_front.helpdesk.mobile' : '_bak.helpdesk.main';
        return view($view, $params);
        // } else {
        // return view('_bak.helpdesk.main', $params);
        // }
    }

    public function data(Request $request)
    {
        $query = Mod::with([
            'answers',
            'answers.createdBy',
            'answers.user_mentions',
            'organization',
            'user_mentions',
            'category',
            'category.organizations',
            'uploads',
            'createdBy',
            'updatedBy',
            'deletedBy'
        ]);

        if ($folder = $request->input('filter-folder')) {
            if ($folder = Organization::find($folder)) {
                if ($request->input('only-current-folder')) {
                    $query->where('organization_id', $folder->id);
                } else {
                    $query->whereHas('organization', function ($q) use ($folder) {
                        $q->where('path', 'LIKE', $folder->path . '%');
                    });
                }
            }
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $user = auth()->user();
        $role = optional($user->role)->name;
        if (!in_array(strtolower($role), ['developer', 'superadmin'])) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('user_mentions', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                })
                    ->orWhere('created_by', $user->id)
                    ->orWhereHas('organizations', function ($q3) use ($user) {
                        $q3->where('iq_org.id', $user->organization_id);
                    });
            });
        }

        $query->orderBy('created_at', 'desc');
        $result = Query::open($query, [
            'title',
            'organization.id',
            'organization.name',
            'category.id',
            'category.name',
            'message',
            'created_at',
        ]);

        foreach ($result['data'] as $item) {
            $photoId = $item->createdBy?->photo_id;
            $item->setAttribute('created_by_avatar', $photoId ? route('file', $photoId) : null);
        }

        return response()->json($result);
    }

    public function dataOrganizations(Request $request, $helpdeskId = null)
    {
        if ($helpdeskId) {
            $data = $this->treeModules($helpdeskId);
            return response()->json($data);
        }
    }

    public function data_category(Request $request, $organization_id)
    {
        $query = HelpdeskCategory::with([
            'organizations',
            'createdBy',
            'updatedBy',
            'deletedBy',
        ]);

        $organization_id = $request->input('organization_id') ? $request->input('organization_id') : $organization_id;

        if ($organization_id) {
            $query->whereHas('organizations', function ($q) use ($organization_id) {
                $q->where('organization_id', $organization_id);
            });
        }

        return $query->get();
    }

    public function get(Request $request, $id = null)
    {
        return Mod::where('id', $id)->with(
            'answers',
            'answers.createdBy',
            'answers.user_mentions',
            'organization',
            'user_mentions',
            'category',
            'category.organizations',
            'uploads',
            'createdBy',
            'updatedBy',
            'deletedBy'
        )->first();
    }

    public function show(Request $request, $id = null)
    {
        $helpdesk = Mod::with([
            'organization',
            'category',
            'category.organizations',
            'createdBy',
            'updatedBy',
            'deletedBy',
            'answers',
            'last_answer',
            'uploads'
        ])->find($id);

        if (!$helpdesk) {
            abort(404);
        }

        $answers = HelpdeskAnswer::with([
            'uploads',
            'createdBy',
            'updatedBy',
            'deletedBy'
        ])->where('helpdesk_id', $id)->get();

        $user = $request->user();

        $params = [
            'user' => $user,
            'answer' => $answers,
            'helpdesk' => $helpdesk
        ];

        return view('_bak.helpdesk.details.info', $params);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'nullable|exists:iq_helpdesk_category,id',
            'message' => 'required|string',
            'files.*' => 'nullable|file',
            'user_mentions' => 'nullable',
        ], [
            'title.required' => 'Title is required',
            'message.required' => 'Message is required',
            'category_id.exists' => 'Category not found',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => false], 422);
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
            $helpdesk = Mod::create([
                'category_id' => $request->input('category_id'),
                'title' => $request->input('title'),
                'message' => $request->input('message'),
                'status' => 'pending',
            ]);

            if ($request->hasFile('files')) {
                $fileIds = FileUpload::upload('files', 'helpdesk');
                if ($fileIds) {
                    foreach ($fileIds as $fileId) {
                        $uploadedFile = Uploads::find($fileId);
                        if ($uploadedFile) {
                            HelpdeskFile::create([
                                'upload_id' => $uploadedFile->id,
                                'helpdesk_id' => $helpdesk->id
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
                $helpdesk->user_mentions()->sync($userMentions);

                Notifier::sendToMany($userMentions, 'New helpdesk', 'helpdesk', $helpdesk->id, $helpdesk->title);
            }

            DB::commit();
            return response()->json(['data' => $helpdesk, 'message' => "Data created successfully", 'success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'nullable|exists:iq_helpdesk_category,id',
            'message' => 'required|string',
            'existing_files.*' => 'nullable|exists:uploads,id',
            'files.*' => 'nullable|file',
            'user_mentions' => 'nullable',
        ], [
            'title.required' => 'Title is required',
            'message.required' => 'Message is required',
            'category_id.exists' => 'Category not found',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'success' => false], 422);
        }

        $helpdesk = Mod::find($id);

        if (!$helpdesk) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }

        DB::beginTransaction();
        try {
            if ($request->has('category_id')) {
                $helpdesk->category_id = $request->input('category_id');
            }

            if ($request->has('title')) {
                $helpdesk->title = $request->input('title');
            }

            if ($request->has('message')) {
                $helpdesk->message = $request->input('message');
            }

            $helpdesk->status = $helpdesk->status != 'pending' ? $helpdesk->status : 'pending';
            $helpdesk->save();

            $existingFileIds = $request->input('existing_files', []);
            $currentFileIds = HelpdeskFile::where('helpdesk_id', $helpdesk->id)->pluck('upload_id')->toArray();

            $filesToKeep = array_intersect($currentFileIds, $existingFileIds);
            $filesToRemove = array_diff($currentFileIds, $filesToKeep);

            if (!empty($filesToRemove)) {
                HelpdeskFile::whereIn('upload_id', $filesToRemove)->forceDelete();
                FileUpload::removeFileById($filesToRemove);
            }

            if ($request->hasFile('files')) {
                $fileIds = FileUpload::upload('files', 'helpdesk');

                if ($fileIds) {
                    foreach ($fileIds as $fileId) {
                        $uploadedFile = Uploads::find($fileId);

                        if ($uploadedFile) {
                            HelpdeskFile::create([
                                'helpdesk_id' => $helpdesk->id,
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
                    return response()->json(['message' => 'Invalid user_mentions format'], 400);
                }

                $helpdesk->user_mentions()->sync($userMentions);
            } else {
                $helpdesk->user_mentions()->detach();
            }

            DB::commit();
            return response()->json(['data' => $helpdesk, 'message' => "Data update successfully", 'success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function setOrganization(Request $request, $helpdeskId)
    {
        $input  = $request->all();
        $organization  = $input['organization'];
        $auth = $input['auth'];

        if ($helpdeskId && $organization) {
            $data   = Mod::find($helpdeskId);
            $data->organizations()->detach([$organization]);
            if ($auth) $data->organizations()->attach([$organization]);
            return response()->json(['success' => true, 'message' => 'Success!!!']);
        }
        return response()->json(['success' => false, 'message' => 'Organization Not Found']);
    }


    public function destroy(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($rec = Mod::find($id)) {
                        $answerIds = HelpdeskAnswer::where('helpdesk_id', $rec->id)->pluck('id')->toArray();

                        if (!empty($answerIds)) {
                            HelpdeskAnswer::whereIn('id', $answerIds)->delete();
                        }

                        $rec->delete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data soft-deleted successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to soft delete data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($rec = Mod::onlyTrashed()->where('id', $id)->first()) {
                        $answerIds = HelpdeskAnswer::where('helpdesk_id', $rec->id)->onlyTrashed()->pluck('id')->toArray();

                        if (!empty($answerIds)) {
                            HelpdeskAnswer::whereIn('id', $answerIds)->onlyTrashed()->restore();
                        }

                        $rec->restore();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data restored successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function forcedelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($rec = Mod::withTrashed()->where('id', $id)->first()) {
                        $rec->last_answer_id = null;
                        $rec->save();

                        if ($rec->last_answer_id == null) {
                            $rec->user_mentions()->detach();

                            $answerIds = HelpdeskAnswer::where('helpdesk_id', $rec->id)->pluck('id')->toArray();

                            if (!empty($answerIds)) {
                                $answerFiles = HelpdeskAnswerFile::whereIn('helpdesk_answer_id', $answerIds)
                                    ->get(['helpdesk_answer_id', 'upload_id']);

                                $uploadIds = $answerFiles->pluck('upload_id')->toArray();

                                if (!empty($answerFiles)) {
                                    foreach ($answerFiles as $answerFileId) {
                                        $answerFile = HelpdeskAnswerFile::where('helpdesk_answer_id', $answerFileId->helpdesk_answer_id)
                                            ->where('upload_id', $answerFileId->upload_id)
                                            ->first();
                                        if ($answerFile) {
                                            $answerFile->forceDelete();
                                        }
                                    }
                                }

                                if (!empty($uploadIds)) {
                                    FileUpload::removeFileById($uploadIds);
                                }

                                foreach ($answerIds as $answerId) {
                                    $answer = HelpdeskAnswer::withTrashed()->find($answerId);
                                    if ($answer) {
                                        $answer->forceDelete();
                                    }
                                }
                            }

                            $helpdeskFiles = HelpdeskFile::where('helpdesk_id', $rec->id)
                                ->get(['helpdesk_id', 'upload_id']);

                            $helpdeskUploadIds = [];

                            foreach ($helpdeskFiles as $file) {
                                $helpdeskUploadIds[] = $file->upload_id;
                            }

                            if ($helpdeskFiles->isNotEmpty()) {
                                HelpdeskFile::whereIn('upload_id', $helpdeskUploadIds)
                                    ->where('helpdesk_id', $rec->id)
                                    ->forceDelete();
                            }

                            if (!empty($helpdeskUploadIds)) {
                                FileUpload::removeFileById($helpdeskUploadIds);
                            }

                            $rec->forceDelete();
                        }
                    }
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
                    'success' => false,
                    'message' => 'Failed to delete data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function closed(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($rec = Mod::find($id)) {
                        $rec->closed_at = Carbon::now('Asia/Jakarta');
                        $rec->status = 'closed';
                        $rec->save();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data updated successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function unclosed(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($rec = Mod::find($id)) {
                        $rec->closed_at = null;
                        if ($rec->last_answer_id) {
                            $rec->status = 'discussed';
                        } else {
                            $rec->status = 'pending';
                        }
                        $rec->save();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data updated successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    private function treeModules($helpdeskId, $parent = null)
    {
        $result = Organization::where('parent_id', $parent)
            ->orderBy('name')
            ->get();
        $helpdesk = Mod::find($helpdeskId);

        foreach ($result as $row) {
            $row->children = $this->treeModules($helpdesk->id, $row->id);
            $row->leaf = (count($row->children)) ? false : true;
            $row->checked = $row->hasHelpdeskOrganization($helpdesk->id);
            $row->icon = asset('images/icons/' . ($row->type->icon ?? 'home') . '.png');
            $row->home = ($helpdesk->home == $row->id);
        }

        return $result;
    }
}
