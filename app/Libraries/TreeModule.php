<?php


namespace App\Libraries;


use App\Models\Projects\Project;
use App\SystemModels\Auth\Module;
use Exception;
use Illuminate\Support\Facades\Auth;

class TreeModule
{
    static function get($ismobile = false)
    {
        return (new TreeModule())->treeMenu(Auth::user()->role_id, $ismobile);
    }

    public function treeMenu($role = null, $ismobile = 0, $parent = null)
    {
        $modules = Module::where(function ($query) use ($role, $parent, $ismobile) {
            $query->where('parent', $parent)
                ->where('is_active', 1)
                ->whereHas('type', function ($query) {
                    $query->where('show_menu', 1);
                })
                ->where(function ($query) use ($role) {
                    $query->where('is_locked', 0)->orwhereHas('roles', function ($q) use ($role) {
                        $q->where('role_id', $role);
                    });
                })
                ->where(function ($query) use ($ismobile) {
                    $query->whereNull('device')
                        ->orWhere('device', 0);
                    if ($ismobile) {
                        $query->orWhere('device', 1);
                    } else {
                        $query->orWhere('device', 2);
                    }
                });
        })->orderBy('sort')->get();
        $result = [];
        foreach ($modules as $module) {
            $items = $this->treeMenu($role, $ismobile, $module->id);

            $route = '';
            try {
                $route = route($module->route);
            } catch (Exception $e) {
                //$route = route('abort.500', 'ROUTING ERROR');
            }

            $url = $module->route ? $route : $module->url;
            $param = $module->param ?: '';
            $url = $url . $param;

            $result[] = (object)[
                'id' => $module->id,
                'activity_id' => $module->activity_id,
                'parent' => $module->parent,
                'text' => $module->text,
                'icon' => $module->icon,
                'url' => $url,
                'items' => $items,
            ];
        }
        return $result;
    }
}
