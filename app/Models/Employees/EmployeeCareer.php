<?php

namespace App\Models\Employees;

use App\Models\GlobalData;
use App\Models\Organization;
use App\Models\Placement;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCareer extends Model
{
    use SoftDeletes;
    use UserStamp;
    use UuidKey;

    protected $table   = 'iq_employ_career';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'career_id',
        'employ_id',
        'placement_id',
        'org_id',
        'file_id',
        'date',
        'description',
    ];

    public function career()
    {
        return $this->belongsTo(GlobalData::class, 'career_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class, 'placement_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }
}
