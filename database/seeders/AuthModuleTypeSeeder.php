<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthModuleTypeSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 100,
    'group' => 'Menu',
    'name' => 'Menu Directory',
    'icon' => 'menu-square',
    'show_menu' => 1,
    'xurl' => 0,
    'xroute' => 0,
    'xauth' => 0,
    'xicon' => 1,
    'xdevice' => 1,
    'description' => NULL,
  ),
  1 => 
  array (
    'id' => 110,
    'group' => 'Menu',
    'name' => 'Route Menu',
    'icon' => 'menu',
    'show_menu' => 1,
    'xurl' => 0,
    'xroute' => 1,
    'xauth' => 0,
    'xicon' => 1,
    'xdevice' => 1,
    'description' => NULL,
  ),
  2 => 
  array (
    'id' => 210,
    'group' => NULL,
    'name' => 'View',
    'icon' => 'file',
    'show_menu' => 0,
    'xurl' => 0,
    'xroute' => 1,
    'xauth' => 0,
    'xicon' => 0,
    'xdevice' => 0,
    'description' => NULL,
  ),
  3 => 
  array (
    'id' => 310,
    'group' => NULL,
    'name' => 'Service',
    'icon' => 'gear',
    'show_menu' => 0,
    'xurl' => 0,
    'xroute' => 1,
    'xauth' => 0,
    'xicon' => 0,
    'xdevice' => 0,
    'description' => NULL,
  ),
  4 => 
  array (
    'id' => 410,
    'group' => NULL,
    'name' => 'Auth',
    'icon' => 'users',
    'show_menu' => 0,
    'xurl' => 0,
    'xroute' => 0,
    'xauth' => 1,
    'xicon' => 0,
    'xdevice' => 0,
    'description' => NULL,
  ),
  5 => 
  array (
    'id' => 510,
    'group' => NULL,
    'name' => 'Hidden Directory',
    'icon' => 'folder',
    'show_menu' => 0,
    'xurl' => 0,
    'xroute' => 0,
    'xauth' => 0,
    'xicon' => 0,
    'xdevice' => 0,
    'description' => NULL,
  ),
);

        foreach ($data as $item) {
            DB::table('auth_module_type')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
