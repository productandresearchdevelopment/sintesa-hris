<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\ExportExcel;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalEmployee as Mod;
use App\Models\Appraisals\AppraisalPeriod;
use App\Models\Appraisals\AppraisalQuestionTemplate;
use App\Models\Employees\Employee;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppraisalEmployee extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('employee');

        $params = [
            'user' => $user
        ];

        $view = isMobile() ? '_front.appraisal.mobile' : '_bak.appraisal.employee.main';
        return view($view, $params);
    }

    public function data(Request $request, $counter = true)
    {
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');

        $query = Mod::with(['period', 'employee', 'template', 'evaluator1', 'evaluator2', 'appraisal_employee_questions', 'appraisal_employee_summaries']);

        if ($roleName !== 'superadmin' && $roleName !== 'developer') {
            $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;
            if ($userCompany) {
                $query->whereHas('employee', function ($q) use ($userCompany) {
                    $q->where('company_id', $userCompany);
                });
            }
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, [
            'period.period',
            'template.title',
            'employee.fullname',
            'total_point',
            'grade',
            'evaluator1.fullname',
            'evaluator2.fullname'
        ], $counter);

        return $result;
    }

    public function data_employee(Request $request, $counter = true)
    {
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');

        $query = Employee::with([
            'user',
            'user.role',
            'organization',
            'organization.authorized1',
            'organization.authorized2',
            'organization.parent',
            'organization.parent.parent',
            'organization.position',
            'division',
            'company',
            'photo',
            'placement',
            'careers',
            'last_career',
            'last_career.career',
            'contracts',
            'last_contract',
            'last_contract.status',
            'appraisal_employees',
            'appraisal_employees.period',
            'appraisal_employees.period.appraisal_period',
        ]);

        if ($roleName !== 'superadmin' && $roleName !== 'developer') {
            $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;
            if ($userCompany) {
                $query->where('company_id', $userCompany);
            }
        }

        if ($request->filled('organization')) {
            $userOrg = $request->organization;
            $childOrganizations = array_merge([$userOrg], $this->getAllChildOrganizations($userOrg));
            $query->whereIn('org_id', $childOrganizations);
            $query->orderByRaw("FIELD(org_id, " . implode(',', $childOrganizations) . ")");
        } elseif ($roleName !== 'superadmin' && $roleName !== 'developer') {
            $userOrg = optional(optional($user)->employee)->org_id ?? optional($user)->organization_id;
            if ($userOrg) {
                $childOrganizations = array_merge([$userOrg], $this->getAllChildOrganizations($userOrg));
                $query->whereIn('org_id', $childOrganizations);
                $query->orderByRaw("FIELD(org_id, " . implode(',', $childOrganizations) . ")");
            }
        }

        $query->whereHas('last_contract', function ($q) {
            $q->whereNotIn('status_id', [1508, 1509, 1510])
                ->where(function ($q2) {
                    $q2->whereNull('end_date')
                        ->orWhere('end_date', '>=', date('Y-m-d'))
                        ->orWhere('status_id', 1507);
                });
        });

        if ($request->trash == 1) {
            $query->withTrashed();
        } elseif ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, [
            'nickname',
            'fullname',
            'organization.id',
            'organization.name',
            'division.id',
            'division.name',
            'company.id',
            'company.name',
        ], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        $data = Employee::with([
            'organization',
            'organization.authorized1',
            'organization.authorized2',
            'organization.parent.parent',
            'organization.position',
            'division',
            'company',
            'photo',
            'placement',
            'last_career.career',
            'last_contract.status',
        ])
            ->when($id, fn($q) => $q->where('id', $id))
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $periodYear = $request->input('period_year');
        $periodSmt = $request->input('period_smt');

        $period = null;
        if ($periodYear && $periodSmt) {
            $period = AppraisalPeriod::with('appraisal_period_organizations')
                ->where('period', $periodYear)
                ->where('smester', $periodSmt)
                ->when($data->company_id, fn($q) => $q->where('company_id', $data->company_id))
                ->first();
        }

        $data_appraisal_employee = Mod::with([
            'period',
            'employee',
            'template',
            'evaluator1',
            'evaluator2',
            'appraisal_employee_questions',
            'appraisal_employee_questions.question',
            'appraisal_employee_summaries',
        ])
            ->where('employ_id', $data->id)
            ->when($period, function ($q) use ($period, $data) {
                $orgPeriodIds = $period->appraisal_period_organizations
                    ->where('organization_id', $data->org_id)
                    ->pluck('id')
                    ->toArray();

                $q->where(function ($sub) use ($period, $orgPeriodIds) {
                    $sub->where('period_id', $period->id)
                        ->orWhereHas('period', fn($qp) => $qp->where('period_id', $period->id));
                    if (!empty($orgPeriodIds)) {
                        $sub->orWhereIn('period_id', $orgPeriodIds);
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $data_appraisal_template = null;
        $templateRelations = ['appraisal_questions', 'appraisal_questions.category', 'division'];

        if ($period) {
            $orgTemplateId = $period->appraisal_period_organizations
                ->where('organization_id', $data->org_id)
                ->pluck('template_id')
                ->filter()
                ->first();

            if ($orgTemplateId) {
                $data_appraisal_template = AppraisalQuestionTemplate::with($templateRelations)
                    ->where('id', $orgTemplateId)
                    ->where('is_archived', 0)
                    ->first();
            }

            if (!$data_appraisal_template) {
                $data_appraisal_template = AppraisalQuestionTemplate::with($templateRelations)
                    ->where('period_year', $periodYear)
                    ->where('period_smt', $periodSmt)
                    ->where('is_archived', 0)
                    ->when($data->division_id, function ($q) use ($data) {
                        $q->where(fn($sub) => $sub->where('division_id', $data->division_id)->orWhereNull('division_id'));
                    })
                    ->first();
            }
        }

        if (!$data_appraisal_template && $data_appraisal_employee?->template_id) {
            $data_appraisal_template = AppraisalQuestionTemplate::with($templateRelations)
                ->find($data_appraisal_employee->template_id);
        }

        if (!$data_appraisal_template) {
            $data_appraisal_template = AppraisalQuestionTemplate::with($templateRelations)
                ->where('is_archived', 0)
                ->first();
        }

        $data->appraisal_template = $data_appraisal_template;
        $data->appraisal_employee = $data_appraisal_employee;

        return response()->json($data);
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'period_id' => 'required|integer|exists:iq_appraisal_period,id',
                'template_id' => 'required|integer|exists:iq_appraisal_question_template,id',
                'employ_id' => 'required|string|exists:iq_employ,id',
                'total_point' => 'required|integer',
                'grade' => 'required|integer',
                'evaluator1_by' => 'nullable|string|exists:iq_employ,id',
                'evaluator1_at' => 'nullable|date',
                'evaluator2_by' => 'nullable|string|exists:iq_employ,id',
                'evaluator2_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $appraisal = Mod::create([
                'period_id' => $request->period_id,
                'template_id' => $request->template_id,
                'employ_id' => $request->employ_id,
                'total_point' => $request->total_point,
                'grade' => $request->grade,
                'evaluator1_by' => $request->evaluator1_by,
                'evaluator1_at' => $request->evaluator1_at,
                'evaluator2_by' => $request->evaluator2_by,
                'evaluator2_at' => $request->evaluator2_at
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
                'period_id' => 'required|integer|exists:iq_appraisal_period,id',
                'template_id' => 'required|integer|exists:iq_appraisal_question_template,id',
                'employ_id' => 'required|string|exists:iq_employ,id',
                'total_point' => 'required|integer',
                'grade' => 'required|integer',
                'evaluator1_by' => 'nullable|string|exists:iq_employ,id',
                'evaluator1_at' => 'nullable|date',
                'evaluator2_by' => 'nullable|string|exists:iq_employ,id',
                'evaluator2_at' => 'nullable|date',
            ]);

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
                'period_id' => $request->period_id,
                'template_id' => $request->template_id,
                'employ_id' => $request->employ_id,
                'total_point' => $request->total_point,
                'grade' => $request->grade,
                'evaluator1_by' => $request->evaluator1_by,
                'evaluator1_at' => $request->evaluator1_at,
                'evaluator2_by' => $request->evaluator2_by,
                'evaluator2_at' => $request->evaluator2_at
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


    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '64048M');
        ini_set('max_execution_time', '300');

        $title = [];
        $title[] = ['Appraisal Employee Report', 'h2'];
        if ($request->input('trash') !== null && $request->input('trash') !== 'null') {
            if ($request->input('trash') == 1) {
                $title[] = ['DATA : Active', 'h5'];
            } elseif ($request->input('trash') == 2) {
                $title[] = ['DATA : Deleted', 'h5'];
            }
        }

        $employeeResult = $this->data_employee($request, false);
        $employees = is_object($employeeResult) && method_exists($employeeResult, 'all')
            ? $employeeResult->all()
            : (is_array($employeeResult) ? ($employeeResult['data'] ?? $employeeResult) : []);

        $exportData = [];

        foreach ($employees as $emp) {
            $nik = $emp->nik ?? '-';
            $fullname = $emp->fullname ?? '-';
            $position = $emp->organization->name ?? '-';
            $division = $emp->division->name ?? '-';
            $contract = $emp->last_contract->status->name ?? $emp->last_contract->status->description ?? '-';
            $career = $emp->last_career->career->name ?? '-';

            $appraisals = $emp->appraisal_employees ?? [];

            if (count($appraisals) > 0) {
                foreach ($appraisals as $ae) {
                    $aeMod = Mod::with([
                        'period',
                        'period.appraisal_period',
                        'template',
                        'evaluator1',
                        'evaluator2',
                        'appraisal_employee_summaries'
                    ])->find($ae->id);

                    if (!$aeMod) continue;

                    $periodStr = '-';
                    if ($aeMod->period && $aeMod->period->appraisal_period) {
                        $p = $aeMod->period->appraisal_period;
                        $periodStr = $p->period . ' (SMT ' . $p->smester . ')';
                    }

                    $templateTitle = $aeMod->template->title ?? '-';
                    $evaluator1 = $aeMod->evaluator1->fullname ?? '-';
                    $evaluator2 = $aeMod->evaluator2->fullname ?? '-';

                    $finalScore = $aeMod->total_point !== null ? number_format((float)$aeMod->total_point, 2, '.', '') : '-';
                    $finalGrade = $finalScore !== '-' ? AppraisalEmployeeSummary::getGrade($finalScore) : '-';

                    $techScore = '-';
                    $behaviorScore = '-';
                    $leadershipScore = '-';

                    if ($aeMod->appraisal_employee_summaries) {
                        foreach ($aeMod->appraisal_employee_summaries as $sum) {
                            $p1 = is_numeric($sum->evaluator1_point) ? (float)$sum->evaluator1_point : null;
                            $p2 = is_numeric($sum->evaluator2_point) ? (float)$sum->evaluator2_point : null;

                            if ($p1 !== null && $p2 !== null) {
                                $avg = ($p1 + $p2) / 2;
                            } else {
                                $avg = $p1 ?? $p2;
                            }

                            $formattedAvg = $avg !== null ? number_format((float)$avg, 2, '.', '') : '-';

                            if ($sum->category_id == 1) $techScore = $formattedAvg;
                            elseif ($sum->category_id == 2) $behaviorScore = $formattedAvg;
                            elseif ($sum->category_id == 3) $leadershipScore = $formattedAvg;
                        }
                    }

                    $exportData[] = (object)[
                        'nik' => $nik,
                        'fullname' => $fullname,
                        'position' => $position,
                        'division' => $division,
                        'contract' => $contract,
                        'career' => $career,
                        'period_smt' => $periodStr,
                        'template_title' => $templateTitle,
                        'tech_score' => $techScore,
                        'behavior_score' => $behaviorScore,
                        'leadership_score' => $leadershipScore,
                        'final_score' => $finalScore,
                        'final_grade' => $finalGrade,
                        'evaluator1' => $evaluator1,
                        'evaluator2' => $evaluator2,
                    ];
                }
            } else {
                $exportData[] = (object)[
                    'nik' => $nik,
                    'fullname' => $fullname,
                    'position' => $position,
                    'division' => $division,
                    'contract' => $contract,
                    'career' => $career,
                    'period_smt' => '-',
                    'template_title' => '-',
                    'tech_score' => '-',
                    'behavior_score' => '-',
                    'leadership_score' => '-',
                    'final_score' => '-',
                    'final_grade' => '-',
                    'evaluator1' => '-',
                    'evaluator2' => '-',
                ];
            }
        }

        $columns = [
            ['text' => 'ID EMPLOYEE', 'dataIndex' => 'nik', 'width' => 120, 'align' => 'center'],
            ['text' => 'FULLNAME', 'dataIndex' => 'fullname', 'width' => 200],
            ['text' => 'POSITION', 'dataIndex' => 'position', 'width' => 200],
            ['text' => 'DIVISION', 'dataIndex' => 'division', 'width' => 160],
            ['text' => 'CONTRACT', 'dataIndex' => 'contract', 'width' => 140, 'align' => 'center'],
            ['text' => 'CAREER', 'dataIndex' => 'career', 'width' => 140, 'align' => 'center'],
            ['text' => 'PERIOD & SEMESTER', 'dataIndex' => 'period_smt', 'width' => 160, 'align' => 'center'],
            ['text' => 'TEMPLATE', 'dataIndex' => 'template_title', 'width' => 200],
            ['text' => 'TECHNICAL SCORE', 'dataIndex' => 'tech_score', 'width' => 140, 'align' => 'center'],
            ['text' => 'BEHAVIOR SCORE', 'dataIndex' => 'behavior_score', 'width' => 140, 'align' => 'center'],
            ['text' => 'LEADERSHIP SCORE', 'dataIndex' => 'leadership_score', 'width' => 140, 'align' => 'center'],
            ['text' => 'FINAL SCORE', 'dataIndex' => 'final_score', 'width' => 120, 'align' => 'center'],
            ['text' => 'FINAL GRADE', 'dataIndex' => 'final_grade', 'width' => 140, 'align' => 'center'],
            ['text' => 'EVALUATOR 1', 'dataIndex' => 'evaluator1', 'width' => 180],
            ['text' => 'EVALUATOR 2', 'dataIndex' => 'evaluator2', 'width' => 180],
        ];

        $params = array(
            'title' => $title,
            'columns' => $columns,
            'data' => $exportData,
            'filename' => 'Appraisal Employee Report-' . date('YmdHi'),
            'footer' => [config('app.name') . ' (' . date('d F Y H:i:s') . ')'],
        );

        return ExportExcel::export($params);
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

    private function getAllChildOrganizations($parentId, &$visited = [])
    {
        if (in_array($parentId, $visited)) {
            return [];
        }

        $visited[] = $parentId;
        $childs = Organization::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($childs as $childId) {
            $childs = array_merge($childs, $this->getAllChildOrganizations($childId, $visited));
        }

        return $childs;
    }
}
