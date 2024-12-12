<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractPrice extends Model
{
    public $guarded=[];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public function priceable()
    {
        return $this->morphTo();
    }
}
