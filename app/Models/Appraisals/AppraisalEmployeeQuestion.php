<?php

namespace App\Models\Appraisals;

use Illuminate\Database\Eloquent\Model;

class AppraisalEmployeeQuestion extends Model
{
    protected $table   = 'iq_appraisal_employ_question';
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = null;
    public $incrementing = false;


    public function appraisal_employee()
    {
        return $this->belongsTo(AppraisalEmployee::class, 'appraisal_employ_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(AppraisalQuestion::class, 'question_id', 'id');
    }
}
