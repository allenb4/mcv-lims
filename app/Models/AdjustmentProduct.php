<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjustmentProduct extends Model
{
    public $guarded=[];

    //type 
    //1 => In
    //2 => Out

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id')->withTrashed();
    }

    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class,'adjustment_id','id');
    }
    
}
