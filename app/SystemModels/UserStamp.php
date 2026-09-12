<?php

namespace App\SystemModels;

use App\SystemModels\Auth\User;
use Auth;
use Illuminate\Support\Facades\Schema;

trait UserStamp {
    public static function bootUserStamp()
    {
        static::creating(function ($model) {
            if($user = Auth::user()) {
                if ($model->timestamps) {
                    if (Schema::hasColumn($model->table, 'created_by')) $model->created_by = $user->id;
                    if (Schema::hasColumn($model->table, 'updated_by')) $model->updated_by = $user->id;
                }
            }
        });

        static::updating(function($model){
            if($user = Auth::user()) {
                if ($model->timestamps) {
                    if (Schema::hasColumn($model->table, 'updated_by')) $model->updated_by = $user->id;
                }
            }
        });

        static::deleting(function($model){
            if($user = Auth::user()) {
                if (!$model->forceDeleting && $model->timestamps) {
                    if (Schema::hasColumn($model->table, 'deleted_by')) $model->deleted_by = $user->id;
                    if (Schema::hasColumn($model->table, 'updated_by')) $model->updated_by = $user->id;
                    $model->save();
                }
            }
        });

        if(method_exists(static::class, "restoring")) {
            static::restoring(function ($model) {
                if ($user = Auth::user()) {
                    if ($model->timestamps) {
                        if (Schema::hasColumn($model->table, 'deleted_by')) $model->deleted_by = null;
                        if (Schema::hasColumn($model->table, 'updated_by')) $model->updated_by = $user->id;
                        $model->save();
                    }
                }
            });
        }
    }


    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by')
            ->withTrashed()
            ->select('id','role_id','name', 'photo_id')
            ->with(['role' => function($query) {
                $query->select(['id', 'name', 'alias', 'color']);
            }])->withTrashed();
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by')
            ->withTrashed()
            ->select('id','role_id','name', 'photo_id')
            ->with(['role' => function($query) {
                $query->select(['id', 'name', 'alias', 'color']);
            }])->withTrashed();
    }

    public function deletedBy(){
        return $this->hasOne(User::class, 'id', 'deleted_by')
            ->withTrashed()
            ->select('id','role_id','name', 'photo_id')
            ->with(['role' => function($query) {
                $query->select(['id', 'name', 'alias', 'color']);
            }])->withTrashed();
    }
}

