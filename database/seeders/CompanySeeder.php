<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'id' => 1,
                'name' => 'PT SINTESA TALENTA ASIA',
                'latitude' => null,
                'longitude' => null,
                'max_distance_allowed' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ];

        // Ensure only allowed company IDs remain
        $allowedIds = array_column($companies, 'id');
        DB::table('iq_company')->whereNotIn('id', $allowedIds)->delete();

        foreach ($companies as $item) {
            DB::table('iq_company')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
