<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalDataSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 1001,
    'group' => 'gender',
    'name' => 'LAKI LAKI',
    'alias' => 'MALE',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  1 => 
  array (
    'id' => 1002,
    'group' => 'gender',
    'name' => 'PEREMPUAN',
    'alias' => 'FEMALE',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  2 => 
  array (
    'id' => 1101,
    'group' => 'marital',
    'name' => 'LAJANG',
    'alias' => 'SINGLE',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  3 => 
  array (
    'id' => 1102,
    'group' => 'marital',
    'name' => 'KAWIN',
    'alias' => 'KAWIN',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  4 => 
  array (
    'id' => 1103,
    'group' => 'marital',
    'name' => 'CERAI',
    'alias' => 'CERAI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  5 => 
  array (
    'id' => 1201,
    'group' => 'religion',
    'name' => 'ISLAM',
    'alias' => 'ISLAM',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  6 => 
  array (
    'id' => 1202,
    'group' => 'religion',
    'name' => 'KRISTEN',
    'alias' => 'KRISTEN',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  7 => 
  array (
    'id' => 1203,
    'group' => 'religion',
    'name' => 'KATOLIK',
    'alias' => 'KATOLIK',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  8 => 
  array (
    'id' => 1204,
    'group' => 'religion',
    'name' => 'HINDU',
    'alias' => 'HINDU',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  9 => 
  array (
    'id' => 1205,
    'group' => 'religion',
    'name' => 'BUDHA',
    'alias' => 'BUDHA',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  10 => 
  array (
    'id' => 1206,
    'group' => 'religion',
    'name' => 'KATOLIK',
    'alias' => 'KATOLIK',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  11 => 
  array (
    'id' => 1207,
    'group' => 'religion',
    'name' => 'LAINYA',
    'alias' => 'LAINYA',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  12 => 
  array (
    'id' => 1301,
    'group' => 'bank',
    'name' => 'BANK NEGARA INDONESIA (BNI)',
    'alias' => 'BNI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  13 => 
  array (
    'id' => 1302,
    'group' => 'bank',
    'name' => 'BANK RAKYAT INDONESIA (BRI)',
    'alias' => 'BRI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  14 => 
  array (
    'id' => 1303,
    'group' => 'bank',
    'name' => 'BANK CENTRAL ASIA (BCA)',
    'alias' => 'BCA',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  15 => 
  array (
    'id' => 1304,
    'group' => 'bank',
    'name' => 'BANK SYARIAH INDONESIA (BSI)',
    'alias' => 'BSI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  16 => 
  array (
    'id' => 1305,
    'group' => 'bank',
    'name' => 'BANK MANDIRI',
    'alias' => 'MANDIRI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  17 => 
  array (
    'id' => 1401,
    'group' => 'citizen',
    'name' => 'NOMOR KTP',
    'alias' => 'KTP',
    'color' => NULL,
    'property' => 'true',
    'description' => NULL,
  ),
  18 => 
  array (
    'id' => 1402,
    'group' => 'citizen',
    'name' => 'NOMOR PASSPOR',
    'alias' => 'PASPOR',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  19 => 
  array (
    'id' => 1403,
    'group' => 'citizen',
    'name' => 'NOMOR BPJS KESEHATAN',
    'alias' => 'BPJS KHN',
    'color' => NULL,
    'property' => 'true',
    'description' => NULL,
  ),
  20 => 
  array (
    'id' => 1405,
    'group' => 'citizen',
    'name' => 'NOMOR NPWP',
    'alias' => 'NPWP',
    'color' => NULL,
    'property' => 'true',
    'description' => NULL,
  ),
  21 => 
  array (
    'id' => 1406,
    'group' => 'citizen',
    'name' => 'NOMOR KARTU KELUARGA',
    'alias' => 'KK',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  22 => 
  array (
    'id' => 1407,
    'group' => 'citizen',
    'name' => 'NOMOR BPJS KETENAGAKERJAAN',
    'alias' => 'BPJS KTN',
    'color' => '',
    'property' => 'true',
    'description' => NULL,
  ),
  23 => 
  array (
    'id' => 1408,
    'group' => 'citizen',
    'name' => 'NOMOR ASURANSI',
    'alias' => 'ASURANSI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  24 => 
  array (
    'id' => 1409,
    'group' => 'citizen',
    'name' => 'NOMOR VAKSIN',
    'alias' => 'VAKSIN',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  25 => 
  array (
    'id' => 1501,
    'group' => 'contract_status',
    'name' => 'CONTRACT - I',
    'alias' => 'CONTRACT - I',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  26 => 
  array (
    'id' => 1502,
    'group' => 'contract_status',
    'name' => 'CONTRACT - II',
    'alias' => 'CONTRACT - II',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  27 => 
  array (
    'id' => 1503,
    'group' => 'contract_status',
    'name' => 'CONTRACT - III',
    'alias' => 'CONTRACT - III',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  28 => 
  array (
    'id' => 1504,
    'group' => 'contract_status',
    'name' => 'CONTRACT - IV',
    'alias' => 'CONTRACT - IV',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  29 => 
  array (
    'id' => 1505,
    'group' => 'contract_status',
    'name' => 'CONTRACT - V',
    'alias' => 'CONTRACT - V',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  30 => 
  array (
    'id' => 1506,
    'group' => 'contract_status',
    'name' => 'CONTRACT - VI',
    'alias' => 'CONTRACT - VI',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  31 => 
  array (
    'id' => 1507,
    'group' => 'contract_status',
    'name' => 'PERMANENT',
    'alias' => 'PERMANENT',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  32 => 
  array (
    'id' => 1508,
    'group' => 'contract_status',
    'name' => 'RETIREMENT',
    'alias' => 'RETIREMENT',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  33 => 
  array (
    'id' => 1509,
    'group' => 'contract_status',
    'name' => 'RESIGN',
    'alias' => 'RESIGN',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  34 => 
  array (
    'id' => 1510,
    'group' => 'contract_status',
    'name' => 'TERMINATE',
    'alias' => 'TERMINATE',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  35 => 
  array (
    'id' => 1511,
    'group' => 'contract_status',
    'name' => 'MUTATION',
    'alias' => 'MUTATION',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  36 => 
  array (
    'id' => 1512,
    'group' => 'contract_status',
    'name' => 'FREELANCE',
    'alias' => 'FREELANCE',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  37 => 
  array (
    'id' => 1601,
    'group' => 'career',
    'name' => 'MUTATION',
    'alias' => 'MUTATION',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  38 => 
  array (
    'id' => 1602,
    'group' => 'career',
    'name' => 'PROMOTION',
    'alias' => 'PROMOTION',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  39 => 
  array (
    'id' => 1603,
    'group' => 'career',
    'name' => 'DEMOTION',
    'alias' => 'DEMOTION',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  40 => 
  array (
    'id' => 1604,
    'group' => 'education',
    'name' => 'SEKOLAH DASAR (SD)',
    'alias' => 'SD',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  41 => 
  array (
    'id' => 1605,
    'group' => 'education',
    'name' => 'SEKOLAH MENENGAH PERTAMA (SMP)',
    'alias' => 'SMP',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  42 => 
  array (
    'id' => 1606,
    'group' => 'education',
    'name' => 'SEKOLAH MENENGAH ATAS (SMA)',
    'alias' => 'SMA',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  43 => 
  array (
    'id' => 1607,
    'group' => 'education',
    'name' => 'DIPLOMA I',
    'alias' => 'D1',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  44 => 
  array (
    'id' => 1608,
    'group' => 'education',
    'name' => 'DIPLOMA II',
    'alias' => 'D2',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  45 => 
  array (
    'id' => 1609,
    'group' => 'education',
    'name' => 'DIPLOMA III',
    'alias' => 'D3',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  46 => 
  array (
    'id' => 1610,
    'group' => 'education',
    'name' => 'SARJANA I',
    'alias' => 'S1',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  47 => 
  array (
    'id' => 1611,
    'group' => 'education',
    'name' => 'SARJANA II',
    'alias' => 'S2',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  48 => 
  array (
    'id' => 1714,
    'group' => 'familly',
    'name' => 'ISTERI / SUAMI',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  49 => 
  array (
    'id' => 1715,
    'group' => 'familly',
    'name' => 'ANAK KANDUNG',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  50 => 
  array (
    'id' => 1716,
    'group' => 'familly',
    'name' => 'IBU',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  51 => 
  array (
    'id' => 1717,
    'group' => 'familly',
    'name' => 'ADIK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  52 => 
  array (
    'id' => 1718,
    'group' => 'familly',
    'name' => 'KAKAK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  53 => 
  array (
    'id' => 1719,
    'group' => 'familly',
    'name' => 'BAPAK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  54 => 
  array (
    'id' => 1801,
    'group' => 'familly_occupation',
    'name' => 'BEKERJA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  55 => 
  array (
    'id' => 1802,
    'group' => 'familly_occupation',
    'name' => 'TIDAK BEKERJA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  56 => 
  array (
    'id' => 1803,
    'group' => 'familly_occupation',
    'name' => 'SEKOLAH',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  57 => 
  array (
    'id' => 1901,
    'group' => 'emergency_relation',
    'name' => 'IBU',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  58 => 
  array (
    'id' => 1902,
    'group' => 'emergency_relation',
    'name' => 'AYAH',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  59 => 
  array (
    'id' => 1903,
    'group' => 'emergency_relation',
    'name' => 'KAKA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  60 => 
  array (
    'id' => 1904,
    'group' => 'emergency_relation',
    'name' => 'ADIK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  61 => 
  array (
    'id' => 1905,
    'group' => 'emergency_relation',
    'name' => 'SAUDARA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  62 => 
  array (
    'id' => 1906,
    'group' => 'emergency_relation',
    'name' => 'PASANGAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  63 => 
  array (
    'id' => 2101,
    'group' => 'leave_type',
    'name' => 'SAKIT',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":true}',
    'description' => NULL,
  ),
  64 => 
  array (
    'id' => 2102,
    'group' => 'leave_type',
    'name' => 'BERSAMA',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  65 => 
  array (
    'id' => 2103,
    'group' => 'leave_type',
    'name' => 'CUTI TAHUNAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":true}',
    'description' => NULL,
  ),
  66 => 
  array (
    'id' => 2104,
    'group' => 'leave_type',
    'name' => 'BAPTIS',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  67 => 
  array (
    'id' => 2105,
    'group' => 'leave_type',
    'name' => 'MENIKAHKAN ANAK',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  68 => 
  array (
    'id' => 2106,
    'group' => 'leave_type',
    'name' => 'KHITAN ANAK',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  69 => 
  array (
    'id' => 2107,
    'group' => 'leave_type',
    'name' => 'SUAMI/ISTRI, ORANG TUA/MERTUA, ANAK, MENANTU MENINGGAL',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  70 => 
  array (
    'id' => 2108,
    'group' => 'leave_type',
    'name' => 'ANGGOTA KELUARGA (SEDARAH SEKANDUNG) TINGGAL 1 RUMAH MENINGGAL',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  71 => 
  array (
    'id' => 2109,
    'group' => 'leave_type',
    'name' => 'ISTRI MELAHIRKAN/KEGUGURANKEGUGURAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  72 => 
  array (
    'id' => 2110,
    'group' => 'leave_type',
    'name' => 'KEGUGURAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  73 => 
  array (
    'id' => 2111,
    'group' => 'leave_type',
    'name' => 'MELAHIRKAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  74 => 
  array (
    'id' => 3101,
    'group' => 'appraisal_category',
    'name' => 'Technical Ability & Work Result',
    'alias' => 'TECHNICAL',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  75 => 
  array (
    'id' => 3102,
    'group' => 'appraisal_category',
    'name' => 'Behavior & Work Processes',
    'alias' => 'BEHAVIOR',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  76 => 
  array (
    'id' => 3103,
    'group' => 'appraisal_category',
    'name' => 'Leadership',
    'alias' => 'LEADERSHIP',
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  77 => 
  array (
    'id' => 3201,
    'group' => 'appraisal_grade',
    'name' => 'GOOD',
    'alias' => 'GOOD',
    'color' => NULL,
    'property' => '{"max": 10, "min": 0}',
    'description' => NULL,
  ),
  78 => 
  array (
    'id' => 3202,
    'group' => 'appraisal_grade',
    'name' => 'EXELENT',
    'alias' => 'EXELENT',
    'color' => NULL,
    'property' => '{"max": 10, "min": 0}',
    'description' => NULL,
  ),
  79 => 
  array (
    'id' => 3203,
    'group' => 'appraisal_grade',
    'name' => 'DLL',
    'alias' => 'DLL',
    'color' => NULL,
    'property' => '{"max": 10, "min": 0}',
    'description' => NULL,
  ),
  80 => 
  array (
    'id' => 4101,
    'group' => 'position',
    'name' => 'CEO',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  81 => 
  array (
    'id' => 4102,
    'group' => 'position',
    'name' => 'FIELD SERVICE DIV HEAD',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  82 => 
  array (
    'id' => 4103,
    'group' => 'position',
    'name' => 'DIREKTUR',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  83 => 
  array (
    'id' => 4104,
    'group' => 'position',
    'name' => 'FIELD SERVICE MANAGER NON JAWA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  84 => 
  array (
    'id' => 4105,
    'group' => 'position',
    'name' => 'FIELD SERVICE MANAGER JABODETABEK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  85 => 
  array (
    'id' => 4106,
    'group' => 'position',
    'name' => 'FIELD SERVICE  MANAGER
 MONITORING & REPORTING',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  86 => 
  array (
    'id' => 4107,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR SUMATERA 1',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  87 => 
  array (
    'id' => 4108,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR SUMATERA 2',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  88 => 
  array (
    'id' => 4109,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR BALI NUSRA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  89 => 
  array (
    'id' => 4110,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR INDONESIA TIMUR',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  90 => 
  array (
    'id' => 4111,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR KALIMANTAN',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  91 => 
  array (
    'id' => 4112,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR JABODETABEK',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  92 => 
  array (
    'id' => 4113,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR JAWA TIMUR',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  93 => 
  array (
    'id' => 4114,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR JAWA BARAT',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  94 => 
  array (
    'id' => 4115,
    'group' => 'position',
    'name' => 'FIELD SERVICE SUPERVISOR RO YOGYAKARTA',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  95 => 
  array (
    'id' => 4122,
    'group' => 'leave_type',
    'name' => 'CUTI UMROH',
    'alias' => NULL,
    'color' => NULL,
    'property' => '{"flag_reduce_balance":false}',
    'description' => NULL,
  ),
  96 => 
  array (
    'id' => 5001,
    'group' => 'education_major',
    'name' => 'Periklanan/Media',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  97 => 
  array (
    'id' => 5002,
    'group' => 'education_major',
    'name' => 'Agrikultur/Aquakultur/Perhutanan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  98 => 
  array (
    'id' => 5003,
    'group' => 'education_major',
    'name' => 'Operasi Pesawat Terbang/Manajemen Bandara',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  99 => 
  array (
    'id' => 5004,
    'group' => 'education_major',
    'name' => 'Arsitektur',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  100 => 
  array (
    'id' => 5005,
    'group' => 'education_major',
    'name' => 'Seni/Desain/Multimedia Kreatif',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  101 => 
  array (
    'id' => 5006,
    'group' => 'education_major',
    'name' => 'Biologi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  102 => 
  array (
    'id' => 5007,
    'group' => 'education_major',
    'name' => 'BioTeknologi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  103 => 
  array (
    'id' => 5008,
    'group' => 'education_major',
    'name' => 'Bisnis/Administrasi/Manajemen',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  104 => 
  array (
    'id' => 5009,
    'group' => 'education_major',
    'name' => 'Kimia',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  105 => 
  array (
    'id' => 5010,
    'group' => 'education_major',
    'name' => 'Komersial',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  106 => 
  array (
    'id' => 5011,
    'group' => 'education_major',
    'name' => 'Ilmu Komputer/Teknologi Informasi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  107 => 
  array (
    'id' => 5012,
    'group' => 'education_major',
    'name' => 'Kedokteran Gigi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  108 => 
  array (
    'id' => 5013,
    'group' => 'education_major',
    'name' => 'Ekonomi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  109 => 
  array (
    'id' => 5014,
    'group' => 'education_major',
    'name' => 'Jurnalisme',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  110 => 
  array (
    'id' => 5015,
    'group' => 'education_major',
    'name' => 'Pendidikan/Pengajaran/Pelatihan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  111 => 
  array (
    'id' => 5016,
    'group' => 'education_major',
    'name' => 'Teknik (Aviasi/Penerbangan/Astronotika)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  112 => 
  array (
    'id' => 5017,
    'group' => 'education_major',
    'name' => 'Teknik (Bioteknologi/Biomedikal)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  113 => 
  array (
    'id' => 5018,
    'group' => 'education_major',
    'name' => 'Teknik Kimia',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  114 => 
  array (
    'id' => 5019,
    'group' => 'education_major',
    'name' => 'Teknik Sipil',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  115 => 
  array (
    'id' => 5020,
    'group' => 'education_major',
    'name' => 'Teknik (Komputer/Telekomunikasi)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  116 => 
  array (
    'id' => 5021,
    'group' => 'education_major',
    'name' => 'Teknik (Elektro)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  117 => 
  array (
    'id' => 5022,
    'group' => 'education_major',
    'name' => 'Teknik (Lingkungan/Kesehatan/Keamanan)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  118 => 
  array (
    'id' => 5023,
    'group' => 'education_major',
    'name' => 'Teknik (Industri)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  119 => 
  array (
    'id' => 5024,
    'group' => 'education_major',
    'name' => 'Teknik (Kelautan)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  120 => 
  array (
    'id' => 5025,
    'group' => 'education_major',
    'name' => 'Teknik (Ilmu Materi)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  121 => 
  array (
    'id' => 5026,
    'group' => 'education_major',
    'name' => 'Teknik (Mekanikal)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  122 => 
  array (
    'id' => 5027,
    'group' => 'education_major',
    'name' => 'Teknik (Mechatronik/Elektromekanikal)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  123 => 
  array (
    'id' => 5028,
    'group' => 'education_major',
    'name' => 'Teknik (Fabrikasi/Peralatan Metal & Pencelupan/Pengelasan)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  124 => 
  array (
    'id' => 5029,
    'group' => 'education_major',
    'name' => 'Teknik (Pertambangan/Mineral)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  125 => 
  array (
    'id' => 5030,
    'group' => 'education_major',
    'name' => 'Teknik (Lainnya)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  126 => 
  array (
    'id' => 5031,
    'group' => 'education_major',
    'name' => 'Teknik (Petroleum/Minyak/Gas)',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  127 => 
  array (
    'id' => 5032,
    'group' => 'education_major',
    'name' => 'Keuangan/Akuntansi/Perbankan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  128 => 
  array (
    'id' => 5033,
    'group' => 'education_major',
    'name' => 'Manajemen Pelayanan Makanan & Minuman',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  129 => 
  array (
    'id' => 5034,
    'group' => 'education_major',
    'name' => 'Teknologi Pangan/Nutrisi/Gizi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  130 => 
  array (
    'id' => 5035,
    'group' => 'education_major',
    'name' => 'Ilmu Geografi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  131 => 
  array (
    'id' => 5036,
    'group' => 'education_major',
    'name' => 'Geologi/Geofisika',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  132 => 
  array (
    'id' => 5037,
    'group' => 'education_major',
    'name' => 'Sejarah',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  133 => 
  array (
    'id' => 5038,
    'group' => 'education_major',
    'name' => 'Perhotelan/Pariwisata/Manajemen Hotel',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  134 => 
  array (
    'id' => 5039,
    'group' => 'education_major',
    'name' => 'Manajemen HR',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  135 => 
  array (
    'id' => 5040,
    'group' => 'education_major',
    'name' => 'Kemanusiaan/Pengetahuan Budaya',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  136 => 
  array (
    'id' => 5041,
    'group' => 'education_major',
    'name' => 'Logistik/Transportasi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  137 => 
  array (
    'id' => 5042,
    'group' => 'education_major',
    'name' => 'Hukum',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  138 => 
  array (
    'id' => 5043,
    'group' => 'education_major',
    'name' => 'Manajemen Perpustakaan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  139 => 
  array (
    'id' => 5044,
    'group' => 'education_major',
    'name' => 'Linguistik/Bahasa',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  140 => 
  array (
    'id' => 5045,
    'group' => 'education_major',
    'name' => 'Komunikasi Massa',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  141 => 
  array (
    'id' => 5046,
    'group' => 'education_major',
    'name' => 'Matematika',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  142 => 
  array (
    'id' => 5047,
    'group' => 'education_major',
    'name' => 'Kedokteran',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  143 => 
  array (
    'id' => 5048,
    'group' => 'education_major',
    'name' => 'Apoteker',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  144 => 
  array (
    'id' => 5049,
    'group' => 'education_major',
    'name' => 'Ilmu Kelautan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  145 => 
  array (
    'id' => 5050,
    'group' => 'education_major',
    'name' => 'Pemasaran',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  146 => 
  array (
    'id' => 5051,
    'group' => 'education_major',
    'name' => 'Musik/Seni Panggung',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  147 => 
  array (
    'id' => 5052,
    'group' => 'education_major',
    'name' => 'Keperawatan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  148 => 
  array (
    'id' => 5053,
    'group' => 'education_major',
    'name' => 'Optometri',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  149 => 
  array (
    'id' => 5054,
    'group' => 'education_major',
    'name' => 'Personal Service',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  150 => 
  array (
    'id' => 5055,
    'group' => 'education_major',
    'name' => 'Farmasi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  151 => 
  array (
    'id' => 5056,
    'group' => 'education_major',
    'name' => 'Filosofi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  152 => 
  array (
    'id' => 5057,
    'group' => 'education_major',
    'name' => 'Terapi Fisik/Fisioterapi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  153 => 
  array (
    'id' => 5058,
    'group' => 'education_major',
    'name' => 'Fisika',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  154 => 
  array (
    'id' => 5059,
    'group' => 'education_major',
    'name' => 'Ilmu Politik',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  155 => 
  array (
    'id' => 5060,
    'group' => 'education_major',
    'name' => 'Pengembangan Properti/Manajemen Real Estate',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  156 => 
  array (
    'id' => 5061,
    'group' => 'education_major',
    'name' => 'Pelayanan & Manajemen Perlindungan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  157 => 
  array (
    'id' => 5062,
    'group' => 'education_major',
    'name' => 'Psikologi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  158 => 
  array (
    'id' => 5063,
    'group' => 'education_major',
    'name' => 'Survei Kuantitas',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  159 => 
  array (
    'id' => 5064,
    'group' => 'education_major',
    'name' => 'Ilmu Pengetahuan & Teknologi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  160 => 
  array (
    'id' => 5065,
    'group' => 'education_major',
    'name' => 'Sekretari',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  161 => 
  array (
    'id' => 5066,
    'group' => 'education_major',
    'name' => 'Ilmu Sosial/Sosiologi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  162 => 
  array (
    'id' => 5067,
    'group' => 'education_major',
    'name' => 'Ilmu & Manajemen Olahraga',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  163 => 
  array (
    'id' => 5068,
    'group' => 'education_major',
    'name' => 'Tekstil/Fashion Design',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  164 => 
  array (
    'id' => 5069,
    'group' => 'education_major',
    'name' => 'Studi Perkotaan/Perencanaan Kota',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  165 => 
  array (
    'id' => 5070,
    'group' => 'education_major',
    'name' => 'Kedokteran Hewan',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  166 => 
  array (
    'id' => 5071,
    'group' => 'education_major',
    'name' => 'Teknik Listrik',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  167 => 
  array (
    'id' => 5072,
    'group' => 'education_major',
    'name' => 'Teknik Otomotif',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  168 => 
  array (
    'id' => 5073,
    'group' => 'education_major',
    'name' => 'Ilmu Komunikasi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  169 => 
  array (
    'id' => 5074,
    'group' => 'education_major',
    'name' => 'Sistem Informasi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  170 => 
  array (
    'id' => 5075,
    'group' => 'education_major',
    'name' => 'Komputerasi Akuntansi',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
  171 => 
  array (
    'id' => 5076,
    'group' => 'education_major',
    'name' => 'Informatika',
    'alias' => NULL,
    'color' => NULL,
    'property' => NULL,
    'description' => NULL,
  ),
);

        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $item) {
                DB::table('iq_global_data')->updateOrInsert(['id' => $item['id']], $item);
            }
        }
    }
}
