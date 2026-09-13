<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('iq_bulletin_category')->count() == 0) {
            $cat1 = DB::table('iq_bulletin_category')->insertGetId([
                'name' => 'Pengumuman',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cat2 = DB::table('iq_bulletin_category')->insertGetId([
                'name' => 'Informasi HR',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('iq_bulletin')->insert([
                [
                    'title' => 'Selamat Datang di Aplikasi Sintesa HRIS Mobile',
                    'description' => 'Aplikasi Sintesa HRIS kini hadir dengan tampilan mobile baru yang lebih cepat dan responsif.',
                    'content' => '<p>Yth. Seluruh Karyawan PT Sintesa Talenta Asia,</p><p>Kami dengan bangga mengumumkan peluncuran <strong>Aplikasi Mobile Sintesa HRIS</strong> yang dirancang untuk mempermudah aktivitas harian Anda.</p><ul><li>Presensi Masuk &amp; Pulang Real-time via GPS</li><li>Pengajuan Cuti dan Izin Kerja secara Online</li><li>Pembaruan Profil &amp; Data Keluarga Diri</li></ul><p>Mari manfaatkan fitur-fitur baru ini untuk efisiensi kerja bersama.</p>',
                    'category_id' => $cat1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Panduan Presensi Masuk & Pulang via Smartphone',
                    'description' => 'Gunakan fitur quick presensi pada halaman utama mobile untuk mencatat kehadiran Harian Anda.',
                    'content' => '<h3>Panduan Presensi Mobile:</h3><ol><li>Buka aplikasi Sintesa HRIS di browser smartphone Anda.</li><li>Pada halaman utama, klik widget <strong>Presensi Masuk</strong>.</li><li>Izinkan lokasi GPS jika diminta oleh browser.</li><li>Tekan tombol <strong>Clock In / Clock Out</strong> untuk mencatat kehadiran.</li></ol><p><em>Catatan: Pastikan Anda berada dalam radius kantor yang telah ditentukan.</em></p>',
                    'category_id' => $cat2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Pengajuan Cuti & Perubahan Data Diri',
                    'description' => 'Seluruh pengajuan cuti dan perubahan data kini dapat dilakukan langsung dari genggaman.',
                    'content' => '<p>Pengajuan Cuti Tahunan dan Pembaruan Data Pegawai dapat diajukan langsung melalui menu <strong>Leave</strong> dan <strong>Profile</strong> pada aplikasi mobile.</p><p>Setiap pengajuan perubahan data akan diverifikasi oleh Tim HRGA sebelum diperbarui di sistem utama.</p>',
                    'category_id' => $cat2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        } else {
            // Update content for existing records if content is null or empty
            DB::table('iq_bulletin')->whereNull('content')->orWhere('content', '')->update([
                'content' => '<p>Informasi selengkapnya mengenai pengumuman ini dapat diakses pada sistem Sintesa HRIS.</p>'
            ]);
        }
    }
}
