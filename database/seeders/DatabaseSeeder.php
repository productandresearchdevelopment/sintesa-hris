<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AuthApiSeeder::class,
            FileManagerSeeder::class,
            AuthAppsSeeder::class,
            AuthModuleTypeSeeder::class,
            AuthModuleSeeder::class,
            AuthRoleSeeder::class,
            AuthRoleAppsSeeder::class,
            AuthRoleModuleSeeder::class,
            CompanySeeder::class,
            DivisionSeeder::class,
            OfficeSeeder::class,
            JobsSeeder::class,
            CitySeeder::class,
            GlobalDataSeeder::class,
            PlacementSeeder::class,
            OrgSeeder::class,
            EmploySeeder::class,
            Appraisals\AppraisalMasterSeeder::class,
            AuthUserSeeder::class,
            BulletinSeeder::class,
        ]);
    }
}
