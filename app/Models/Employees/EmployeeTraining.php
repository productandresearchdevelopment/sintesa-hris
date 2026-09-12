<?php

namespace App\Models\Employees;

use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTraining extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_training';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'employ_id',
        'title',
        'location',
        'start_date',
        'end_date',
        'file_id',
        'description',
        'is_internal',
        'is_certification',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }
}
