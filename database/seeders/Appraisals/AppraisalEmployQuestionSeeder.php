<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalEmployQuestionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('iq_appraisal_employ_question')->truncate();

        $appraisalEmploys = DB::table('iq_appraisal_employ')->get();

        foreach ($appraisalEmploys as $ae) {
            $questions = DB::table('iq_appraisal_question')
                ->where('template_id', $ae->template_id)
                ->get();

            if ($questions->isEmpty()) {
                $questions = DB::table('iq_appraisal_question')->limit(5)->get();
            }

            foreach ($questions as $q) {
                DB::table('iq_appraisal_employ_question')->insert([
                    'appraisal_employ_id' => $ae->id,
                    'question_id' => $q->id,
                    'system_info' => null,
                    'evaluator1_value' => 9,
                    'evaluator1_point' => 9.0,
                    'evaluator1_weight' => $q->weight ?? 10.0,
                    'evaluator1_note' => 'Kinerja Sangat Baik',
                    'evaluator2_value' => null,
                    'evaluator2_point' => null,
                    'evaluator2_weight' => null,
                    'evaluator2_note' => null,
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}