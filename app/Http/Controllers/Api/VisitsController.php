<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Response;
use App\Models\Visit;
use App\Models\Patient;
use Str;

class VisitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->has('patient_id'))
        {
            $validation=Response::validation($request,[
                'branch_id'=>'required',
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
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'email'=>[
                    'nullable',
                    'email',
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'national_id'=>[
                    'nullable',
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'passport_no'=>[
                    'nullable',
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'address'=>'nullable',
                'visit_date'=>'required|date_format:Y-m-d H:i',
                'visit_address'=>'required',
                'lat'=>'required',
                'lng'=>'required',
            ]);
        }
        else{
            $validation=Response::validation($request,[
                'branch_id'=>'required',
                'visit_date'=>'required|date_format:Y-m-d H:i',
                'visit_address'=>'required',
                'lat'=>'required',
                'lng'=>'required',
            ]);
        }
      
        if(!empty($validation))
        {
            return $validation;
        }
        
        if(!empty($request['patient_id']))
        {
            $patient=Patient::find($request['patient_id']);

            if(empty($patient))
            {
                return Response::response(400,'error','unknown patient_id');
            }
        }
        else{
            $patient=Patient::create([
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

            patient_code($patient['id']);
        }
        
        //create patient visit
        $visit=Visit::create([
            'branch_id'=>$request['branch_id'],
            'patient_id'=>$patient['id'],
            'lat'=>$request['lat'],
            'lng'=>$request['lng'],
            'visit_date'=>$request['visit_date'],
            'visit_address'=>$request['visit_address']
        ]);

        //tests
        if($request->has('tests'))
        {
            $request['tests'] = explode( ',',$request['tests']);

            if(count($request['tests']))
            {
                foreach($request['tests'] as $test)
                {
                    $visit->visit_tests()->create([
                        'testable_id'=>$test,
                        'testable_type'=>'App\Models\Test'
                    ]);
                }
            }
        }

        if($request->has('cultures'))
        {
            $request['cultures'] = explode( ',',$request['cultures']);

            if(count($request['cultures']))
            {
                foreach($request['cultures'] as $culture)
                {
                    $visit->visit_tests()->create([
                        'testable_id'=>$culture,
                        'testable_type'=>'App\Models\Culture'
                    ]);
                }
            }
        }

        if($request->has('packages'))
        {
            $request['packages'] = explode( ',',$request['packages']);

            if(count($request['packages']))
            {
                foreach($request['packages'] as $package)
                {
                    $visit->visit_tests()->create([
                        'testable_id'=>$package,
                        'testable_type'=>'App\Models\Package'
                    ]);
                }
            }
        }

        if($request->has('attach'))
        {
            //save file
            $data = explode( ',',$request['attach']);
            $extension=explode('/',mime_content_type($request['attach']))[1];
            $decoded = base64_decode($data[1]);
            //generte name
            $name=time().Str::random(4).'.'.$extension;
            file_put_contents("uploads/visits/".$name,$decoded);
            //save file name to record
            $visit->update(['attach'=>$name]);
        }

        return Response::response(200,'success',['visit'=>$visit]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
