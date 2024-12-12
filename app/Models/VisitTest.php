<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitTest extends Model
{
    public $guarded=[];

    public function visit()
    {
        return $this->belongsTo(Visit::class,'visit_id','id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class,'testable_id','id');
    }

    public function culture()
    {
        return $this->belongsTo(Culture::class,'testable_id','id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'testable_id','id');
    }
}
