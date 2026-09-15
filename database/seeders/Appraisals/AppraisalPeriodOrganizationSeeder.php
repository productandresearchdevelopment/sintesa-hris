<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalPeriodOrganizationSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'period_id' => 12,
    'organization_id' => 1,
    'template_id' => 1,
  ),
  1 => 
  array (
    'id' => 2,
    'period_id' => 12,
    'organization_id' => 2,
    'template_id' => 2,
  ),
  2 => 
  array (
    'id' => 3,
    'period_id' => 12,
    'organization_id' => 3,
    'template_id' => 3,
  ),
  3 => 
  array (
    'id' => 4,
    'period_id' => 12,
    'organization_id' => 4,
    'template_id' => 4,
  ),
  4 => 
  array (
    'id' => 5,
    'period_id' => 12,
    'organization_id' => 5,
    'template_id' => 5,
  ),
  5 => 
  array (
    'id' => 6,
    'period_id' => 12,
    'organization_id' => 6,
    'template_id' => 6,
  ),
  6 => 
  array (
    'id' => 7,
    'period_id' => 12,
    'organization_id' => 7,
    'template_id' => 7,
  ),
  7 => 
  array (
    'id' => 8,
    'period_id' => 12,
    'organization_id' => 8,
    'template_id' => 8,
  ),
  8 => 
  array (
    'id' => 9,
    'period_id' => 12,
    'organization_id' => 9,
    'template_id' => 9,
  ),
  9 => 
  array (
    'id' => 10,
    'period_id' => 12,
    'organization_id' => 10,
    'template_id' => 10,
  ),
  10 => 
  array (
    'id' => 11,
    'period_id' => 12,
    'organization_id' => 11,
    'template_id' => 11,
  ),
  11 => 
  array (
    'id' => 12,
    'period_id' => 12,
    'organization_id' => 12,
    'template_id' => 12,
  ),
  12 => 
  array (
    'id' => 13,
    'period_id' => 12,
    'organization_id' => 13,
    'template_id' => 13,
  ),
  13 => 
  array (
    'id' => 14,
    'period_id' => 9,
    'organization_id' => 1,
    'template_id' => 1,
  ),
  14 => 
  array (
    'id' => 15,
    'period_id' => 9,
    'organization_id' => 2,
    'template_id' => 2,
  ),
  15 => 
  array (
    'id' => 16,
    'period_id' => 9,
    'organization_id' => 3,
    'template_id' => 3,
  ),
  16 => 
  array (
    'id' => 17,
    'period_id' => 9,
    'organization_id' => 4,
    'template_id' => 4,
  ),
  17 => 
  array (
    'id' => 18,
    'period_id' => 9,
    'organization_id' => 5,
    'template_id' => 5,
  ),
  18 => 
  array (
    'id' => 19,
    'period_id' => 9,
    'organization_id' => 6,
    'template_id' => 6,
  ),
  19 => 
  array (
    'id' => 20,
    'period_id' => 9,
    'organization_id' => 7,
    'template_id' => 7,
  ),
  20 => 
  array (
    'id' => 21,
    'period_id' => 9,
    'organization_id' => 8,
    'template_id' => 8,
  ),
  21 => 
  array (
    'id' => 22,
    'period_id' => 9,
    'organization_id' => 9,
    'template_id' => 9,
  ),
  22 => 
  array (
    'id' => 23,
    'period_id' => 9,
    'organization_id' => 10,
    'template_id' => 10,
  ),
  23 => 
  array (
    'id' => 24,
    'period_id' => 9,
    'organization_id' => 11,
    'template_id' => 11,
  ),
  24 => 
  array (
    'id' => 25,
    'period_id' => 9,
    'organization_id' => 12,
    'template_id' => 12,
  ),
  25 => 
  array (
    'id' => 26,
    'period_id' => 9,
    'organization_id' => 13,
    'template_id' => 13,
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_appraisal_period_organization')->updateOrInsert(['id' => $item['id']], $item);
            }
        }
    }
}