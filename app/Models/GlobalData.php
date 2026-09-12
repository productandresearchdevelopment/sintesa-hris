<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalData extends Model
{
    protected $table   = 'iq_global_data';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'property' => 'object',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'type_id', 'id');
    }
}
