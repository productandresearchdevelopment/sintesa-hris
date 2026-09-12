<?php

namespace App\Models;

use App\SystemModels\Auth\User;
use App\SystemModels\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_notification';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
