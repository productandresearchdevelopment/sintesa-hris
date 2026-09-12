<?php

namespace App\Controllers\Admins\GlobalDatas;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\GlobalData as Mod;
use Illuminate\Http\Request;

class GlobalData extends Controller
{
    public function data(Request $request)
    {
        $query = Mod::select('*');

        if ($request->has('group')) {
            $query->where('group', $request->group);
        }

        $result = $query->get();
        return response()->json([
            'data' => $result,
            'count' => $result->count()
        ]);
    }
}
