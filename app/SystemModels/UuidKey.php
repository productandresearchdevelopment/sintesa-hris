<?php

namespace App\SystemModels;

use Auth;
use Illuminate\Support\Str;

/*
 * CREATE BY : andika.gumelar @2022
 * TRAIT UUID KEY
 * Trait ini digunakan jika menginginkan nilai (Key "ID") menggunakan UUID
 * Jenis Karakter "Key / ID" disarankan menggunakan type "char (36)"
 * Cara penggunaan, masukan perintah "use UuidKey;" pada model anda
 * Example:
 *   class User extends Model
 *   {
 *       use SoftDeletes;
 *       use UuidKey;
 *
 *       protected $table = 'auth_user';
 *       .....
 *   }
 */

trait UuidKey {
    private $model;

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            if(!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing(){
        return false;
    }

    public function getKeyType(){
        return 'string';
    }
}

