<?php

namespace App\Models\Employees;

use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeJobExperience extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_job_experience';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'name',
        'start_date',
        'end_date',
        'job_title',
        'job_description',
        'salary',
        'reason_leaving',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }
}
