<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalQuestionSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'template_id' => 1,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'id' => 2,
    'template_id' => 1,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  2 => 
  array (
    'id' => 3,
    'template_id' => 1,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  3 => 
  array (
    'id' => 4,
    'template_id' => 1,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'id' => 5,
    'template_id' => 1,
    'category_id' => 1,
    'group_kpi' => 'Pencapaian Target Departemen',
    'question' => 'Pencapaian Key Performance Indicators (KPI) unit kerja sesuai target tahunan',
    'formula_description' => 'Tercapai 100% = 10
Tercapai 90-99% = 8
Tercapai 80-89% = 6
Tercapai <80% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id' => 6,
    'template_id' => 1,
    'category_id' => 1,
    'group_kpi' => 'Efisiensi Operasional & Budget',
    'question' => 'Pengelolaan penggunaan anggaran dan efisiensi proses bisnis unit kerja',
    'formula_description' => 'Sesuai budget = 10
Over budget <5% = 8
Over budget 5-10% = 6
Over budget >10% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'id' => 7,
    'template_id' => 1,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  7 => 
  array (
    'id' => 8,
    'template_id' => 1,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'id' => 9,
    'template_id' => 2,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  9 => 
  array (
    'id' => 10,
    'template_id' => 2,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  10 => 
  array (
    'id' => 11,
    'template_id' => 2,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  11 => 
  array (
    'id' => 12,
    'template_id' => 2,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  12 => 
  array (
    'id' => 13,
    'template_id' => 2,
    'category_id' => 1,
    'group_kpi' => 'Pencapaian Target Departemen',
    'question' => 'Pencapaian Key Performance Indicators (KPI) unit kerja sesuai target tahunan',
    'formula_description' => 'Tercapai 100% = 10
Tercapai 90-99% = 8
Tercapai 80-89% = 6
Tercapai <80% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  13 => 
  array (
    'id' => 14,
    'template_id' => 2,
    'category_id' => 1,
    'group_kpi' => 'Efisiensi Operasional & Budget',
    'question' => 'Pengelolaan penggunaan anggaran dan efisiensi proses bisnis unit kerja',
    'formula_description' => 'Sesuai budget = 10
Over budget <5% = 8
Over budget 5-10% = 6
Over budget >10% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  14 => 
  array (
    'id' => 15,
    'template_id' => 2,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  15 => 
  array (
    'id' => 16,
    'template_id' => 2,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  16 => 
  array (
    'id' => 17,
    'template_id' => 3,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  17 => 
  array (
    'id' => 18,
    'template_id' => 3,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  18 => 
  array (
    'id' => 19,
    'template_id' => 3,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  19 => 
  array (
    'id' => 20,
    'template_id' => 3,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  20 => 
  array (
    'id' => 21,
    'template_id' => 3,
    'category_id' => 1,
    'group_kpi' => 'Pencapaian Target Departemen',
    'question' => 'Pencapaian Key Performance Indicators (KPI) unit kerja sesuai target tahunan',
    'formula_description' => 'Tercapai 100% = 10
Tercapai 90-99% = 8
Tercapai 80-89% = 6
Tercapai <80% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  21 => 
  array (
    'id' => 22,
    'template_id' => 3,
    'category_id' => 1,
    'group_kpi' => 'Efisiensi Operasional & Budget',
    'question' => 'Pengelolaan penggunaan anggaran dan efisiensi proses bisnis unit kerja',
    'formula_description' => 'Sesuai budget = 10
Over budget <5% = 8
Over budget 5-10% = 6
Over budget >10% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  22 => 
  array (
    'id' => 23,
    'template_id' => 3,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  23 => 
  array (
    'id' => 24,
    'template_id' => 3,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  24 => 
  array (
    'id' => 25,
    'template_id' => 4,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  25 => 
  array (
    'id' => 26,
    'template_id' => 4,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  26 => 
  array (
    'id' => 27,
    'template_id' => 4,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  27 => 
  array (
    'id' => 28,
    'template_id' => 4,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  28 => 
  array (
    'id' => 29,
    'template_id' => 4,
    'category_id' => 1,
    'group_kpi' => 'Pencapaian Target Departemen',
    'question' => 'Pencapaian Key Performance Indicators (KPI) unit kerja sesuai target tahunan',
    'formula_description' => 'Tercapai 100% = 10
Tercapai 90-99% = 8
Tercapai 80-89% = 6
Tercapai <80% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  29 => 
  array (
    'id' => 30,
    'template_id' => 4,
    'category_id' => 1,
    'group_kpi' => 'Efisiensi Operasional & Budget',
    'question' => 'Pengelolaan penggunaan anggaran dan efisiensi proses bisnis unit kerja',
    'formula_description' => 'Sesuai budget = 10
Over budget <5% = 8
Over budget 5-10% = 6
Over budget >10% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  30 => 
  array (
    'id' => 31,
    'template_id' => 4,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  31 => 
  array (
    'id' => 32,
    'template_id' => 4,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  32 => 
  array (
    'id' => 33,
    'template_id' => 5,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  33 => 
  array (
    'id' => 34,
    'template_id' => 5,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  34 => 
  array (
    'id' => 35,
    'template_id' => 5,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  35 => 
  array (
    'id' => 36,
    'template_id' => 5,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  36 => 
  array (
    'id' => 37,
    'template_id' => 5,
    'category_id' => 1,
    'group_kpi' => 'Pencapaian Target Departemen',
    'question' => 'Pencapaian Key Performance Indicators (KPI) unit kerja sesuai target tahunan',
    'formula_description' => 'Tercapai 100% = 10
Tercapai 90-99% = 8
Tercapai 80-89% = 6
Tercapai <80% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  37 => 
  array (
    'id' => 38,
    'template_id' => 5,
    'category_id' => 1,
    'group_kpi' => 'Efisiensi Operasional & Budget',
    'question' => 'Pengelolaan penggunaan anggaran dan efisiensi proses bisnis unit kerja',
    'formula_description' => 'Sesuai budget = 10
Over budget <5% = 8
Over budget 5-10% = 6
Over budget >10% = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  38 => 
  array (
    'id' => 39,
    'template_id' => 5,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  39 => 
  array (
    'id' => 40,
    'template_id' => 5,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  40 => 
  array (
    'id' => 41,
    'template_id' => 6,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  41 => 
  array (
    'id' => 42,
    'template_id' => 6,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  42 => 
  array (
    'id' => 43,
    'template_id' => 6,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  43 => 
  array (
    'id' => 44,
    'template_id' => 6,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  44 => 
  array (
    'id' => 45,
    'template_id' => 6,
    'category_id' => 1,
    'group_kpi' => 'Pengawasan & Eksekusi Program Kerja',
    'question' => 'Memastikan seluruh operasional dan tim berjalan sesuai SLA dan standar kualitas',
    'formula_description' => 'Tercapai 100% SLA = 10
Tercapai 90-99% SLA = 8
Tercapai 80-89% SLA = 6
Tercapai <80% SLA = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  45 => 
  array (
    'id' => 46,
    'template_id' => 6,
    'category_id' => 1,
    'group_kpi' => 'Pelaporan & Evaluasi Kinerja Tim',
    'question' => 'Penyusunan laporan kinerja berkala dan mitigasi kendala operasional',
    'formula_description' => 'Tepat waktu & akurat = 10
Terlambat 1 hari = 8
Terlambat 2 hari = 6
Terlambat >2 hari = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  46 => 
  array (
    'id' => 47,
    'template_id' => 6,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  47 => 
  array (
    'id' => 48,
    'template_id' => 6,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  48 => 
  array (
    'id' => 49,
    'template_id' => 7,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  49 => 
  array (
    'id' => 50,
    'template_id' => 7,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  50 => 
  array (
    'id' => 51,
    'template_id' => 7,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  51 => 
  array (
    'id' => 52,
    'template_id' => 7,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  52 => 
  array (
    'id' => 53,
    'template_id' => 7,
    'category_id' => 1,
    'group_kpi' => 'Pengawasan & Eksekusi Program Kerja',
    'question' => 'Memastikan seluruh operasional dan tim berjalan sesuai SLA dan standar kualitas',
    'formula_description' => 'Tercapai 100% SLA = 10
Tercapai 90-99% SLA = 8
Tercapai 80-89% SLA = 6
Tercapai <80% SLA = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  53 => 
  array (
    'id' => 54,
    'template_id' => 7,
    'category_id' => 1,
    'group_kpi' => 'Pelaporan & Evaluasi Kinerja Tim',
    'question' => 'Penyusunan laporan kinerja berkala dan mitigasi kendala operasional',
    'formula_description' => 'Tepat waktu & akurat = 10
Terlambat 1 hari = 8
Terlambat 2 hari = 6
Terlambat >2 hari = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  54 => 
  array (
    'id' => 55,
    'template_id' => 7,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  55 => 
  array (
    'id' => 56,
    'template_id' => 7,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  56 => 
  array (
    'id' => 57,
    'template_id' => 8,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  57 => 
  array (
    'id' => 58,
    'template_id' => 8,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  58 => 
  array (
    'id' => 59,
    'template_id' => 8,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  59 => 
  array (
    'id' => 60,
    'template_id' => 8,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  60 => 
  array (
    'id' => 61,
    'template_id' => 8,
    'category_id' => 1,
    'group_kpi' => 'Pengawasan & Eksekusi Program Kerja',
    'question' => 'Memastikan seluruh operasional dan tim berjalan sesuai SLA dan standar kualitas',
    'formula_description' => 'Tercapai 100% SLA = 10
Tercapai 90-99% SLA = 8
Tercapai 80-89% SLA = 6
Tercapai <80% SLA = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  61 => 
  array (
    'id' => 62,
    'template_id' => 8,
    'category_id' => 1,
    'group_kpi' => 'Pelaporan & Evaluasi Kinerja Tim',
    'question' => 'Penyusunan laporan kinerja berkala dan mitigasi kendala operasional',
    'formula_description' => 'Tepat waktu & akurat = 10
Terlambat 1 hari = 8
Terlambat 2 hari = 6
Terlambat >2 hari = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  62 => 
  array (
    'id' => 63,
    'template_id' => 8,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  63 => 
  array (
    'id' => 64,
    'template_id' => 8,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  64 => 
  array (
    'id' => 65,
    'template_id' => 9,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  65 => 
  array (
    'id' => 66,
    'template_id' => 9,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  66 => 
  array (
    'id' => 67,
    'template_id' => 9,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  67 => 
  array (
    'id' => 68,
    'template_id' => 9,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  68 => 
  array (
    'id' => 69,
    'template_id' => 9,
    'category_id' => 1,
    'group_kpi' => 'Pengawasan & Eksekusi Program Kerja',
    'question' => 'Memastikan seluruh operasional dan tim berjalan sesuai SLA dan standar kualitas',
    'formula_description' => 'Tercapai 100% SLA = 10
Tercapai 90-99% SLA = 8
Tercapai 80-89% SLA = 6
Tercapai <80% SLA = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  69 => 
  array (
    'id' => 70,
    'template_id' => 9,
    'category_id' => 1,
    'group_kpi' => 'Pelaporan & Evaluasi Kinerja Tim',
    'question' => 'Penyusunan laporan kinerja berkala dan mitigasi kendala operasional',
    'formula_description' => 'Tepat waktu & akurat = 10
Terlambat 1 hari = 8
Terlambat 2 hari = 6
Terlambat >2 hari = 4',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  70 => 
  array (
    'id' => 71,
    'template_id' => 9,
    'category_id' => 3,
    'group_kpi' => 'Leadership (Strategic Thinking)',
    'question' => 'Kemampuan menyusun dan mengeksekusi strategi unit kerja sesuai visi misi perusahaan',
    'formula_description' => '1-3 = Memahami strategi dasar
4-6 = Mengimplementasi rencana kerja
7-8 = Mengoptimalkan strategi unit
9-10 = Merumuskan strategi inovatif',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  71 => 
  array (
    'id' => 72,
    'template_id' => 9,
    'category_id' => 3,
    'group_kpi' => 'Leadership (People Development)',
    'question' => 'Kemampuan membimbing, mengarahkan, dan mengembangkaan anggota tim',
    'formula_description' => '1-3 = Memberi arahan umum
4-6 = Melakukan coaching rutin
7-8 = Mengembangkan kompetensi tim
9-10 = Menciptakan kader pemimpin baru',
    'weight' => 20,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  72 => 
  array (
    'id' => 73,
    'template_id' => 10,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  73 => 
  array (
    'id' => 74,
    'template_id' => 10,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  74 => 
  array (
    'id' => 75,
    'template_id' => 10,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  75 => 
  array (
    'id' => 76,
    'template_id' => 10,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  76 => 
  array (
    'id' => 77,
    'template_id' => 10,
    'category_id' => 1,
    'group_kpi' => 'Kualitas Hasil Pekerjaan',
    'question' => 'Hasil penyelesaian tugas sesuai dengan petunjuk teknis dan bebas dari kesalahan',
    'formula_description' => 'Bebas error = 10
Error <2% = 8
Error 2-5% = 6
Error >5% = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  77 => 
  array (
    'id' => 78,
    'template_id' => 10,
    'category_id' => 1,
    'group_kpi' => 'Ketepatan Waktu (Punctuality)',
    'question' => 'Penyelesaian tugas harian/mingguan sesuai deadline yang ditentukan',
    'formula_description' => 'On time 100% = 10
Terlambat 1 kali = 8
Terlambat 2 kali = 6
Terlambat >2 kali = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  78 => 
  array (
    'id' => 79,
    'template_id' => 11,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  79 => 
  array (
    'id' => 80,
    'template_id' => 11,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  80 => 
  array (
    'id' => 81,
    'template_id' => 11,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  81 => 
  array (
    'id' => 82,
    'template_id' => 11,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  82 => 
  array (
    'id' => 83,
    'template_id' => 11,
    'category_id' => 1,
    'group_kpi' => 'Kualitas Hasil Pekerjaan',
    'question' => 'Hasil penyelesaian tugas sesuai dengan petunjuk teknis dan bebas dari kesalahan',
    'formula_description' => 'Bebas error = 10
Error <2% = 8
Error 2-5% = 6
Error >5% = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  83 => 
  array (
    'id' => 84,
    'template_id' => 11,
    'category_id' => 1,
    'group_kpi' => 'Ketepatan Waktu (Punctuality)',
    'question' => 'Penyelesaian tugas harian/mingguan sesuai deadline yang ditentukan',
    'formula_description' => 'On time 100% = 10
Terlambat 1 kali = 8
Terlambat 2 kali = 6
Terlambat >2 kali = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  84 => 
  array (
    'id' => 85,
    'template_id' => 12,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  85 => 
  array (
    'id' => 86,
    'template_id' => 12,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  86 => 
  array (
    'id' => 87,
    'template_id' => 12,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  87 => 
  array (
    'id' => 88,
    'template_id' => 12,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  88 => 
  array (
    'id' => 89,
    'template_id' => 12,
    'category_id' => 1,
    'group_kpi' => 'Kualitas Hasil Pekerjaan',
    'question' => 'Hasil penyelesaian tugas sesuai dengan petunjuk teknis dan bebas dari kesalahan',
    'formula_description' => 'Bebas error = 10
Error <2% = 8
Error 2-5% = 6
Error >5% = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  89 => 
  array (
    'id' => 90,
    'template_id' => 12,
    'category_id' => 1,
    'group_kpi' => 'Ketepatan Waktu (Punctuality)',
    'question' => 'Penyelesaian tugas harian/mingguan sesuai deadline yang ditentukan',
    'formula_description' => 'On time 100% = 10
Terlambat 1 kali = 8
Terlambat 2 kali = 6
Terlambat >2 kali = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  90 => 
  array (
    'id' => 91,
    'template_id' => 13,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Communication)',
    'question' => 'Kemampuan untuk berkomunikasi dua arah
Kemampuan untuk mendengarkan dan didengarkan
Kemampuan untuk responsif',
    'formula_description' => '1-3 = Pasif
4-6 = Mampu merespon
7-8 = Mendengar responsif
9-10 = Siap menolong',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  91 => 
  array (
    'id' => 92,
    'template_id' => 13,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Customer Service Orientation)',
    'question' => 'Kemampuan untuk membantu dan melayani customer internal maupun eksternal',
    'formula_description' => '1-3 = Respon seadanya
4-6 = Menindaklanjuti keluhan
7-8 = Memperbaiki masalah
9-10 = Memberikan alternatif solusi',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  92 => 
  array (
    'id' => 93,
    'template_id' => 13,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Integrity)',
    'question' => 'Kemampuan untuk konsisten dan menunjukkan komitmen tinggi dalam bekerja',
    'formula_description' => '1-3 = Upaya minimal
4-6 = Menyesuaikan diri
7-8 = Kesetiaan dan kesadaran
9-10 = Mendukung misi perusahaan',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  93 => 
  array (
    'id' => 94,
    'template_id' => 13,
    'category_id' => 2,
    'group_kpi' => 'Core Competency (Teamwork)',
    'question' => 'Kemampuan untuk bekerja bersama dengan orang lain dan saling membantu',
    'formula_description' => '1-3 = Pasif dalam kelompok
4-6 = Kooperatif
7-8 = Memberikan semangat
9-10 = Membangun tim',
    'weight' => 10,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  94 => 
  array (
    'id' => 95,
    'template_id' => 13,
    'category_id' => 1,
    'group_kpi' => 'Kualitas Hasil Pekerjaan',
    'question' => 'Hasil penyelesaian tugas sesuai dengan petunjuk teknis dan bebas dari kesalahan',
    'formula_description' => 'Bebas error = 10
Error <2% = 8
Error 2-5% = 6
Error >5% = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
  95 => 
  array (
    'id' => 96,
    'template_id' => 13,
    'category_id' => 1,
    'group_kpi' => 'Ketepatan Waktu (Punctuality)',
    'question' => 'Penyelesaian tugas harian/mingguan sesuai deadline yang ditentukan',
    'formula_description' => 'On time 100% = 10
Terlambat 1 kali = 8
Terlambat 2 kali = 6
Terlambat >2 kali = 4',
    'weight' => 30,
    'created_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'updated_by' => '7df26957-6614-4c96-bde0-340ead4504ee',
    'deleted_by' => NULL,
    'created_at' => '2026-09-15 11:40:00',
    'updated_at' => '2026-09-15 11:40:00',
    'deleted_at' => NULL,
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_appraisal_question')->updateOrInsert(['id' => $item['id']], $item);
            }
        }
    }
}