<?php

namespace App\Models\Helpdesks;

use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskAnswer extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_helpdesk_answer';
    protected $guarded = ['id'];

    protected $fillable = [
        'helpdesk_id',
        'message',
    ];

    public function helpdesk()
    {
        return $this->belongsTo(Helpdesk::class, 'helpdesk_id', 'id');
    }

    public function user_mentions()
    {
        return $this->belongsToMany(User::class, 'iq_helpdesk_answer_mention', 'answer_id', 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function uploads()
    {
        return $this->belongsToMany(Upload::class, 'iq_helpdesk_answer_file', 'helpdesk_answer_id', 'upload_id');
    }
}
