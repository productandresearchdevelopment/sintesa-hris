<?php

namespace App\Controllers\Admins\Cities;

use App\Http\Controllers\Controller;
use App\Models\City as Mod;
use Illuminate\Http\Request;

class City extends Controller
{
    public function data(Request $request)
    {
        $query = Mod::query();

        return response()->json([
            'data' => $query->get(),
            'count' => $query->count()
        ]);
    }
}
