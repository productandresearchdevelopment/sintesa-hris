<?php

namespace App\Models\Employees;

use App\Models\City;
use App\Models\Company;
use App\Models\Division;
use App\Models\GlobalData;
use App\Models\Organization;
use App\Models\Placement;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRequest extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_request';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'org_id',
        'division_id',
        'company_id',
        'placement_id',
        'last_contract_id',
        'last_career_id',
        'tax_id',
        'status',
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
    ];

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

    public function employ()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
