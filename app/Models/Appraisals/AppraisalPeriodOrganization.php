<?php

namespace App\Models\Appraisals;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class AppraisalPeriodOrganization extends Model
{
    protected $table   = 'iq_appraisal_period_organization';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function appraisal_period()
    {
        return $this->belongsTo(AppraisalPeriod::class, 'period_id', 'id');
    }

    public function appraisal_question_template()
    {
        return $this->belongsTo(AppraisalQuestionTemplate::class, 'template_id', 'id');
    }
}
