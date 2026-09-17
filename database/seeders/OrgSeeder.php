<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $orgs = [
            [
                'id' => 1,
                'company_id' => 1,
                'parent_id' => null,
                'authorized1' => null,
                'authorized2' => null,
                'name' => 'Human Resource',
                'alias' => 'HR',
                'path' => '/1',
                'description' => 'Human Resource Department - PT Dieboldnixdorf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 1,
                'authorized1' => 1,
                'authorized2' => 1,
                'name' => 'Staff',
                'alias' => 'STF',
                'path' => '/1/2',
                'description' => 'Staff Department - PT Dieboldnixdorf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'company_id' => 2,
                'parent_id' => null,
                'authorized1' => null,
                'authorized2' => null,
                'name' => 'Human Resource',
                'alias' => 'HR',
                'path' => '/3',
                'description' => 'Human Resource Department - PT Hitachi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'company_id' => 2,
                'parent_id' => 3,
                'authorized1' => 3,
                'authorized2' => 3,
                'name' => 'Staff',
                'alias' => 'STF',
                'path' => '/3/4',
                'description' => 'Staff Department - PT Hitachi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $allowedIds = array_column($orgs, 'id');
        DB::table('iq_org')->whereNotIn('id', $allowedIds)->delete();

        foreach ($orgs as $org) {
            DB::table('iq_org')->updateOrInsert(
                ['id' => $org['id']],
                $org
            );
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
