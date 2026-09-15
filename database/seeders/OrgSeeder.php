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
            // Level 0: Root Directorate
            [
                'id' => 1,
                'company_id' => 1,
                'parent_id' => null,
                'authorized1' => null,
                'authorized2' => null,
                'name' => 'Director',
                'alias' => 'DR',
                'path' => '/1',
                'description' => 'Directorate',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Level 1: Heads (parent_id: 1, authorized1: 1, authorized2: 1)
            [
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 1,
                'authorized1' => 1,
                'authorized2' => 1,
                'name' => 'Head Finance & Accounting',
                'alias' => 'FIN',
                'path' => '/1/2',
                'description' => 'Head Finance & Accounting',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'parent_id' => 1,
                'authorized1' => 1,
                'authorized2' => 1,
                'name' => 'Head HRGA',
                'alias' => 'HRGA',
                'path' => '/1/3',
                'description' => 'Head HRGA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'parent_id' => 1,
                'authorized1' => 1,
                'authorized2' => 1,
                'name' => 'Head Information Technology',
                'alias' => 'IT',
                'path' => '/1/4',
                'description' => 'Head Information Technology',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'company_id' => 1,
                'parent_id' => 1,
                'authorized1' => 1,
                'authorized2' => 1,
                'name' => 'Head Operations',
                'alias' => 'HOS',
                'path' => '/1/5',
                'description' => 'Head Operations',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Level 2: Managers (parent_id: Head, authorized1: Head, authorized2: Head)
            [
                'id' => 6,
                'company_id' => 1,
                'parent_id' => 2,
                'authorized1' => 2,
                'authorized2' => 2,
                'name' => 'Manager Finance & Accounting',
                'alias' => 'MFIN',
                'path' => '/1/2/6',
                'description' => 'Manager Finance & Accounting',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'company_id' => 1,
                'parent_id' => 3,
                'authorized1' => 3,
                'authorized2' => 3,
                'name' => 'Manager HRGA',
                'alias' => 'MHRGA',
                'path' => '/1/3/7',
                'description' => 'Manager HRGA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'company_id' => 1,
                'parent_id' => 4,
                'authorized1' => 4,
                'authorized2' => 4,
                'name' => 'Manager Information Technology',
                'alias' => 'MIT',
                'path' => '/1/4/8',
                'description' => 'Manager Information Technology',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'company_id' => 1,
                'parent_id' => 5,
                'authorized1' => 5,
                'authorized2' => 5,
                'name' => 'Manager Operation',
                'alias' => 'MO',
                'path' => '/1/5/9',
                'description' => 'Manager Operation',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Level 3: Staff (parent_id: Manager, authorized1: Manager, authorized2: Manager)
            [
                'id' => 10,
                'company_id' => 1,
                'parent_id' => 6,
                'authorized1' => 6,
                'authorized2' => 6,
                'name' => 'Staff Finance & Accounting',
                'alias' => 'SFIN',
                'path' => '/1/2/6/10',
                'description' => 'Staff Finance & Accounting',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'company_id' => 1,
                'parent_id' => 7,
                'authorized1' => 7,
                'authorized2' => 7,
                'name' => 'Staff HRGA',
                'alias' => 'SHRGA',
                'path' => '/1/3/7/11',
                'description' => 'Staff HRGA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'company_id' => 1,
                'parent_id' => 8,
                'authorized1' => 8,
                'authorized2' => 8,
                'name' => 'Staff Information Technology',
                'alias' => 'SIT',
                'path' => '/1/4/8/12',
                'description' => 'Staff Information Technology',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'company_id' => 1,
                'parent_id' => 9,
                'authorized1' => 9,
                'authorized2' => 9,
                'name' => 'Staff Operation',
                'alias' => 'SO',
                'path' => '/1/5/9/13',
                'description' => 'Staff Operation',
                'created_at' => $now,
                'updated_at' => $now,
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
