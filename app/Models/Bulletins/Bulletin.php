<?php

namespace App\Models\Bulletins;

// use App\Models\Upload as ModelsUpload;

use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload as Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bulletin extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_bulletin';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(BulletinCategory::class, 'category_id', 'id');
    }

    public function cover_image()
    {
        return $this->belongsTo(Upload::class, 'cover_image_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
