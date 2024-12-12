<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferProduct extends Model
{
    public $guarded=[];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class,'transfer_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id')->withTrashed();
    }

    public function from_branch()
    {
        return $this->belongsTo(Branch::class,'from_branch_id','id')->withTrashed();
    }

    public function to_branch()
    {
        return $this->belongsTo(Branch::class,'to_branch_id','id')->withTrashed();
    }
}
