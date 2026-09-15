<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalQuestionCategorySeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Technical Ability & Work Result',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Behavior & Work Processes',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'Leadership',
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_appraisal_question_category')->updateOrInsert(['id' => $item['id']], $item);
            }
        }
    }
}