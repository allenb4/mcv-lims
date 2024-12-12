<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Requests\Patient\ProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $patient=Patient::findOrFail(auth()->guard('patient')->user()['id']);
        return view('patient.profile.edit',compact('patient'));
    }

    public function update(ProfileRequest $request)
    {
        $patient=Patient::find(auth()->guard('patient')->user()->id);

        $patient->update($request->except('_token','avatar','age_unit','age'));

        $patient->update([
            'country_id'=>($request->has('country_id'))?$request['country_id']:''
        ]);

        if($request->hasFile('avatar'))
        {
            $avatar=$request->file('avatar');
            $name=time().$patient['id'].'.png';
            $avatar->move('uploads/patient-avatar/',$name);
            $patient->update([
                'avatar'=>$name
            ]);
        }
        
        session()->flash('success',__('Profile updated successfully'));

        return redirect()->back();
    }
}
