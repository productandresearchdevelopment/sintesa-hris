<?php

namespace App\Models\Employees;

use App\Models\GlobalData;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCitizen extends Model
{
    use UuidKey;
    protected $table   = 'iq_employ_citizen';
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'citizen_id',
        'file_id',
        'value',
        'description'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function citizen()
    {
        return $this->belongsTo(GlobalData::class, 'citizen_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }
}
