<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $guarded=[];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function tests()
    {
        return $this->hasMany(PackageTest::class,'package_id','id')
                    ->where('testable_type','App\Models\Test');
    }

    public function cultures()
    {
        return $this->hasMany(PackageTest::class,'package_id','id')
                    ->where('testable_type','App\Models\Culture');
    }

    public function package_price()
    {
        return $this->hasOne(PackagePrice::class,'package_id','id')
                    ->where('branch_id',session('branch_id'));
    }

    public function contract_prices()
    {
        return $this->morphMany(ContractPrice::class,'priceable');
    }

    public function prices()
    {
        return $this->hasMany(PackagePrice::class,'package_id','id');
    }
}
