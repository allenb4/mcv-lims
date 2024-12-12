<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestPrice extends Model
{
    public $guarded=[];

    public function test()
    {
        return $this->belongsTo(Test::class,'test_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
