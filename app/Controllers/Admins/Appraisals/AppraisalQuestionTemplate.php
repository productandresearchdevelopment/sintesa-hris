<?php

namespace App\Controllers\Admins\Appraisals;

use App\Exports\Appraisal\Template\ImportFormat\Format;
use App\Http\Controllers\Controller;
use App\Imports\Appraisal\Template\Import;
use App\Libraries\ExportExcel;
use App\Libraries\FileUpload;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalEmployee;
use App\Models\Appraisals\AppraisalEmployeeQuestion;
use App\Models\Appraisals\AppraisalEmployeeSummary;
use App\Models\Appraisals\AppraisalQuestion;
use App\Models\Appraisals\AppraisalQuestionTemplate as Mod;
use App\Models\Division;
use App\Models\Employees\Employee;
use App\Models\Organization;
use App\SystemModels\Globals\Upload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AppraisalQuestionTemplate extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['employee', 'role']);

        $params = [
            'user' => $user,
            'divisions' => Division::all(),
        ];

        $view = isMobile() ? '_front.appraisal.mobile' : '_bak.appraisal.template.main';
        return view($view, $params);
    }

    public function index_mobile(Request $request)
    {
        $user = $request->user()->load(['employee', 'role']);

        $params = [
            'user' => $user,
            'divisions' => Division::all(),
        ];

        return view('_front.appraisal.mobile', $params);
    }

    public function data(Request $request, $counter = true)
    {
        $query = Mod::with(['division', 'appraisal_questions']);
        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer', 'administrator']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;

        if (!$isSuperUser && $userCompany) {
            $query->whereHas('templates_organizations', function ($q2) use ($userCompany) {
                $q2->where('company_id', $userCompany);
            });
        }

        if (!$request->filled('archived')) {
            $query->where('is_archived', 0);
        } elseif ($request->archived == 1) {
            $query->where('is_archived', 1);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }

        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        if ($roleName !== 'hrga' && $roleName !== 'developer' && $roleName !== 'superadmin') {
            $divisionId = optional($user->employee)->division_id;

            if ($divisionId) {
                $query->where('division_id', $divisionId);
            } else {
                $query->whereNull('division_id');
            }
        } else {
            if ($request->filled('division')) {
                $query->where('division_id', $request->division);
            }
        }

        if ($request->filled('period_smt')) {
            $query->where('period_smt', $request->period_smt);
        }

        if ($request->filled('period_year')) {
            $query->where('period_year', $request->period_year);
        }

        $result = Query::open($query, [
            'division.id',
            'division.name',
            'title',
            'period_year',
            'period_smt'
        ], $counter);

        return $result;
    }

    public function dataOrganizations(Request $request, $role = null)
    {
        if (empty($role)) {
            return response()->json([]);
        }

        $template = Mod::with('division')->find($role);
        if (!$template) {
            return response()->json([]);
        }

        $user = $request->user();
        $userCompanyId = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;
        $templateCompanyId = optional($template->division)->company_id;

        $companyId = $templateCompanyId ?? $userCompanyId;

        $data = $this->treeModules($template, null, $companyId);
        return response()->json($data);
    }

    public function dataSummary(Request $request, $counter = true, $employee)
    {
        $query = AppraisalEmployeeSummary::with([
            'appraisal_employee',
            'appraisal_employee.period',
            'appraisal_employee.period.appraisal_period',
            'appraisal_employee.evaluator1',
            'appraisal_employee.evaluator1.organization',
            'appraisal_employee.evaluator2',
            'appraisal_employee.evaluator2.organization',
            'category'
        ])
            ->whereHas('appraisal_employee', function ($query) use ($employee) {
                $query->where('employ_id', $employee);
            })
            ->get();

        $grouped = [];

        foreach ($query as $item) {
            $key = $item->appraisal_employ_id;

            if (!isset($grouped[$key])) {
                $period = $item->appraisal_employee->period->appraisal_period->period ?? '-';
                $smester = $item->appraisal_employee->period->appraisal_period->smester ?? '-';
                $formattedPeriod = "$period - (PERIODE $smester)";

                $grouped[$key] = [
                    'employ_id' => $item->appraisal_employee->employ_id,
                    'appraisal_employ_id' => $key,
                    'formatted_period' => $formattedPeriod ?? '-',
                    'period' => $period ?? '-',
                    'smester' => $smester ?? '-',
                    'evaluator1' => $item->appraisal_employee->evaluator1 ?? '-',
                    'evaluator2' => $item->appraisal_employee->evaluator2 ?? '-',
                    'tech_weight' => null,
                    'tech_eval1_point' => null,
                    'tech_eval1_grade' => null,
                    'tech_eval2_point' => null,
                    'tech_eval2_grade' => null,
                    'behavior_weight' => null,
                    'behavior_eval1_point' => null,
                    'behavior_eval1_grade' => null,
                    'behavior_eval2_point' => null,
                    'behavior_eval2_grade' => null,
                    'leadership_weight' => null,
                    'leadership_eval1_point' => null,
                    'leadership_eval1_grade' => null,
                    'leadership_eval2_point' => null,
                    'leadership_eval2_grade' => null,
                    'final_score' => $item->appraisal_employee->total_point ?? null,
                    'final_grade' => $this->getGrade($item->appraisal_employee->grade) ?? null,
                ];
            }

            $eval1_point = is_numeric($item->evaluator1_point) ? number_format((float)$item->evaluator1_point, 2, '.', '') : null;
            $eval1_grade = $eval1_point !== null ? $this->getGrade($eval1_point) : null;

            $eval2_point = is_numeric($item->evaluator2_point) ? number_format((float)$item->evaluator2_point, 2, '.', '') : null;
            $eval2_grade = $eval2_point !== null ? $this->getGrade($eval2_point) : null;

            switch ($item->category_id) {
                case 1:
                    $grouped[$key]['tech_weight'] = $item->evaluator1_weight ?? $item->evaluator2_weight;
                    $grouped[$key]['tech_eval1_point'] = $eval1_point;
                    $grouped[$key]['tech_eval1_grade'] = $eval1_grade;
                    $grouped[$key]['tech_eval2_point'] = $eval2_point;
                    $grouped[$key]['tech_eval2_grade'] = $eval2_grade;
                    break;

                case 2:
                    $grouped[$key]['behavior_weight'] = $item->evaluator1_weight ?? $item->evaluator2_weight;
                    $grouped[$key]['behavior_eval1_point'] = $eval1_point;
                    $grouped[$key]['behavior_eval1_grade'] = $eval1_grade;
                    $grouped[$key]['behavior_eval2_point'] = $eval2_point;
                    $grouped[$key]['behavior_eval2_grade'] = $eval2_grade;
                    break;

                case 3:
                    $grouped[$key]['leadership_weight'] = $item->evaluator1_weight ?? $item->evaluator2_weight;
                    $grouped[$key]['leadership_eval1_point'] = $eval1_point;
                    $grouped[$key]['leadership_eval1_grade'] = $eval1_grade;
                    $grouped[$key]['leadership_eval2_point'] = $eval2_point;
                    $grouped[$key]['leadership_eval2_grade'] = $eval2_grade;
                    break;
            }
        }

        return response()->json([
            'count' => count($grouped),
            'data' => array_values($grouped),
        ]);
    }

    public function dataQuestion(Request $request, $counter = true)
    {
        $query = AppraisalEmployeeQuestion::with(['appraisal_employee', 'question']);
        $result = Query::open($query, [], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['division', 'appraisal_questions'])->where('id', $id)->first();
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => [
                    'required',
                    'string',
                    Rule::unique('iq_appraisal_question_template', 'title')
                        ->where(function ($query) use ($request) {
                            return $query->where('period_year', $request->period_year)
                                ->where('period_smt', $request->period_smt)
                                ->whereNull('deleted_at');
                        })
                ],
                'period_year' => 'required|string',
                'period_smt' => 'required|integer',
                'division_id' => 'required|integer|exists:iq_division,id',
                'duplicate_id' => 'nullable|integer|exists:iq_appraisal_question_template,id',
                'is_locked' => 'nullable|boolean',
                'is_archived' => 'nullable|boolean',
                'description' => 'nullable|string',
                'tech.*' => 'nullable',
                'behavior.*' => 'nullable',
                'leadership.*' => 'nullable',
            ], [
                'title.required' => 'Template Title is required.',
                'title.unique' => 'An Appraisal Template with this Title already exists for the selected Period Year and Semester.',
                'period_year.required' => 'Period Year is required.',
                'period_smt.required' => 'Period Semester is required.',
                'division_id.required' => 'Division is required.',
                'division_id.exists' => 'The selected division is invalid or does not exist in the system.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create appraisal template: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $appraisal = Mod::create([
                'title' => $request->title,
                'period_year' => $request->period_year,
                'period_smt' => $request->period_smt,
                'division_id' => $request->division_id,
                'is_locked' => $request->has('is_locked') ? (int)$request->is_locked : 0,
                'is_archived' => $request->has('is_archived') ? (int)$request->is_archived : 0,
                'description' => $request->description
            ]);

            $data_detail_appraisal = [
                'tech' => $this->deduplicateQuestions($request->tech ?? []),
                'behavior' => $this->deduplicateQuestions($request->behavior ?? []),
                'leadership' => $this->deduplicateQuestions($request->leadership ?? [])
            ];

            AppraisalQuestion::where('template_id', $appraisal->id)->forceDelete();
            $this->processAppraisalDetail($data_detail_appraisal, $appraisal->id);

            if ($request->filled('duplicate_id')) {
                $templateDivision = DB::table('iq_division')->where('id', $request->division_id)->first();
                $companyId = $templateDivision?->company_id;

                $targetPeriodQuery = DB::table('iq_appraisal_period')
                    ->where('period', $request->period_year)
                    ->where('smester', $request->period_smt);
                if ($companyId) {
                    $targetPeriodQuery->where('company_id', $companyId);
                }
                $targetPeriod = $targetPeriodQuery->first();

                if ($targetPeriod) {
                    $sourceOrganizations = DB::table('iq_appraisal_period_organization')
                        ->where('template_id', $request->duplicate_id)
                        ->get();

                    foreach ($sourceOrganizations as $srcOrg) {
                        DB::table('iq_appraisal_period_organization')
                            ->where('period_id', $targetPeriod->id)
                            ->where('organization_id', $srcOrg->organization_id)
                            ->delete();

                        DB::table('iq_appraisal_period_organization')->insert([
                            'period_id' => $targetPeriod->id,
                            'organization_id' => $srcOrg->organization_id,
                            'template_id' => $appraisal->id,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $appraisal,
                'message' => 'Appraisal template created successfully.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $this->formatExceptionMessage($e, 'Failed to create appraisal template.'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:iq_appraisal_question_template,id',
                'title' => [
                    'required',
                    'string',
                    Rule::unique('iq_appraisal_question_template', 'title')
                        ->ignore($request->id)
                        ->where(function ($query) use ($request) {
                            return $query->where('period_year', $request->period_year)
                                ->where('period_smt', $request->period_smt)
                                ->whereNull('deleted_at');
                        })
                ],
                'period_year' => 'required|string',
                'period_smt' => 'required|integer',
                'division_id' => 'required|integer|exists:iq_division,id',
                'is_locked' => 'nullable|boolean',
                'is_archived' => 'nullable|boolean',
                'description' => 'nullable|string',
                'tech.*' => 'nullable',
                'behavior.*' => 'nullable',
                'leadership.*' => 'nullable',
            ], [
                'title.required' => 'Template Title is required.',
                'title.unique' => 'An Appraisal Template with this Title already exists for the selected Period Year and Semester.',
                'period_year.required' => 'Period Year is required.',
                'period_smt.required' => 'Period Semester is required.',
                'division_id.required' => 'Division is required.',
                'division_id.exists' => 'The selected division is invalid or does not exist in the system.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update appraisal template: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $appraisal = Mod::find($request->id);

            if (!$appraisal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Appraisal not found'
                ], 404);
            }

            $data_detail_appraisal = array_merge(
                ['tech' => $request->tech ?? []],
                ['behavior' => $request->behavior ?? []],
                ['leadership' => $request->leadership ?? []]
            );

            if (count($data_detail_appraisal['tech']) > 0) {
                $this->deleteRelatedItems(
                    AppraisalQuestion::where('template_id', $appraisal->id)->where('category_id', 1)->get(),
                    collect($data_detail_appraisal['tech']),
                );
            }

            if (count($data_detail_appraisal['behavior']) > 0) {
                $this->deleteRelatedItems(
                    AppraisalQuestion::where('template_id', $appraisal->id)->where('category_id', 2)->get(),
                    collect($data_detail_appraisal['behavior']),
                );
            }

            if (count($data_detail_appraisal['leadership']) > 0) {
                $this->deleteRelatedItems(
                    AppraisalQuestion::where('template_id', $appraisal->id)->where('category_id', 3)->get(),
                    collect($data_detail_appraisal['leadership']),
                );
            }

            $this->processAppraisalDetail($data_detail_appraisal, $appraisal->id);

            $appraisal->title = $request->title;
            $appraisal->period_year = $request->period_year;
            $appraisal->period_smt = $request->period_smt;
            $appraisal->division_id = $request->division_id;
            $appraisal->is_locked = $request->has('is_locked') ? (int)$request->is_locked : 0;
            $appraisal->is_archived = $request->has('is_archived') ? (int)$request->is_archived : 0;
            $appraisal->description = $request->description;
            $appraisal->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $appraisal,
                'message' => 'Appraisal updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $this->formatExceptionMessage($e, 'Error updating appraisal.'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '64048M');
        ini_set('max_execution_time', '300');

        $title = [];

        $title[] = ['Appraisal', 'h2'];

        $dataStatus = 'All';

        if ($request->input('trash') !== null && $request->input('trash') !== 'null') {
            $dataStatus = $request->input('trash') == 1 ? 'Active' : 'Deleted';
        }

        if ($request->filled('archived') && $request->archived == 1) {
            $dataStatus .= ' | Archived';
        }

        $title[] = ['DATA : ' . $dataStatus, 'h5'];

        if ($request->filled('division') && $request->division !== 'all' && $request->division != 0) {
            $client = Division::find($request->input('division'));
            if ($client) {
                $title[] = ['DIVISION : ' . $client->title, 'h5'];
            }
        }

        $data = $this->data($request, false);

        $columns = [[
            'text' => 'Template',
            'columns' => [
                ['text' => 'TITLE', 'dataIndex' => 'title', 'width' => 150, 'align' => 'center'],
                ['text' => 'PERIOD YEAR', 'dataIndex' => 'period_year', 'width' => 150, 'align' => 'center'],
                ['text' => 'PERIOD SMT', 'dataIndex' => 'period_smt', 'width' => 150, 'align' => 'center'],
                [
                    'text' => 'DIVISION',
                    'dataIndex' => 'division',
                    'width' => 150,
                    'align' => 'center',
                    'renderer' => function ($e) {
                        return $e ? $e->name : '-';
                    }
                ],
                ['text' => 'IS LOCKED', 'dataIndex' => 'is_locked', 'width' => 150, 'align' => 'center'],
                ['text' => 'DESCRIPTION', 'dataIndex' => 'description', 'width' => 200, 'align' => 'center'],
            ]
        ]];

        function generateCategoryColumns($categoryName, $categoryId)
        {
            return [
                ['text' => $categoryName, 'columns' => [
                    ['text' => 'Group KPI', 'dataIndex' => "appraisal_questions", 'width' => 150, 'align' => 'center', 'renderer' => function ($questions) use ($categoryId) {
                        $items = collect($questions)
                            ->where('category_id', $categoryId)
                            ->pluck('group_kpi');
                        if ($items->count() > 1) {
                            return $items->map(fn($item) => "• " . $item)->implode("\n");
                        } else {
                            return $items->first() ?? '-';
                        }
                    }],
                    ['text' => 'Question', 'dataIndex' => "appraisal_questions", 'width' => 300, 'align' => 'left', 'renderer' => function ($questions) use ($categoryId) {
                        $items = collect($questions)
                            ->where('category_id', $categoryId)
                            ->pluck('question');
                        if ($items->count() > 1) {
                            return $items->map(fn($item) => "• " . $item)->implode("\n");
                        } else {
                            return $items->first() ?? '-';
                        }
                    }],
                    ['text' => 'Formula Description', 'dataIndex' => "appraisal_questions", 'width' => 200, 'align' => 'center', 'renderer' => function ($questions) use ($categoryId) {
                        $items = collect($questions)
                            ->where('category_id', $categoryId)
                            ->pluck('formula_description');
                        if ($items->count() > 1) {
                            return $items->map(fn($item) => "• " . $item)->implode("\n");
                        } else {
                            return $items->first() ?? '-';
                        }
                    }],
                    ['text' => 'Weight', 'dataIndex' => "appraisal_questions", 'width' => 100, 'align' => 'center', 'renderer' => function ($questions) use ($categoryId) {
                        $items = collect($questions)
                            ->where('category_id', $categoryId)
                            ->pluck('weight');
                        if ($items->count() > 1) {
                            return $items->map(fn($item) => "• " . $item)->implode("\n");
                        } else {
                            return $items->first() ?? '-';
                        }
                    }],
                ]]
            ];
        }

        $columns = array_merge($columns, generateCategoryColumns('Technical Ability & Work Result', 1));
        $columns = array_merge($columns, generateCategoryColumns('Behavior & Work Processes', 2));
        $columns = array_merge($columns, generateCategoryColumns('Leadership', 3));

        $params = [
            'title' => $title,
            'columns' => $columns,
            'data' => $data,
            'filename' => 'Appraisal Template' . '-' . date('YmdHi'),
            'footer' => [config('app.name') . ' (' . date('d F Y H:i:s') . ')'],
        ];

        return ExportExcel::export($params);
    }

    public function importFormat(Request $request)
    {
        $filename = 'appraisal_template_format.xlsx';
        return Excel::download(new Format(), $filename);
    }

    public function importData(Request $request)
    {
        if ($upload = FileUpload::upload('file', 'appraisal-template-import')) {
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

    public function setArchived(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();

                foreach ($data as $id) {
                    $rec = Mod::find($id);
                    if ($rec) {
                        $rec->is_archived = $rec->is_archived == 1 ? 0 : 1;
                        $rec->save();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Appraisal template archiving status updated successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No appraisal templates selected for archiving.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update archiving status: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setOrganization(Request $request, $template)
    {
        try {
            $validatedData = $request->validate([
                'period' => 'required|integer|exists:iq_appraisal_period,id',
                'organization' => 'required|integer|exists:iq_org,id',
                'auth' => 'nullable|boolean'
            ]);

            $period_id = $validatedData['period'];
            $organization_id = $validatedData['organization'];
            $auth = $validatedData['auth'] ?? false;

            DB::beginTransaction();

            DB::table('iq_appraisal_period_organization')
                ->where('period_id', $period_id)
                ->where('organization_id', $organization_id)
                ->delete();

            if ($auth) {
                DB::table('iq_appraisal_period_organization')->insert([
                    'period_id' => $period_id,
                    'organization_id' => $organization_id,
                    'template_id' => $template,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template organization assignment updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update template organization assignment: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function frontEvaluator(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'answers' => 'required|array',
                'period_id' => 'required|integer|exists:iq_appraisal_period_organization,id',
                'employee_id' => 'required|string|exists:iq_employ,id',
                'organization_id' => 'required|integer|exists:iq_org,id',
                'template_id' => 'required|integer|exists:iq_appraisal_question_template,id',
                'evaluator_role' => 'required|string|in:evaluator1,evaluator2,single_evaluator',
                'user_id' => 'required|string|exists:iq_employ,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process evaluation: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $roleName = strtolower(optional(optional($user)->role)->name ?? '');
            $isAdmin = in_array($roleName, ['developer', 'superadmin', 'administrator']);

            $targetEmployee = Employee::with(['organization', 'organization.authorized1', 'organization.authorized2'])->find($request->employee_id);
            if (!$targetEmployee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Target employee not found.'
                ], 404);
            }

            $userOrgId = optional(optional($user)->employee)->org_id ?? optional($user)->organization_id;
            $empOrg = $targetEmployee->organization;

            $auth1Id = $empOrg?->authorized1?->id ?? (is_scalar($empOrg?->authorized1) ? $empOrg?->authorized1 : null);
            $auth2Id = $empOrg?->authorized2?->id ?? (is_scalar($empOrg?->authorized2) ? $empOrg?->authorized2 : null);

            $isAuth1 = $auth1Id && $userOrgId && (string) $auth1Id === (string) $userOrgId;
            $isAuth2 = $auth2Id && $userOrgId && (string) $auth2Id === (string) $userOrgId;

            if (!$isAdmin && !$isAuth1 && !$isAuth2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You are not authorized as an evaluator for this employee.'
                ], 403);
            }

            $appraisal_employee = AppraisalEmployee::firstOrCreate(
                [
                    'period_id' => $request->period_id,
                    'template_id' => $request->template_id,
                    'employ_id' => $request->employee_id,
                ],
                [
                    'total_point' => 0,
                    'grade' => 0,
                ]
            );

            if (!$appraisal_employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create or find appraisal employee record.',
                ], 500);
            }

            foreach ($request->answers as $answer) {
                $existingAnswer = DB::table('iq_appraisal_employ_question')
                    ->where('appraisal_employ_id', $appraisal_employee->id)
                    ->where('question_id', $answer['questionId'])
                    ->first();

                $e1Val = array_key_exists('evaluator1', $answer) && $answer['evaluator1'] !== null && $answer['evaluator1'] !== ''
                    ? $answer['evaluator1']
                    : ($existingAnswer->evaluator1_point ?? null);

                $e2Val = array_key_exists('evaluator2', $answer) && $answer['evaluator2'] !== null && $answer['evaluator2'] !== ''
                    ? $answer['evaluator2']
                    : ($existingAnswer->evaluator2_point ?? null);

                $weight = $answer['evalutor1Weight'] ?? $answer['evalutor2Weight'] ?? DB::table('iq_appraisal_question')->where('id', $answer['questionId'])->value('weight');

                DB::table('iq_appraisal_employ_question')->updateOrInsert(
                    [
                        'appraisal_employ_id' => $appraisal_employee->id,
                        'question_id' => $answer['questionId']
                    ],
                    [
                        'evaluator1_value' => $e1Val,
                        'evaluator1_point' => $e1Val,
                        'evaluator1_weight' => $weight,
                        'evaluator1_note' => array_key_exists('evaluator1Note', $answer) ? $answer['evaluator1Note'] : ($existingAnswer->evaluator1_note ?? null),
                        'evaluator2_value' => $e2Val,
                        'evaluator2_point' => $e2Val,
                        'evaluator2_weight' => $weight,
                        'evaluator2_note' => array_key_exists('evaluator2Note', $answer) ? $answer['evaluator2Note'] : ($existingAnswer->evaluator2_note ?? null),
                    ]
                );
            }

            if ($request->evaluator_role === 'evaluator1') {
                $appraisal_employee->update([
                    'evaluator1_by' => $request->user_id,
                    'evaluator1_at' => now(),
                ]);
            } elseif ($request->evaluator_role === 'evaluator2') {
                $appraisal_employee->update([
                    'evaluator2_by' => $request->user_id,
                    'evaluator2_at' => now(),
                ]);
            } elseif ($request->evaluator_role === 'single_evaluator') {
                $appraisal_employee->update([
                    'evaluator1_by' => $request->user_id,
                    'evaluator1_at' => now(),
                    'evaluator2_by' => $request->user_id,
                    'evaluator2_at' => now(),
                ]);
            }

            $categoryIds = DB::table('iq_appraisal_employ_question')
                ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
                ->where('iq_appraisal_employ_question.appraisal_employ_id', $appraisal_employee->id)
                ->pluck('iq_appraisal_question.category_id')
                ->unique();

            foreach ($categoryIds as $catId) {
                $catQuestions = DB::table('iq_appraisal_employ_question')
                    ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
                    ->where('iq_appraisal_employ_question.appraisal_employ_id', $appraisal_employee->id)
                    ->where('iq_appraisal_question.category_id', $catId)
                    ->select('iq_appraisal_employ_question.*', 'iq_appraisal_question.weight as q_weight')
                    ->get();

                $e1WeightedScore = 0;
                $e1Weight = 0;
                $e2WeightedScore = 0;
                $e2Weight = 0;

                foreach ($catQuestions as $cq) {
                    $w = (float)($cq->q_weight ?? $cq->evaluator1_weight ?? 0);
                    if (is_numeric($cq->evaluator1_point)) {
                        $e1WeightedScore += ((float)$cq->evaluator1_point * $w);
                        $e1Weight += $w;
                    }
                    if (is_numeric($cq->evaluator2_point)) {
                        $e2WeightedScore += ((float)$cq->evaluator2_point * $w);
                        $e2Weight += $w;
                    }
                }

                $e1Avg = $e1Weight > 0 ? round($e1WeightedScore / $e1Weight, 2) : null;
                $e2Avg = $e2Weight > 0 ? round($e2WeightedScore / $e2Weight, 2) : null;

                DB::table('iq_appraisal_employ_summary')->updateOrInsert(
                    [
                        'appraisal_employ_id' => $appraisal_employee->id,
                        'category_id' => $catId
                    ],
                    [
                        'evaluator1_weight' => $e1Weight > 0 ? $e1Weight : null,
                        'evaluator1_point' => $e1Avg,
                        'evaluator1_grade' => $e1Avg,
                        'evaluator2_weight' => $e2Weight > 0 ? $e2Weight : null,
                        'evaluator2_point' => $e2Avg,
                        'evaluator2_grade' => $e2Avg,
                    ]
                );
            }

            $allEmpQuestions = DB::table('iq_appraisal_employ_question')
                ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
                ->where('iq_appraisal_employ_question.appraisal_employ_id', $appraisal_employee->id)
                ->select('iq_appraisal_employ_question.*', 'iq_appraisal_question.weight as q_weight')
                ->get();

            $totalWeightedScore = 0;
            $totalWeight = 0;

            foreach ($allEmpQuestions as $q) {
                $e1 = is_numeric($q->evaluator1_point) ? (float)$q->evaluator1_point : null;
                $e2 = is_numeric($q->evaluator2_point) ? (float)$q->evaluator2_point : null;

                if ($e1 !== null && $e2 !== null) {
                    $kpiScore = ($e1 + $e2) / 2;
                } elseif ($e1 !== null) {
                    $kpiScore = $e1;
                } elseif ($e2 !== null) {
                    $kpiScore = $e2;
                } else {
                    continue;
                }

                $w = (float)($q->q_weight ?? $q->evaluator1_weight ?? 0);
                if ($w <= 0) continue;

                $totalWeightedScore += ($kpiScore * $w);
                $totalWeight += $w;
            }

            $finalScore = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;

            $appraisal_employee->update([
                'total_point' => $finalScore,
                'grade' => $finalScore,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Evaluation data submitted successfully.',
                'data' => [
                    'appraisal_employee' => $appraisal_employee,
                    'appraisal_questions' => AppraisalQuestion::where('template_id', $request->template_id)->get(),
                    'summaries' => AppraisalEmployeeSummary::where('appraisal_employ_id', $appraisal_employee->id)->get()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to process evaluation data: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function frontExportPdf(Request $request, $employeeId)
    {
        try {
            $employee = Employee::with('organization')->find($employeeId);
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            $allSummaryResponse = $this->dataSummary($request, false, $employeeId);
            $allSummary = $allSummaryResponse instanceof \Illuminate\Http\JsonResponse
                ? $allSummaryResponse->getData(true)
                : [];

            $summary = collect($allSummary['data'] ?? [])->first(function ($item) use ($request) {
                return $item['period'] == $request->period && $item['smester'] == $request->smester;
            });

            if (!$summary) {
                return response()->json(['success' => false, 'message' => 'Summary not found'], 404);
            }

            $allQuestionResponse = $this->dataQuestion($request, false);
            if ($allQuestionResponse instanceof \Illuminate\Http\JsonResponse) {
                $allQuestion = $allQuestionResponse->getData(true)['data'] ?? [];
            } elseif ($allQuestionResponse instanceof \Illuminate\Support\Collection) {
                $allQuestion = $allQuestionResponse->toArray();
            } else {
                $allQuestion = [];
            }

            $question = collect($allQuestion)->filter(function ($item) use ($summary) {
                return $item['appraisal_employ_id'] == $summary['appraisal_employ_id'];
            });

            if ($question->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Question data not found'], 404);
            }

            $fileName = preg_replace('/\s+/', '_', ucfirst($employee->fullname));
            $period = $summary['period'];
            $semester = $summary['smester'];

            $params = [
                'employee' => $employee,
                'appraisal' => $summary,
                'questions' => $question,
            ];

            $pdf = Pdf::loadView('reports.appraisal_pdf', $params);
            return $pdf->stream("Appraisal_Report_{$fileName}_Period_{$period}_Semester_{$semester}.pdf");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting PDF',
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
                    $qeustion = AppraisalQuestion::where('template_id', $id)->get();

                    foreach ($qeustion as $question) {
                        $question->delete();
                    }

                    if ($rec) {
                        $rec->delete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Appraisal templates deleted successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No appraisal templates selected for deletion.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete appraisal templates: ' . $e->getMessage(),
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
                    $qeustion = AppraisalQuestion::where('template_id', $id)->withTrashed()->get();

                    foreach ($qeustion as $question) {
                        $question->restore();
                    }

                    if ($rec) {
                        $rec->restore();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Appraisal templates restored successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No appraisal templates selected for restoration.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to restore appraisal templates: ' . $e->getMessage(),
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
                    $qeustion = AppraisalQuestion::where('template_id', $id)->withTrashed()->get();

                    foreach ($qeustion as $question) {
                        $question->forceDelete();
                    }

                    if ($rec) {
                        $rec->forceDelete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Appraisal templates permanently deleted successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No appraisal templates selected for permanent deletion.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete appraisal templates: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processAppraisalDetail($data, $appraisalId)
    {
        if (isset($data['tech']) && is_array($data['tech']) && count($data['tech']) > 0) {
            foreach ($data['tech'] as $tech) {
                if ($tech) {
                    if (isset($tech['id'])) {
                        $existingTech = AppraisalQuestion::find($tech['id']);
                        if ($existingTech) {
                            $existingTech->update([
                                'template_id' => $appraisalId,
                                'category_id' => 1,
                                'group_kpi' => $tech['group_kpi'] ?? null,
                                'question' => $tech['question'] ?? null,
                                'formula_description' => $tech['formula_description'] ?? null,
                                'weight' => $tech['weight'] ?? null,
                            ]);
                        }
                    } else {
                        AppraisalQuestion::create([
                            'template_id' => $appraisalId,
                            'category_id' => 1,
                            'group_kpi' => $tech['group_kpi'] ?? null,
                            'question' => $tech['question'] ?? null,
                            'formula_description' => $tech['formula_description'] ?? null,
                            'weight' => $tech['weight'] ?? null,
                        ]);
                    }
                }
            }
        }

        if (isset($data['behavior']) && is_array($data['behavior']) && count($data['behavior']) > 0) {
            foreach ($data['behavior'] as $behavior) {
                if ($behavior) {
                    if (isset($behavior['id'])) {
                        $existingTech = AppraisalQuestion::find($behavior['id']);
                        if ($existingTech) {
                            $existingTech->update([
                                'template_id' => $appraisalId,
                                'category_id' => 2,
                                'group_kpi' => $behavior['group_kpi'] ?? null,
                                'question' => $behavior['question'] ?? null,
                                'formula_description' => $behavior['formula_description'] ?? null,
                                'weight' => $behavior['weight'] ?? null,
                            ]);
                        }
                    } else {
                        AppraisalQuestion::create([
                            'template_id' => $appraisalId,
                            'category_id' => 2,
                            'group_kpi' => $behavior['group_kpi'] ?? null,
                            'question' => $behavior['question'] ?? null,
                            'formula_description' => $behavior['formula_description'] ?? null,
                            'weight' => $behavior['weight'] ?? null,
                        ]);
                    }
                }
            }
        }

        if (isset($data['leadership']) && is_array($data['leadership']) && count($data['leadership']) > 0) {
            foreach ($data['leadership'] as $leadership) {
                if ($leadership) {
                    if (isset($leadership['id'])) {
                        $existingTech = AppraisalQuestion::find($leadership['id']);
                        if ($existingTech) {
                            $existingTech->update([
                                'template_id' => $appraisalId,
                                'category_id' => 3,
                                'group_kpi' => $leadership['group_kpi'] ?? null,
                                'question' => $leadership['question'] ?? null,
                                'formula_description' => $leadership['formula_description'] ?? null,
                                'weight' => $leadership['weight'] ?? null,
                            ]);
                        }
                    } else {
                        AppraisalQuestion::create([
                            'template_id' => $appraisalId,
                            'category_id' => 3,
                            'group_kpi' => $leadership['group_kpi'] ?? null,
                            'question' => $leadership['question'] ?? null,
                            'formula_description' => $leadership['formula_description'] ?? null,
                            'weight' => $leadership['weight'] ?? null,
                        ]);
                    }
                }
            }
        }
    }

    private function deduplicateQuestions($items)
    {
        if (!is_array($items)) return [];
        $unique = [];
        $seen = [];
        foreach ($items as $item) {
            if (!$item || !is_array($item)) continue;
            $key = trim($item['group_kpi'] ?? '') . '|' . trim($item['question'] ?? '') . '|' . trim($item['formula_description'] ?? '') . '|' . trim($item['weight'] ?? '');
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = Arr::except($item, ['id']);
            }
        }
        return $unique;
    }

    private function getGrade($score)
    {
        if ($score === null || $score === '') return null;

        $score = round((float)$score, 2);

        if ($score >= 9.50) return 'Outstanding';
        if ($score >= 8.50) return 'Excellent';
        if ($score >= 7.50) return 'Good';
        if ($score >= 6.50) return 'Fair';
        return 'Need Improvement';
    }

    private function deleteRelatedItems($existingItems, $currentItems)
    {
        $itemsToDelete = $existingItems->filter(function ($existing) use ($currentItems) {
            return !$currentItems->contains(function ($current) use ($existing) {
                if (isset($current['id']) && isset($existing['id'])) {
                    return $current['id'] == $existing->id;
                }
            });
        });

        foreach ($itemsToDelete as $item) {
            $item->forceDelete();
        }
    }

    private function treeModules($template, $parent = null, $companyId = null)
    {
        $query = Organization::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if (is_null($parent)) {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', $parent);
        }

        $result = $query->orderBy('name')->get()->unique('id')->values();

        foreach ($result as $row) {
            $row->children = $this->treeModules($template, $row->id, $companyId);
            $row->leaf      = count($row->children) ? false : true;
            $row->checked   = $row->hasTemplateOrganization($template->id);
            $row->icon      = asset('images/icons/' . ($row->type->icon ?? 'home') . '.png');
            $row->home      = ($template->home == $row->id) ? true : false;
        }

        return $result;
    }

    private function formatExceptionMessage(\Throwable $e, string $fallback = 'An unexpected error occurred.'): string
    {
        $msg = $e->getMessage();
        if ($e instanceof \Illuminate\Database\QueryException || $e->getCode() == 23000) {
            if (str_contains($msg, 'Duplicate entry') || str_contains($msg, '1062')) {
                return 'An appraisal template with this title already exists for the selected period year and semester.';
            }
            if (str_contains($msg, 'Cannot delete or update a parent row') || str_contains($msg, '1451')) {
                return 'Cannot delete this record because it is currently being referenced by other data.';
            }
            if (str_contains($msg, 'Cannot add or update a child row') || str_contains($msg, '1452')) {
                return 'Invalid reference data provided. Please verify related records exist.';
            }
            return 'Database constraint error. Please check your data and try again.';
        }
        return $fallback;
    }
}
