<?php

namespace App\Controllers\Admins\Placements;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Placement as Mod;
use Illuminate\Http\Request;

class Placement extends Controller
{
    public function data(Request $request)
    {
        $query = Mod::query();

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = [
            'data' => $query->get(),
            'count' => $query->count(),
        ];

        return response()->json($result);
    }
}
