<?php

namespace App\Models\Appraisals;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalQuestion extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_appraisal_question';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(AppraisalQuestionCategory::class, 'category_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(AppraisalQuestionTemplate::class, 'template_id', 'id');
    }

    public function employee_appraisals()
    {
        return $this->hasMany(AppraisalEmployeeQuestion::class, 'question_id', 'id');
    }
}
