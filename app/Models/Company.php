<?php

namespace App\Models;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_company';
    protected $guarded = ['id'];

    public function offices()
    {
        return $this->hasMany(Office::class, 'company_id', 'id');
    }
}
