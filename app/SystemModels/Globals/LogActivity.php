<?php

namespace App\SystemModels\Globals;

use App\SystemModels\UuidKey;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use UuidKey;

    protected $table = 'log_modified';
    protected $guarded = ['id'];
}
