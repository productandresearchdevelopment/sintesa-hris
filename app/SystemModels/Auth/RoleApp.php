<?php

namespace App\SystemModels\Auth;

use App\Models\Projects\Project;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SystemModels\Projects\Clients;

class RoleApp extends Model
{
    protected $table   = 'auth_role_apps';
    protected $guarded = ['id'];
}
