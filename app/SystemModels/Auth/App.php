<?php

namespace App\SystemModels\Auth;

use App\Models\Projects\Project;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SystemModels\Projects\Clients;

class App extends Model
{
    protected $table   = 'auth_apps';
    protected $guarded = ['id'];
    protected $casts = ['ip' => 'object'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'auth_role_apps', 'app_id', 'role_id');
    }
}
