<?php

namespace App\Imports\Appraisal\Template;

use App\Models\Appraisals\AppraisalPeriod;
use App\Models\Appraisals\AppraisalQuestion;
use App\Models\Appraisals\AppraisalQuestionCategory;
use App\Models\Appraisals\AppraisalQuestionTemplate;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportSheet implements ToCollection, WithChunkReading
{
    public $logs = [];
    private $user = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    private function isExcelError($v): bool
    {
        if (!is_string($v)) return false;
        $v = strtoupper(trim($v));
        $errs = ['#N/A', '#DIV/0!', '#VALUE!', '#REF!', '#NAME?', '#NUM!', '#NULL!'];
        return in_array($v, $errs, true);
    }

    private function vStr($v): ?string
    {
        if ($this->isExcelError($v)) return null;
        if (is_null($v)) return null;
        if (is_string($v)) {
            $s = trim($v);
        } elseif (is_numeric($v)) {
            $s = (string)$v;
        } else {
            $s = '';
        }
        $s = preg_replace("/\x{00A0}|\x{200B}|\x{200E}|\x{200F}/u", '', $s);
        return $s === '' ? null : $s;
    }

    private function vInt($v): ?int
    {
        if ($this->isExcelError($v) || $v === '' || $v === null) return null;
        if (is_numeric($v)) return (int)$v;
        $v = preg_replace('/[^\d\-]/', '', (string)$v);
        return $v === '' ? null : (int)$v;
    }

    private function vFloat($v): ?float
    {
        if ($this->isExcelError($v) || $v === '' || $v === null) return null;
        if (is_numeric($v)) return (float)$v;
        if (is_string($v)) {
            $s = trim($v);
            $s = str_replace(',', '.', $s);
            $s = preg_replace('/[^\d\.\-]/', '', $s);
            if (is_numeric($s)) return (float)$s;
        }
        return null;
    }

    private function vBool($v): int
    {
        $t = is_string($v) ? strtolower(trim($v)) : $v;
        $truthy = ['1', 'true', 'yes', 'ya', 'y'];
        $falsy  = ['0', 'false', 'no', 'tidak', 'n', '', null];
        if (in_array($t, $truthy, true)) return 1;
        if (in_array($t, $falsy,  true)) return 0;
        return filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ? 1 : 0;
    }

    private function allNull(array $vals): bool
    {
        foreach ($vals as $v) {
            if ($v !== null && $v !== '') return false;
        }
        return true;
    }

    public function collection(Collection $rows)
    {
        $startLine = 4;
        $totalRow     = 0;
        $totalSuccess = 0;
        $totalError   = 0;
        $log          = [];

        $validDivisionIds = Division::pluck('id')->toArray();
        $validCategoryIds = AppraisalQuestionCategory::pluck('id')->toArray();
        $groups = [];
        $lastFilled = [
            'title'       => null,
            'period_year' => null,
            'period_smt'  => null,
            'division_id' => null,
            'is_locked'   => 0,
            'is_archived' => 0,
            'description' => null,
        ];

        $col = function ($row, int $idx) {
            if ($row instanceof Collection) {
                return $row->get($idx);
            }
            if (is_array($row)) {
                return array_key_exists($idx, $row) ? $row[$idx] : null;
            }
            return null;
        };

        for ($i = $startLine; $i < count($rows); $i++) {
            $row = $rows[$i];

            $title       = $this->vStr($col($row, 0)) ?? $lastFilled['title'];
            $periodYear  = $this->vInt($col($row, 1)) ?? $lastFilled['period_year'];
            $periodSmt   = $this->vStr($col($row, 2)) ?? $lastFilled['period_smt'];
            $divisionId  = $this->vInt($col($row, 3)) ?? $lastFilled['division_id'];
            $isLocked    = $this->vBool($col($row, 5) ?? $lastFilled['is_locked']);
            $isArchived  = $this->vBool($col($row, 6) ?? $lastFilled['is_archived']);
            $description = $this->vStr($col($row, 7)) ?? $lastFilled['description'];

            if (
                $this->allNull([$title, $periodYear, $periodSmt, $divisionId, $description])
                && $isLocked === 0 && $isArchived === 0
            ) {
                continue;
            }

            $lastFilled = [
                'title'       => $title,
                'period_year' => $periodYear,
                'period_smt'  => $periodSmt,
                'division_id' => $divisionId,
                'is_locked'   => $isLocked,
                'is_archived' => $isArchived,
                'description' => $description,
            ];

            if (!in_array($divisionId, $validDivisionIds)) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Undefined Division ({$divisionId})"];
                $totalError++;
                $totalRow++;
                continue;
            }
            if (!$title || !$periodYear || !$periodSmt) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Missing required fields in AppraisalQuestionTemplate"];
                $totalError++;
                $totalRow++;
                continue;
            }
            $periodExists = AppraisalPeriod::where('period', $periodYear)
                ->where('smester', $periodSmt)
                ->exists();
            if (!$periodExists) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Appraisal Period not found: {$periodYear}/{$periodSmt}"];
                $totalError++;
                $totalRow++;
                continue;
            }

            $key = implode('|', [$title, $periodYear, $periodSmt, $divisionId]);
            $uid = $this->user->id;
            $templateData = [
                'title'         => $title,
                'period_year'   => $periodYear,
                'period_smt'    => $periodSmt,
                'division_id'   => $divisionId,
                'is_locked'     => $isLocked,
                'is_archived'   => $isArchived,
                'description'   => $description,
                'created_by'    => $uid,
                'updated_by'    => $uid,
            ];

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'template' => $templateData,
                    'rows'     => [],
                ];
            }
            $groups[$key]['rows'][] = $i;

            $totalRow++;
        }

        if (empty($groups)) {
            $this->logs = [
                'totalRow' => $totalRow,
                'totalSuccess' => 0,
                'totalError' => $totalError,
                'errorLog' => $log,
            ];
            return;
        }

        try {
            DB::beginTransaction();

            $now = Carbon::now();
            $insertedTemplateIdsByKey = [];
            $validDataQuestion = [];

            foreach ($groups as $key => $payload) {
                $t = $payload['template'];

                $duplicateTemplate = AppraisalQuestionTemplate::where([
                    'title' => $t['title'],
                    'period_year' => $t['period_year'],
                    'period_smt' => $t['period_smt'],
                    'division_id' => $t['division_id'],
                ])->first();

                if ($duplicateTemplate) {
                    $duplicateTemplate->update([
                        'is_locked'   => $t['is_locked'],
                        'is_archived' => $t['is_archived'],
                        'description' => $t['description'],
                        'updated_by'  => $t['updated_by'],
                    ]);
                    $templateId = $duplicateTemplate->id;
                } else {
                    $templateId = AppraisalQuestionTemplate::create($t)->id;
                }

                $insertedTemplateIdsByKey[$key] = $templateId;

                foreach ($payload['rows'] as $rowIndex) {
                    $row = $rows[$rowIndex];
                    $categoryId         = $this->vInt($col($row, 8));
                    $groupKpi           = $this->vStr($col($row, 10));
                    $question           = $this->vStr($col($row, 11));
                    $formulaDescription = $this->vStr($col($row, 12));
                    $weight             = $this->vFloat($col($row, 13));

                    if ($this->allNull([$categoryId, $groupKpi, $question, $formulaDescription, $weight])) {
                        continue;
                    }

                    if ($categoryId === null || !$groupKpi || !$question || !$formulaDescription || $weight === null) {
                        $log[] = [
                            'row' => $rowIndex + 1,
                            'success' => false,
                            'message' => "Incomplete row for AppraisalQuestion (some fields null/#N/A)"
                        ];
                        $totalError++;
                        continue;
                    }

                    if (!in_array($categoryId, $validCategoryIds)) {
                        $log[] = ['row' => $rowIndex + 1, 'success' => false, 'message' => "Undefined Category ({$categoryId})"];
                        $totalError++;
                        continue;
                    }
                    if ($weight < 0) {
                        $log[] = ['row' => $rowIndex + 1, 'success' => false, 'message' => "Weight must be >= 0"];
                        $totalError++;
                        continue;
                    }

                    $validDataQuestion[] = [
                        'template_id'         => $templateId,
                        'category_id'         => $categoryId,
                        'group_kpi'           => $groupKpi,
                        'question'            => $question,
                        'formula_description' => $formulaDescription,
                        'weight'              => $weight,
                        'created_by'          => $t['created_by'],
                        'updated_by'          => $t['updated_by'],
                        'created_at'          => $now,
                        'updated_at'          => $now,
                    ];
                }
            }

            if (!empty($validDataQuestion)) {
                AppraisalQuestion::insert($validDataQuestion);
            }

            DB::commit();
            $totalSuccess += count($insertedTemplateIdsByKey);
        } catch (QueryException $e) {
            DB::rollBack();
            $log[] = [
                'row' => 'BATCH_INSERT',
                'success' => false,
                'message' => $e->getMessage()
            ];
            $totalError += count($groups);
        }

        $this->logs = [
            'totalRow'     => $totalRow,
            'totalSuccess' => $totalSuccess,
            'totalError'   => $totalError,
            'errorLog'     => $log,
        ];
    }

    // private function syncEmployeeAppraisals($templateId)
    // {
    //     $appEmps = DB::table('iq_appraisal_employ')->where('template_id', $templateId)->pluck('id');

    //     foreach ($appEmps as $appEmpId) {
    //         $catQuestions = DB::table('iq_appraisal_employ_question')
    //             ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
    //             ->where('iq_appraisal_employ_question.appraisal_employ_id', $appEmpId)
    //             ->select('iq_appraisal_employ_question.question_id', 'iq_appraisal_question.weight as q_weight')
    //             ->get();

    //         foreach ($catQuestions as $cq) {
    //             DB::table('iq_appraisal_employ_question')
    //                 ->where('appraisal_employ_id', $appEmpId)
    //                 ->where('question_id', $cq->question_id)
    //                 ->update([
    //                     'evaluator1_weight' => $cq->q_weight,
    //                     'evaluator2_weight' => $cq->q_weight,
    //                 ]);
    //         }

    //         $categoryIds = DB::table('iq_appraisal_employ_question')
    //             ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
    //             ->where('iq_appraisal_employ_question.appraisal_employ_id', $appEmpId)
    //             ->pluck('iq_appraisal_question.category_id')
    //             ->unique();

    //         foreach ($categoryIds as $catId) {
    //             $qs = DB::table('iq_appraisal_employ_question')
    //                 ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
    //                 ->where('iq_appraisal_employ_question.appraisal_employ_id', $appEmpId)
    //                 ->where('iq_appraisal_question.category_id', $catId)
    //                 ->get();

    //             $e1WeightedScore = 0;
    //             $e1Weight = 0;
    //             $e2WeightedScore = 0;
    //             $e2Weight = 0;

    //             foreach ($qs as $cq) {
    //                 $w = (float)($cq->q_weight ?? $cq->evaluator1_weight ?? 0);
    //                 if (is_numeric($cq->evaluator1_point)) {
    //                     $e1WeightedScore += ((float)$cq->evaluator1_point * $w);
    //                     $e1Weight += $w;
    //                 }
    //                 if (is_numeric($cq->evaluator2_point)) {
    //                     $e2WeightedScore += ((float)$cq->evaluator2_point * $w);
    //                     $e2Weight += $w;
    //                 }
    //             }

    //             $e1Avg = $e1Weight > 0 ? round($e1WeightedScore / $e1Weight, 2) : null;
    //             $e2Avg = $e2Weight > 0 ? round($e2WeightedScore / $e2Weight, 2) : null;

    //             DB::table('iq_appraisal_employ_summary')->updateOrInsert(
    //                 [
    //                     'appraisal_employ_id' => $appEmpId,
    //                     'category_id' => $catId
    //                 ],
    //                 [
    //                     'evaluator1_weight' => $e1Weight > 0 ? $e1Weight : null,
    //                     'evaluator1_point' => $e1Avg,
    //                     'evaluator1_grade' => $e1Avg,
    //                     'evaluator2_weight' => $e2Weight > 0 ? $e2Weight : null,
    //                     'evaluator2_point' => $e2Avg,
    //                     'evaluator2_grade' => $e2Avg,
    //                 ]
    //             );
    //         }

    //         $allEmpQuestions = DB::table('iq_appraisal_employ_question')
    //             ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
    //             ->where('iq_appraisal_employ_question.appraisal_employ_id', $appEmpId)
    //             ->select('iq_appraisal_employ_question.*', 'iq_appraisal_question.weight as q_weight')
    //             ->get();

    //         $totalWeightedScore = 0;
    //         $totalWeight = 0;

    //         foreach ($allEmpQuestions as $q) {
    //             $e1 = is_numeric($q->evaluator1_point) ? (float)$q->evaluator1_point : null;
    //             $e2 = is_numeric($q->evaluator2_point) ? (float)$q->evaluator2_point : null;

    //             if ($e1 !== null && $e2 !== null) {
    //                 $kpiScore = ($e1 + $e2) / 2;
    //             } elseif ($e1 !== null) {
    //                 $kpiScore = $e1;
    //             } elseif ($e2 !== null) {
    //                 $kpiScore = $e2;
    //             } else {
    //                 continue;
    //             }

    //             $w = (float)($q->q_weight ?? $q->evaluator1_weight ?? 0);
    //             if ($w <= 0) continue;

    //             $totalWeightedScore += ($kpiScore * $w);
    //             $totalWeight += $w;
    //         }

    //         $finalScore = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;

    //         DB::table('iq_appraisal_employ')->where('id', $appEmpId)->update([
    //             'total_point' => $finalScore,
    //             'grade' => $finalScore,
    //         ]);
    //     }
    // }

    public function chunkSize(): int
    {
        return 100;
    }
}
