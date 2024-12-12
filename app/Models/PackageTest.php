<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageTest extends Model
{
    public $guarded=[];

    public function test()
    {
        return $this->belongsTo(Test::class,'testable_id','id');
    }

    public function culture()
    {
        return $this->belongsTo(Culture::class,'testable_id','id');
    }
}
