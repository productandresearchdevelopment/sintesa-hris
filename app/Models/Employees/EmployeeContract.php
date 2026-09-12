<?php

namespace App\Models\Employees;

use App\Models\GlobalData;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContract extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_contract';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'status_id',
        'start_date',
        'end_date',
        'file_id',
        'description'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(GlobalData::class, 'status_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }
}
