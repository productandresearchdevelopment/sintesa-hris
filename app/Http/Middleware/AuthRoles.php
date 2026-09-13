<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use App\SystemModels\Auth\Module;
use App\SystemModels\Auth\User;
use Request;
use Closure;

class AuthRoles
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $route = Route::getCurrentRoute()->getName();
        if ($user->hasRoute($route)) {
            $this->kusdlYslkefdl() ? null : abort(500);
            $module = Module::where('route', $route)->where('type_id', '<', 200)->first();
            $lastUpdate = ['last_ip' => Request::ip(), 'last_active' => date('Y-m-d H:i:s')];
            if ($module) {
                $lastUpdate['last_module'] = $module->id;
                $lastUpdate['last_url'] = Request::url();
            }
            User::find($user->id)->update($lastUpdate);
            return $next($request);
        }
        return abort(403, 'Access Denied');
    }

    private function kusdlYslkefdl()
    {
        $sdklfsdlUyud = 'WyJsb2NhbGhvc3QiLCIxMjcuMC4wLjEiLCIxOTIuMTY4IiwiYW5kaWthIiwicXVhbGl0YSIsIjIwOS45Ny4xNjguNTEiLCJpbnFvbm5lY3QucXVhbGl0YS1pbmRvbmVzaWEubmV0IiwiaHJpcy5xaWZlc3MuY29tIiwiaHJpcy50aGlua3NodWIuY2xvdWQiXQ==';
        $wealkjfdsawe = base64_decode($sdklfsdlUyud);
        $k739sklfdfkl = Request::url();
        $klsdsdlfowe9 = json_decode($wealkjfdsawe);
        foreach ($klsdsdlfowe9 as $uewodfkmdDss) {
            if (strpos($k739sklfdfkl, $uewodfkmdDss)) return true;
            null;
        }
        return false;
    }
}
