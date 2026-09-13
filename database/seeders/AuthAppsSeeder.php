<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthAppsSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => '1',
    'name' => 'SINTESA APP',
    'token' => 'abab',
    'ip' => '["172.20.0.1"]',
    'icon' => 'https://sso.sintesa-hris.com/images/apps/sintesa.png',
    'url' => 'https://app.sintesa-hris.com',
  ),
  1 => 
  array (
    'id' => '2',
    'name' => 'SIMS',
    'token' => 'dede',
    'ip' => NULL,
    'icon' => 'https://sso.sintesa-hris.com/images/apps/sintesa.png',
    'url' => 'https://sims.sintesa-hris.com',
  ),
  2 => 
  array (
    'id' => '3',
    'name' => 'PQUBE',
    'token' => 'jkjjkjk',
    'ip' => NULL,
    'icon' => 'https://sso.sintesa-hris.com/images/apps/pqube.png',
    'url' => 'https://app.sintesa-hris.com',
  ),
  3 => 
  array (
    'id' => '4',
    'name' => 'GA SYSTEM',
    'token' => 'kaka',
    'ip' => NULL,
    'icon' => 'https://sso.sintesa-hris.com/images/apps/sintesa.png',
    'url' => 'https://app.sintesa-hris.com',
  ),
  4 => 
  array (
    'id' => '5',
    'name' => 'CRM',
    'token' => 'koko',
    'ip' => NULL,
    'icon' => 'https://sso.sintesa-hris.com/images/apps/sintesa.png',
    'url' => 'https://app.sintesa-hris.com',
  ),
  5 => 
  array (
    'id' => '6',
    'name' => 'HRIS',
    'token' => 'cici',
    'ip' => NULL,
    'icon' => 'https://sso.sintesa-hris.com/images/apps/hr.png',
    'url' => 'https://app.sintesa-hris.com',
  ),
);

        foreach ($data as $item) {
            DB::table('auth_apps')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
