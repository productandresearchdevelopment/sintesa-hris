<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            // =========================================================================
            // PT Dieboldnixdorf (3 Staff Records: Approved, Pending, Rejected)
            // =========================================================================
            // 1. Approved
            [
                'id' => 1,
                'employ_id' => 'e0000000-0000-0000-0001-000000000002', // Andi Pratama (Staff)
                'type_id' => 2103, // CUTI TAHUNAN
                'start_date' => '2026-09-20',
                'end_date' => '2026-09-22',
                'duration' => 3,
                'description' => 'Cuti tahunan keperluan keluarga',
                'approved1_status' => 1,
                'approved1_at' => '2026-09-14 09:00:00',
                'approved1_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'approved1_note' => 'Disetujui',
                'approved2_status' => 1,
                'approved2_at' => '2026-09-14 09:00:00',
                'approved2_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'approved2_note' => 'Disetujui',
                'allowed_status' => 1,
                'allowed_at' => '2026-09-14 09:00:00',
                'allowed_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'allowed_note' => 'Disetujui',
                'leave_saldo' => 9,
                'created_by' => 'u0000000-0000-0000-0001-000000000002', // Andi Pratama
                'updated_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'created_at' => '2026-09-13 08:30:00',
                'updated_at' => '2026-09-14 09:00:00',
            ],
            // 2. Pending (Menunggu Persetujuan)
            [
                'id' => 2,
                'employ_id' => 'e0000000-0000-0000-0001-000000000003', // Dewi Lestari (Staff)
                'type_id' => 2101, // SAKIT
                'start_date' => '2026-09-25',
                'end_date' => '2026-09-26',
                'duration' => 2,
                'description' => 'Izin periksa kesehatan dan istirahat',
                'approved1_status' => null,
                'approved1_at' => null,
                'approved1_by' => null,
                'approved1_note' => null,
                'approved2_status' => null,
                'approved2_at' => null,
                'approved2_by' => null,
                'approved2_note' => null,
                'allowed_status' => null,
                'allowed_at' => null,
                'allowed_by' => null,
                'allowed_note' => null,
                'leave_saldo' => 12,
                'created_by' => 'u0000000-0000-0000-0001-000000000003', // Dewi Lestari
                'updated_by' => null,
                'created_at' => '2026-09-15 10:00:00',
                'updated_at' => '2026-09-15 10:00:00',
            ],
            // 3. Rejected (Ditolak)
            [
                'id' => 3,
                'employ_id' => 'e0000000-0000-0000-0001-000000000004', // Rizky Febrian (Staff)
                'type_id' => 2103, // CUTI TAHUNAN
                'start_date' => '2026-09-18',
                'end_date' => '2026-09-19',
                'duration' => 2,
                'description' => 'Cuti tahunan mendadak',
                'approved1_status' => 0,
                'approved1_at' => '2026-09-15 11:00:00',
                'approved1_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'approved1_note' => 'Tidak dapat disetujui karena kuota cuti tim operasional pada tanggal tersebut sudah penuh.',
                'approved2_status' => null,
                'approved2_at' => null,
                'approved2_by' => null,
                'approved2_note' => null,
                'allowed_status' => null,
                'allowed_at' => null,
                'allowed_by' => null,
                'allowed_note' => null,
                'leave_saldo' => 12,
                'created_by' => 'u0000000-0000-0000-0001-000000000004', // Rizky Febrian
                'updated_by' => 'u0000000-0000-0000-0001-000000000001', // HR Budi Santoso
                'created_at' => '2026-09-14 15:00:00',
                'updated_at' => '2026-09-15 11:00:00',
            ],

            // =========================================================================
            // PT Hitachi (3 Staff Records: Approved, Pending, Rejected)
            // =========================================================================
            // 4. Approved
            [
                'id' => 4,
                'employ_id' => 'e0000000-0000-0000-0002-000000000002', // Ahmad Hidayat (Staff)
                'type_id' => 2103, // CUTI TAHUNAN
                'start_date' => '2026-09-21',
                'end_date' => '2026-09-22',
                'duration' => 2,
                'description' => 'Cuti tahunan urusan keluarga',
                'approved1_status' => 1,
                'approved1_at' => '2026-09-14 10:00:00',
                'approved1_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'approved1_note' => 'Disetujui',
                'approved2_status' => 1,
                'approved2_at' => '2026-09-14 10:00:00',
                'approved2_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'approved2_note' => 'Disetujui',
                'allowed_status' => 1,
                'allowed_at' => '2026-09-14 10:00:00',
                'allowed_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'allowed_note' => 'Disetujui',
                'leave_saldo' => 10,
                'created_by' => 'u0000000-0000-0000-0002-000000000002', // Ahmad Hidayat
                'updated_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'created_at' => '2026-09-13 09:00:00',
                'updated_at' => '2026-09-14 10:00:00',
            ],
            // 5. Pending (Menunggu Persetujuan)
            [
                'id' => 5,
                'employ_id' => 'e0000000-0000-0000-0002-000000000003', // Nurul Hidayah (Staff)
                'type_id' => 2101, // SAKIT
                'start_date' => '2026-09-28',
                'end_date' => '2026-09-29',
                'duration' => 2,
                'description' => 'Izin istirahat sakit',
                'approved1_status' => null,
                'approved1_at' => null,
                'approved1_by' => null,
                'approved1_note' => null,
                'approved2_status' => null,
                'approved2_at' => null,
                'approved2_by' => null,
                'approved2_note' => null,
                'allowed_status' => null,
                'allowed_at' => null,
                'allowed_by' => null,
                'allowed_note' => null,
                'leave_saldo' => 12,
                'created_by' => 'u0000000-0000-0000-0002-000000000003', // Nurul Hidayah
                'updated_by' => null,
                'created_at' => '2026-09-15 11:30:00',
                'updated_at' => '2026-09-15 11:30:00',
            ],
            // 6. Rejected (Ditolak)
            [
                'id' => 6,
                'employ_id' => 'e0000000-0000-0000-0002-000000000004', // Fajar Nugraha (Staff)
                'type_id' => 2103, // CUTI TAHUNAN
                'start_date' => '2026-09-19',
                'end_date' => '2026-09-19',
                'duration' => 1,
                'description' => 'Cuti keperluan mendadak',
                'approved1_status' => 0,
                'approved1_at' => '2026-09-15 14:00:00',
                'approved1_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'approved1_note' => 'Tidak dapat disetujui karena bertepatan dengan deadline project unit kerja.',
                'approved2_status' => null,
                'approved2_at' => null,
                'approved2_by' => null,
                'approved2_note' => null,
                'allowed_status' => null,
                'allowed_at' => null,
                'allowed_by' => null,
                'allowed_note' => null,
                'leave_saldo' => 12,
                'created_by' => 'u0000000-0000-0000-0002-000000000004', // Fajar Nugraha
                'updated_by' => 'u0000000-0000-0000-0002-000000000001', // HR Siti Rahmawati
                'created_at' => '2026-09-14 16:00:00',
                'updated_at' => '2026-09-15 14:00:00',
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_leave')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_leave')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
