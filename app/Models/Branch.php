<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use SoftDeletes;
    use LogsActivity;
    
    public $guarded=[];

    public function tests()
    {
        return $this->hasMany(TestPrice::class,'branch_id','id');
    }

    public function cultures()
    {
        return $this->hasMany(CulturePrice::class,'branch_id','id');
    }

    public function packages()
    {
        return $this->hasMany(PackagePrice::class,'branch_id','id');
    }

    public function user_branches()
    {
        return $this->hasMany(UserBranch::class,'branch_id','id');
    }

    public function products()
    {
        return $this->hasMany(BranchProduct::class,'branch_id','id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Antibiotic was {$eventName}";
    }
}
