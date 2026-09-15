<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinSeeder extends Seeder
{
    public function run()
    {
        $catPengumuman = DB::table('iq_bulletin_category')->whereIn('name', ['Pengumuman', 'Announcement', 'ANNOUNCEMENT'])->first();
        $catHR = DB::table('iq_bulletin_category')->whereIn('name', ['Informasi HR', 'HR Information', 'HR INFORMATION'])->first();
        $catTraining = DB::table('iq_bulletin_category')->whereIn('name', ['Training & Development', 'TRAINING & DEVELOPMENT'])->first();

        if (!$catPengumuman) {
            $cat1 = DB::table('iq_bulletin_category')->insertGetId([
                'name' => 'Announcement',
                'alias' => 'ANNOUNCEMENT',
                'color' => '0073E6',
                'description' => 'Official corporate announcements and memos',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cat1 = $catPengumuman->id;
            DB::table('iq_bulletin_category')->where('id', $cat1)->update([
                'name' => 'Announcement',
                'alias' => 'ANNOUNCEMENT',
                'color' => '0073E6',
                'description' => 'Official corporate announcements and memos',
                'updated_at' => now(),
            ]);
        }

        if (!$catHR) {
            $cat2 = DB::table('iq_bulletin_category')->insertGetId([
                'name' => 'HR Information',
                'alias' => 'HR INFO',
                'color' => '10B981',
                'description' => 'Human resource updates, policies, and regulations',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cat2 = $catHR->id;
            DB::table('iq_bulletin_category')->where('id', $cat2)->update([
                'name' => 'HR Information',
                'alias' => 'HR INFO',
                'color' => '10B981',
                'description' => 'Human resource updates, policies, and regulations',
                'updated_at' => now(),
            ]);
        }

        if (!$catTraining) {
            $cat3 = DB::table('iq_bulletin_category')->insertGetId([
                'name' => 'Training & Development',
                'alias' => 'TRAINING',
                'color' => 'F59E0B',
                'description' => 'Training programs, workshops, and career learning',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cat3 = $catTraining->id;
            DB::table('iq_bulletin_category')->where('id', $cat3)->update([
                'name' => 'Training & Development',
                'alias' => 'TRAINING',
                'color' => 'F59E0B',
                'description' => 'Training programs, workshops, and career learning',
                'updated_at' => now(),
            ]);
        }

        // Link categories to organizations in iq_bulletin_category_organization
        if (DB::table('iq_bulletin_category_organization')->count() == 0) {
            $orgs = DB::table('iq_org')->pluck('id');
            foreach ([$cat1, $cat2, $cat3] as $catId) {
                foreach ($orgs as $orgId) {
                    DB::table('iq_bulletin_category_organization')->insert([
                        'category_id' => $catId,
                        'organization_id' => $orgId,
                    ]);
                }
            }
        }

        // Clear existing bulletin rows for fresh seed
        DB::table('iq_bulletin')->delete();

        DB::table('iq_bulletin')->insert([
            [
                'title' => 'Panduan Pelatihan 2026: Menguasai Soft Skills & Hard Skills Utama untuk Akselerasi Karir',
                'description' => 'Program komprehensif peningkatan kemampuan teknis dan interpersonal karyawan PT Sintesa Talenta Asia.',
                'category_id' => $cat3,
                'is_pinned' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=1000&auto=format&fit=crop" alt="Employee Training Workshop" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Di era transformasi digital dan iklim kerja yang dinamis, keberhasilan seorang profesional ditentukan oleh keseimbangan antara <strong>Hard Skills</strong> yang presisi dan <strong>Soft Skills</strong> yang adaptif.
                        </p>

                        <div class="callout-box p-3 mb-4" style="background: #eff6ff; border-left: 4px solid #0073e6; border-radius: 0 14px 14px 0; text-align: left !important;">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-lightbulb-fill me-2"></i>Key Highlight Training 2026</h6>
                            <p class="mb-0 text-dark small" style="line-height: 1.5; text-align: left !important;">Tim HR & Development merancang modul khusus untuk memperkuat 5 Pilar Utama Kompetensi Karyawan guna menghadapi tantangan bisnis terkini.</p>
                        </div>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-gear-wide-connected text-primary me-2"></i>1. Hard Skills Prioritas 2026</h4>
                        <p class="small text-secondary" style="text-align: left !important;">Kemampuan teknis yang terukur sangat penting untuk meningkatkan efisiensi operasional dan kualitas hasil kerja:</p>

                        <div class="skills-grid my-3">
                            <div class="p-3 mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; text-align: left !important;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1">Data & Analytics</span>
                                    <strong class="text-dark">Business Intelligence & Reporting</strong>
                                </div>
                                <small class="text-secondary d-block" style="text-align: left !important;">Penguasaan visualisasi data dengan PowerBI/Excel Dashboard untuk pengambilan keputusan berbasis data (Data-Driven Decisions).</small>
                            </div>

                            <div class="p-3 mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; text-align: left !important;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success rounded-pill px-2.5 py-1">Automation</span>
                                    <strong class="text-dark">AI-Assisted Productivity</strong>
                                </div>
                                <small class="text-secondary d-block" style="text-align: left !important;">Pemanfaatan AI tools dan prompt engineering untuk otomatisasi dokumen, riset cepat, dan efisiensi alur kerja harian.</small>
                            </div>

                            <div class="p-3 mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; text-align: left !important;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-info text-dark rounded-pill px-2.5 py-1">Project Mgmt</span>
                                    <strong class="text-dark">Agile & Sprint Planning</strong>
                                </div>
                                <small class="text-secondary d-block" style="text-align: left !important;">Metodologi kerja Agile untuk eksekusi proyek tepat waktu dengan standar kontrol kualitas tinggi.</small>
                            </div>
                        </div>

                        <div class="my-4 text-center">
                            <img src="https://images.unsplash.com/photo-1531497865144-0464ef8fb9a9?q=80&w=1000&auto=format&fit=crop" alt="Team Collaboration" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 14px;">
                            <small class="text-muted d-block mt-1">Sesi Diskusi & Hands-on Lab Karyawan Sintesa</small>
                        </div>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-people-fill text-success me-2"></i>2. Soft Skills Esensial</h4>
                        <p class="small text-secondary" style="text-align: left !important;">Keterampilan interpersonal membentuk budaya kerja yang kolaboratif dan sehat:</p>

                        <ul class="list-unstyled ps-0" style="text-align: left !important;">
                            <li class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="text-dark d-block">Communication & Empathy</strong>
                                    <span class="text-secondary small">Menyampaikan ide dengan jelas, mendengarkan secara aktif, dan membangun hubungan kerja yang harmonis antardepartemen.</span>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="text-dark d-block">Critical Thinking & Problem Solving</strong>
                                    <span class="text-secondary small">Menganalisis akar masalah secara rinci serta merumuskan solusi alternatif dengan cepat saat situasi tak terduga.</span>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="text-dark d-block">Adaptability & Resiliency</strong>
                                    <span class="text-secondary small">Fleksibilitas dalam beradaptasi dengan teknologi baru dan resiliensi tinggi dalam menghadapi perubahan dinamika industri.</span>
                                </div>
                            </li>
                        </ul>

                        <div class="p-3 my-4" style="background: linear-gradient(135deg, #0073e6 0%, #00a651 100%); color: #ffffff; border-radius: 16px; text-align: left !important;">
                            <h6 class="fw-bold text-white mb-2"><i class="bi bi-journal-bookmark-fill me-2"></i>Jadwal Sesi Pelatihan Karyawan</h6>
                            <p class="small mb-2 opacity-90">Daftarkan diri Anda melalui portal LMS Sintesa atau hubungi Tim HRGA untuk informasi slot pelatihan bulanan.</p>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <span class="badge bg-white text-primary fw-bold">Setiap Hari Jumat</span>
                                <span class="badge bg-white text-success fw-bold">14:00 - 16:00 WIB</span>
                            </div>
                        </div>
                    </div>
                ',
            ],
            [
                'title' => 'Workshop Kepemimpinan 2026: Strategi Effective Delegation & Team Synergies',
                'description' => 'Pelatihan kepemimpinan interaktif untuk Supervisors, Team Leads, dan Managers Sintesa.',
                'category_id' => $cat1,
                'is_pinned' => 0,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=1000&auto=format&fit=crop" alt="Leadership Workshop" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Kepemimpinan yang efektif bukan tentang memberikan perintah, melainkan memberikan ruang bagi tim untuk tumbuh dan mengambil tanggung jawab secara percaya diri.
                        </p>

                        <blockquote class="my-4 p-3" style="background: #f8fafc; border-left: 4px solid #00a651; border-radius: 0 14px 14px 0; font-style: italic; color: #334155; text-align: left !important;">
                            "Leadership is unlocking people\'s potential to become better." – Executive HR Leadership Team
                        </blockquote>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-award-fill text-warning me-2"></i>Materi Pokok Workshop</h4>
                        <ol class="ps-3 mb-4 text-dark" style="line-height: 1.8; text-align: left !important;">
                            <li class="mb-2"><strong>Situational Leadership Model:</strong> Memahami kapan harus mengarahkan, mendukung, atau mendelegasikan tugas berdasarkan tingkat kematangan anggota tim.</li>
                            <li class="mb-2"><strong>Constructive Feedback & 1-on-1 Coaching:</strong> Teknik menyampaikan umpan balik yang membangun tanpa mengurangi motivasi kerja.</li>
                            <li class="mb-2"><strong>Conflict Resolution:</strong> Menyelesaikan perbedaan pendapat dalam tim dengan pendekatan win-win solution.</li>
                        </ol>

                        <div class="p-3 my-4" style="background: #fff7ed; border: 1px solid #ffedd5; border-radius: 14px; text-align: left !important;">
                            <h6 class="fw-bold text-warning mb-1" style="color: #c2410c !important;"><i class="bi bi-calendar-event me-2"></i>Catat Tanggalnya!</h6>
                            <p class="small mb-0 text-dark">Sesi Executive Leadership Workshop akan diadakan pada <strong>28 September 2026</strong> di Auditorium Utama Sintesa Tower & Streaming via Zoom Link.</p>
                        </div>
                    </div>
                ',
            ],
            [
                'title' => 'Panduan Digital Workplace: Maksimalisasi Fitur Sintesa HRIS Mobile',
                'description' => 'Tips dan trik menggunakan aplikasi mobile Sintesa untuk absensi, pengajuan cuti, dan penilaian kinerja.',
                'category_id' => $cat2,
                'is_pinned' => 0,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
                'content' => '
                    <div class="article-content-wrapper" style="text-align: left !important;">
                        <div class="article-hero-box mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1000&auto=format&fit=crop" alt="Mobile HRIS App Usage" style="width: 100%; height: 220px; object-fit: cover; display: block;">
                        </div>

                        <p class="lead fw-bold text-dark" style="font-size: 15px; line-height: 1.6; text-align: left !important;">
                            Aplikasi Sintesa HRIS Mobile dirancang untuk memberikan kemudahan akses layanan mandiri (Employee Self-Service) secara efisien langsung dari smartphone Anda.
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3" style="font-size: 16px; text-align: left !important;"><i class="bi bi-phone-vibrate text-primary me-2"></i>Fitur Unggulan Mobile</h4>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="p-3 text-center" style="background: #eff6ff; border-radius: 14px; border: 1px solid #dbeafe;">
                                    <i class="bi bi-geo-alt-fill text-primary fs-3 d-block mb-1"></i>
                                    <strong class="text-dark d-block small">GPS Attendance</strong>
                                    <span class="text-muted" style="font-size: 11px;">Clock in & out akurat</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 text-center" style="background: #ecfdf5; border-radius: 14px; border: 1px solid #a7f3d0;">
                                    <i class="bi bi-calendar2-check-fill text-success fs-3 d-block mb-1"></i>
                                    <strong class="text-dark d-block small">Leave Portal</strong>
                                    <span class="text-muted" style="font-size: 11px;">Pengajuan cuti cepat</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-secondary small" style="text-align: left !important;">Pastikan koneksi internet dan GPS pada perangkat Anda aktif untuk kenyamanan penggunaan aplikasi.</p>
                    </div>
                ',
            ],
        ]);
    }
}
