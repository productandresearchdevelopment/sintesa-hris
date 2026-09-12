<?php

namespace App\Models\Helpdesks;

use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskFile extends Model
{
    protected $table   = 'iq_helpdesk_file';

    protected $fillable = [
        'helpdesk_id',
        'upload_id',
    ];

    public $timestamps = false;
    protected $primaryKey = 'upload_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function helpdesk()
    {
        return $this->belongsTo(Helpdesk::class, 'helpdesk_id');
    }
}
