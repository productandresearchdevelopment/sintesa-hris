<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinSeeder extends Seeder
{
    public function run()
    {
        $superadminId = 'a8a531d5-7968-450f-90ee-a4dfc4dbca38';
        $hrDieboldId  = 'u0000000-0000-0000-0001-000000000001';
        $hrHitachiId  = 'u0000000-0000-0000-0002-000000000001';

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('iq_bulletin_category_organization')->truncate();
        DB::table('iq_bulletin')->truncate();
        DB::table('iq_bulletin_category')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $catGlobal = DB::table('iq_bulletin_category')->insertGetId([
            'name' => 'Pengumuman',
            'alias' => 'ANNOUNCE',
            'color' => '0073E6',
            'description' => 'Pengumuman dan memo resmi perusahaan untuk seluruh organisasi',
            'company_id' => null,
            'created_by' => $superadminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catDieboldInfo = DB::table('iq_bulletin_category')->insertGetId([
            'name' => 'Berita & Informasi',
            'alias' => 'INFO',
            'color' => '10B981',
            'description' => 'Informasi operasional, kebijakan internal, dan pembaruan HR',
            'company_id' => 1,
            'created_by' => $hrDieboldId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catDieboldTraining = DB::table('iq_bulletin_category')->insertGetId([
            'name' => 'Pelatihan & Pengembangan',
            'alias' => 'TRAINING',
            'color' => '06B6D4',
            'description' => 'Program pelatihan teknikal, sertifikasi keahlian, dan workshop',
            'company_id' => 1,
            'created_by' => $hrDieboldId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catHitachiInfo = DB::table('iq_bulletin_category')->insertGetId([
            'name' => 'Berita & Informasi',
            'alias' => 'INFO',
            'color' => 'F59E0B',
            'description' => 'Informasi operasional, pengumuman divisi, dan fasilitas karyawan',
            'company_id' => 2,
            'created_by' => $hrHitachiId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catHitachiTraining = DB::table('iq_bulletin_category')->insertGetId([
            'name' => 'Pelatihan & Pengembangan',
            'alias' => 'TRAINING',
            'color' => 'EC4899',
            'description' => 'Program pelatihan otomasi, keselamatan kerja K3, dan modul teknis',
            'company_id' => 2,
            'created_by' => $hrHitachiId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dieboldOrgs = [1, 2];
        $hitachiOrgs = [3, 4];
        $allOrgs     = array_merge($dieboldOrgs, $hitachiOrgs);

        foreach ($allOrgs as $orgId) {
            DB::table('iq_bulletin_category_organization')->insert([
                'category_id' => $catGlobal,
                'organization_id' => $orgId,
            ]);
        }

        foreach ([$catDieboldInfo, $catDieboldTraining] as $catId) {
            foreach ($dieboldOrgs as $orgId) {
                DB::table('iq_bulletin_category_organization')->insert([
                    'category_id' => $catId,
                    'organization_id' => $orgId,
                ]);
            }
        }

        foreach ([$catHitachiInfo, $catHitachiTraining] as $catId) {
            foreach ($hitachiOrgs as $orgId) {
                DB::table('iq_bulletin_category_organization')->insert([
                    'category_id' => $catId,
                    'organization_id' => $orgId,
                ]);
            }
        }

        DB::table('iq_bulletin')->insert([
            [
                'title' => 'Program Pelatihan Technical Skill: Pemeliharaan & Troubleshooting Hardware ATM PT Diebold Nixdorf',
                'description' => 'Pelatihan intensif pemeliharaan mesin ATM, sistem keamanan hardware, dan penanganan insiden teknis di lapangan.',
                'category_id' => $catDieboldTraining,
                'is_pinned' => 1,
                'created_by' => $hrDieboldId,
                'created_at' => now(),
                'updated_at' => now(),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=1000&auto=format&fit=crop" alt="Hardware Maintenance Training" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Guna meningkatkan keandalan layanan perbankan mandiri dan standar SLA teknis, Tim Engineering PT Diebold Nixdorf menyelenggarakan program sertifikasi teknikal skill semester pertama 2026.
                        </p>

                        <div class="callout-box p-3 mb-4" style="background: #eff6ff; border-left: 4px solid #0073e6; border-radius: 0 14px 14px 0; text-align: left !important;">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-cpu-fill me-2"></i>Target & Sasaran Pelatihan</h6>
                            <p class="mb-0 text-dark small" style="line-height: 1.5; text-align: left !important;">Seluruh Teknisi Lapangan dan Field Engineer diwajibkan mengikuti modul perakitan modul dispenser, sensor keamanan optik, dan diagnostik firmware terbaru.</p>
                        </div>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-tools text-primary me-2"></i>Materi Pokok Pelatihan Teknikal</h4>
                        <ul class="list-unstyled ps-0" style="text-align: left !important;">
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>Hardware Architecture:</strong> Pembongkaran, pembersihan preventif, dan kalibrasi komponen mekanis card reader dan cash dispenser.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>Electronic Diagnostics:</strong> Penggunaan multimeter presisi dan diagnostic software untuk mendeteksi error sirkuit catu daya.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>Firmware & Security Patching:</strong> Prosedur aman pembaruan firmware dan perlindungan dari skimming fisik.</span>
                            </li>
                        </ul>

                        <div class="p-3 my-4" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; text-align: left !important;">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-calendar-check text-success me-2"></i>Jadwal Hands-on Workshop</h6>
                            <p class="small text-secondary mb-0">Workshop praktik dilaksanakan setiap hari Selasa & Kamis di Diebold Technical Lab Lt. 3.</p>
                        </div>
                    </div>
                ',
            ],
            [
                'title' => 'Panduan Penggunaan Fitur Absensi GPS & Self-Service Mobile PT Diebold Nixdorf',
                'description' => 'Tata cara absensi mobile, radius check-in, dan pengajuan lembur/cuti mandiri.',
                'category_id' => $catDieboldInfo,
                'is_pinned' => 0,
                'created_by' => $hrDieboldId,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1000&auto=format&fit=crop" alt="Mobile Attendance" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Mulai bulan ini, pencatatan kehadiran karyawan PT Diebold Nixdorf sepenuhnya menggunakan sistem absensi mandiri berbasis GPS melalui aplikasi Sintesa HRIS.
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Ketentuan Radius Kehadiran</h4>
                        <p class="small text-secondary" style="text-align: left !important;">Pastikan perangkat GPS Anda aktif dan berada dalam radius 100 meter dari lokasi penempatan kantor atau site operasional saat melakukan Clock-In dan Clock-Out.</p>
                    </div>
                ',
            ],
            [
                'title' => 'Jadwal Medical Check-Up (MCU) Tahunan 2026 Karyawan PT Diebold Nixdorf',
                'description' => 'Informasi pelaksanaan pemeriksaan kesehatan berkala tahunan untuk seluruh staf dan teknisi.',
                'category_id' => $catDieboldInfo,
                'is_pinned' => 0,
                'created_by' => $hrDieboldId,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1000&auto=format&fit=crop" alt="Medical Checkup" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Sebagai wujud kepedulian terhadap kesehatan dan keselamatan kerja karyawan, PT Diebold Nixdorf memfasilitasi Medical Check-Up tahunan secara gratis bekerjasama dengan klinik rekanan resmi.
                        </p>

                        <div class="p-3 my-3" style="background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 0 14px 14px 0; text-align: left !important;">
                            <h6 class="fw-bold text-success mb-1"><i class="bi bi-hospital me-2"></i>Periode Pelaksanaan</h6>
                            <p class="small mb-0 text-dark">Pelaksanaan dimulai tanggal 1 Oktober hingga 15 Oktober 2026. Silakan reservasi jadwal melalui portal HRGA.</p>
                        </div>
                    </div>
                ',
            ],

            [
                'title' => 'Program Pelatihan Technical Skill: Otomasi Industri & Pemrograman PLC PT Hitachi',
                'description' => 'Pelatihan lanjutan sistem kontrol PLC, SCADA, sensor industri, dan keselamatan operasional mesin PT Hitachi.',
                'category_id' => $catHitachiTraining,
                'is_pinned' => 1,
                'created_by' => $hrHitachiId,
                'created_at' => now(),
                'updated_at' => now(),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1000&auto=format&fit=crop" alt="Industrial Automation Training" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Dalam rangka mendukung modernisasi lini manufaktur dan otomasi cerdas, Divisi Engineering & HR PT Hitachi menyelenggarakan pelatihan technical skill intensif pemrograman PLC dan integrasi sistem SCADA.
                        </p>

                        <div class="callout-box p-3 mb-4" style="background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 0 14px 14px 0; text-align: left !important;">
                            <h6 class="fw-bold text-warning mb-1" style="color: #b45309 !important;"><i class="bi bi-gear-fill me-2"></i>Fokus Kompetensi Teknikal</h6>
                            <p class="mb-0 text-dark small" style="line-height: 1.5; text-align: left !important;">Meningkatkan kapabilitas teknisi dalam pemrograman Ladder Logic, integrasi IoT gateway, dan deteksi error real-time pada jalur perakitan.</p>
                        </div>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-motherboard text-warning me-2"></i>Kurikulum Pelatihan</h4>
                        <ul class="list-unstyled ps-0" style="text-align: left !important;">
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-warning fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>PLC Programming & Architecture:</strong> Pemrograman Ladder Diagram, Function Block, serta konfigurasi I/O module.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-warning fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>HMI & SCADA Interface:</strong> Desain antarmuka operator, alarm handling, dan data logging operasional mesin.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-warning fs-6 mt-1"></i>
                                <span class="text-dark small"><strong>Preventive & Predictive Maintenance:</strong> Analisis getaran mesin, sensor termal, dan pencegahan downtime mesin kritis.</span>
                            </li>
                        </ul>

                        <div class="p-3 my-4" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; text-align: left !important;">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-building-gear text-warning me-2"></i>Lokasi & Sertifikasi</h6>
                            <p class="small text-secondary mb-0">Pelatihan bertempat di Hitachi Automation Training Center dengan sertifikasi kompetensi resmi setelah kelulusan tes praktik.</p>
                        </div>
                    </div>
                ',
            ],
            [
                'title' => 'Sosialisasi Standar Keselamatan Kerja (K3) & Penggunaan APD PT Hitachi',
                'description' => 'Pedoman wajib kepatuhan K3 dan standar alat pelindung diri di area produksi dan workshop.',
                'category_id' => $catHitachiInfo,
                'is_pinned' => 0,
                'created_by' => $hrHitachiId,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1000&auto=format&fit=crop" alt="Safety First K3" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Keselamatan dan kesehatan kerja merupakan prioritas utama. Seluruh karyawan dan kontraktor wajib mematuhi protokol keselamatan kerja (Zero Accident Target).
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-shield-shaded text-danger me-2"></i>Peralatan APD Wajib</h4>
                        <p class="small text-secondary" style="text-align: left !important;">Wajib mengenakan Safety Helmet, Safety Shoes, Kacamata Pelindung, dan Rompi Reflektif saat berada di zona produksi aktif.</p>
                    </div>
                ',
            ],
            [
                'title' => 'Informasi Penyesuaian Fasilitas Asuransi & Penggantian Klaim Karyawan PT Hitachi',
                'description' => 'Prosedur pengajuan reimbursement rawat jalan dan pembaruan kartu kepesertaan asuransi kesehatan.',
                'category_id' => $catHitachiInfo,
                'is_pinned' => 0,
                'created_by' => $hrHitachiId,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1450133064473-71024230f91b?q=80&w=1000&auto=format&fit=crop" alt="Insurance Claim" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Departemen HR PT Hitachi telah memperbarui benefit asuransi rawat inap dan rawat jalan dengan sistem cashless pada jaringan rumah sakit rekanan.
                        </p>

                        <div class="p-3 my-3" style="background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 0 14px 14px 0; text-align: left !important;">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-credit-card-2-front me-2"></i>Digital Health Card</h6>
                            <p class="small mb-0 text-dark">Kartu asuransi digital dapat diakses langsung melalui aplikasi mobile portal kesehatan yang terhubung.</p>
                        </div>
                    </div>
                ',
            ],
        ]);
    }
}
