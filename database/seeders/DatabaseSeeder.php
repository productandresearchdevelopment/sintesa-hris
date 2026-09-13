<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            FileManagerSeeder::class,
            AuthAppsSeeder::class,
            AuthModuleTypeSeeder::class,
            AuthModuleSeeder::class,
            AuthRoleSeeder::class,
            AuthRoleAppsSeeder::class,
            AuthRoleModuleSeeder::class,
        ]);
    }
}
