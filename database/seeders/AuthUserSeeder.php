<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthUserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $password = Hash::make('password');

        $users = [
            [
                'id' => '394c94ca-d220-4799-83a8-d6ccafc0b1af',
                'role_id' => 1, // DEVELOPER
                'organization_id' => 3, // IT Dept
                'employ_id' => 'e0000000-0000-0000-0000-000000000001',
                'username' => 'developer',
                'email' => 'developer@sintesa.com',
                'password' => $password,
                'name' => 'Developer',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'a8a531d5-7968-450f-90ee-a4dfc4dbca38',
                'role_id' => 10, // SUPERADMIN
                'organization_id' => 1, // Head Office
                'employ_id' => 'e0000000-0000-0000-0000-000000000002',
                'username' => 'superadmin',
                'email' => 'superadmin@sintesa.com',
                'password' => $password,
                'name' => 'Superadmin',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 15, // HRGA
                'organization_id' => 2, // HRGA Dept
                'employ_id' => 'e0000000-0000-0000-0000-000000000003',
                'username' => 'hrga',
                'email' => 'hrga@sintesa.com',
                'password' => $password,
                'name' => 'HRGA Admin',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 5, // Operations Dept
                'employ_id' => 'e0000000-0000-0000-0000-000000000004',
                'username' => 'manager',
                'email' => 'manager@sintesa.com',
                'password' => $password,
                'name' => 'Manager User',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 5, // Operations Dept
                'employ_id' => 'e0000000-0000-0000-0000-000000000005',
                'username' => 'staff',
                'email' => 'staff@sintesa.com',
                'password' => $password,
                'name' => 'Staff User',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($users as $user) {
            DB::table('auth_user')->updateOrInsert(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
