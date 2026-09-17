<?php

namespace App\Controllers\Admins\Companies;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Company as Mod;
use App\Traits\UserScopingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Company extends Controller
{
    use UserScopingTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $params = ['user' => $user];
        return view('_bak.company.main', $params);
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $isSuper = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $query = Mod::select('*');

        if (!$isSuper && $userCompany) {
            $query->where('id', $userCompany);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, [
            'name',
        ]);

        return response()->json($result);
    }

    public function get(Request $request, $id = null)
    {
        $user = $request->user();
        $query = Mod::where('id', $id);

        if (!$this->isSuperUser($user)) {
            $query->where('id', $this->getUserCompanyId($user));
        }

        return $query->first();
    }

    public function create(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $company = Mod::create([
                'name' => $request->input('name')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $company,
                'message' => 'Company created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:iq_company,id',
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $company = Mod::find($request->input('id'));
            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $company->name = $request->input('name');
            $company->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $company,
                'message' => 'Company updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();

                foreach ($data as $id) {
                    $rec = Mod::find($id);
                    if ($rec) {
                        $rec->delete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success deleting companies'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No Data!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();

                foreach ($data as $id) {
                    $rec = Mod::withTrashed()->find($id);
                    if ($rec) {
                        $rec->restore();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success restoring companies'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No Data!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error restoring companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function forcedelete(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();

                foreach ($data as $id) {
                    $rec = Mod::where('id', $id)->withTrashed()->first();
                    if ($rec) {
                        $rec->forcedelete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success permanently deleted companies'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No Data!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error permanently deleting companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
