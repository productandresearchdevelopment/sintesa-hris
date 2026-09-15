<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalPeriod as Mod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppraisalPeriod extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $params = [
            'user' => $user
        ];

        // if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
        // $view = isMobile() ? '_front.aprraisal.period.mobile' : '_front.aprraisal.period.index';
        $view = isMobile() ? '_front.aprraisal.period.mobile' : '_bak.appraisal.period.main';
        return view($view, $params);
        // } else {
        // return view('_bak.appraisal.period.main', $params);
        // }
    }

    public function data(Request $request, $counter = true)
    {
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;

        $query = Mod::with(['appraisal_period_organizations']);

        if (!$isSuperUser && $userCompany) {
            $query->whereHas('appraisal_period_organizations.organization', function ($q) use ($userCompany) {
                $q->where('company_id', $userCompany);
            });
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $query->orderBy('period', 'asc')->orderBy('smester', 'asc');

        $result = Query::open($query, [
            'period',
            'smester',
            'start_date',
            'end_date',
            'is_closed'
        ], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['appraisal_period_organizations'])->where('id', $id)->first();
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'period' => 'required|string',
                'smester' => 'required|integer|in:1,2',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_closed' => 'nullable|boolean',
                'template_id' => 'nullable|integer|exists:iq_appraisal_question_template,id',
                'organization_id' => 'nullable|integer|exists:iq_org,id',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists = Mod::where('period', $request->period)
                    ->where('smester', $request->smester)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('smester', 'This combination of period and smester already exists.');
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $appraisal = Mod::create([
                'period' => $request->period,
                'smester' => $request->smester,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_closed' => $request->has('is_closed') ? (int)$request->is_closed : 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $appraisal,
                'message' => 'Appraisal created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating appraisal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:iq_appraisal_period,id',
                'period' => 'required|string',
                'smester' => 'required|integer|in:1,2',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_closed' => 'nullable|boolean',
                'template_id' => 'nullable|integer|exists:iq_appraisal_question_template,id',
                'organization_id' => 'nullable|integer|exists:iq_org,id',
                'appraisal_period_organization_id' => 'nullable|integer|exists:iq_appraisal_period_organization,id',
            ]);

            // Custom validation to check uniqueness of period and smester
            $validator->after(function ($validator) use ($request) {
                $exists = Mod::where('period', $request->period)
                    ->where('smester', $request->smester)
                    ->where('id', '!=', $request->id) // Exclude the current record
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('smester', 'This combination of period and smester already exists.');
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appraisal = Mod::find($request->id);
            if (!$appraisal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appraisal not found'
                ], 404);
            }

            DB::beginTransaction();

            $appraisal->update([
                'period' => $request->period,
                'smester' => $request->smester,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_closed' => $request->has('is_closed') ? (int)$request->is_closed : ($appraisal->is_closed ?? 0),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $appraisal,
                'message' => 'Appraisal updated successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating appraisal',
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
                    'message' => 'Success deleting appraisals'
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
                'message' => 'Error deleting appraisal',
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
                    'message' => 'Success restoring appraisals'
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
                'message' => 'Error restoring appraisals',
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
                        $rec->appraisal_period_organizations()->delete();
                        $rec->forceDelete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success permanently deleted appraisals'
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
                'message' => 'Error permanently deleting appraisals',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
