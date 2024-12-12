<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturePrice extends Model
{
    public $guarded=[];

    public function culture()
    {
        return $this->belongsTo(Culture::class,'culture_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
