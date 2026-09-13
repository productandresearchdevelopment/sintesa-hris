<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    public function run()
    {
        $offices = [
            [
                'id' => 1,
                'company_id' => 1,
                'name' => 'Head Office Jakarta',
                'address' => 'Jl. Jend. Sudirman No. 52, Jakarta Selatan, DKI Jakarta 12190',
                'latitude' => -6.229746,
                'longitude' => 106.807466,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'name' => 'Branch Office Surabaya',
                'address' => 'Jl. Pemuda No. 33, Surabaya, Jawa Timur 60271',
                'latitude' => -7.265432,
                'longitude' => 112.748651,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'name' => 'Branch Office Bandung',
                'address' => 'Jl. Asia Afrika No. 140, Bandung, Jawa Barat 40112',
                'latitude' => -6.921800,
                'longitude' => 107.611100,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'name' => 'Branch Office Medan',
                'address' => 'Jl. Imam Bonjol No. 18, Medan, Sumatera Utara 20112',
                'latitude' => 3.585242,
                'longitude' => 98.675583,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'company_id' => 1,
                'name' => 'Branch Office Semarang',
                'address' => 'Jl. Pahlawan No. 8, Semarang, Jawa Tengah 50241',
                'latitude' => -6.992683,
                'longitude' => 110.420800,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'company_id' => 1,
                'name' => 'Branch Office Makassar',
                'address' => 'Jl. AP Pettarani No. 45, Makassar, Sulawesi Selatan 90222',
                'latitude' => -5.156100,
                'longitude' => 119.432900,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'company_id' => 1,
                'name' => 'Branch Office Palembang',
                'address' => 'Jl. Jend. Sudirman No. 288, Palembang, Sumatera Selatan 30129',
                'latitude' => -2.976074,
                'longitude' => 104.762825,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'company_id' => 1,
                'name' => 'Branch Office Denpasar',
                'address' => 'Jl. Teuku Umar No. 100, Denpasar, Bali 80114',
                'latitude' => -8.670458,
                'longitude' => 115.212629,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'company_id' => 1,
                'name' => 'Branch Office Yogyakarta',
                'address' => 'Jl. Malioboro No. 56, Yogyakarta, DI Yogyakarta 55213',
                'latitude' => -7.792750,
                'longitude' => 110.365840,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'company_id' => 1,
                'name' => 'Branch Office Balikpapan',
                'address' => 'Jl. Jend. Sudirman No. 12, Balikpapan, Kalimantan Timur 76114',
                'latitude' => -1.265386,
                'longitude' => 116.831200,
                'max_distance_allowed' => 1000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Ensure only these 10 office IDs exist
        $allowedIds = array_column($offices, 'id');
        DB::table('iq_office')->whereNotIn('id', $allowedIds)->delete();

        foreach ($offices as $item) {
            DB::table('iq_office')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
