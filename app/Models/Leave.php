<?php

namespace App\Models;

use App\Models\Employees\Employee;
use App\SystemModels\Auth\User;
use App\SystemModels\Globals\Upload;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_leave';
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employ_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(GlobalData::class, 'type_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id', 'id');
    }

    public function approver1()
    {
        return $this->belongsTo(User::class, 'approved1_by', 'id');
    }

    public function approver2()
    {
        return $this->belongsTo(User::class, 'approved2_by', 'id');
    }

    public function allowed()
    {
        return $this->belongsTo(User::class, 'allowed_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
