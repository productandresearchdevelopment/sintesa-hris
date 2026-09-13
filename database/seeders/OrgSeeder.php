<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgSeeder extends Seeder
{
    public function run()
    {
        $orgs = [
            [
                'id' => 1,
                'company_id' => 1,
                'parent_id' => null,
                'name' => 'Head Office - PT Sintesa Talenta Asia',
                'alias' => 'HO',
                'path' => '/1',
                'description' => 'Kantor Pusat PT Sintesa Talenta Asia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 1,
                'name' => 'Departemen HRGA',
                'alias' => 'HRGA',
                'path' => '/1/2',
                'description' => 'Human Resources & General Affair',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'parent_id' => 1,
                'name' => 'Departemen Information Technology',
                'alias' => 'IT',
                'path' => '/1/3',
                'description' => 'Software & Infrastructure Development',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'parent_id' => 1,
                'name' => 'Departemen Finance & Accounting',
                'alias' => 'FIN',
                'path' => '/1/4',
                'description' => 'Finance and Accounting',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'company_id' => 1,
                'parent_id' => 1,
                'name' => 'Departemen Operations',
                'alias' => 'OPS',
                'path' => '/1/5',
                'description' => 'Business Operations & Field Services',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($orgs as $org) {
            DB::table('iq_org')->updateOrInsert(
                ['id' => $org['id']],
                $org
            );
        }
    }
}
