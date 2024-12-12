<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
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
            'address'=>'nullable'
        ];
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
