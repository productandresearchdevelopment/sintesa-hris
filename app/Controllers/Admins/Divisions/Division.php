<?php

namespace App\Controllers\Admins\Divisions;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Division as Mod;
use App\Traits\UserScopingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Division extends Controller
{
    use UserScopingTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $params = ['user' => $user];
        return view('_bak.division.main', $params);
    }

    public function data(Request $request)
    {
        $query = Mod::with(['company']);
        $company = $request->input('company_id');

        $user = $request->user();
        $isSuper = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        if (!$isSuper && $userCompany) {
            $company = $userCompany;
        }

        if ($company) {
            $query->where('company_id', $company);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, [
            'name',
            'company.id',
            'company.name',
        ]);

        return response()->json($result);
    }

    public function get(Request $request, $id = null)
    {
        $user = $request->user();
        $query = Mod::with(['company'])->where('id', $id);

        if (!$this->isSuperUser($user)) {
            $query->where('company_id', $this->getUserCompanyId($user));
        }

        return $query->first();
    }

    public function create(Request $request)
    {
        try {
            $user = $request->user();
            $companyId = $this->isSuperUser($user)
                ? $request->input('company_id')
                : $this->getUserCompanyId($user);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'company_id' => 'required|exists:iq_company,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $division = Mod::create([
                'name' => $request->input('name'),
                'company_id' => $companyId
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $division,
                'message' => 'Division created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating division',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $user = $request->user();
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:iq_division,id',
                'name' => 'required|string|max:255',
                'company_id' => 'required|exists:iq_company,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $division = Mod::find($request->input('id'));
            if (!$division) {
                return response()->json([
                    'success' => false,
                    'message' => 'Division not found'
                ], 404);
            }

            if (!$this->isSuperUser($user)) {
                $userCompanyId = $this->getUserCompanyId($user);
                if ($division->company_id !== $userCompanyId) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action for this company.'], 403);
                }
            }

            $companyId = $this->isSuperUser($user)
                ? $request->input('company_id')
                : $this->getUserCompanyId($user);

            DB::beginTransaction();

            $division->name = $request->input('name');
            $division->company_id = $companyId;
            $division->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $division,
                'message' => 'Division updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating division',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                $user = $request->user();
                $userCompanyId = $this->getUserCompanyId($user);
                $isSuper = $this->isSuperUser($user);

                DB::beginTransaction();

                foreach ($data as $id) {
                    $query = Mod::where('id', $id);
                    if (!$isSuper) {
                        $query->where('company_id', $userCompanyId);
                    }
                    $rec = $query->first();
                    if ($rec) {
                        $rec->delete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success deleting divisions'
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
                'message' => 'Error deleting division',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                $user = $request->user();
                $userCompanyId = $this->getUserCompanyId($user);
                $isSuper = $this->isSuperUser($user);

                DB::beginTransaction();

                foreach ($data as $id) {
                    $query = Mod::withTrashed()->where('id', $id);
                    if (!$isSuper) {
                        $query->where('company_id', $userCompanyId);
                    }
                    $rec = $query->first();
                    if ($rec) {
                        $rec->restore();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success restoring divisions'
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
                'message' => 'Error restoring division',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function forcedelete(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                $user = $request->user();
                $userCompanyId = $this->getUserCompanyId($user);
                $isSuper = $this->isSuperUser($user);

                DB::beginTransaction();

                foreach ($data as $id) {
                    $query = Mod::where('id', $id)->withTrashed();
                    if (!$isSuper) {
                        $query->where('company_id', $userCompanyId);
                    }
                    $rec = $query->first();
                    if ($rec) {
                        $rec->forcedelete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success permanently deleted divisions'
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
                'message' => 'Error permanently deleting divisions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
