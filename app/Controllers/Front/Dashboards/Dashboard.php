<?php

namespace App\Controllers\Front\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('_front.dashboard.index',  [
            'user' => $user
        ]);
    }
}
