<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalQuestionTemplateSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'id' => 1,
                'title' => 'Template HR - PT Dieboldnixdorf',
                'period_year' => '2026',
                'period_smt' => 1,
                'division_id' => 1,
                'is_locked' => 0,
                'is_archived' => 0,
                'description' => 'Appraisal Template for HR PT Dieboldnixdorf',
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'title' => 'Template Staff - PT Dieboldnixdorf',
                'period_year' => '2026',
                'period_smt' => 1,
                'division_id' => 4,
                'is_locked' => 0,
                'is_archived' => 0,
                'description' => 'Appraisal Template for Staff PT Dieboldnixdorf',
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'title' => 'Template HR - PT Hitachi',
                'period_year' => '2026',
                'period_smt' => 1,
                'division_id' => 1,
                'is_locked' => 0,
                'is_archived' => 0,
                'description' => 'Appraisal Template for HR PT Hitachi',
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'title' => 'Template Staff - PT Hitachi',
                'period_year' => '2026',
                'period_smt' => 1,
                'division_id' => 4,
                'is_locked' => 0,
                'is_archived' => 0,
                'description' => 'Appraisal Template for Staff PT Hitachi',
                'created_by' => 'e0000000-0000-0000-0000-000000000001',
                'updated_by' => 'e0000000-0000-0000-0000-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_appraisal_question_template')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_appraisal_question_template')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}