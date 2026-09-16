<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalEmploySummarySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('iq_appraisal_employ_summary')->truncate();

        $appraisalEmploys = DB::table('iq_appraisal_employ')->get();

        foreach ($appraisalEmploys as $ae) {
            // Get all evaluated questions for this employee with their category and weight
            $evaluatedQuestions = DB::table('iq_appraisal_employ_question')
                ->join('iq_appraisal_question', 'iq_appraisal_employ_question.question_id', '=', 'iq_appraisal_question.id')
                ->where('iq_appraisal_employ_question.appraisal_employ_id', $ae->id)
                ->select(
                    'iq_appraisal_employ_question.*',
                    'iq_appraisal_question.category_id',
                    'iq_appraisal_question.weight as q_weight'
                )
                ->get();

            if ($evaluatedQuestions->isEmpty()) {
                continue;
            }

            $categories = $evaluatedQuestions->pluck('category_id')->unique();

            $totalWeightedScore = 0;
            $totalWeight = 0;

            foreach ($categories as $catId) {
                $catQuestions = $evaluatedQuestions->where('category_id', $catId);

                $e1Weighted = 0;
                $e1WeightSum = 0;
                $e2Weighted = 0;
                $e2WeightSum = 0;

                foreach ($catQuestions as $cq) {
                    $weight = (float)($cq->q_weight ?? 10);

                    if ($cq->evaluator1_point !== null && is_numeric($cq->evaluator1_point)) {
                        $e1Weighted += ((float)$cq->evaluator1_point * $weight);
                        $e1WeightSum += $weight;

                        $totalWeightedScore += ((float)$cq->evaluator1_point * $weight);
                        $totalWeight += $weight;
                    }

                    if ($cq->evaluator2_point !== null && is_numeric($cq->evaluator2_point)) {
                        $e2Weighted += ((float)$cq->evaluator2_point * $weight);
                        $e2WeightSum += $weight;
                    }
                }

                $e1Avg = $e1WeightSum > 0 ? round($e1Weighted / $e1WeightSum, 2) : null;
                $e2Avg = $e2WeightSum > 0 ? round($e2Weighted / $e2WeightSum, 2) : null;

                DB::table('iq_appraisal_employ_summary')->insert([
                    'appraisal_employ_id' => $ae->id,
                    'category_id' => $catId,
                    'evaluator1_weight' => $e1WeightSum > 0 ? $e1WeightSum : null,
                    'evaluator1_point' => $e1Avg,
                    'evaluator1_grade' => $e1Avg !== null ? round($e1Avg) : null,
                    'evaluator2_weight' => $e2WeightSum > 0 ? $e2WeightSum : null,
                    'evaluator2_point' => $e2Avg,
                    'evaluator2_grade' => $e2Avg !== null ? round($e2Avg) : null,
                ]);
            }

            // Calculate overall final total point for the employee
            $finalTotalPoint = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;
            $finalGrade = round($finalTotalPoint);

            DB::table('iq_appraisal_employ')->where('id', $ae->id)->update([
                'total_point' => $finalTotalPoint,
                'grade' => $finalGrade,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
