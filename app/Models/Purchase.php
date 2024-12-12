<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public $guarded=[];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id')->withTrashed();
    }

    public function products()
    {
        return $this->hasMany(PurchaseProduct::class,'purchase_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class,'purchase_id','id');
    }
}
