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
            [
                'username' => 'developer',
                'employ_id' => 'e0000000-0000-0000-0000-000000000001',
                'nik' => 'EMP-001',
                'fullname' => 'System Developer',
                'nickname' => 'Developer',
                'email' => 'developer@sintesa.com',
                'org_id' => 4, // Head IT
                'division_id' => 2, // IT Division
            ],
            [
                'username' => 'superadmin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000002',
                'nik' => 'EMP-002',
                'fullname' => 'Super Administrator',
                'nickname' => 'Superadmin',
                'email' => 'superadmin@sintesa.com',
                'org_id' => 1, // Director
                'division_id' => 1, // HRGA Division
            ],
            [
                'username' => 'hrga',
                'employ_id' => 'e0000000-0000-0000-0000-000000000003',
                'nik' => 'EMP-003',
                'fullname' => 'HRGA Administrator',
                'nickname' => 'HRGA',
                'email' => 'hrga@sintesa.com',
                'org_id' => 3, // Head HRGA
                'division_id' => 1, // HRGA Division
            ],
            [
                'username' => 'manager',
                'employ_id' => 'e0000000-0000-0000-0000-000000000004',
                'nik' => 'EMP-004',
                'fullname' => 'Operations Manager',
                'nickname' => 'Manager',
                'email' => 'manager@sintesa.com',
                'org_id' => 9, // Manager Operation
                'division_id' => 4, // Operations Division
            ],
            [
                'username' => 'staff',
                'employ_id' => 'e0000000-0000-0000-0000-000000000005',
                'nik' => 'EMP-005',
                'fullname' => 'Operational Staff',
                'nickname' => 'Staff',
                'email' => 'staff@sintesa.com',
                'org_id' => 13, // Staff Operation
                'division_id' => 4, // Operations Division
            ],
            [
                'username' => 'head_fin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000006',
                'nik' => 'EMP-006',
                'fullname' => 'Head Finance & Accounting',
                'nickname' => 'Head Fin',
                'email' => 'head_fin@sintesa.com',
                'org_id' => 2,
                'division_id' => 3,
            ],
            [
                'username' => 'head_ops',
                'employ_id' => 'e0000000-0000-0000-0000-000000000007',
                'nik' => 'EMP-007',
                'fullname' => 'Head Operations',
                'nickname' => 'Head Ops',
                'email' => 'head_ops@sintesa.com',
                'org_id' => 5,
                'division_id' => 4,
            ],
            [
                'username' => 'mgr_fin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000008',
                'nik' => 'EMP-008',
                'fullname' => 'Manager Finance & Accounting',
                'nickname' => 'Mgr Fin',
                'email' => 'mgr_fin@sintesa.com',
                'org_id' => 6,
                'division_id' => 3,
            ],
            [
                'username' => 'mgr_hrga',
                'employ_id' => 'e0000000-0000-0000-0000-000000000009',
                'nik' => 'EMP-009',
                'fullname' => 'Manager HRGA',
                'nickname' => 'Mgr HRGA',
                'email' => 'mgr_hrga@sintesa.com',
                'org_id' => 7,
                'division_id' => 1,
            ],
            [
                'username' => 'mgr_it',
                'employ_id' => 'e0000000-0000-0000-0000-000000000010',
                'nik' => 'EMP-010',
                'fullname' => 'Manager Information Technology',
                'nickname' => 'Mgr IT',
                'email' => 'mgr_it@sintesa.com',
                'org_id' => 8,
                'division_id' => 2,
            ],
            [
                'username' => 'stf_fin',
                'employ_id' => 'e0000000-0000-0000-0000-000000000011',
                'nik' => 'EMP-011',
                'fullname' => 'Staff Finance & Accounting',
                'nickname' => 'Staff Fin',
                'email' => 'stf_fin@sintesa.com',
                'org_id' => 10,
                'division_id' => 3,
            ],
            [
                'username' => 'stf_hrga',
                'employ_id' => 'e0000000-0000-0000-0000-000000000012',
                'nik' => 'EMP-012',
                'fullname' => 'Staff HRGA',
                'nickname' => 'Staff HRGA',
                'email' => 'stf_hrga@sintesa.com',
                'org_id' => 11,
                'division_id' => 1,
            ],
            [
                'username' => 'stf_it',
                'employ_id' => 'e0000000-0000-0000-0000-000000000013',
                'nik' => 'EMP-013',
                'fullname' => 'Staff Information Technology',
                'nickname' => 'Staff IT',
                'email' => 'stf_it@sintesa.com',
                'org_id' => 12,
                'division_id' => 2,
            ],
        ];

        foreach ($users as $u) {
            $employId = $u['employ_id'];
            $contractId = (string) Str::uuid();
            $careerId = (string) Str::uuid();

            // Step 1: Create iq_employ without circular FK references
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
                    'company_id' => 1,
                    'office_id' => 1, // Head Office Jakarta
                    'org_id' => $u['org_id'],
                    'division_id' => $u['division_id'],
                    'placement_id' => 1,
                    'gender_id' => 1001, // LAKI LAKI
                    'shift_start_time' => '08:00:00',
                    'shift_end_time' => '17:00:00',
                    'address' => 'Jl. Sudirman No. 100, Jakarta',
                    'last_contract_id' => null,
                    'last_career_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // Step 2: Create iq_employ_contract (references iq_employ)
            DB::table('iq_employ_contract')->updateOrInsert(
                ['id' => $contractId],
                [
                    'id' => $contractId,
                    'employ_id' => $employId,
                    'status_id' => 1507, // PERMANENT
                    'start_date' => '2024-01-01 00:00:00',
                    'end_date' => '2026-12-31 23:59:59',
                    'description' => 'Kontrak Kerja PKWT ' . $u['fullname'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // Step 3: Create iq_employ_career (references iq_employ)
            DB::table('iq_employ_career')->updateOrInsert(
                ['id' => $careerId],
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

            // Step 4: Update iq_employ with last_contract_id & last_career_id
            DB::table('iq_employ')->where('id', $employId)->update([
                'last_contract_id' => $contractId,
                'last_career_id' => $careerId,
                'updated_at' => $now,
            ]);

            // Step 5: Seed sub-tables
            DB::table('iq_employ_citizen')->updateOrInsert(
                ['employ_id' => $employId],
                [
                    'id' => (string) Str::uuid(),
                    'employ_id' => $employId,
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

            // Step 6: Link employ_id to auth_user
            DB::table('auth_user')->where('username', $u['username'])->update([
                'employ_id' => $employId,
                'updated_at' => $now,
            ]);
        }
    }
}
