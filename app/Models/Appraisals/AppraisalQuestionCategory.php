<?php

namespace App\Models\Appraisals;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalQuestionCategory extends Model
{
    protected $table   = 'iq_appraisal_question_category';
    protected $guarded = ['id'];
}
