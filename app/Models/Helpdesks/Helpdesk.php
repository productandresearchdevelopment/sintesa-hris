<?php

namespace App\Models\Helpdesks;

use App\Models\Organization;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Helpdesk extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_helpdesk';
    protected $guarded = ['id'];

    protected $fillable = [
        'organization_id',
        'category_id',
        'last_answer_id',
        'title',
        'message',
        'status',
        'closed_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'iq_helpdesk_organization', 'helpdesk_id', 'organization_id');
    }

    public function helpdesk_answers()
    {
        return $this->hasMany(HelpdeskAnswer::class, 'helpdesk_id', 'id');
    }

    public function last_answer()
    {
        return $this->belongsTo(HelpdeskAnswer::class, 'last_answer_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(HelpdeskAnswer::class, 'helpdesk_id', 'id');
    }

    public function uploads()
    {
        return $this->belongsToMany(Upload::class, 'iq_helpdesk_file', 'helpdesk_id', 'upload_id');
    }

    public function category()
    {
        return $this->belongsTo(HelpdeskCategory::class, 'category_id', 'id');
    }

    public function user_mentions()
    {
        return $this->belongsToMany(User::class, 'iq_helpdesk_mention', 'helpdesk_id', 'user_id');
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
}
