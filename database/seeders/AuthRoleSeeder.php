<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthRoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'DEVELOPER',
                'alias' => 'DEVEL',
                'home' => 2044,
                'color' => '333333',
                'description' => 'System Developer Role',
                'property' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'id' => 10,
                'name' => 'SUPERADMIN',
                'alias' => 'SU',
                'home' => 2044,
                'color' => 'CC0000',
                'description' => 'Super Administrator Role',
                'property' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'id' => 11,
                'name' => 'ADMINISTRATOR',
                'alias' => 'ADMIN',
                'home' => 2044,
                'color' => '0066CC',
                'description' => 'Administrator Role',
                'property' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'id' => 15,
                'name' => 'HRGA',
                'alias' => 'HRGA',
                'home' => 2044,
                'color' => '969696',
                'description' => 'Human Resources & General Affair Role',
                'property' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'id' => 17,
                'name' => 'STAFF',
                'alias' => 'ST',
                'home' => 2137,
                'color' => 'CC99FF',
                'description' => 'Staff / Employee Role',
                'property' => null,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ];

        $allowedIds = array_column($roles, 'id');
        DB::table('auth_role')->whereNotIn('id', $allowedIds)->delete();

        foreach ($roles as $item) {
            DB::table('auth_role')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
