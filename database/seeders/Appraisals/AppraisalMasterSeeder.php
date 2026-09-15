<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;

class AppraisalMasterSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AppraisalPeriodSeeder::class,
            AppraisalQuestionCategorySeeder::class,
            AppraisalQuestionTemplateSeeder::class,
            AppraisalQuestionSeeder::class,
            AppraisalPeriodOrganizationSeeder::class,
            AppraisalEmploySeeder::class,
            AppraisalEmployQuestionSeeder::class,
            AppraisalEmploySummarySeeder::class,
        ]);
    }
}