<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Culture extends Model
{
    use SoftDeletes;
    use LogsActivity;
    
    public $guarded=[];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    
    public function culture_price()
    {
        return $this->hasOne(CulturePrice::class,'culture_id','id')
                    ->where('branch_id',session('branch_id'));
    }

    public function prices()
    {
        return $this->hasMany(CulturePrice::class,'culture_id','id');
    }

    public function contract_prices()
    {
        return $this->morphMany(ContractPrice::class,'priceable');
    }

    public function comments()
    {
        return $this->hasMany(CultureComment::class,'culture_id','id')->orderBy('id','asc');
    }

    public function consumptions()
    {
        return $this->morphMany(TestConsumption::class,'testable')->orderBy('id','asc');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Culture was {$eventName}";
    }

}
