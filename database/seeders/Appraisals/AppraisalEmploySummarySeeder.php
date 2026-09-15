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
        $categories = DB::table('iq_appraisal_question_category')->pluck('id');

        foreach ($appraisalEmploys as $ae) {
            foreach ($categories as $catId) {
                DB::table('iq_appraisal_employ_summary')->insert([
                    'appraisal_employ_id' => $ae->id,
                    'category_id' => $catId,
                    'evaluator1_weight' => 40,
                    'evaluator1_point' => 9.0,
                    'evaluator1_grade' => 9,
                    'evaluator2_weight' => null,
                    'evaluator2_point' => null,
                    'evaluator2_grade' => null,
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}