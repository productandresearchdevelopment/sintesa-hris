<?php

namespace App\Models\Appraisals;

use App\Models\Division;
use App\Models\Organization;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalQuestionTemplate extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_appraisal_question_template';
    protected $guarded = ['id'];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function appraisal_questions()
    {
        return $this->hasMany(AppraisalQuestion::class, 'template_id', 'id');
    }

    public function templates_organizations()
    {
        return $this->belongsToMany(Organization::class, 'iq_appraisal_period_organization', 'template_id', 'organization_id');
    }
}
