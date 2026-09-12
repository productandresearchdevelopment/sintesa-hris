<?php

namespace App\SystemModels;

use App\SystemModels\Globals\LogActivity;
use Auth;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

/*
 * CREATE BY : andika.gumelar @2022
 * TRAIT LOG MODEL
 * Trait ini digunakan jika ingin membuat log pada setiap aktifitas yang mempengaruhi model yang anda gunakan
 * Cara penggunaan, masukan perintah "use LogModel;" pada model anda
 *
 * Jika anda tidak ingin mengcapture aktifitas pada suatu field tertentu,
 * anda dapat menambahkan protected variable pada model dengan nama variabel "protected $skipLogFields"
 *
 * Contoh: $skipLogFields = ['last_module', 'last_url', 'last_active', 'property'];
 * dari contoh diatas diartikan bahwa:
 * jika terjadi perubahan hanya pada field ('last_module', 'last_url', 'last_active', 'property')
 * maka capture log akan di skip, atau tidak akan masuk kedalam log.
 *
 * Example:
 *   class User extends Model
 *   {
 *       use SoftDeletes;
 *       use LogModel;
 *
 *       protected $table = 'auth_user';
 *
 *       protected $skipLogFields = ['last_module', 'last_url', 'last_active', 'property'];
 *       .....
 *   }
 */

trait LogModel {
    use PivotEventTrait;

    private $model;

    public static function bootLogModel()
    {
        static::updated(function ($model) {
             if(!$changes = $model->getChanges()) {
                 return;
             }
             $noupdate = ['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'];

             if(isset($model->skipLogFields) && $model->skipLogFields){
                $noupdate = array_merge($noupdate, $model->skipLogFields);
             }

             $return = false;
             foreach ($changes AS $key => $value){
                 if(!in_array($key, $noupdate)) {
                     $return = true;
                     break;
                 }
             }

             if($return) static::storeLog($model, 'UPDATED');
        });

        static::created(function ($model) {
            static::storeLog($model, 'CREATED');
        });

        static::deleted(function($model){
            if($model->forceDeleting){
                static::storeLog($model, 'FORCEDELETE');
            }
            else {
                static::storeLog($model, 'DELETED');
            }
        });

        static::restoring(function($model){
            static::storeLog($model, 'RESTORE');
        });

        static::pivotAttached(function ($model, $relation, $pivotIds) {
            static::storeLog($model, 'ATTACH', $relation, $pivotIds);
        });

        static::pivotDetached(function ($model, $relation, $pivotIds) {
            static::storeLog($model, 'DETACH', $relation, $pivotIds);
        });

    }

    public static function storeLog($model, $action, $relation = null, $pivotIds=null){
        $userId = null;
        if($user = Auth::user()) $userId = $user->id;

        $oldValues = null;
        if($action != 'CREATED') $oldValues = $model->getOriginal();

        $newValues = null;
        if($action == 'CREATED')  $newValues = $model->getAttributes();
        elseif ($action == 'UPDATED') $newValues = $model->getChanges();

        if($pivotIds) $newValues = $pivotIds;

        $tableName = $model->getTable();

        $input = [
            'user_id' => $userId,
            'action' => $action,
            'model' => get_class($model),
            'table' => $relation ? "$tableName.$relation" : $tableName,
            'old_value' => !empty($oldValues) ? json_encode($oldValues) : null,
            'new_value' => !empty($newValues) ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
        ];

        LogActivity::create($input);
    }
}

