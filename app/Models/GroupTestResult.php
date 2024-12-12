<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\TestReferenceRange;
class GroupTestResult extends Model
{
    public $guarded=[];

    public function component()
    {
        return $this->belongsTo(Test::class,'test_id','id')->withTrashed();
    }

    public function group_test()
    {
        return $this->belongsTo(GroupTest::class,'group_test_id','id');
    }

    public function reference_range()
    {
        $patient=Patient::find($this->group_test->group->patient_id);

        if(isset($patient))
        {
            $reference_range=TestReferenceRange::where('test_id',$this->test_id)
            ->where('age_from_days','<=',$patient['age_days'])
            ->where('age_to_days','>=',$patient['age_days'])
            ->where(function($query)use($patient){
                return $query->where('gender',$patient['gender'])
                            ->orWhere('gender','both');
            })
            ->first();

            return $reference_range;
        }
    }
}
