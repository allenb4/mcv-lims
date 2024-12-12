<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Visit extends Model
{
    use LogsActivity;

    public $guarded=[];

    public $appends=['since'];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id')->withTrashed();
    }

    public function visit_tests()
    {
        return $this->hasMany(VisitTest::class,'visit_id','id');
    }

    public function tests()
    {
        return $this->hasMany(VisitTest::class,'visit_id','id')
                    ->where('testable_type','App\Models\Test');
    }

    public function cultures()
    {
        return $this->hasMany(VisitTest::class,'visit_id','id')
                    ->where('testable_type','App\Models\Culture');
    }

    public function packages()
    {
        return $this->hasMany(VisitTest::class,'visit_id','id')
                    ->where('testable_type','App\Models\Package');
    }

    public function getSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Visit was {$eventName}";
    }
}
