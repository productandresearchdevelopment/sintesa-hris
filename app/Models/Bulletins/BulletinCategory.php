<?php

namespace App\Models\Bulletins;

use App\Models\Organization;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulletinCategory extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_bulletin_category';
    protected $guarded = ['id'];

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class, 'category_id', 'id');
    }

    public function logo()
    {
        return $this->hasOne(Upload::class, 'id', 'logo_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'iq_bulletin_category_organization', 'category_id', 'organization_id');
    }
}
