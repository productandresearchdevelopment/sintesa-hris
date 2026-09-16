<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalPeriodSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            // PT Dieboldnixdorf
            [
                'id' => 1,
                'company_id' => 1,
                'period' => '2026',
                'smester' => 1,
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'is_closed' => 0,
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'period' => '2026',
                'smester' => 2,
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-31',
                'is_closed' => 0,
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // PT Hitachi
            [
                'id' => 3,
                'company_id' => 2,
                'period' => '2026',
                'smester' => 1,
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'is_closed' => 0,
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'company_id' => 2,
                'period' => '2026',
                'smester' => 2,
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-31',
                'is_closed' => 0,
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_appraisal_period')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_appraisal_period')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}