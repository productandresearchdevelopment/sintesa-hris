<?php

namespace App\Models\Employees;

use App\Models\GlobalData;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeEducation extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_education';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'education_id',
        'major_id',
        'institution',
        'graduate',
        'ipk',
        'description',
        'file_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function education()
    {
        return $this->belongsTo(GlobalData::class, 'education_id', 'id');
    }

    public function major()
    {
        return $this->belongsTo(GlobalData::class, 'major_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }
}
