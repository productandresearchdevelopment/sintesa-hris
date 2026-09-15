<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalEmploySummarySeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000001',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  1 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000001',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  2 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000001',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  3 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000002',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.25,
    'evaluator1_grade' => 8,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  4 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000002',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  5 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000002',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.5,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  6 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000003',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.25,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  7 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000003',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.5,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  8 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000003',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  9 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000004',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.25,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  10 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000004',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  11 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000004',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  12 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000005',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  13 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000005',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.5,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  14 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000005',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 10.0,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => NULL,
    'evaluator2_point' => NULL,
    'evaluator2_grade' => NULL,
  ),
  15 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000006',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  16 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000006',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  17 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000006',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 10.0,
    'evaluator2_grade' => 10,
  ),
  18 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000007',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.25,
    'evaluator1_grade' => 8,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  19 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000007',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  20 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000007',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.5,
    'evaluator2_grade' => 10,
  ),
  21 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000008',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 10.0,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  22 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000008',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.5,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  23 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000008',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.0,
    'evaluator1_grade' => 8,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  24 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000009',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.75,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
  25 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000009',
    'category_id' => 1,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  26 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000009',
    'category_id' => 3,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.0,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  27 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000010',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 8.75,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 9.5,
    'evaluator2_grade' => 10,
  ),
  28 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000010',
    'category_id' => 1,
    'evaluator1_weight' => 60,
    'evaluator1_point' => 8.0,
    'evaluator1_grade' => 8,
    'evaluator2_weight' => 60,
    'evaluator2_point' => 9.5,
    'evaluator2_grade' => 10,
  ),
  29 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000011',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  30 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000011',
    'category_id' => 1,
    'evaluator1_weight' => 60,
    'evaluator1_point' => 10.0,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 60,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  31 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000012',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.25,
    'evaluator1_grade' => 9,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  32 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000012',
    'category_id' => 1,
    'evaluator1_weight' => 60,
    'evaluator1_point' => 9.5,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 60,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  33 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000013',
    'category_id' => 2,
    'evaluator1_weight' => 40,
    'evaluator1_point' => 9.75,
    'evaluator1_grade' => 10,
    'evaluator2_weight' => 40,
    'evaluator2_point' => 8.5,
    'evaluator2_grade' => 9,
  ),
  34 => 
  array (
    'appraisal_employ_id' => 'a0000000-0000-0000-0000-000000000013',
    'category_id' => 1,
    'evaluator1_weight' => 60,
    'evaluator1_point' => 8.0,
    'evaluator1_grade' => 8,
    'evaluator2_weight' => 60,
    'evaluator2_point' => 9.0,
    'evaluator2_grade' => 9,
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_appraisal_employ_summary')->updateOrInsert(['appraisal_employ_id' => $item['appraisal_employ_id'], 'category_id' => $item['category_id']], $item);
            }
        }
    }
}