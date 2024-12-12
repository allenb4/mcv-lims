<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        if(isset($request['id']))
        {
            return [
                'name'=>[
                    'required',
                    Rule::unique('patients')->ignore($request['id'])->whereNull('deleted_at')
                ],
                'gender'=>[
                    'required',
                    Rule::in(['male','female']),
                ],
                'dob'=>'required|date_format:Y-m-d',
                'phone'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($request['id'])->whereNull('deleted_at')
                ],
                'email'=>[
                    'nullable',
                    'email',
                    Rule::unique('patients')->ignore($request['id'])->whereNull('deleted_at')
                ],
                'national_id'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($request['id'])->whereNull('deleted_at')
                ],
                'passport_no'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($request['id'])->whereNull('deleted_at')
                ],
                'address'=>'nullable'
            ];
        }

        if($this->patient)
        {
            return [
                'name'=>[
                    'required',
                    Rule::unique('patients')->ignore($this->patient)->whereNull('deleted_at')
                ],
                'gender'=>[
                    'required',
                    Rule::in(['male','female']),
                ],
                'dob'=>'required|date_format:Y-m-d',
                'phone'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($this->patient)->whereNull('deleted_at')
                ],
                'email'=>[
                    'nullable',
                    'email',
                    Rule::unique('patients')->ignore($this->patient)->whereNull('deleted_at')
                ],
                'national_id'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($this->patient)->whereNull('deleted_at')
                ],
                'passport_no'=>[
                    'nullable',
                    Rule::unique('patients')->ignore($this->patient)->whereNull('deleted_at')
                ],
                'address'=>'nullable'
            ];
        }
        else{
            return [
                'name'=>[
                    'required',
                    Rule::unique('patients')->WhereNull('deleted_at')
                ],
                'gender'=>[
                    'required',
                    Rule::in(['male','female']),
                ],
                'dob'=>'required|date_format:Y-m-d',
                'phone'=>[
                    'nullable',
                    Rule::unique('patients')->WhereNull('deleted_at')
                ],
                'email'=>[
                    'nullable',
                    'email',
                    Rule::unique('patients')->WhereNull('deleted_at')
                ],
                'national_id'=>[
                    'nullable',
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'passport_no'=>[
                    'nullable',
                    Rule::unique('patients')->whereNull('deleted_at')
                ],
                'address'=>'nullable'
            ];
        }
        
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'dob'=>'date of birth',
        ];
    }
}
