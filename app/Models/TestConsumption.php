<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestConsumption extends Model
{
    public $guarded=[];

    public function testable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
