<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Response;
use Illuminate\Validation\Rule;
use App\Models\Patient;
use App\Models\Group;

class ProfileController extends Controller
{
    public function dashboard(Request $request)
    {
        $groups=Group::where('patient_id',$request->user()->id)->count();
        $pending_groups=Group::where('patient_id',$request->user()->id)->where('done',0)->count();
        $completed_groups=Group::where('patient_id',$request->user()->id)->where('done',1)->count();

        return Response::response(200,'success',[
            'groups'=>$groups,
            'pending_groups'=>$pending_groups,
            'completed_groups'=>$completed_groups
        ]);

    }

    public function update_profile(Request $request)
    {        
        $validation=Response::validation($request,[
            'name'=>[
                'required',
                Rule::unique('patients')->whereNull('deleted_at')
            ],
            'gender'=>[
                'required',
                Rule::in(['male','female']),
            ],
            'dob'=>'required|date_format:Y-m-d',
            'phone'=>[
                'nullable',
                Rule::unique('patients')->ignore($request->user()->id)->whereNull('deleted_at')
            ],
            'email'=>[
                'nullable',
                'email',
                Rule::unique('patients')->ignore($request->user()->id)->whereNull('deleted_at')
            ],
            'national_id'=>[
                'nullable',
                Rule::unique('patients')->ignore($request->user()->id)->whereNull('deleted_at')
            ],
            'passport_no'=>[
                'nullable',
                Rule::unique('patients')->ignore($request->user()->id)->whereNull('deleted_at')
            ],
            'address'=>'nullable'
        ]);

        if(!empty($validation))
        {
            return $validation;
        }

        $patient=Patient::where('id',$request->user()->id)->first();

        $patient->update([
            'name'=>$request['name'],
            'national_id'=>$request['national_id'],
            'passport_no'=>$request['passport_no'],
            'country_id'=>$request['country_id'],
            'phone'=>$request['phone'],
            'email'=>$request['email'],
            'gender'=>$request['gender'],
            'dob'=>$request['dob'],
            'address'=>$request['address'],
        ]);

        if($request->has('avatar')&&!empty($request['avatar']))
        {
            //save file
            $data = explode( ',',$request['avatar']);
            $extension=explode('/',mime_content_type($request['avatar']))[1];
            $decoded = base64_decode($data[1]);
            //generte name
            $name=time().$patient['id'].'.'.$extension;
            file_put_contents("uploads/patient-avatar/".$name,$decoded);
            //save file name to record
            $patient->update(['avatar'=>$name]);
        }

        $patient=Patient::where('id',$request->user()->id)->first();

        return Response::response(200,'success',['patient'=>$patient]);
    }

   



}
