<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $imgDir = public_path('images/attendance');
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }

        $photoSources = [
            'andi_clock_in.jpg' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&auto=format&fit=crop&q=80',
            'andi_clock_out.jpg' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
            'dewi_clock_in.jpg' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&auto=format&fit=crop&q=80',
            'dewi_clock_out.jpg' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
            'rizky_clock_in.jpg' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
            'rizky_clock_out.jpg' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=600&auto=format&fit=crop&q=80',
            'ahmad_clock_in.jpg' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
            'ahmad_clock_out.jpg' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80',
            'nurul_clock_in.jpg' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80',
            'nurul_clock_out.jpg' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=600&auto=format&fit=crop&q=80',
            'fajar_clock_in.jpg' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=600&auto=format&fit=crop&q=80',
            'fajar_clock_out.jpg' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=600&auto=format&fit=crop&q=80',
        ];

        foreach ($photoSources as $filename => $url) {
            $dest = $imgDir . '/' . $filename;
            $this->downloadAndCropPhoto($url, $dest);
        }

        $data = [
            [
                'id' => 'att00000-0000-0000-0001-000000000002',
                'employee_id' => 'e0000000-0000-0000-0001-000000000002',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:25:00',
                'clock_in_lat' => -6.208800,
                'clock_in_lng' => 106.845600,
                'clock_in_photo' => '/images/attendance/andi_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:35:00',
                'clock_out_lat' => -6.208800,
                'clock_out_lng' => 106.845600,
                'clock_out_photo' => '/images/attendance/andi_clock_out.jpg',
                'distance_in_from_office' => 12.50,
                'distance_out_from_office' => 12.50,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0001-000000000002',
                'updated_by' => 'e0000000-0000-0000-0001-000000000002',
                'created_at' => '2026-09-15 08:25:00',
                'updated_at' => '2026-09-15 17:35:00',
            ],
            [
                'id' => 'att00000-0000-0000-0001-000000000003',
                'employee_id' => 'e0000000-0000-0000-0001-000000000003',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:20:00',
                'clock_in_lat' => -6.208800,
                'clock_in_lng' => 106.845600,
                'clock_in_photo' => '/images/attendance/dewi_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:40:00',
                'clock_out_lat' => -6.208800,
                'clock_out_lng' => 106.845600,
                'clock_out_photo' => '/images/attendance/dewi_clock_out.jpg',
                'distance_in_from_office' => 10.00,
                'distance_out_from_office' => 10.00,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0001-000000000003',
                'updated_by' => 'e0000000-0000-0000-0001-000000000003',
                'created_at' => '2026-09-15 08:20:00',
                'updated_at' => '2026-09-15 17:40:00',
            ],
            [
                'id' => 'att00000-0000-0000-0001-000000000004',
                'employee_id' => 'e0000000-0000-0000-0001-000000000004',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:15:00',
                'clock_in_lat' => -6.208800,
                'clock_in_lng' => 106.845600,
                'clock_in_photo' => '/images/attendance/rizky_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:30:00',
                'clock_out_lat' => -6.208800,
                'clock_out_lng' => 106.845600,
                'clock_out_photo' => '/images/attendance/rizky_clock_out.jpg',
                'distance_in_from_office' => 15.00,
                'distance_out_from_office' => 15.00,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0001-000000000004',
                'updated_by' => 'e0000000-0000-0000-0001-000000000004',
                'created_at' => '2026-09-15 08:15:00',
                'updated_at' => '2026-09-15 17:30:00',
            ],
            [
                'id' => 'att00000-0000-0000-0002-000000000002',
                'employee_id' => 'e0000000-0000-0000-0002-000000000002',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:28:00',
                'clock_in_lat' => -6.210000,
                'clock_in_lng' => 106.850000,
                'clock_in_photo' => '/images/attendance/ahmad_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:30:00',
                'clock_out_lat' => -6.210000,
                'clock_out_lng' => 106.850000,
                'clock_out_photo' => '/images/attendance/ahmad_clock_out.jpg',
                'distance_in_from_office' => 14.00,
                'distance_out_from_office' => 14.00,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0002-000000000002',
                'updated_by' => 'e0000000-0000-0000-0002-000000000002',
                'created_at' => '2026-09-15 08:28:00',
                'updated_at' => '2026-09-15 17:30:00',
            ],
            [
                'id' => 'att00000-0000-0000-0002-000000000003',
                'employee_id' => 'e0000000-0000-0000-0002-000000000003',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:18:00',
                'clock_in_lat' => -6.210000,
                'clock_in_lng' => 106.850000,
                'clock_in_photo' => '/images/attendance/nurul_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:32:00',
                'clock_out_lat' => -6.210000,
                'clock_out_lng' => 106.850000,
                'clock_out_photo' => '/images/attendance/nurul_clock_out.jpg',
                'distance_in_from_office' => 11.50,
                'distance_out_from_office' => 11.50,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0002-000000000003',
                'updated_by' => 'e0000000-0000-0000-0002-000000000003',
                'created_at' => '2026-09-15 08:18:00',
                'updated_at' => '2026-09-15 17:32:00',
            ],
            [
                'id' => 'att00000-0000-0000-0002-000000000004',
                'employee_id' => 'e0000000-0000-0000-0002-000000000004',
                'date' => '2026-09-15',
                'clock_in_time' => '2026-09-15 08:10:00',
                'clock_in_lat' => -6.210000,
                'clock_in_lng' => 106.850000,
                'clock_in_photo' => '/images/attendance/fajar_clock_in.jpg',
                'clock_out_time' => '2026-09-15 17:35:00',
                'clock_out_lat' => -6.210000,
                'clock_out_lng' => 106.850000,
                'clock_out_photo' => '/images/attendance/fajar_clock_out.jpg',
                'distance_in_from_office' => 13.00,
                'distance_out_from_office' => 13.00,
                'status' => 'on_time',
                'work_from' => 'office',
                'created_by' => 'e0000000-0000-0000-0002-000000000004',
                'updated_by' => 'e0000000-0000-0000-0002-000000000004',
                'created_at' => '2026-09-15 08:10:00',
                'updated_at' => '2026-09-15 17:35:00',
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($data, 'id');
        DB::table('iq_attendance')->whereNotIn('id', $allowedIds)->delete();

        foreach ($data as $item) {
            DB::table('iq_attendance')->updateOrInsert(['id' => $item['id']], $item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function downloadAndCropPhoto(string $url, string $destPath)
    {
        $targetW = 480;
        $targetH = 640;

        $rawImgData = null;

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            $rawImgData = curl_exec($ch);
            curl_close($ch);
        }

        if (!$rawImgData) {
            $rawImgData = @file_get_contents($url);
        }

        $srcImg = null;
        if ($rawImgData) {
            $srcImg = @imagecreatefromstring($rawImgData);
        }

        $canvas = imagecreatetruecolor($targetW, $targetH);

        if ($srcImg) {
            $srcW = imagesx($srcImg);
            $srcH = imagesy($srcImg);

            $targetRatio = $targetW / $targetH;
            $srcRatio = $srcW / $srcH;

            if ($srcRatio > $targetRatio) {
                $cropW = (int) ($srcH * $targetRatio);
                $cropH = $srcH;
                $cropX = (int) (($srcW - $cropW) / 2);
                $cropY = 0;
            } else {
                $cropW = $srcW;
                $cropH = (int) ($srcW / $targetRatio);
                $cropX = 0;
                $cropY = (int) (($srcH - $cropH) / 4);
            }

            imagecopyresampled($canvas, $srcImg, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);
            imagedestroy($srcImg);
        } else {
            $bgTop = [30, 41, 59];
            $bgBottom = [15, 23, 42];
            for ($y = 0; $y < $targetH; $y++) {
                $r = (int) ($bgTop[0] + ($bgBottom[0] - $bgTop[0]) * ($y / $targetH));
                $g = (int) ($bgTop[1] + ($bgBottom[1] - $bgTop[1]) * ($y / $targetH));
                $b = (int) ($bgTop[2] + ($bgBottom[2] - $bgTop[2]) * ($y / $targetH));
                $c = imagecolorallocate($canvas, $r, $g, $b);
                imageline($canvas, 0, $y, $targetW, $y, $c);
            }
        }

        imagejpeg($canvas, $destPath, 92);
        imagedestroy($canvas);
    }
}
