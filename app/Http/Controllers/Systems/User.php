<?php

namespace App\Http\Controllers\Systems;

use App\Exports\Users\ImportFormat\Format;
use App\Imports\User\Import;
use App\Jobs\SendEmailValidationJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use App\SystemModels\Auth;
use App\Libraries\Query;
use App\SystemModels\Auth\Role;
use App\SystemModels\Globals\Upload;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class User extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $view = 'systems.users.main';

        $roleName = strtolower(optional($user->role)->name ?? '');
        if ($roleName === 'developer') {
            $roles = Role::all();
        } elseif ($roleName === 'superadmin') {
            $roles = Role::whereRaw('LOWER(name) != ?', ['developer'])->get();
        } else {
            $roles = Role::whereRaw('LOWER(name) NOT IN (?, ?)', ['developer', 'superadmin'])->get();
        }

        $params = [
            'user'  => $user,
            'roles' => $roles,
        ];

        return view($view, $params);
    }

    public function data(Request $request)
    {
        $user   = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;

        $search = ['id', 'name', 'username', 'last_ip', 'email', 'phone'];
        $query  = Auth\User::with(['role', 'organization', 'employee']);

        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereHas('employee', function ($q2) use ($userCompany) {
                    $q2->where('company_id', $userCompany);
                })->orWhereHas('organization', function ($q2) use ($userCompany) {
                    $q2->where('company_id', $userCompany);
                });
            });
        }

        // FILTER USER ---------------------------------------------------------------------------------------------
        if ($filter = $request->input('role')) $query->where('role_id', $filter);

        if (!$request->trash) $query->withTrashed();
        if ($request->trash == 2) $query->onlyTrashed();

        return Query::open($query, $search);
    }

    public function get(Request $request, $id = null)
    {
        $data = Auth\User::where('id', $id)
            ->with('createdBy', 'updatedBy', 'deletedBy', 'organization')
            ->first();
        return $data;
    }

    public function view(Request $request, $id = null)
    {
        if ($data = Auth\User::find($id)) {
            $user = $request->user();
            $params = [
                'data' => $data
            ];

            return view('systems.users.detail', $params);
        }
        abort('404');
    }

    public function push(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $input = [
                'role_id' => $request->input('role_id'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'description' => $request->input('description'),
                'organization_id' => $request->input('organization_id'),
                'employ_id' => $request->input('employ_id'),
            ];

            if (!$id && !$request->password) return ['success' => false, 'message' => 'Password Is Null'];
            else if ($input['email'] && Auth\User::where('email', $input['email'])->where('id', '<>', $id)->withTrashed()->first()) return ['success' => false, 'message' => 'Email Duplicate'];
            else if (Auth\User::where('username', $input['username'])->where('id', '<>', $id)->withTrashed()->first()) return ['success' => false, 'message' => 'Username Duplicate'];

            if ($password = $request->password) $input['password'] = Hash::make($password);

            $emailValidation = false;
            if ($id) {
                $data = Auth\User::find($id);
                if ($data->email != $input['email']) {
                    $emailValidation = true;
                    $input['email_validation_code'] = rand(100000, 999999);
                    $input['email_validation_sent_at'] = date('Y-m-d H:i:s');
                    $input['email_validation_at'] = null;
                }
                $data->update($input);
            } else {
                $emailValidation = true;
                $input['email_validation_code'] = rand(100000, 999999);
                $input['email_validation_sent_at'] = date('Y-m-d H:i:s');
                $input['email_validation_at'] = null;
                $data = Auth\User::create($input);
            }

            // SEND EMAIL PASSWORD -------------------------------------------------------------------------------------
            if ($emailValidation) {
                //dispatch(new SendEmailValidationJob($data->id));
            }

            DB::commit();
            return ['success' => true, 'message' => 'Success...'];
        } catch (Exception $error) {
            DB::rollback();
            return ['success' => false, 'message' => '500 ' . $error->getMessage()];
        }
    }

    public function sendEmailValidation(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($user = Auth\User::find($id)) {
                    if (!$user->email_validation_at) {
                        $input = [
                            'email_validation_code' => rand(100000, 999999),
                            'email_validation_sent_at' => date('Y-m-d H:i:s'),
                        ];
                        $user->update($input);

                        dispatch(new SendEmailValidationJob($id));
                    }
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function setRole(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Auth\User::where('id', $id)->withTrashed()->first()) {
                    $rec->update(['role_id' => $request->role_id]);
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function setPassword(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Auth\User::where('id', $id)->withTrashed()->first()) {
                    $rec->update(['password' => Hash::make($request->password)]);
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function importFormat(Request $request)
    {
        $filename = 'user_format.xlsx';
        return Excel::download(new Format($request->user()), $filename);
    }

    public function importData(Request $request)
    {
        if ($upload = FileUpload::upload('file', 'employee-import')) {
            $user = $request->user();
            $file = Upload::find($upload);
            $fileexcel = storage_path('app/public/uploads/' . $file->filename);
            $importExcel = new Import($user);
            Excel::import($importExcel, $fileexcel);
            unlink($fileexcel);
            Upload::where('id', $upload)->delete();

            return ['success' => true, 'message' => $importExcel->logs()];
        }
        return ['success' => false, 'message' => 'The data you uploaded was not found'];
    }

    // public function importData(Request $request)
    // {
    //     $file = $request->file('file');
    //     $path = Storage::disk('local')->putFileAs('tmp', $file, (Str::uuid() . '.xlsx'));
    //     $path = Storage::disk('local')->path($path);
    //     $import = new Import($request->user());
    //     Excel::import($import, $path);
    //     unlink($path);
    //     return ['success' => true, 'message' => $import->get()];
    // }

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Auth\User::where('id', $id)->withTrashed()->first()) {
                    $rec->restore();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function forceDelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Auth\User::where('id', $id)->withTrashed()->first()) {
                    $rec->forcedelete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Auth\User::where('id', $id)->withTrashed()->first()) {
                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }
}
