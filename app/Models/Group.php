<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Group extends Model
{
    use LogsActivity;

    public $guarded=[];

    public function all_tests()
    {
        return $this->hasMany(GroupTest::class,'group_id','id');
    }

    public function tests()
    {
        return $this->hasMany(GroupTest::class,'group_id','id')
                    ->where('package_id',null);
    }

    public function all_cultures()
    {
        return $this->hasMany(GroupCulture::class,'group_id','id');
    }
    
    public function cultures()
    {
        return $this->hasMany(GroupCulture::class,'group_id','id')
                    ->where('package_id',null);
    }

    public function packages()
    {
        return $this->hasMany(GroupPackage::class,'group_id','id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id')->withTrashed();
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->withTrashed();
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id','id')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(GroupPayment::class,'group_id','id');
    }

    public function consumptions()
    {
        return $this->hasMany(ProductConsumption::class,'group_id','id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class,'created_by','id')->withTrashed();
    }

    public function signed_by_user()
    {
        return $this->belongsTo(User::class,'signed_by','id')->withTrashed();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Group test was {$eventName}";
    }
    
}
