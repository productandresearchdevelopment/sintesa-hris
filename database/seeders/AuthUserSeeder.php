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
                'organization_id' => 4, // Head IT
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
                'organization_id' => 1, // Director
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
                'organization_id' => 3, // Head HRGA
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
                'organization_id' => 9, // Manager Operation
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
                'organization_id' => 13, // Staff Operation
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
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 2,
                'employ_id' => 'e0000000-0000-0000-0000-000000000006',
                'username' => 'head_fin',
                'email' => 'head_fin@sintesa.com',
                'password' => $password,
                'name' => 'Head Finance & Accounting',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 5,
                'employ_id' => 'e0000000-0000-0000-0000-000000000007',
                'username' => 'head_ops',
                'email' => 'head_ops@sintesa.com',
                'password' => $password,
                'name' => 'Head Operations',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 6,
                'employ_id' => 'e0000000-0000-0000-0000-000000000008',
                'username' => 'mgr_fin',
                'email' => 'mgr_fin@sintesa.com',
                'password' => $password,
                'name' => 'Manager Finance & Accounting',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 7,
                'employ_id' => 'e0000000-0000-0000-0000-000000000009',
                'username' => 'mgr_hrga',
                'email' => 'mgr_hrga@sintesa.com',
                'password' => $password,
                'name' => 'Manager HRGA',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 14, // MANAGER
                'organization_id' => 8,
                'employ_id' => 'e0000000-0000-0000-0000-000000000010',
                'username' => 'mgr_it',
                'email' => 'mgr_it@sintesa.com',
                'password' => $password,
                'name' => 'Manager Information Technology',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 10,
                'employ_id' => 'e0000000-0000-0000-0000-000000000011',
                'username' => 'stf_fin',
                'email' => 'stf_fin@sintesa.com',
                'password' => $password,
                'name' => 'Staff Finance & Accounting',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 11,
                'employ_id' => 'e0000000-0000-0000-0000-000000000012',
                'username' => 'stf_hrga',
                'email' => 'stf_hrga@sintesa.com',
                'password' => $password,
                'name' => 'Staff HRGA',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 12,
                'employ_id' => 'e0000000-0000-0000-0000-000000000013',
                'username' => 'stf_it',
                'email' => 'stf_it@sintesa.com',
                'password' => $password,
                'name' => 'Staff Information Technology',
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
