<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $guarded=[];

    public function tests()
    {
        return $this->hasMany(Test::class,'category_id','id');
    }

    public function cultures()
    {
        return $this->hasMany(Culture::class,'category_id','id');
    }
}
