<?php

namespace App\Models\Helpdesks;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskCategoryOrganization extends Model
{
    protected $table   = 'iq_bulletin_category_organization';

    protected $fillable = [
        'category_id',
        'organization_id',
    ];
}
