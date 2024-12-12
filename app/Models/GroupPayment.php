<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPayment extends Model
{
    public $guarded=[];

    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id','id');
    }
}
