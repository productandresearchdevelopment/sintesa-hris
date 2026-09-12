<?php

namespace App\Models\Helpdesks;

use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskAnswerFile extends Model
{
    protected $table   = 'iq_helpdesk_answer_file';

    protected $fillable = [
        'helpdesk_answer_id',
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

    public function helpdesk_answer()
    {
        return $this->belongsTo(HelpdeskAnswer::class, 'helpdesk_answer_id');
    }
}
