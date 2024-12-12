<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    public $guarded=[];
    
    public function purchases()
    {
        return $this->hasMany(Purchase::class,'supplier_id','id');
    }

    public function getTotalAttribute()
    {
        return $this->purchases()->sum('total');
    }

    public function getPaidAttribute()
    {
        return $this->purchases()->sum('paid');
    }

    public function getDueAttribute()
    {
        return $this->purchases()->sum('due');
    }

}
