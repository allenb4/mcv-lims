<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CultureComment extends Model
{
    public $guarded=[];

    public function culture()
    {
        return $this->belongsTo(Culture::class,'culture','id');
    }
}
