<?php

namespace App\SystemModels\Globals;

use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use UuidKey;

    protected $table = 'log';
    protected $guarded = ['id'];
    protected $casts = ['message' => 'object'];
}
