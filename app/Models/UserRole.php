<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
