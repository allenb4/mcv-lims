<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    public $guarded=[];

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
