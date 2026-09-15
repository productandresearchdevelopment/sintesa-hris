<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalPeriodSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 12,
    'period' => '2026',
    'smester' => 1,
    'start_date' => '2026-01-01',
    'end_date' => '2026-10-01',
    'is_closed' => 0,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'id' => 9,
    'period' => '2025',
    'smester' => 2,
    'start_date' => '2025-07-01',
    'end_date' => '2025-12-31',
    'is_closed' => 1,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_appraisal_period')->updateOrInsert(['id' => $item['id']], $item);
            }
        }
    }
}