<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Models\Appraisals\AppraisalEmployeeSummary as Mod;
use Illuminate\Http\Request;

class AppraisalEmployeeSummary extends Controller
{
    public function data(Request $request, $counter = true)
    {
        $employ_id = $request->get('employee');

        $query = Mod::with(['appraisal_employee', 'appraisal_employee.period', 'appraisal_employee.period.appraisal_period', 'category'])
            ->whereHas('appraisal_employee', function ($query) use ($employ_id) {
                $query->where('employ_id', $employ_id);
            })
            ->get();

        $grouped = [];

        foreach ($query as $item) {
            $key = $item->appraisal_employ_id;

            if (!isset($grouped[$key])) {
                $period = $item->appraisal_employee->period->appraisal_period->period ?? '-';
                $smester = $item->appraisal_employee->period->appraisal_period->smester ?? '-';
                $formattedPeriod = "$period - (PERIODE $smester)";

                $finalScore = $item->appraisal_employee->total_point !== null
                    ? number_format((float)$item->appraisal_employee->total_point, 2, '.', '')
                    : null;
                $finalGrade = $finalScore !== null ? self::getGrade($finalScore) : null;

                $grouped[$key] = [
                    'employ_id' => $item->appraisal_employee->employ_id,
                    'appraisal_employ_id' => $key,
                    'formatted_period' => $formattedPeriod ?? '-',
                    'period' => $period ?? '-',
                    'smester' => $smester ?? '-',
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
                    'final_score' => $finalScore,
                    'final_grade' => $finalGrade,
                ];
            }

            $eval1_point = is_numeric($item->evaluator1_point) ? number_format((float)$item->evaluator1_point, 2, '.', '') : null;
            $eval1_grade = $eval1_point !== null ? self::getGrade($eval1_point) : null;

            $eval2_point = is_numeric($item->evaluator2_point) ? number_format((float)$item->evaluator2_point, 2, '.', '') : null;
            $eval2_grade = $eval2_point !== null ? self::getGrade($eval2_point) : null;

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

    public static function getGrade($score)
    {
        if ($score === null || $score === '') return null;

        $score = round((float)$score, 2);

        if ($score >= 9.50) return 'Outstanding';
        if ($score >= 8.50) return 'Excellent';
        if ($score >= 7.50) return 'Good';
        if ($score >= 6.50) return 'Fair';
        return 'Need Improvement';
    }


    public function get(Request $request, $id = null)
    {
        return Mod::with(['appraisal_employee', 'category'])->where('id', $id)->first();
    }
}
