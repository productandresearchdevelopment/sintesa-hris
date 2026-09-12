<?php

namespace App\Models\Helpdesks;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskOrganization extends Model
{
    protected $table   = 'iq_helpdesk_organization';

    protected $fillable = [
        'helpdesk_id',
        'organization_id',
    ];
}
