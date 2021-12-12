<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded=[];

    const TYPE_LIKE = "like";
    const TYPE_UNLIKE = "unlike";
    const LIKE = 1;
    const UNLIKE = 0 ;
    const IS_TOP_VOTE =1;

    public function category()
    {
        return $this->hasOne(Category::class, "id", "cate");
    }
}
