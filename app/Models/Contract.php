<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contract extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $guarded=[];

    public function tests()
    {
        return $this->hasMany(ContractPrice::class,'contract_id','id')
                    ->where('priceable_type','App\Models\Test')
                    ->orderBy('id','asc');
    }

    public function cultures()
    {
        return $this->hasMany(ContractPrice::class,'contract_id','id')
                    ->where('priceable_type','App\Models\Culture')
                    ->orderBy('id','asc');
    }

    public function packages()
    {
        return $this->hasMany(ContractPrice::class,'contract_id','id')
                    ->where('priceable_type','App\Models\Package')
                    ->orderBy('id','asc');
    }

    public function prices()
    {
        return $this->hasMany(ContractPrice::class,'contract_id','id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Contract was {$eventName}";
    }

}
