<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmploySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            // DEVELOPER, SUPERADMIN & ADMINISTRATOR
            [
                'username' => 'developer',
                'employ_id' => 'e0000000-0000-0000-0000-000000000001',
                'nik' => 'EMP-001',
                'fullname' => 'Developer',
                'nickname' => 'Developer',
                'email' => 'developer@sintesa.com',
                'company_id' => null,
                'org_id' => null,
                'division_id' => null,
            ],
            [
                'username' => 'superadmin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000002',
                'nik' => 'EMP-002',
                'fullname' => 'Superadmin',
                'nickname' => 'Superadmin',
                'email' => 'superadmin@sintesa.com',
                'company_id' => null,
                'org_id' => null,
                'division_id' => null,
            ],
            [
                'username' => 'admin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000003',
                'nik' => 'EMP-003',
                'fullname' => 'Administrator',
                'nickname' => 'Admin',
                'email' => 'admin@sintesa.com',
                'company_id' => null,
                'org_id' => null,
                'division_id' => null,
            ],

            // PT DIEBOLDNIXDORF
            [
                'username' => 'hr_diebold',
                'employ_id' => 'e0000000-0000-0000-0001-000000000001',
                'nik' => 'DN-HR-001',
                'fullname' => 'Budi Santoso',
                'nickname' => 'Budi',
                'email' => 'budi.santoso@dieboldnixdorf.com',
                'company_id' => 1,
                'org_id' => 1,
                'division_id' => 1,
            ],
            [
                'username' => 'andi_diebold',
                'employ_id' => 'e0000000-0000-0000-0001-000000000002',
                'nik' => 'DN-STF-001',
                'fullname' => 'Andi Pratama',
                'nickname' => 'Andi',
                'email' => 'andi.pratama@dieboldnixdorf.com',
                'company_id' => 1,
                'org_id' => 2,
                'division_id' => 4,
            ],
            [
                'username' => 'dewi_diebold',
                'employ_id' => 'e0000000-0000-0000-0001-000000000003',
                'nik' => 'DN-STF-002',
                'fullname' => 'Dewi Lestari',
                'nickname' => 'Dewi',
                'email' => 'dewi.lestari@dieboldnixdorf.com',
                'company_id' => 1,
                'org_id' => 2,
                'division_id' => 4,
            ],
            [
                'username' => 'rizky_diebold',
                'employ_id' => 'e0000000-0000-0000-0001-000000000004',
                'nik' => 'DN-STF-003',
                'fullname' => 'Rizky Febrian',
                'nickname' => 'Rizky',
                'email' => 'rizky.febrian@dieboldnixdorf.com',
                'company_id' => 1,
                'org_id' => 2,
                'division_id' => 4,
            ],

            // PT HITACHI
            [
                'username' => 'hr_hitachi',
                'employ_id' => 'e0000000-0000-0000-0002-000000000001',
                'nik' => 'HTC-HR-001',
                'fullname' => 'Siti Rahmawati',
                'nickname' => 'Siti',
                'email' => 'siti.rahmawati@hitachi.com',
                'company_id' => 2,
                'org_id' => 3,
                'division_id' => 1,
            ],
            [
                'username' => 'ahmad_hitachi',
                'employ_id' => 'e0000000-0000-0000-0002-000000000002',
                'nik' => 'HTC-STF-001',
                'fullname' => 'Ahmad Hidayat',
                'nickname' => 'Ahmad',
                'email' => 'ahmad.hidayat@hitachi.com',
                'company_id' => 2,
                'org_id' => 4,
                'division_id' => 4,
            ],
            [
                'username' => 'nurul_hitachi',
                'employ_id' => 'e0000000-0000-0000-0002-000000000003',
                'nik' => 'HTC-STF-002',
                'fullname' => 'Nurul Hidayah',
                'nickname' => 'Nurul',
                'email' => 'nurul.hidayah@hitachi.com',
                'company_id' => 2,
                'org_id' => 4,
                'division_id' => 4,
            ],
            [
                'username' => 'fajar_hitachi',
                'employ_id' => 'e0000000-0000-0000-0002-000000000004',
                'nik' => 'HTC-STF-003',
                'fullname' => 'Fajar Nugraha',
                'nickname' => 'Fajar',
                'email' => 'fajar.nugraha@hitachi.com',
                'company_id' => 2,
                'org_id' => 4,
                'division_id' => 4,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedEmployIds = array_column($users, 'employ_id');
        DB::table('iq_employ')->whereNotIn('id', $allowedEmployIds)->delete();

        foreach ($users as $u) {
            $employId = $u['employ_id'];
            $contractId = (string) Str::uuid();
            $careerId = (string) Str::uuid();

            DB::table('iq_employ')->updateOrInsert(
                ['id' => $employId],
                [
                    'id' => $employId,
                    'nik' => $u['nik'],
                    'fullname' => $u['fullname'],
                    'nickname' => $u['nickname'],
                    'email' => $u['email'],
                    'phone' => '081234567890',
                    'birth_place' => 'Jakarta',
                    'birth_date' => '1995-01-01',
                    'join_date' => '2024-01-01',
                    'leave_saldo' => 12,
                    'company_id' => $u['company_id'],
                    'office_id' => 1,
                    'org_id' => $u['org_id'],
                    'division_id' => $u['division_id'],
                    'placement_id' => 1,
                    'gender_id' => in_array($u['username'], ['dewi_diebold', 'siti_hitachi', 'hr_hitachi', 'nurul_hitachi']) ? 1002 : 1001,
                    'marital_id' => 1101,
                    'religion_id' => 1201,
                    'shift_start_time' => '08:00:00',
                    'shift_end_time' => '17:00:00',
                    'address' => 'Jl. Sudirman No. 100, Jakarta',
                    'address_city_id' => 131,
                    'address_province_id' => 131,
                    'address_permanent' => 'Jl. Sudirman No. 100, Jakarta',
                    'address_permanent_city_id' => 131,
                    'address_permanent_province_id' => 131,
                    'bank_id' => 1303,
                    'bank_account' => '5270123456',
                    'bank_alias' => $u['fullname'],
                    'emergency_relation_id' => 1901,
                    'emergency_contact_name' => 'Keluarga ' . $u['nickname'],
                    'emergency_contact_phone' => '081298765432',
                    'emergency_contact_address' => 'Jl. Sudirman No. 100, Jakarta',
                    'last_contract_id' => null,
                    'last_career_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ_contract')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => $contractId,
                    'employ_id' => $employId,
                    'status_id' => 1507,
                    'start_date' => '2024-01-01 00:00:00',
                    'end_date' => '2026-12-31 23:59:59',
                    'description' => 'Kontrak Kerja PKWT ' . $u['fullname'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ_career')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => $careerId,
                    'employ_id' => $employId,
                    'placement_id' => 1,
                    'org_id' => $u['org_id'],
                    'date' => '2024-01-01',
                    'description' => 'Pengangkatan Karyawan ' . $u['fullname'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ')->where('id', $employId)->update([
                'last_contract_id' => $contractId,
                'last_career_id' => $careerId,
                'updated_at' => $now,
            ]);

            DB::table('iq_employ_citizen')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
                    'citizen_id' => 1401,
                    'value' => '317100000000' . rand(1000, 9999),
                    'description' => 'KTP Elektronik',
                ]
            );

            DB::table('iq_employ_education')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
                    'institution' => 'Universitas Indonesia',
                    'graduate' => 2017,
                    'ipk' => 3.75,
                    'description' => 'Sarjana Komputer / Manajemen',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ_family')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
                    'name' => 'Keluarga ' . $u['nickname'],
                    'address' => 'Jakarta',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ_job_experience')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
                    'name' => 'PT Sintesa Group',
                    'start_date' => '2020-01-01',
                    'end_date' => '2023-12-31',
                    'job_title' => 'Senior Specialist',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('iq_employ_training')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
                    'title' => 'Orientation & Onboarding Sintesa HRIS',
                    'location' => 'Head Office Jakarta',
                    'start_date' => '2024-01-05 09:00:00',
                    'end_date' => '2024-01-05 17:00:00',
                    'is_internal' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            DB::table('auth_user')->where('username', $u['username'])->update([
                'employ_id' => $employId,
                'updated_at' => $now,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
