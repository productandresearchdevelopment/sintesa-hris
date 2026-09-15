<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $companies = [
            [
                'id' => 1,
                'name' => 'PT Dieboldnixdorf',
                'latitude' => null,
                'longitude' => null,
                'max_distance_allowed' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'PT Hitachi',
                'latitude' => null,
                'longitude' => null,
                'max_distance_allowed' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($companies, 'id');
        DB::table('iq_company')->whereNotIn('id', $allowedIds)->delete();

        foreach ($companies as $item) {
            DB::table('iq_company')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
