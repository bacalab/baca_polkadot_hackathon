<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{

    public static function updates($where, $params) {
        return self::query()->where($where)->update($params);
    }
}
