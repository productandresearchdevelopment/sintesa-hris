<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $divisions = [
            [
                'id' => 1,
                'company_id' => 1,
                'name' => 'Human Resource & General Affair',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'name' => 'Information Technology',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'name' => 'Finance & Accounting',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'name' => 'Operations & Field Support',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'company_id' => 1,
                'name' => 'Marketing & Business Development',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($divisions as $item) {
            DB::table('iq_division')->updateOrInsert(
                ['id' => $item['id']],
                $item
            );
        }
    }
}
