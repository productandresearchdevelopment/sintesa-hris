<?php

namespace App\Models;

use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileManager extends Model
{
    use HasFactory;
    use UserStamp;
    use SoftDeletes;

    protected $table   = 'iq_filemanager';
    protected $guarded = ['id'];

    public function files()
    {
        return $this->hasMany(Upload::class, 'id', 'file_id');
    }


    public function file()
    {
        return $this->hasOne(Upload::class, 'id', 'file_id');
    }

    public function parent()
    {
        return $this->hasOne(FileManager::class, 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(FileManager::class, 'parent_id', 'id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'iq_filemanager_organization', 'filemanager_id', 'organization_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'iq_filemanager_user', 'filemanager_id', 'user_id');
    }
}
