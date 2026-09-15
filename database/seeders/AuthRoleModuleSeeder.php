<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthRoleModuleSeeder extends Seeder
{
    public function run()
    {
        $validRoleIds = DB::table('auth_role')->pluck('id')->toArray();
        $validModuleIds = DB::table('auth_module')->pluck('id')->toArray();
        $allModuleIds = $validModuleIds;

        $getCategoryModuleIds = function (array $categories) {
            $query = DB::table('auth_module');
            $query->where(function ($q) use ($categories) {
                foreach ($categories as $cat) {
                    switch ($cat) {
                        case 'filemanager':
                            $q->orWhere('id', 2019)->orWhere('path', 'like', '/2019%');
                            break;
                        case 'helpdesk':
                            $q->orWhere('id', 2014)->orWhere('path', 'like', '/2014%');
                            break;
                        case 'roles':
                            $q->orWhere('id', 106)->orWhere('path', 'like', '/10/106%');
                            break;
                        case 'modules':
                            $q->orWhere('id', 104)->orWhere('path', 'like', '/10/104%');
                            break;
                        case 'user_manager':
                            $q->orWhere('id', 102)->orWhere('path', 'like', '/10/102%');
                            break;
                        case 'company':
                            $q->orWhere('id', 2041)->orWhere('path', 'like', '%2041%');
                            break;
                        case 'office':
                            $q->orWhere('id', 2168)->orWhere('path', 'like', '%2168%');
                            break;
                        case 'division':
                            $q->orWhere('id', 2042)->orWhere('path', 'like', '%2042%');
                            break;
                        case 'organization':
                            $q->orWhere('id', 2038)->orWhere('path', 'like', '%2038%');
                            break;
                    }
                }
            });
            return $query->pluck('id')->toArray();
        };

        $roleModules = [];

        // DEVELOPER & SUPERADMIN
        $devExcluded = $getCategoryModuleIds(['filemanager', 'helpdesk']);
        $devAllowed = array_diff($allModuleIds, $devExcluded);
        foreach ([1, 10] as $roleId) {
            if (in_array($roleId, $validRoleIds)) {
                foreach ($devAllowed as $moduleId) {
                    $roleModules[] = ['role_id' => $roleId, 'module_id' => $moduleId];
                }
            }
        }

        // ADMINISTRATOR
        if (in_array(11, $validRoleIds)) {
            $adminExcluded = $getCategoryModuleIds(['filemanager', 'helpdesk', 'roles', 'modules']);
            $adminAllowed = array_diff($allModuleIds, $adminExcluded);
            foreach ($adminAllowed as $moduleId) {
                $roleModules[] = ['role_id' => 11, 'module_id' => $moduleId];
            }
        }

        // HRGA
        if (in_array(15, $validRoleIds)) {
            $hrgaExcluded = $getCategoryModuleIds([
                'filemanager',
                'helpdesk',
                'roles',
                'modules',
                'user_manager',
                'company',
                'office',
                'division',
                'organization'
            ]);
            $hrgaExcluded[] = 10;
            $hrgaExcluded = array_unique($hrgaExcluded);

            $lookupModuleIds = [
                2104, // company.data
                2105, // division.data
                2113, // organization.data
                2176, // office.data
                2173, // company.get
                2177, // office.get
                2183, // division.get
                2242, // organization.path
            ];
            $hrgaExcluded = array_diff($hrgaExcluded, $lookupModuleIds);

            $hrgaAllowed = array_diff($allModuleIds, $hrgaExcluded);
            foreach ($hrgaAllowed as $moduleId) {
                $roleModules[] = ['role_id' => 15, 'module_id' => $moduleId];
            }
        }

        // STAFF
        if (in_array(17, $validRoleIds)) {
            $staffModuleIds = [
                2015,
                2017,
                2044,
                2045,
                2046,
                2050,
                2051,
                2052,
                2057,
                2058,
                2068,
                2071,
                2072,
                2073,
                2074,
                2075,
                2076,
                2079,
                2080,
                2081,
                2082,
                2083,
                2084,
                2085,
                2086,
                2087,
                2088,
                2089,
                2092,
                2093,
                2096,
                2100,
                2104,
                2105,
                2106,
                2107,
                2108,
                2109,
                2111,
                2112,
                2113,
                2115,
                2118,
                2123,
                2124,
                2125,
                2126,
                2127,
                2129,
                2131,
                2132,
                2133,
                2134,
                2135,
                2136,
                2137,
                2138,
                2139,
                2140,
                2141,
                2152,
                2154,
                2155,
                2157,
                2159,
                2160,
                2161,
                2173,
                2176,
                2177,
                2183,
                2196,
                2197,
                2203,
                2205,
                2206,
                2207,
                2208,
                2216,
                2220,
                2227,
                2228,
                2229,
                2230,
                2241,
                2242,
                2243,
                2252,
                2253
            ];
            foreach ($staffModuleIds as $moduleId) {
                if (in_array($moduleId, $validModuleIds)) {
                    $roleModules[] = ['role_id' => 17, 'module_id' => $moduleId];
                }
            }
        }

        DB::table('auth_role_module')->delete();

        foreach (array_chunk($roleModules, 500) as $chunk) {
            DB::table('auth_role_module')->insertOrIgnore($chunk);
        }
    }
}
