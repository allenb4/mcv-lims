<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public $guarded=[];

    public function products()
    {
        return $this->hasMany(TransferProduct::class,'transfer_id','id');
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
