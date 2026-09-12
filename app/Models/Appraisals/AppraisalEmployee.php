<?php

namespace App\Models\Appraisals;

use App\Models\Employees\Employee;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalEmployee extends Model
{
    use SoftDeletes;
    use SoftDeletes;
    use UuidKey;
    use UserStamp;

    protected $table   = 'iq_appraisal_employ';
    protected $guarded = ['id'];

    public function period()
    {
        return $this->belongsTo(AppraisalPeriodOrganization::class, 'period_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(AppraisalQuestionTemplate::class, 'template_id', 'id');
    }

    public function evaluator1()
    {
        return $this->belongsTo(Employee::class, 'evaluator1_by', 'id');
    }

    public function evaluator2()
    {
        return $this->belongsTo(Employee::class, 'evaluator2_by', 'id');
    }

    public function appraisal_employee_questions()
    {
        return $this->hasMany(AppraisalEmployeeQuestion::class, 'appraisal_employ_id', 'id');
    }

    public function appraisal_employee_summaries()
    {
        return $this->hasMany(AppraisalEmployeeSummary::class, 'appraisal_employ_id', 'id');
    }
}
