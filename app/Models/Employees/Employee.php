<?php

namespace App\Models\Employees;

use App\Models\Appraisals\AppraisalEmployee;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Company;
use App\Models\Division;
use App\Models\GlobalData;
use App\Models\Office;
use App\Models\Organization;
use App\Models\Placement;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'org_id',
        'division_id',
        'company_id',
        'placement_id',
        'last_contract_id',
        'last_career_id',
        'tax_id',
        'nik',
        'nickname',
        'fullname',
        'birth_place',
        'birth_date',
        'phone',
        'email',
        'gender_id',
        'marital_id',
        'religion_id',
        'join_date',
        'leave_saldo',
        'address',
        'address_city_id',
        'address_province_id',
        'address_permanent',
        'address_permanent_city_id',
        'address_permanent_province_id',
        'bank_id',
        'bank_account',
        'bank_alias',
        'emergency_relation_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_address',
        'photo_id',
        'shift_start_time',
        'shift_end_time',
        'office_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'employ_id', 'id');
    }

    public function appraisal_employees()
    {
        return $this->hasMany(AppraisalEmployee::class, 'employ_id', 'id');
    }

    public function employee_requests()
    {
        return $this->hasMany(EmployeeRequest::class, 'employ_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function gender()
    {
        return $this->belongsTo(GlobalData::class, 'gender_id', 'id');
    }

    public function marital()
    {
        return $this->belongsTo(GlobalData::class, 'marital_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo(GlobalData::class, 'religion_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(GlobalData::class, 'bank_id', 'id');
    }

    public function emergency_relation()
    {
        return $this->belongsTo(GlobalData::class, 'emergency_relation_id', 'id');
    }

    public function photo()
    {
        return $this->belongsTo(Upload::class, 'photo_id', 'id');
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class, 'placement_id', 'id');
    }

    public function address_city()
    {
        return $this->belongsTo(City::class, 'address_city_id', 'id');
    }

    public function address_province()
    {
        return $this->belongsTo(City::class, 'address_province_id', 'id');
    }

    public function address_permanent_city()
    {
        return $this->belongsTo(City::class, 'address_permanent_city_id', 'id');
    }

    public function address_permanent_province()
    {
        return $this->belongsTo(City::class, 'address_permanent_province_id', 'id');
    }

    public function last_contract()
    {
        return $this->belongsTo(EmployeeContract::class, 'last_contract_id', 'id');
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class, 'employ_id', 'id');
    }

    public function citizens()
    {
        return $this->hasMany(EmployeeCitizen::class, 'employ_id', 'id');
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class, 'employ_id', 'id');
    }

    public function last_career()
    {
        return $this->belongsTo(EmployeeCareer::class, 'last_career_id', 'id');
    }

    public function careers()
    {
        return $this->hasMany(EmployeeCareer::class, 'employ_id', 'id');
    }

    public function families()
    {
        return $this->hasMany(EmployeeFamily::class, 'employ_id', 'id');
    }

    public function job_experiences()
    {
        return $this->hasMany(EmployeeJobExperience::class, 'employ_id', 'id');
    }

    public function trainings()
    {
        return $this->hasMany(EmployeeTraining::class, 'employ_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
}
