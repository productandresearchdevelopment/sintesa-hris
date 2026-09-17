<?php

namespace App\Controllers\Admins\Offices;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Office as Mod;
use App\Traits\UserScopingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Office extends Controller
{
    use UserScopingTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $params = ['user' => $user];
        return view('_bak.office.main', $params);
    }

    public function data(Request $request)
    {
        $query = Mod::with(['company'])->orderBy('id', 'desc');
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

        return response()->json([
            'data' => $query->get(),
            'count' => $query->count()
        ]);
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'company_id' => 'required|integer|exists:iq_company,id',
                'address' => 'nullable|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'max_distance_allowed' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $companyId = $this->isSuperUser($user)
                ? $request->input('company_id')
                : $this->getUserCompanyId($user);

            DB::beginTransaction();

            $office = Mod::create([
                'name' => $request->input('name'),
                'company_id' => $companyId,
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'max_distance_allowed' => $request->input('max_distance_allowed'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $office,
                'message' => 'Office created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating office',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $user = $request->user();
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:iq_office,id',
                'name' => 'required|string|max:255',
                'company_id' => 'required|integer|exists:iq_company,id',
                'address' => 'nullable|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'max_distance_allowed' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $office = Mod::find($request->input('id'));
            if (!$office) {
                return response()->json([
                    'success' => false,
                    'message' => 'Office not found'
                ], 404);
            }

            if (!$this->isSuperUser($user)) {
                $userCompanyId = $this->getUserCompanyId($user);
                if ($office->company_id !== $userCompanyId) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action for this company.'], 403);
                }
            }

            $companyId = $this->isSuperUser($user)
                ? $request->input('company_id')
                : $this->getUserCompanyId($user);

            DB::beginTransaction();

            $office->name = $request->input('name');
            $office->company_id = $companyId;
            $office->address = $request->input('address');
            $office->latitude = $request->input('latitude');
            $office->longitude = $request->input('longitude');
            $office->max_distance_allowed = $request->input('max_distance_allowed');
            $office->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $office,
                'message' => 'Office updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating office',
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
                    'message' => 'Success deleting offices'
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
                'message' => 'Error deleting office',
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
                    'message' => 'Success restoring offices'
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
                'message' => 'Error restoring offices',
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
                    'message' => 'Success permanently deleted offices'
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
                'message' => 'Error permanently deleting offices',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
