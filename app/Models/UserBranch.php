<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    public $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
