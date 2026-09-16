<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalEmploySeeder extends Seeder
{
    public function run()
    {
        $now = '2026-09-15 11:40:00';

        $data = [
            // ==========================================
            // PT Dieboldnixdorf (Staff in Period 1: 2026 Semester 1)
            // ==========================================
            [
                'id' => 'a0000000-0000-0000-0001-000000000002',
                'period_id' => 1,
                'template_id' => 2,
                'employ_id' => 'e0000000-0000-0000-0001-000000000002', // Andi Pratama (Staff)
                'total_point' => 9.0,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0001-000000000001',
                'updated_by' => 'u0000000-0000-0000-0001-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'a0000000-0000-0000-0001-000000000003',
                'period_id' => 1,
                'template_id' => 2,
                'employ_id' => 'e0000000-0000-0000-0001-000000000003', // Dewi Lestari (Staff)
                'total_point' => 8.5,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0001-000000000001',
                'updated_by' => 'u0000000-0000-0000-0001-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'a0000000-0000-0000-0001-000000000004',
                'period_id' => 1,
                'template_id' => 2,
                'employ_id' => 'e0000000-0000-0000-0001-000000000004', // Rizky Febrian (Staff)
                'total_point' => 9.0,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0001-000000000001',
                'updated_by' => 'u0000000-0000-0000-0001-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ==========================================
            // PT Hitachi (Staff in Period 3: 2026 Semester 1)
            // ==========================================
            [
                'id' => 'a0000000-0000-0000-0002-000000000002',
                'period_id' => 3,
                'template_id' => 4,
                'employ_id' => 'e0000000-0000-0000-0002-000000000002', // Ahmad Hidayat (Staff)
                'total_point' => 9.0,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0002-000000000001',
                'updated_by' => 'u0000000-0000-0000-0002-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'a0000000-0000-0000-0002-000000000003',
                'period_id' => 3,
                'template_id' => 4,
                'employ_id' => 'e0000000-0000-0000-0002-000000000003', // Nurul Hidayah (Staff)
                'total_point' => 8.5,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0002-000000000001',
                'updated_by' => 'u0000000-0000-0000-0002-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'a0000000-0000-0000-0002-000000000004',
                'period_id' => 3,
                'template_id' => 4,
                'employ_id' => 'e0000000-0000-0000-0002-000000000004', // Fajar Nugraha (Staff)
                'total_point' => 9.0,
                'grade' => 9,
                'evaluator1_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator1_at' => $now,
                'evaluator2_by' => 'e0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'evaluator2_at' => $now,
                'created_by' => 'u0000000-0000-0000-0002-000000000001',
                'updated_by' => 'u0000000-0000-0000-0002-000000000001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_appraisal_employ')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_appraisal_employ')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
