<?php

namespace App\Http\Controllers\Systems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SystemModels\Auth\App as Mod;
use App\SystemModels\Auth\Role;

class App extends Controller
{
    public function data(Request $request, $roleId = null)
    {
        $query = Mod::query()->orderBy('name', 'desc');
        $apps = $query->get(['id', 'name']);

        if ($roleId) {
            $role = Role::find($roleId);
            $roleApps = $role ? $role->apps()->pluck('app_id')->toArray() : [];

            $apps = $apps->map(function ($app) use ($roleApps) {
                $app->auth = in_array($app->id, $roleApps);
                return $app;
            });
        } else {
            $apps->map(function ($app) {
                $app->auth = false;
                return $app;
            });
        }

        return response()->json($apps);
    }

    public function setRole(Request $request, $roleId)
    {
        $appId = $request->input('app');
        $auth  = $request->boolean('auth');

        if (!$roleId || !$appId) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters'
            ], 400);
        }

        $role = Role::find($roleId);
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        if ($auth) {
            $role->apps()->syncWithoutDetaching([$appId]);
        } else {
            $role->apps()->detach([$appId]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Organization permissions updated successfully'
        ]);
    }
}
