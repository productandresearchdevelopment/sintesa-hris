<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthRoleAppsSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'role_id' => 1,
    'app_id' => '1',
  ),
  1 => 
  array (
    'role_id' => 1,
    'app_id' => '2',
  ),
  2 => 
  array (
    'role_id' => 1,
    'app_id' => '3',
  ),
  3 => 
  array (
    'role_id' => 1,
    'app_id' => '4',
  ),
  4 => 
  array (
    'role_id' => 1,
    'app_id' => '5',
  ),
  5 => 
  array (
    'role_id' => 1,
    'app_id' => '6',
  ),
);

        DB::table('auth_role_apps')->delete();
        foreach (array_chunk($data, 200) as $chunk) {
            DB::table('auth_role_apps')->insert($chunk);
        }
    }
}
