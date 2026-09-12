<?php

namespace App\Models\Helpdesks;

use App\Models\Organization;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpdeskCategory extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_helpdesk_category';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'iq_helpdesk_category_organization', 'category_id', 'organization_id');
    }
}
