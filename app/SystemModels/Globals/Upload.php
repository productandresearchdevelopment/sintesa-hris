<?php

namespace App\SystemModels\Globals;

use App\Models\Bulletin;
use App\Models\FileManager;
use App\Models\Helpdesk;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use UuidKey;
    use SoftDeletes;
    use UserStamp;

    protected $table = 'uploads';
    protected $guarded = ['created_at', 'updated_at'];
    public $paternId = 'uuid';
    public $modifyBy = false;

    protected $casts = [
        'size' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function bulletin()
    {
        return $this->belongsTo(Bulletin::class, 'iq_bulletin', 'id');
    }

    public function helpdesk()
    {
        return $this->belongsTo(Helpdesk::class, 'helpdesk_id');
    }

    public function foldermanager()
    {
        return $this->belongsTo(FileManager::class, 'folder_id', 'id');
    }
}
