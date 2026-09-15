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
                'organization_id' => null,
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
                'organization_id' => null,
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
                'id' => 'b1a11111-0000-0000-0000-000000000001',
                'role_id' => 11, // ADMINISTRATOR
                'organization_id' => null,
                'employ_id' => 'e0000000-0000-0000-0000-000000000003',
                'username' => 'admin',
                'email' => 'admin@sintesa.com',
                'password' => $password,
                'name' => 'Administrator',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // PT Dieboldnixdorf Users
            [
                'id' => (string) Str::uuid(),
                'role_id' => 15, // HRGA
                'organization_id' => 1, // Human Resource (Diebold)
                'employ_id' => 'e0000000-0000-0000-0001-000000000001',
                'username' => 'hr_diebold',
                'email' => 'budi.santoso@dieboldnixdorf.com',
                'password' => $password,
                'name' => 'Budi Santoso',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 2, // Staff (Diebold)
                'employ_id' => 'e0000000-0000-0000-0001-000000000002',
                'username' => 'andi_diebold',
                'email' => 'andi.pratama@dieboldnixdorf.com',
                'password' => $password,
                'name' => 'Andi Pratama',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 2, // Staff (Diebold)
                'employ_id' => 'e0000000-0000-0000-0001-000000000003',
                'username' => 'dewi_diebold',
                'email' => 'dewi.lestari@dieboldnixdorf.com',
                'password' => $password,
                'name' => 'Dewi Lestari',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 2, // Staff (Diebold)
                'employ_id' => 'e0000000-0000-0000-0001-000000000004',
                'username' => 'rizky_diebold',
                'email' => 'rizky.febrian@dieboldnixdorf.com',
                'password' => $password,
                'name' => 'Rizky Febrian',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // PT Hitachi Users
            [
                'id' => (string) Str::uuid(),
                'role_id' => 15, // HRGA
                'organization_id' => 3, // Human Resource (Hitachi)
                'employ_id' => 'e0000000-0000-0000-0002-000000000001',
                'username' => 'hr_hitachi',
                'email' => 'siti.rahmawati@hitachi.com',
                'password' => $password,
                'name' => 'Siti Rahmawati',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 4, // Staff (Hitachi)
                'employ_id' => 'e0000000-0000-0000-0002-000000000002',
                'username' => 'ahmad_hitachi',
                'email' => 'ahmad.hidayat@hitachi.com',
                'password' => $password,
                'name' => 'Ahmad Hidayat',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 4, // Staff (Hitachi)
                'employ_id' => 'e0000000-0000-0000-0002-000000000003',
                'username' => 'nurul_hitachi',
                'email' => 'nurul.hidayah@hitachi.com',
                'password' => $password,
                'name' => 'Nurul Hidayah',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_id' => 17, // STAFF
                'organization_id' => 4, // Staff (Hitachi)
                'employ_id' => 'e0000000-0000-0000-0002-000000000004',
                'username' => 'fajar_hitachi',
                'email' => 'fajar.nugraha@hitachi.com',
                'password' => $password,
                'name' => 'Fajar Nugraha',
                'email_validation_at' => $now,
                'email_validation_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedUsernames = array_column($users, 'username');
        DB::table('auth_user')->whereNotIn('username', $allowedUsernames)->delete();

        foreach ($users as $user) {
            DB::table('auth_user')->updateOrInsert(
                ['username' => $user['username']],
                $user
            );
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
