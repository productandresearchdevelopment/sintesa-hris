<?php

namespace App\Models;

use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Placement extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_placement';
    protected $guarded = ['id'];
}
