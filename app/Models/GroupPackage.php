<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPackage extends Model
{
    public $guarded=[];

    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id','id');
    }

    public function tests()
    {
        return $this->hasMany(GroupTest::class,'package_id','id');
    }

    public function cultures()
    {
        return $this->hasMany(GroupCulture::class,'package_id','id');
    }
}
