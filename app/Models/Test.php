<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Test extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $guarded=[];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    
    public function components()
    {
        return $this->hasMany(Test::class,'parent_id','id')->orderBy('id','asc');
    }

    public function sub_analyses()
    {
        return $this->hasMany(Test::class,'parent_id','id')->where('separated',1);
    }

    public function options()
    {
        return $this->hasMany(TestOption::class,'test_id','id');
    }

    public function test_price()
    {
        return $this->hasOne(TestPrice::class,'test_id','id')
                    ->where('branch_id',session('branch_id'));
    }

    public function prices()
    {
        return $this->hasMany(TestPrice::class,'test_id','id');
    }

    public function contract_prices()
    {
        return $this->morphMany(ContractPrice::class,'priceable');
    }

    public function reference_ranges()
    {
        return $this->hasMany(TestReferenceRange::class,'test_id','id');
    }

    public function comments()
    {
        return $this->hasMany(TestComment::class,'test_id','id')->orderBy('id','asc');
    }

    public function consumptions()
    {
        return $this->morphMany(TestConsumption::class,'testable')->orderBy('id','asc');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Test was {$eventName}";
    }


}
