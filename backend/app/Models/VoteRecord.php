<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteRecord extends Model
{
    protected $guarded = [];


    public function article()
    {
        return $this->hasOne(Article::class, "id", "aid");
    }

}
