<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthApiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'SERVER',
                'key' => '2adf4d9b-a59b-4aa3-959b-3e55f8880081',
            ],
        ];

        foreach ($data as $item) {
            DB::table('auth_api')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
