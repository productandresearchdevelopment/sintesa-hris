<?php

namespace App\SystemModels;

use App\SystemModels\Auth\User;
use Auth;
use Illuminate\Support\Str;

trait PaternKey {
    private $model;

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            if(!$model->getKey()) {
                $lengthPad = (isset($model->lengthPad) && $model->lengthPad) ? $model->lengthPad : 5;
                $prefix  = (isset($model->prefix) && $model->prefix) ? $model->prefix : '';

                $prefix .= date('Ym');
                $data = $model->select('id')->where('id', 'LIKE',  "$prefix%")->withTrashed()->orderBy('id', 'DESC')->first();
                $no = 1;
                if($data){
                    $no = $data->id;
                    $no = str_replace($prefix, '', $no);
                    $no = $no + 1;
                }
                $model->{$model->getKeyName()} = $prefix . str_pad($no, $lengthPad, "0", STR_PAD_LEFT);
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

