<?php

namespace App\SystemModels\Auth;

use App\Models\Projects\Project;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use UserStamp;

    protected $table    = 'auth_module';
    protected $guarded  = ['id'];

    public function type()
    {
        return $this->hasOne(ModuleType::class, 'id', 'type_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'auth_role_module');
    }

    public function childs()
    {
        return $this->hasMany(Module::class, 'parent', 'id');
    }

    public function hasRole($roleId)
    {
        if ($roles = $this->roles()->getResults()) {
            foreach ($roles as $role) {
                if ($role->id == $roleId) return true;
            }
        }
        return false;
    }

    public static function setPath($id)
    {
        $module = static::find($id);
        $parent = $module->parent;
        $path   = '/' . $id;
        while ($parent) {
            $path = '/' . $parent . $path;
            $parent = static::find($parent)->parent;
        }
        $module->update(['path' => $path]);
        return $path;
    }

    public static function resorting($parent)
    {
        $module = static::where('parent', $parent)->where('sort', null)->first();
        if ($module) $module->update(['sort' => 99999]);

        $modules = static::where('parent', $parent)->orderBy('sort')->get();
        $i = 0;
        foreach ($modules as $module) {
            $i++;
            $module->update(['sort' => $i]);
        }
    }

    public static function tree($role = null, $withChecked = false, $parent = null)
    {
        $result = static::where('parent', $parent);

        if ($role && !$withChecked) {
            $result->whereHas('type', function ($q) {
                $q->where('show_menu', 1);
            });
            $result->where(function ($query) use ($role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('role_id', $role);
                });
                $query->orWhere('is_locked', 0);
            });
            $result->where(function ($query) {
                $query->whereNull('device')
                    ->orWhere('device', 0)
                    ->orWhere('device', 2);
            });
        } else if (!$withChecked) $result->with('roles');

        $result =  $result->orderBy('sort')->get();

        foreach ($result as $row) {
            $row->children = static::tree($role, $withChecked, $row->id);
            $row->leaf     = (count($row->children)) ? false : true;
            if ($withChecked) $row->checked = $row->hasRole($role);

            if (!$role && !$withChecked) {
                $row->title    = $row->text;
                $row->menuIcon = $row->icon;
                $row->icon     = asset('images/icons/' . $row->type->icon . '.png');
            }

            if ($role && !$withChecked && $row->route) $row->url = route($row->route, '');
        }
        return $result;
    }

    public static function list($role)
    {
        $result = static::whereHas('type', function ($q) {
            $q->where('show_menu', 1);
        });
        $result->where(function ($query) use ($role) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('role_id', $role);
            });
            $query->orWhere('is_locked', 0);
        });
        $result = $result->orderBy('sort');
        $result = $result->get();
        foreach ($result as $row) {
            if ($row->route) {
                $row->url = route($row->route);
            }
        }
        return $result;
    }
}
