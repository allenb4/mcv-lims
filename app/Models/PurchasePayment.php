<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    public $guarded=[];
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id','id')->withTrashed();
    }
}
