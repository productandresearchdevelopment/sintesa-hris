<?php

namespace App\Models\Appraisals;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalPeriod extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_appraisal_period';
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    public function appraisal_period_organizations()
    {
        return $this->hasMany(AppraisalPeriodOrganization::class, 'period_id', 'id');
    }
}
