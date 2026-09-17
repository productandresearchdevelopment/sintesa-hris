<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalPeriodOrganizationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'period_id' => 1,
                'organization_id' => 1,
                'template_id' => 1,
            ],
            [
                'id' => 2,
                'period_id' => 1,
                'organization_id' => 2,
                'template_id' => 2,
            ],
            [
                'id' => 3,
                'period_id' => 2,
                'organization_id' => 1,
                'template_id' => 1,
            ],
            [
                'id' => 4,
                'period_id' => 2,
                'organization_id' => 2,
                'template_id' => 2,
            ],
            [
                'id' => 5,
                'period_id' => 3,
                'organization_id' => 3,
                'template_id' => 3,
            ],
            [
                'id' => 6,
                'period_id' => 3,
                'organization_id' => 4,
                'template_id' => 4,
            ],
            [
                'id' => 7,
                'period_id' => 4,
                'organization_id' => 3,
                'template_id' => 3,
            ],
            [
                'id' => 8,
                'period_id' => 4,
                'organization_id' => 4,
                'template_id' => 4,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_appraisal_period_organization')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_appraisal_period_organization')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}