<?php

namespace App\Controllers\Admins\Offices;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Office as Mod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Office extends Controller
{
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

        if ($company) {
            $query->where('company_id', $company);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        // $result = Query::open($query, [
        //     'name',
        // ]);

        return response()->json([
            'data' => $query->get(),
            'count' => $query->count()
        ]);

        // return response()->json([
        //     'data' => Mod::find(1),
        //     'count' => 1
        // ]);
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['company'])->where('id', $id)->first();
    }

    public function create(Request $request)
    {
        try {
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

            DB::beginTransaction();

            $office = Mod::create([
                'name' => $request->input('name'),
                'company_id' => $request->input('company_id'),
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

            DB::beginTransaction();

            $office = Mod::find($request->input('id'));

            if (!$office) {
                return response()->json([
                    'success' => false,
                    'message' => 'Office not found'
                ], 404);
            }

            $office->name = $request->input('name');
            $office->company_id = $request->input('company_id');
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
