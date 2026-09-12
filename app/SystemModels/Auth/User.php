<?php

namespace App\SystemModels\Auth;

use App\Models\Employees\Employee;
use App\Models\Example\Project;
use App\Models\FileManager;
use App\Models\Notification;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Station;
use App\Models\Team;
use App\SystemModels\Globals\Upload;
use App\SystemModels\LogModel;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use UuidKey;
    use UserStamp;

    protected $guarded = ['id'];
    protected $hidden = ['password', 'token'];
    protected $table = 'auth_user';
    protected $casts = [
        'property' => 'object',
        'email_validation_sent_at' => 'datetime',
        'email_validation_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $skipLogFields = ['last_module', 'last_url', 'last_active', 'property'];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function lastModule()
    {
        return $this->hasOne(Module::class, 'id', 'last_module');
    }

    public function photo()
    {
        return $this->hasOne(Upload::class, 'id', 'photo_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function hasRoute($routes)
    {
        if ($role = $this->role()->getResults()) {
            return $role->hasRoute($routes);
        }
        return false;
    }

    public function hasAuth($tags)
    {
        if ($role = $this->role()->getResults()) {
            return $role->hasAuth($tags);
        }
        return false;
    }

    public function filemanagers()
    {
        return $this->belongsToMany(FileManager::class, 'iq_filemanager_user', 'user_id', 'filemanager_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
