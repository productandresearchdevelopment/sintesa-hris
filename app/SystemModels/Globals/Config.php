<?php

namespace App\SystemModels\Globals;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {
    protected $table = 'config';
    protected $guarded = ['id'];
    protected $fillable = ['key','value','description'];
    protected $casts = ['value' => 'object'];
    public $timestamps = false;
}
