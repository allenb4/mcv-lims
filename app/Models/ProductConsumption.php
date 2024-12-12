<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductConsumption extends Model
{
    public $guarded=[];

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id')->withTrashed();
    }

    public function testable()
    {
        return $this->morphTo()->withTrashed();
    }
}
