<?php

namespace App\Models\Employees;

use App\Models\GlobalData;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFamily extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_family';
    protected $guarded = ['id'];

    protected $fillable = [
        'employ_id',
        'relation_id',
        'nik',
        'name',
        'birth_date',
        'occupation_id',
        'occupation_description',
        'address',
        'phone',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function relation()
    {
        return $this->belongsTo(GlobalData::class, 'relation_id', 'id');
    }

    public function occupation()
    {
        return $this->belongsTo(GlobalData::class, 'occupation_id', 'id');
    }
}
