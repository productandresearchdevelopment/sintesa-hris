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

        $data = array (
  0 => 
  array (
    'role_id' => 1,
    'module_id' => 10,
  ),
  1 => 
  array (
    'role_id' => 10,
    'module_id' => 10,
  ),
  2 => 
  array (
    'role_id' => 15,
    'module_id' => 10,
  ),
  3 => 
  array (
    'role_id' => 1,
    'module_id' => 102,
  ),
  4 => 
  array (
    'role_id' => 10,
    'module_id' => 102,
  ),
  5 => 
  array (
    'role_id' => 15,
    'module_id' => 102,
  ),
  6 => 
  array (
    'role_id' => 1,
    'module_id' => 104,
  ),
  7 => 
  array (
    'role_id' => 10,
    'module_id' => 104,
  ),
  8 => 
  array (
    'role_id' => 1,
    'module_id' => 106,
  ),
  9 => 
  array (
    'role_id' => 10,
    'module_id' => 106,
  ),
  10 => 
  array (
    'role_id' => 10,
    'module_id' => 1022,
  ),
  11 => 
  array (
    'role_id' => 15,
    'module_id' => 1022,
  ),
  12 => 
  array (
    'role_id' => 10,
    'module_id' => 1023,
  ),
  13 => 
  array (
    'role_id' => 15,
    'module_id' => 1023,
  ),
  14 => 
  array (
    'role_id' => 10,
    'module_id' => 1026,
  ),
  15 => 
  array (
    'role_id' => 15,
    'module_id' => 1026,
  ),
  16 => 
  array (
    'role_id' => 15,
    'module_id' => 1027,
  ),
  17 => 
  array (
    'role_id' => 10,
    'module_id' => 1028,
  ),
  18 => 
  array (
    'role_id' => 15,
    'module_id' => 1028,
  ),
  19 => 
  array (
    'role_id' => 10,
    'module_id' => 1042,
  ),
  20 => 
  array (
    'role_id' => 10,
    'module_id' => 1043,
  ),
  21 => 
  array (
    'role_id' => 10,
    'module_id' => 1044,
  ),
  22 => 
  array (
    'role_id' => 10,
    'module_id' => 1045,
  ),
  23 => 
  array (
    'role_id' => 10,
    'module_id' => 1046,
  ),
  24 => 
  array (
    'role_id' => 10,
    'module_id' => 1047,
  ),
  25 => 
  array (
    'role_id' => 10,
    'module_id' => 1048,
  ),
  26 => 
  array (
    'role_id' => 10,
    'module_id' => 1049,
  ),
  27 => 
  array (
    'role_id' => 10,
    'module_id' => 1050,
  ),
  28 => 
  array (
    'role_id' => 10,
    'module_id' => 1061,
  ),
  29 => 
  array (
    'role_id' => 10,
    'module_id' => 1062,
  ),
  30 => 
  array (
    'role_id' => 10,
    'module_id' => 1063,
  ),
  31 => 
  array (
    'role_id' => 10,
    'module_id' => 1064,
  ),
  32 => 
  array (
    'role_id' => 10,
    'module_id' => 1065,
  ),
  33 => 
  array (
    'role_id' => 10,
    'module_id' => 1066,
  ),
  34 => 
  array (
    'role_id' => 10,
    'module_id' => 1067,
  ),
  35 => 
  array (
    'role_id' => 10,
    'module_id' => 1166,
  ),
  36 => 
  array (
    'role_id' => 15,
    'module_id' => 1166,
  ),
  37 => 
  array (
    'role_id' => 10,
    'module_id' => 1167,
  ),
  38 => 
  array (
    'role_id' => 15,
    'module_id' => 1167,
  ),
  39 => 
  array (
    'role_id' => 10,
    'module_id' => 1265,
  ),
  40 => 
  array (
    'role_id' => 10,
    'module_id' => 1272,
  ),
  41 => 
  array (
    'role_id' => 15,
    'module_id' => 1272,
  ),
  42 => 
  array (
    'role_id' => 10,
    'module_id' => 1277,
  ),
  43 => 
  array (
    'role_id' => 15,
    'module_id' => 1277,
  ),
  44 => 
  array (
    'role_id' => 10,
    'module_id' => 1708,
  ),
  45 => 
  array (
    'role_id' => 10,
    'module_id' => 1995,
  ),
  46 => 
  array (
    'role_id' => 15,
    'module_id' => 1995,
  ),
  47 => 
  array (
    'role_id' => 1,
    'module_id' => 2014,
  ),
  48 => 
  array (
    'role_id' => 1,
    'module_id' => 2015,
  ),
  49 => 
  array (
    'role_id' => 10,
    'module_id' => 2015,
  ),
  50 => 
  array (
    'role_id' => 14,
    'module_id' => 2015,
  ),
  51 => 
  array (
    'role_id' => 15,
    'module_id' => 2015,
  ),
  52 => 
  array (
    'role_id' => 17,
    'module_id' => 2015,
  ),
  53 => 
  array (
    'role_id' => 1,
    'module_id' => 2016,
  ),
  54 => 
  array (
    'role_id' => 1,
    'module_id' => 2017,
  ),
  55 => 
  array (
    'role_id' => 10,
    'module_id' => 2017,
  ),
  56 => 
  array (
    'role_id' => 14,
    'module_id' => 2017,
  ),
  57 => 
  array (
    'role_id' => 15,
    'module_id' => 2017,
  ),
  58 => 
  array (
    'role_id' => 17,
    'module_id' => 2017,
  ),
  59 => 
  array (
    'role_id' => 1,
    'module_id' => 2018,
  ),
  60 => 
  array (
    'role_id' => 10,
    'module_id' => 2018,
  ),
  61 => 
  array (
    'role_id' => 1,
    'module_id' => 2019,
  ),
  62 => 
  array (
    'role_id' => 1,
    'module_id' => 2021,
  ),
  63 => 
  array (
    'role_id' => 10,
    'module_id' => 2021,
  ),
  64 => 
  array (
    'role_id' => 1,
    'module_id' => 2037,
  ),
  65 => 
  array (
    'role_id' => 10,
    'module_id' => 2037,
  ),
  66 => 
  array (
    'role_id' => 15,
    'module_id' => 2037,
  ),
  67 => 
  array (
    'role_id' => 1,
    'module_id' => 2038,
  ),
  68 => 
  array (
    'role_id' => 10,
    'module_id' => 2038,
  ),
  69 => 
  array (
    'role_id' => 15,
    'module_id' => 2038,
  ),
  70 => 
  array (
    'role_id' => 1,
    'module_id' => 2041,
  ),
  71 => 
  array (
    'role_id' => 10,
    'module_id' => 2041,
  ),
  72 => 
  array (
    'role_id' => 15,
    'module_id' => 2041,
  ),
  73 => 
  array (
    'role_id' => 1,
    'module_id' => 2042,
  ),
  74 => 
  array (
    'role_id' => 10,
    'module_id' => 2042,
  ),
  75 => 
  array (
    'role_id' => 15,
    'module_id' => 2042,
  ),
  76 => 
  array (
    'role_id' => 1,
    'module_id' => 2044,
  ),
  77 => 
  array (
    'role_id' => 10,
    'module_id' => 2044,
  ),
  78 => 
  array (
    'role_id' => 14,
    'module_id' => 2044,
  ),
  79 => 
  array (
    'role_id' => 15,
    'module_id' => 2044,
  ),
  80 => 
  array (
    'role_id' => 17,
    'module_id' => 2044,
  ),
  81 => 
  array (
    'role_id' => 1,
    'module_id' => 2045,
  ),
  82 => 
  array (
    'role_id' => 10,
    'module_id' => 2045,
  ),
  83 => 
  array (
    'role_id' => 14,
    'module_id' => 2045,
  ),
  84 => 
  array (
    'role_id' => 15,
    'module_id' => 2045,
  ),
  85 => 
  array (
    'role_id' => 17,
    'module_id' => 2045,
  ),
  86 => 
  array (
    'role_id' => 1,
    'module_id' => 2046,
  ),
  87 => 
  array (
    'role_id' => 10,
    'module_id' => 2046,
  ),
  88 => 
  array (
    'role_id' => 14,
    'module_id' => 2046,
  ),
  89 => 
  array (
    'role_id' => 15,
    'module_id' => 2046,
  ),
  90 => 
  array (
    'role_id' => 17,
    'module_id' => 2046,
  ),
  91 => 
  array (
    'role_id' => 1,
    'module_id' => 2050,
  ),
  92 => 
  array (
    'role_id' => 10,
    'module_id' => 2050,
  ),
  93 => 
  array (
    'role_id' => 14,
    'module_id' => 2050,
  ),
  94 => 
  array (
    'role_id' => 15,
    'module_id' => 2050,
  ),
  95 => 
  array (
    'role_id' => 17,
    'module_id' => 2050,
  ),
  96 => 
  array (
    'role_id' => 1,
    'module_id' => 2051,
  ),
  97 => 
  array (
    'role_id' => 10,
    'module_id' => 2051,
  ),
  98 => 
  array (
    'role_id' => 14,
    'module_id' => 2051,
  ),
  99 => 
  array (
    'role_id' => 15,
    'module_id' => 2051,
  ),
  100 => 
  array (
    'role_id' => 17,
    'module_id' => 2051,
  ),
  101 => 
  array (
    'role_id' => 1,
    'module_id' => 2052,
  ),
  102 => 
  array (
    'role_id' => 10,
    'module_id' => 2052,
  ),
  103 => 
  array (
    'role_id' => 14,
    'module_id' => 2052,
  ),
  104 => 
  array (
    'role_id' => 15,
    'module_id' => 2052,
  ),
  105 => 
  array (
    'role_id' => 17,
    'module_id' => 2052,
  ),
  106 => 
  array (
    'role_id' => 1,
    'module_id' => 2053,
  ),
  107 => 
  array (
    'role_id' => 10,
    'module_id' => 2053,
  ),
  108 => 
  array (
    'role_id' => 14,
    'module_id' => 2053,
  ),
  109 => 
  array (
    'role_id' => 15,
    'module_id' => 2053,
  ),
  110 => 
  array (
    'role_id' => 1,
    'module_id' => 2055,
  ),
  111 => 
  array (
    'role_id' => 10,
    'module_id' => 2055,
  ),
  112 => 
  array (
    'role_id' => 14,
    'module_id' => 2055,
  ),
  113 => 
  array (
    'role_id' => 15,
    'module_id' => 2055,
  ),
  114 => 
  array (
    'role_id' => 1,
    'module_id' => 2057,
  ),
  115 => 
  array (
    'role_id' => 10,
    'module_id' => 2057,
  ),
  116 => 
  array (
    'role_id' => 14,
    'module_id' => 2057,
  ),
  117 => 
  array (
    'role_id' => 15,
    'module_id' => 2057,
  ),
  118 => 
  array (
    'role_id' => 17,
    'module_id' => 2057,
  ),
  119 => 
  array (
    'role_id' => 1,
    'module_id' => 2058,
  ),
  120 => 
  array (
    'role_id' => 10,
    'module_id' => 2058,
  ),
  121 => 
  array (
    'role_id' => 14,
    'module_id' => 2058,
  ),
  122 => 
  array (
    'role_id' => 15,
    'module_id' => 2058,
  ),
  123 => 
  array (
    'role_id' => 17,
    'module_id' => 2058,
  ),
  124 => 
  array (
    'role_id' => 1,
    'module_id' => 2059,
  ),
  125 => 
  array (
    'role_id' => 10,
    'module_id' => 2059,
  ),
  126 => 
  array (
    'role_id' => 1,
    'module_id' => 2060,
  ),
  127 => 
  array (
    'role_id' => 10,
    'module_id' => 2060,
  ),
  128 => 
  array (
    'role_id' => 1,
    'module_id' => 2061,
  ),
  129 => 
  array (
    'role_id' => 10,
    'module_id' => 2061,
  ),
  130 => 
  array (
    'role_id' => 1,
    'module_id' => 2062,
  ),
  131 => 
  array (
    'role_id' => 10,
    'module_id' => 2062,
  ),
  132 => 
  array (
    'role_id' => 1,
    'module_id' => 2063,
  ),
  133 => 
  array (
    'role_id' => 10,
    'module_id' => 2063,
  ),
  134 => 
  array (
    'role_id' => 1,
    'module_id' => 2064,
  ),
  135 => 
  array (
    'role_id' => 10,
    'module_id' => 2064,
  ),
  136 => 
  array (
    'role_id' => 1,
    'module_id' => 2066,
  ),
  137 => 
  array (
    'role_id' => 10,
    'module_id' => 2066,
  ),
  138 => 
  array (
    'role_id' => 1,
    'module_id' => 2067,
  ),
  139 => 
  array (
    'role_id' => 10,
    'module_id' => 2067,
  ),
  140 => 
  array (
    'role_id' => 1,
    'module_id' => 2068,
  ),
  141 => 
  array (
    'role_id' => 10,
    'module_id' => 2068,
  ),
  142 => 
  array (
    'role_id' => 14,
    'module_id' => 2068,
  ),
  143 => 
  array (
    'role_id' => 15,
    'module_id' => 2068,
  ),
  144 => 
  array (
    'role_id' => 17,
    'module_id' => 2068,
  ),
  145 => 
  array (
    'role_id' => 1,
    'module_id' => 2070,
  ),
  146 => 
  array (
    'role_id' => 10,
    'module_id' => 2070,
  ),
  147 => 
  array (
    'role_id' => 15,
    'module_id' => 2070,
  ),
  148 => 
  array (
    'role_id' => 1,
    'module_id' => 2071,
  ),
  149 => 
  array (
    'role_id' => 10,
    'module_id' => 2071,
  ),
  150 => 
  array (
    'role_id' => 14,
    'module_id' => 2071,
  ),
  151 => 
  array (
    'role_id' => 15,
    'module_id' => 2071,
  ),
  152 => 
  array (
    'role_id' => 17,
    'module_id' => 2071,
  ),
  153 => 
  array (
    'role_id' => 1,
    'module_id' => 2072,
  ),
  154 => 
  array (
    'role_id' => 10,
    'module_id' => 2072,
  ),
  155 => 
  array (
    'role_id' => 14,
    'module_id' => 2072,
  ),
  156 => 
  array (
    'role_id' => 15,
    'module_id' => 2072,
  ),
  157 => 
  array (
    'role_id' => 17,
    'module_id' => 2072,
  ),
  158 => 
  array (
    'role_id' => 1,
    'module_id' => 2073,
  ),
  159 => 
  array (
    'role_id' => 10,
    'module_id' => 2073,
  ),
  160 => 
  array (
    'role_id' => 14,
    'module_id' => 2073,
  ),
  161 => 
  array (
    'role_id' => 15,
    'module_id' => 2073,
  ),
  162 => 
  array (
    'role_id' => 17,
    'module_id' => 2073,
  ),
  163 => 
  array (
    'role_id' => 1,
    'module_id' => 2074,
  ),
  164 => 
  array (
    'role_id' => 10,
    'module_id' => 2074,
  ),
  165 => 
  array (
    'role_id' => 14,
    'module_id' => 2074,
  ),
  166 => 
  array (
    'role_id' => 15,
    'module_id' => 2074,
  ),
  167 => 
  array (
    'role_id' => 17,
    'module_id' => 2074,
  ),
  168 => 
  array (
    'role_id' => 1,
    'module_id' => 2075,
  ),
  169 => 
  array (
    'role_id' => 10,
    'module_id' => 2075,
  ),
  170 => 
  array (
    'role_id' => 14,
    'module_id' => 2075,
  ),
  171 => 
  array (
    'role_id' => 15,
    'module_id' => 2075,
  ),
  172 => 
  array (
    'role_id' => 17,
    'module_id' => 2075,
  ),
  173 => 
  array (
    'role_id' => 1,
    'module_id' => 2076,
  ),
  174 => 
  array (
    'role_id' => 10,
    'module_id' => 2076,
  ),
  175 => 
  array (
    'role_id' => 14,
    'module_id' => 2076,
  ),
  176 => 
  array (
    'role_id' => 15,
    'module_id' => 2076,
  ),
  177 => 
  array (
    'role_id' => 17,
    'module_id' => 2076,
  ),
  178 => 
  array (
    'role_id' => 15,
    'module_id' => 2077,
  ),
  179 => 
  array (
    'role_id' => 15,
    'module_id' => 2078,
  ),
  180 => 
  array (
    'role_id' => 1,
    'module_id' => 2079,
  ),
  181 => 
  array (
    'role_id' => 10,
    'module_id' => 2079,
  ),
  182 => 
  array (
    'role_id' => 14,
    'module_id' => 2079,
  ),
  183 => 
  array (
    'role_id' => 15,
    'module_id' => 2079,
  ),
  184 => 
  array (
    'role_id' => 17,
    'module_id' => 2079,
  ),
  185 => 
  array (
    'role_id' => 1,
    'module_id' => 2080,
  ),
  186 => 
  array (
    'role_id' => 10,
    'module_id' => 2080,
  ),
  187 => 
  array (
    'role_id' => 14,
    'module_id' => 2080,
  ),
  188 => 
  array (
    'role_id' => 15,
    'module_id' => 2080,
  ),
  189 => 
  array (
    'role_id' => 17,
    'module_id' => 2080,
  ),
  190 => 
  array (
    'role_id' => 1,
    'module_id' => 2081,
  ),
  191 => 
  array (
    'role_id' => 10,
    'module_id' => 2081,
  ),
  192 => 
  array (
    'role_id' => 14,
    'module_id' => 2081,
  ),
  193 => 
  array (
    'role_id' => 15,
    'module_id' => 2081,
  ),
  194 => 
  array (
    'role_id' => 17,
    'module_id' => 2081,
  ),
  195 => 
  array (
    'role_id' => 1,
    'module_id' => 2082,
  ),
  196 => 
  array (
    'role_id' => 10,
    'module_id' => 2082,
  ),
  197 => 
  array (
    'role_id' => 14,
    'module_id' => 2082,
  ),
  198 => 
  array (
    'role_id' => 15,
    'module_id' => 2082,
  ),
  199 => 
  array (
    'role_id' => 17,
    'module_id' => 2082,
  ),
  200 => 
  array (
    'role_id' => 1,
    'module_id' => 2083,
  ),
  201 => 
  array (
    'role_id' => 10,
    'module_id' => 2083,
  ),
  202 => 
  array (
    'role_id' => 14,
    'module_id' => 2083,
  ),
  203 => 
  array (
    'role_id' => 15,
    'module_id' => 2083,
  ),
  204 => 
  array (
    'role_id' => 17,
    'module_id' => 2083,
  ),
  205 => 
  array (
    'role_id' => 1,
    'module_id' => 2084,
  ),
  206 => 
  array (
    'role_id' => 10,
    'module_id' => 2084,
  ),
  207 => 
  array (
    'role_id' => 14,
    'module_id' => 2084,
  ),
  208 => 
  array (
    'role_id' => 15,
    'module_id' => 2084,
  ),
  209 => 
  array (
    'role_id' => 17,
    'module_id' => 2084,
  ),
  210 => 
  array (
    'role_id' => 1,
    'module_id' => 2085,
  ),
  211 => 
  array (
    'role_id' => 10,
    'module_id' => 2085,
  ),
  212 => 
  array (
    'role_id' => 14,
    'module_id' => 2085,
  ),
  213 => 
  array (
    'role_id' => 15,
    'module_id' => 2085,
  ),
  214 => 
  array (
    'role_id' => 17,
    'module_id' => 2085,
  ),
  215 => 
  array (
    'role_id' => 1,
    'module_id' => 2086,
  ),
  216 => 
  array (
    'role_id' => 10,
    'module_id' => 2086,
  ),
  217 => 
  array (
    'role_id' => 14,
    'module_id' => 2086,
  ),
  218 => 
  array (
    'role_id' => 15,
    'module_id' => 2086,
  ),
  219 => 
  array (
    'role_id' => 17,
    'module_id' => 2086,
  ),
  220 => 
  array (
    'role_id' => 1,
    'module_id' => 2087,
  ),
  221 => 
  array (
    'role_id' => 10,
    'module_id' => 2087,
  ),
  222 => 
  array (
    'role_id' => 14,
    'module_id' => 2087,
  ),
  223 => 
  array (
    'role_id' => 15,
    'module_id' => 2087,
  ),
  224 => 
  array (
    'role_id' => 17,
    'module_id' => 2087,
  ),
  225 => 
  array (
    'role_id' => 1,
    'module_id' => 2088,
  ),
  226 => 
  array (
    'role_id' => 10,
    'module_id' => 2088,
  ),
  227 => 
  array (
    'role_id' => 14,
    'module_id' => 2088,
  ),
  228 => 
  array (
    'role_id' => 15,
    'module_id' => 2088,
  ),
  229 => 
  array (
    'role_id' => 17,
    'module_id' => 2088,
  ),
  230 => 
  array (
    'role_id' => 1,
    'module_id' => 2089,
  ),
  231 => 
  array (
    'role_id' => 10,
    'module_id' => 2089,
  ),
  232 => 
  array (
    'role_id' => 14,
    'module_id' => 2089,
  ),
  233 => 
  array (
    'role_id' => 15,
    'module_id' => 2089,
  ),
  234 => 
  array (
    'role_id' => 17,
    'module_id' => 2089,
  ),
  235 => 
  array (
    'role_id' => 1,
    'module_id' => 2092,
  ),
  236 => 
  array (
    'role_id' => 10,
    'module_id' => 2092,
  ),
  237 => 
  array (
    'role_id' => 14,
    'module_id' => 2092,
  ),
  238 => 
  array (
    'role_id' => 15,
    'module_id' => 2092,
  ),
  239 => 
  array (
    'role_id' => 17,
    'module_id' => 2092,
  ),
  240 => 
  array (
    'role_id' => 1,
    'module_id' => 2093,
  ),
  241 => 
  array (
    'role_id' => 10,
    'module_id' => 2093,
  ),
  242 => 
  array (
    'role_id' => 14,
    'module_id' => 2093,
  ),
  243 => 
  array (
    'role_id' => 15,
    'module_id' => 2093,
  ),
  244 => 
  array (
    'role_id' => 17,
    'module_id' => 2093,
  ),
  245 => 
  array (
    'role_id' => 1,
    'module_id' => 2094,
  ),
  246 => 
  array (
    'role_id' => 10,
    'module_id' => 2094,
  ),
  247 => 
  array (
    'role_id' => 1,
    'module_id' => 2095,
  ),
  248 => 
  array (
    'role_id' => 10,
    'module_id' => 2095,
  ),
  249 => 
  array (
    'role_id' => 1,
    'module_id' => 2096,
  ),
  250 => 
  array (
    'role_id' => 10,
    'module_id' => 2096,
  ),
  251 => 
  array (
    'role_id' => 14,
    'module_id' => 2096,
  ),
  252 => 
  array (
    'role_id' => 15,
    'module_id' => 2096,
  ),
  253 => 
  array (
    'role_id' => 17,
    'module_id' => 2096,
  ),
  254 => 
  array (
    'role_id' => 1,
    'module_id' => 2097,
  ),
  255 => 
  array (
    'role_id' => 10,
    'module_id' => 2097,
  ),
  256 => 
  array (
    'role_id' => 1,
    'module_id' => 2098,
  ),
  257 => 
  array (
    'role_id' => 10,
    'module_id' => 2098,
  ),
  258 => 
  array (
    'role_id' => 1,
    'module_id' => 2099,
  ),
  259 => 
  array (
    'role_id' => 10,
    'module_id' => 2099,
  ),
  260 => 
  array (
    'role_id' => 1,
    'module_id' => 2100,
  ),
  261 => 
  array (
    'role_id' => 10,
    'module_id' => 2100,
  ),
  262 => 
  array (
    'role_id' => 14,
    'module_id' => 2100,
  ),
  263 => 
  array (
    'role_id' => 15,
    'module_id' => 2100,
  ),
  264 => 
  array (
    'role_id' => 17,
    'module_id' => 2100,
  ),
  265 => 
  array (
    'role_id' => 1,
    'module_id' => 2101,
  ),
  266 => 
  array (
    'role_id' => 10,
    'module_id' => 2101,
  ),
  267 => 
  array (
    'role_id' => 1,
    'module_id' => 2102,
  ),
  268 => 
  array (
    'role_id' => 10,
    'module_id' => 2102,
  ),
  269 => 
  array (
    'role_id' => 1,
    'module_id' => 2104,
  ),
  270 => 
  array (
    'role_id' => 10,
    'module_id' => 2104,
  ),
  271 => 
  array (
    'role_id' => 14,
    'module_id' => 2104,
  ),
  272 => 
  array (
    'role_id' => 15,
    'module_id' => 2104,
  ),
  273 => 
  array (
    'role_id' => 17,
    'module_id' => 2104,
  ),
  274 => 
  array (
    'role_id' => 1,
    'module_id' => 2105,
  ),
  275 => 
  array (
    'role_id' => 10,
    'module_id' => 2105,
  ),
  276 => 
  array (
    'role_id' => 14,
    'module_id' => 2105,
  ),
  277 => 
  array (
    'role_id' => 15,
    'module_id' => 2105,
  ),
  278 => 
  array (
    'role_id' => 17,
    'module_id' => 2105,
  ),
  279 => 
  array (
    'role_id' => 1,
    'module_id' => 2106,
  ),
  280 => 
  array (
    'role_id' => 10,
    'module_id' => 2106,
  ),
  281 => 
  array (
    'role_id' => 14,
    'module_id' => 2106,
  ),
  282 => 
  array (
    'role_id' => 15,
    'module_id' => 2106,
  ),
  283 => 
  array (
    'role_id' => 17,
    'module_id' => 2106,
  ),
  284 => 
  array (
    'role_id' => 1,
    'module_id' => 2107,
  ),
  285 => 
  array (
    'role_id' => 10,
    'module_id' => 2107,
  ),
  286 => 
  array (
    'role_id' => 14,
    'module_id' => 2107,
  ),
  287 => 
  array (
    'role_id' => 15,
    'module_id' => 2107,
  ),
  288 => 
  array (
    'role_id' => 17,
    'module_id' => 2107,
  ),
  289 => 
  array (
    'role_id' => 1,
    'module_id' => 2108,
  ),
  290 => 
  array (
    'role_id' => 10,
    'module_id' => 2108,
  ),
  291 => 
  array (
    'role_id' => 14,
    'module_id' => 2108,
  ),
  292 => 
  array (
    'role_id' => 15,
    'module_id' => 2108,
  ),
  293 => 
  array (
    'role_id' => 17,
    'module_id' => 2108,
  ),
  294 => 
  array (
    'role_id' => 1,
    'module_id' => 2109,
  ),
  295 => 
  array (
    'role_id' => 10,
    'module_id' => 2109,
  ),
  296 => 
  array (
    'role_id' => 14,
    'module_id' => 2109,
  ),
  297 => 
  array (
    'role_id' => 15,
    'module_id' => 2109,
  ),
  298 => 
  array (
    'role_id' => 17,
    'module_id' => 2109,
  ),
  299 => 
  array (
    'role_id' => 1,
    'module_id' => 2111,
  ),
  300 => 
  array (
    'role_id' => 10,
    'module_id' => 2111,
  ),
  301 => 
  array (
    'role_id' => 14,
    'module_id' => 2111,
  ),
  302 => 
  array (
    'role_id' => 15,
    'module_id' => 2111,
  ),
  303 => 
  array (
    'role_id' => 17,
    'module_id' => 2111,
  ),
  304 => 
  array (
    'role_id' => 1,
    'module_id' => 2112,
  ),
  305 => 
  array (
    'role_id' => 10,
    'module_id' => 2112,
  ),
  306 => 
  array (
    'role_id' => 14,
    'module_id' => 2112,
  ),
  307 => 
  array (
    'role_id' => 15,
    'module_id' => 2112,
  ),
  308 => 
  array (
    'role_id' => 17,
    'module_id' => 2112,
  ),
  309 => 
  array (
    'role_id' => 1,
    'module_id' => 2113,
  ),
  310 => 
  array (
    'role_id' => 10,
    'module_id' => 2113,
  ),
  311 => 
  array (
    'role_id' => 14,
    'module_id' => 2113,
  ),
  312 => 
  array (
    'role_id' => 15,
    'module_id' => 2113,
  ),
  313 => 
  array (
    'role_id' => 17,
    'module_id' => 2113,
  ),
  314 => 
  array (
    'role_id' => 1,
    'module_id' => 2115,
  ),
  315 => 
  array (
    'role_id' => 10,
    'module_id' => 2115,
  ),
  316 => 
  array (
    'role_id' => 14,
    'module_id' => 2115,
  ),
  317 => 
  array (
    'role_id' => 15,
    'module_id' => 2115,
  ),
  318 => 
  array (
    'role_id' => 17,
    'module_id' => 2115,
  ),
  319 => 
  array (
    'role_id' => 1,
    'module_id' => 2118,
  ),
  320 => 
  array (
    'role_id' => 10,
    'module_id' => 2118,
  ),
  321 => 
  array (
    'role_id' => 14,
    'module_id' => 2118,
  ),
  322 => 
  array (
    'role_id' => 15,
    'module_id' => 2118,
  ),
  323 => 
  array (
    'role_id' => 17,
    'module_id' => 2118,
  ),
  324 => 
  array (
    'role_id' => 1,
    'module_id' => 2120,
  ),
  325 => 
  array (
    'role_id' => 10,
    'module_id' => 2120,
  ),
  326 => 
  array (
    'role_id' => 15,
    'module_id' => 2120,
  ),
  327 => 
  array (
    'role_id' => 1,
    'module_id' => 2121,
  ),
  328 => 
  array (
    'role_id' => 10,
    'module_id' => 2121,
  ),
  329 => 
  array (
    'role_id' => 15,
    'module_id' => 2121,
  ),
  330 => 
  array (
    'role_id' => 1,
    'module_id' => 2122,
  ),
  331 => 
  array (
    'role_id' => 10,
    'module_id' => 2122,
  ),
  332 => 
  array (
    'role_id' => 15,
    'module_id' => 2122,
  ),
  333 => 
  array (
    'role_id' => 1,
    'module_id' => 2123,
  ),
  334 => 
  array (
    'role_id' => 10,
    'module_id' => 2123,
  ),
  335 => 
  array (
    'role_id' => 14,
    'module_id' => 2123,
  ),
  336 => 
  array (
    'role_id' => 15,
    'module_id' => 2123,
  ),
  337 => 
  array (
    'role_id' => 17,
    'module_id' => 2123,
  ),
  338 => 
  array (
    'role_id' => 1,
    'module_id' => 2124,
  ),
  339 => 
  array (
    'role_id' => 10,
    'module_id' => 2124,
  ),
  340 => 
  array (
    'role_id' => 14,
    'module_id' => 2124,
  ),
  341 => 
  array (
    'role_id' => 15,
    'module_id' => 2124,
  ),
  342 => 
  array (
    'role_id' => 17,
    'module_id' => 2124,
  ),
  343 => 
  array (
    'role_id' => 1,
    'module_id' => 2125,
  ),
  344 => 
  array (
    'role_id' => 10,
    'module_id' => 2125,
  ),
  345 => 
  array (
    'role_id' => 14,
    'module_id' => 2125,
  ),
  346 => 
  array (
    'role_id' => 15,
    'module_id' => 2125,
  ),
  347 => 
  array (
    'role_id' => 17,
    'module_id' => 2125,
  ),
  348 => 
  array (
    'role_id' => 1,
    'module_id' => 2126,
  ),
  349 => 
  array (
    'role_id' => 10,
    'module_id' => 2126,
  ),
  350 => 
  array (
    'role_id' => 14,
    'module_id' => 2126,
  ),
  351 => 
  array (
    'role_id' => 15,
    'module_id' => 2126,
  ),
  352 => 
  array (
    'role_id' => 17,
    'module_id' => 2126,
  ),
  353 => 
  array (
    'role_id' => 1,
    'module_id' => 2127,
  ),
  354 => 
  array (
    'role_id' => 10,
    'module_id' => 2127,
  ),
  355 => 
  array (
    'role_id' => 14,
    'module_id' => 2127,
  ),
  356 => 
  array (
    'role_id' => 15,
    'module_id' => 2127,
  ),
  357 => 
  array (
    'role_id' => 17,
    'module_id' => 2127,
  ),
  358 => 
  array (
    'role_id' => 1,
    'module_id' => 2128,
  ),
  359 => 
  array (
    'role_id' => 10,
    'module_id' => 2128,
  ),
  360 => 
  array (
    'role_id' => 15,
    'module_id' => 2128,
  ),
  361 => 
  array (
    'role_id' => 1,
    'module_id' => 2129,
  ),
  362 => 
  array (
    'role_id' => 10,
    'module_id' => 2129,
  ),
  363 => 
  array (
    'role_id' => 14,
    'module_id' => 2129,
  ),
  364 => 
  array (
    'role_id' => 15,
    'module_id' => 2129,
  ),
  365 => 
  array (
    'role_id' => 17,
    'module_id' => 2129,
  ),
  366 => 
  array (
    'role_id' => 1,
    'module_id' => 2131,
  ),
  367 => 
  array (
    'role_id' => 10,
    'module_id' => 2131,
  ),
  368 => 
  array (
    'role_id' => 14,
    'module_id' => 2131,
  ),
  369 => 
  array (
    'role_id' => 15,
    'module_id' => 2131,
  ),
  370 => 
  array (
    'role_id' => 17,
    'module_id' => 2131,
  ),
  371 => 
  array (
    'role_id' => 1,
    'module_id' => 2132,
  ),
  372 => 
  array (
    'role_id' => 10,
    'module_id' => 2132,
  ),
  373 => 
  array (
    'role_id' => 14,
    'module_id' => 2132,
  ),
  374 => 
  array (
    'role_id' => 15,
    'module_id' => 2132,
  ),
  375 => 
  array (
    'role_id' => 17,
    'module_id' => 2132,
  ),
  376 => 
  array (
    'role_id' => 1,
    'module_id' => 2133,
  ),
  377 => 
  array (
    'role_id' => 10,
    'module_id' => 2133,
  ),
  378 => 
  array (
    'role_id' => 14,
    'module_id' => 2133,
  ),
  379 => 
  array (
    'role_id' => 15,
    'module_id' => 2133,
  ),
  380 => 
  array (
    'role_id' => 17,
    'module_id' => 2133,
  ),
  381 => 
  array (
    'role_id' => 1,
    'module_id' => 2134,
  ),
  382 => 
  array (
    'role_id' => 10,
    'module_id' => 2134,
  ),
  383 => 
  array (
    'role_id' => 14,
    'module_id' => 2134,
  ),
  384 => 
  array (
    'role_id' => 15,
    'module_id' => 2134,
  ),
  385 => 
  array (
    'role_id' => 17,
    'module_id' => 2134,
  ),
  386 => 
  array (
    'role_id' => 1,
    'module_id' => 2135,
  ),
  387 => 
  array (
    'role_id' => 10,
    'module_id' => 2135,
  ),
  388 => 
  array (
    'role_id' => 14,
    'module_id' => 2135,
  ),
  389 => 
  array (
    'role_id' => 15,
    'module_id' => 2135,
  ),
  390 => 
  array (
    'role_id' => 17,
    'module_id' => 2135,
  ),
  391 => 
  array (
    'role_id' => 1,
    'module_id' => 2136,
  ),
  392 => 
  array (
    'role_id' => 10,
    'module_id' => 2136,
  ),
  393 => 
  array (
    'role_id' => 14,
    'module_id' => 2136,
  ),
  394 => 
  array (
    'role_id' => 15,
    'module_id' => 2136,
  ),
  395 => 
  array (
    'role_id' => 17,
    'module_id' => 2136,
  ),
  396 => 
  array (
    'role_id' => 1,
    'module_id' => 2137,
  ),
  397 => 
  array (
    'role_id' => 10,
    'module_id' => 2137,
  ),
  398 => 
  array (
    'role_id' => 14,
    'module_id' => 2137,
  ),
  399 => 
  array (
    'role_id' => 15,
    'module_id' => 2137,
  ),
  400 => 
  array (
    'role_id' => 17,
    'module_id' => 2137,
  ),
  401 => 
  array (
    'role_id' => 1,
    'module_id' => 2138,
  ),
  402 => 
  array (
    'role_id' => 10,
    'module_id' => 2138,
  ),
  403 => 
  array (
    'role_id' => 14,
    'module_id' => 2138,
  ),
  404 => 
  array (
    'role_id' => 15,
    'module_id' => 2138,
  ),
  405 => 
  array (
    'role_id' => 17,
    'module_id' => 2138,
  ),
  406 => 
  array (
    'role_id' => 1,
    'module_id' => 2139,
  ),
  407 => 
  array (
    'role_id' => 10,
    'module_id' => 2139,
  ),
  408 => 
  array (
    'role_id' => 14,
    'module_id' => 2139,
  ),
  409 => 
  array (
    'role_id' => 15,
    'module_id' => 2139,
  ),
  410 => 
  array (
    'role_id' => 17,
    'module_id' => 2139,
  ),
  411 => 
  array (
    'role_id' => 1,
    'module_id' => 2140,
  ),
  412 => 
  array (
    'role_id' => 10,
    'module_id' => 2140,
  ),
  413 => 
  array (
    'role_id' => 14,
    'module_id' => 2140,
  ),
  414 => 
  array (
    'role_id' => 15,
    'module_id' => 2140,
  ),
  415 => 
  array (
    'role_id' => 17,
    'module_id' => 2140,
  ),
  416 => 
  array (
    'role_id' => 1,
    'module_id' => 2141,
  ),
  417 => 
  array (
    'role_id' => 10,
    'module_id' => 2141,
  ),
  418 => 
  array (
    'role_id' => 14,
    'module_id' => 2141,
  ),
  419 => 
  array (
    'role_id' => 15,
    'module_id' => 2141,
  ),
  420 => 
  array (
    'role_id' => 17,
    'module_id' => 2141,
  ),
  421 => 
  array (
    'role_id' => 10,
    'module_id' => 2142,
  ),
  422 => 
  array (
    'role_id' => 10,
    'module_id' => 2143,
  ),
  423 => 
  array (
    'role_id' => 10,
    'module_id' => 2144,
  ),
  424 => 
  array (
    'role_id' => 10,
    'module_id' => 2145,
  ),
  425 => 
  array (
    'role_id' => 10,
    'module_id' => 2146,
  ),
  426 => 
  array (
    'role_id' => 10,
    'module_id' => 2147,
  ),
  427 => 
  array (
    'role_id' => 10,
    'module_id' => 2148,
  ),
  428 => 
  array (
    'role_id' => 10,
    'module_id' => 2149,
  ),
  429 => 
  array (
    'role_id' => 10,
    'module_id' => 2150,
  ),
  430 => 
  array (
    'role_id' => 10,
    'module_id' => 2151,
  ),
  431 => 
  array (
    'role_id' => 1,
    'module_id' => 2152,
  ),
  432 => 
  array (
    'role_id' => 10,
    'module_id' => 2152,
  ),
  433 => 
  array (
    'role_id' => 14,
    'module_id' => 2152,
  ),
  434 => 
  array (
    'role_id' => 15,
    'module_id' => 2152,
  ),
  435 => 
  array (
    'role_id' => 17,
    'module_id' => 2152,
  ),
  436 => 
  array (
    'role_id' => 1,
    'module_id' => 2154,
  ),
  437 => 
  array (
    'role_id' => 10,
    'module_id' => 2154,
  ),
  438 => 
  array (
    'role_id' => 14,
    'module_id' => 2154,
  ),
  439 => 
  array (
    'role_id' => 15,
    'module_id' => 2154,
  ),
  440 => 
  array (
    'role_id' => 17,
    'module_id' => 2154,
  ),
  441 => 
  array (
    'role_id' => 1,
    'module_id' => 2155,
  ),
  442 => 
  array (
    'role_id' => 10,
    'module_id' => 2155,
  ),
  443 => 
  array (
    'role_id' => 14,
    'module_id' => 2155,
  ),
  444 => 
  array (
    'role_id' => 15,
    'module_id' => 2155,
  ),
  445 => 
  array (
    'role_id' => 17,
    'module_id' => 2155,
  ),
  446 => 
  array (
    'role_id' => 1,
    'module_id' => 2157,
  ),
  447 => 
  array (
    'role_id' => 10,
    'module_id' => 2157,
  ),
  448 => 
  array (
    'role_id' => 14,
    'module_id' => 2157,
  ),
  449 => 
  array (
    'role_id' => 15,
    'module_id' => 2157,
  ),
  450 => 
  array (
    'role_id' => 17,
    'module_id' => 2157,
  ),
  451 => 
  array (
    'role_id' => 1,
    'module_id' => 2159,
  ),
  452 => 
  array (
    'role_id' => 10,
    'module_id' => 2159,
  ),
  453 => 
  array (
    'role_id' => 14,
    'module_id' => 2159,
  ),
  454 => 
  array (
    'role_id' => 15,
    'module_id' => 2159,
  ),
  455 => 
  array (
    'role_id' => 17,
    'module_id' => 2159,
  ),
  456 => 
  array (
    'role_id' => 1,
    'module_id' => 2160,
  ),
  457 => 
  array (
    'role_id' => 10,
    'module_id' => 2160,
  ),
  458 => 
  array (
    'role_id' => 14,
    'module_id' => 2160,
  ),
  459 => 
  array (
    'role_id' => 15,
    'module_id' => 2160,
  ),
  460 => 
  array (
    'role_id' => 17,
    'module_id' => 2160,
  ),
  461 => 
  array (
    'role_id' => 1,
    'module_id' => 2161,
  ),
  462 => 
  array (
    'role_id' => 10,
    'module_id' => 2161,
  ),
  463 => 
  array (
    'role_id' => 14,
    'module_id' => 2161,
  ),
  464 => 
  array (
    'role_id' => 15,
    'module_id' => 2161,
  ),
  465 => 
  array (
    'role_id' => 17,
    'module_id' => 2161,
  ),
  466 => 
  array (
    'role_id' => 10,
    'module_id' => 2162,
  ),
  467 => 
  array (
    'role_id' => 10,
    'module_id' => 2163,
  ),
  468 => 
  array (
    'role_id' => 10,
    'module_id' => 2164,
  ),
  469 => 
  array (
    'role_id' => 10,
    'module_id' => 2165,
  ),
  470 => 
  array (
    'role_id' => 10,
    'module_id' => 2166,
  ),
  471 => 
  array (
    'role_id' => 10,
    'module_id' => 2167,
  ),
  472 => 
  array (
    'role_id' => 10,
    'module_id' => 2168,
  ),
  473 => 
  array (
    'role_id' => 15,
    'module_id' => 2168,
  ),
  474 => 
  array (
    'role_id' => 10,
    'module_id' => 2170,
  ),
  475 => 
  array (
    'role_id' => 15,
    'module_id' => 2170,
  ),
  476 => 
  array (
    'role_id' => 10,
    'module_id' => 2171,
  ),
  477 => 
  array (
    'role_id' => 15,
    'module_id' => 2171,
  ),
  478 => 
  array (
    'role_id' => 10,
    'module_id' => 2172,
  ),
  479 => 
  array (
    'role_id' => 15,
    'module_id' => 2172,
  ),
  480 => 
  array (
    'role_id' => 10,
    'module_id' => 2173,
  ),
  481 => 
  array (
    'role_id' => 15,
    'module_id' => 2173,
  ),
  482 => 
  array (
    'role_id' => 17,
    'module_id' => 2173,
  ),
  483 => 
  array (
    'role_id' => 10,
    'module_id' => 2174,
  ),
  484 => 
  array (
    'role_id' => 15,
    'module_id' => 2174,
  ),
  485 => 
  array (
    'role_id' => 10,
    'module_id' => 2175,
  ),
  486 => 
  array (
    'role_id' => 15,
    'module_id' => 2175,
  ),
  487 => 
  array (
    'role_id' => 10,
    'module_id' => 2176,
  ),
  488 => 
  array (
    'role_id' => 15,
    'module_id' => 2176,
  ),
  489 => 
  array (
    'role_id' => 17,
    'module_id' => 2176,
  ),
  490 => 
  array (
    'role_id' => 10,
    'module_id' => 2177,
  ),
  491 => 
  array (
    'role_id' => 15,
    'module_id' => 2177,
  ),
  492 => 
  array (
    'role_id' => 17,
    'module_id' => 2177,
  ),
  493 => 
  array (
    'role_id' => 10,
    'module_id' => 2178,
  ),
  494 => 
  array (
    'role_id' => 15,
    'module_id' => 2178,
  ),
  495 => 
  array (
    'role_id' => 10,
    'module_id' => 2179,
  ),
  496 => 
  array (
    'role_id' => 15,
    'module_id' => 2179,
  ),
  497 => 
  array (
    'role_id' => 10,
    'module_id' => 2180,
  ),
  498 => 
  array (
    'role_id' => 15,
    'module_id' => 2180,
  ),
  499 => 
  array (
    'role_id' => 10,
    'module_id' => 2181,
  ),
  500 => 
  array (
    'role_id' => 15,
    'module_id' => 2181,
  ),
  501 => 
  array (
    'role_id' => 10,
    'module_id' => 2182,
  ),
  502 => 
  array (
    'role_id' => 15,
    'module_id' => 2182,
  ),
  503 => 
  array (
    'role_id' => 10,
    'module_id' => 2183,
  ),
  504 => 
  array (
    'role_id' => 15,
    'module_id' => 2183,
  ),
  505 => 
  array (
    'role_id' => 17,
    'module_id' => 2183,
  ),
  506 => 
  array (
    'role_id' => 10,
    'module_id' => 2184,
  ),
  507 => 
  array (
    'role_id' => 15,
    'module_id' => 2184,
  ),
  508 => 
  array (
    'role_id' => 10,
    'module_id' => 2185,
  ),
  509 => 
  array (
    'role_id' => 15,
    'module_id' => 2185,
  ),
  510 => 
  array (
    'role_id' => 10,
    'module_id' => 2186,
  ),
  511 => 
  array (
    'role_id' => 15,
    'module_id' => 2186,
  ),
  512 => 
  array (
    'role_id' => 10,
    'module_id' => 2187,
  ),
  513 => 
  array (
    'role_id' => 15,
    'module_id' => 2187,
  ),
  514 => 
  array (
    'role_id' => 10,
    'module_id' => 2188,
  ),
  515 => 
  array (
    'role_id' => 15,
    'module_id' => 2188,
  ),
  516 => 
  array (
    'role_id' => 1,
    'module_id' => 2189,
  ),
  517 => 
  array (
    'role_id' => 15,
    'module_id' => 2189,
  ),
  518 => 
  array (
    'role_id' => 1,
    'module_id' => 2190,
  ),
  519 => 
  array (
    'role_id' => 15,
    'module_id' => 2190,
  ),
  520 => 
  array (
    'role_id' => 1,
    'module_id' => 2191,
  ),
  521 => 
  array (
    'role_id' => 15,
    'module_id' => 2191,
  ),
  522 => 
  array (
    'role_id' => 1,
    'module_id' => 2192,
  ),
  523 => 
  array (
    'role_id' => 15,
    'module_id' => 2192,
  ),
  524 => 
  array (
    'role_id' => 1,
    'module_id' => 2193,
  ),
  525 => 
  array (
    'role_id' => 15,
    'module_id' => 2193,
  ),
  526 => 
  array (
    'role_id' => 1,
    'module_id' => 2194,
  ),
  527 => 
  array (
    'role_id' => 15,
    'module_id' => 2194,
  ),
  528 => 
  array (
    'role_id' => 1,
    'module_id' => 2195,
  ),
  529 => 
  array (
    'role_id' => 15,
    'module_id' => 2195,
  ),
  530 => 
  array (
    'role_id' => 15,
    'module_id' => 2196,
  ),
  531 => 
  array (
    'role_id' => 17,
    'module_id' => 2196,
  ),
  532 => 
  array (
    'role_id' => 14,
    'module_id' => 2197,
  ),
  533 => 
  array (
    'role_id' => 15,
    'module_id' => 2197,
  ),
  534 => 
  array (
    'role_id' => 17,
    'module_id' => 2197,
  ),
  535 => 
  array (
    'role_id' => 15,
    'module_id' => 2198,
  ),
  536 => 
  array (
    'role_id' => 15,
    'module_id' => 2199,
  ),
  537 => 
  array (
    'role_id' => 15,
    'module_id' => 2200,
  ),
  538 => 
  array (
    'role_id' => 15,
    'module_id' => 2201,
  ),
  539 => 
  array (
    'role_id' => 15,
    'module_id' => 2202,
  ),
  540 => 
  array (
    'role_id' => 14,
    'module_id' => 2203,
  ),
  541 => 
  array (
    'role_id' => 15,
    'module_id' => 2203,
  ),
  542 => 
  array (
    'role_id' => 17,
    'module_id' => 2203,
  ),
  543 => 
  array (
    'role_id' => 15,
    'module_id' => 2204,
  ),
  544 => 
  array (
    'role_id' => 14,
    'module_id' => 2205,
  ),
  545 => 
  array (
    'role_id' => 15,
    'module_id' => 2205,
  ),
  546 => 
  array (
    'role_id' => 17,
    'module_id' => 2205,
  ),
  547 => 
  array (
    'role_id' => 14,
    'module_id' => 2206,
  ),
  548 => 
  array (
    'role_id' => 15,
    'module_id' => 2206,
  ),
  549 => 
  array (
    'role_id' => 17,
    'module_id' => 2206,
  ),
  550 => 
  array (
    'role_id' => 14,
    'module_id' => 2207,
  ),
  551 => 
  array (
    'role_id' => 15,
    'module_id' => 2207,
  ),
  552 => 
  array (
    'role_id' => 17,
    'module_id' => 2207,
  ),
  553 => 
  array (
    'role_id' => 15,
    'module_id' => 2208,
  ),
  554 => 
  array (
    'role_id' => 17,
    'module_id' => 2208,
  ),
  555 => 
  array (
    'role_id' => 15,
    'module_id' => 2209,
  ),
  556 => 
  array (
    'role_id' => 15,
    'module_id' => 2210,
  ),
  557 => 
  array (
    'role_id' => 15,
    'module_id' => 2211,
  ),
  558 => 
  array (
    'role_id' => 15,
    'module_id' => 2212,
  ),
  559 => 
  array (
    'role_id' => 15,
    'module_id' => 2213,
  ),
  560 => 
  array (
    'role_id' => 15,
    'module_id' => 2214,
  ),
  561 => 
  array (
    'role_id' => 15,
    'module_id' => 2215,
  ),
  562 => 
  array (
    'role_id' => 14,
    'module_id' => 2216,
  ),
  563 => 
  array (
    'role_id' => 15,
    'module_id' => 2216,
  ),
  564 => 
  array (
    'role_id' => 17,
    'module_id' => 2216,
  ),
  565 => 
  array (
    'role_id' => 15,
    'module_id' => 2217,
  ),
  566 => 
  array (
    'role_id' => 15,
    'module_id' => 2218,
  ),
  567 => 
  array (
    'role_id' => 15,
    'module_id' => 2219,
  ),
  568 => 
  array (
    'role_id' => 14,
    'module_id' => 2220,
  ),
  569 => 
  array (
    'role_id' => 15,
    'module_id' => 2220,
  ),
  570 => 
  array (
    'role_id' => 17,
    'module_id' => 2220,
  ),
  571 => 
  array (
    'role_id' => 15,
    'module_id' => 2221,
  ),
  572 => 
  array (
    'role_id' => 15,
    'module_id' => 2222,
  ),
  573 => 
  array (
    'role_id' => 15,
    'module_id' => 2223,
  ),
  574 => 
  array (
    'role_id' => 15,
    'module_id' => 2224,
  ),
  575 => 
  array (
    'role_id' => 15,
    'module_id' => 2225,
  ),
  576 => 
  array (
    'role_id' => 15,
    'module_id' => 2226,
  ),
  577 => 
  array (
    'role_id' => 14,
    'module_id' => 2227,
  ),
  578 => 
  array (
    'role_id' => 15,
    'module_id' => 2227,
  ),
  579 => 
  array (
    'role_id' => 17,
    'module_id' => 2227,
  ),
  580 => 
  array (
    'role_id' => 14,
    'module_id' => 2228,
  ),
  581 => 
  array (
    'role_id' => 15,
    'module_id' => 2228,
  ),
  582 => 
  array (
    'role_id' => 17,
    'module_id' => 2228,
  ),
  583 => 
  array (
    'role_id' => 14,
    'module_id' => 2229,
  ),
  584 => 
  array (
    'role_id' => 15,
    'module_id' => 2229,
  ),
  585 => 
  array (
    'role_id' => 17,
    'module_id' => 2229,
  ),
  586 => 
  array (
    'role_id' => 14,
    'module_id' => 2230,
  ),
  587 => 
  array (
    'role_id' => 15,
    'module_id' => 2230,
  ),
  588 => 
  array (
    'role_id' => 17,
    'module_id' => 2230,
  ),
  589 => 
  array (
    'role_id' => 15,
    'module_id' => 2231,
  ),
  590 => 
  array (
    'role_id' => 15,
    'module_id' => 2232,
  ),
  591 => 
  array (
    'role_id' => 15,
    'module_id' => 2233,
  ),
  592 => 
  array (
    'role_id' => 15,
    'module_id' => 2234,
  ),
  593 => 
  array (
    'role_id' => 15,
    'module_id' => 2235,
  ),
  594 => 
  array (
    'role_id' => 15,
    'module_id' => 2236,
  ),
  595 => 
  array (
    'role_id' => 15,
    'module_id' => 2237,
  ),
  596 => 
  array (
    'role_id' => 15,
    'module_id' => 2238,
  ),
  597 => 
  array (
    'role_id' => 15,
    'module_id' => 2239,
  ),
  598 => 
  array (
    'role_id' => 15,
    'module_id' => 2240,
  ),
  599 => 
  array (
    'role_id' => 10,
    'module_id' => 2241,
  ),
  600 => 
  array (
    'role_id' => 14,
    'module_id' => 2241,
  ),
  601 => 
  array (
    'role_id' => 15,
    'module_id' => 2241,
  ),
  602 => 
  array (
    'role_id' => 17,
    'module_id' => 2241,
  ),
  603 => 
  array (
    'role_id' => 1,
    'module_id' => 2242,
  ),
  604 => 
  array (
    'role_id' => 10,
    'module_id' => 2242,
  ),
  605 => 
  array (
    'role_id' => 14,
    'module_id' => 2242,
  ),
  606 => 
  array (
    'role_id' => 15,
    'module_id' => 2242,
  ),
  607 => 
  array (
    'role_id' => 17,
    'module_id' => 2242,
  ),
  608 => 
  array (
    'role_id' => 1,
    'module_id' => 2243,
  ),
  609 => 
  array (
    'role_id' => 10,
    'module_id' => 2243,
  ),
  610 => 
  array (
    'role_id' => 14,
    'module_id' => 2243,
  ),
  611 => 
  array (
    'role_id' => 15,
    'module_id' => 2243,
  ),
  612 => 
  array (
    'role_id' => 17,
    'module_id' => 2243,
  ),
  613 => 
  array (
    'role_id' => 1,
    'module_id' => 2245,
  ),
  614 => 
  array (
    'role_id' => 10,
    'module_id' => 2245,
  ),
  615 => 
  array (
    'role_id' => 15,
    'module_id' => 2245,
  ),
  616 => 
  array (
    'role_id' => 1,
    'module_id' => 2246,
  ),
  617 => 
  array (
    'role_id' => 10,
    'module_id' => 2246,
  ),
  618 => 
  array (
    'role_id' => 15,
    'module_id' => 2246,
  ),
  619 => 
  array (
    'role_id' => 1,
    'module_id' => 2247,
  ),
  620 => 
  array (
    'role_id' => 10,
    'module_id' => 2247,
  ),
  621 => 
  array (
    'role_id' => 15,
    'module_id' => 2247,
  ),
  622 => 
  array (
    'role_id' => 1,
    'module_id' => 2248,
  ),
  623 => 
  array (
    'role_id' => 10,
    'module_id' => 2248,
  ),
  624 => 
  array (
    'role_id' => 15,
    'module_id' => 2248,
  ),
  625 => 
  array (
    'role_id' => 1,
    'module_id' => 2249,
  ),
  626 => 
  array (
    'role_id' => 10,
    'module_id' => 2249,
  ),
  627 => 
  array (
    'role_id' => 15,
    'module_id' => 2249,
  ),
  628 => 
  array (
    'role_id' => 1,
    'module_id' => 2250,
  ),
  629 => 
  array (
    'role_id' => 10,
    'module_id' => 2250,
  ),
  630 => 
  array (
    'role_id' => 14,
    'module_id' => 2250,
  ),
  631 => 
  array (
    'role_id' => 15,
    'module_id' => 2250,
  ),
  632 => 
  array (
    'role_id' => 15,
    'module_id' => 2251,
  ),
  633 => 
  array (
    'role_id' => 10,
    'module_id' => 2252,
  ),
  634 => 
  array (
    'role_id' => 14,
    'module_id' => 2252,
  ),
  635 => 
  array (
    'role_id' => 15,
    'module_id' => 2252,
  ),
  636 => 
  array (
    'role_id' => 17,
    'module_id' => 2252,
  ),
  637 => 
  array (
    'role_id' => 10,
    'module_id' => 2253,
  ),
  638 => 
  array (
    'role_id' => 14,
    'module_id' => 2253,
  ),
  639 => 
  array (
    'role_id' => 15,
    'module_id' => 2253,
  ),
  640 => 
  array (
    'role_id' => 17,
    'module_id' => 2253,
  ),
  641 => 
  array (
    'role_id' => 15,
    'module_id' => 2254,
  ),
  642 => 
  array (
    'role_id' => 15,
    'module_id' => 2255,
  ),
  643 => 
  array (
    'role_id' => 15,
    'module_id' => 2256,
  ),
  644 => 
  array (
    'role_id' => 15,
    'module_id' => 2257,
  ),
  645 => 
  array (
    'role_id' => 15,
    'module_id' => 2258,
  ),
  646 => 
  array (
    'role_id' => 15,
    'module_id' => 2259,
  ),
  647 => 
  array (
    'role_id' => 15,
    'module_id' => 2260,
  ),
  648 => 
  array (
    'role_id' => 15,
    'module_id' => 2261,
  ),
  649 => 
  array (
    'role_id' => 15,
    'module_id' => 2262,
  ),
  650 => 
  array (
    'role_id' => 15,
    'module_id' => 2263,
  ),
  651 => 
  array (
    'role_id' => 15,
    'module_id' => 2264,
  ),
  652 => 
  array (
    'role_id' => 15,
    'module_id' => 2265,
  ),
  653 => 
  array (
    'role_id' => 15,
    'module_id' => 2266,
  ),
  654 => 
  array (
    'role_id' => 15,
    'module_id' => 2267,
  ),
  655 => 
  array (
    'role_id' => 1,
    'module_id' => 2268,
  ),
  656 => 
  array (
    'role_id' => 10,
    'module_id' => 2268,
  ),
  657 => 
  array (
    'role_id' => 14,
    'module_id' => 2268,
  ),
  658 => 
  array (
    'role_id' => 15,
    'module_id' => 2268,
  ),
  659 => 
  array (
    'role_id' => 14,
    'module_id' => 2269,
  ),
);

        // Ensure role_id and module_id exist in active tables
        $validData = array_filter($data, function ($item) use ($validRoleIds, $validModuleIds) {
            return in_array($item['role_id'], $validRoleIds) && in_array($item['module_id'], $validModuleIds);
        });

        DB::table('auth_role_module')->delete();

        foreach (array_chunk($validData, 500) as $chunk) {
            DB::table('auth_role_module')->insertOrIgnore($chunk);
        }
    }
}
