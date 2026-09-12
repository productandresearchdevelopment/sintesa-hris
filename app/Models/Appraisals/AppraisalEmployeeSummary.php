<?php

namespace App\Models\Appraisals;

use Illuminate\Database\Eloquent\Model;

class AppraisalEmployeeSummary extends Model
{
    protected $table   = 'iq_appraisal_employ_summary';
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = null;
    public $incrementing = false;


    public function appraisal_employee()
    {
        return $this->belongsTo(AppraisalEmployee::class, 'appraisal_employ_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(AppraisalQuestionCategory::class, 'category_id', 'id');
    }
}
