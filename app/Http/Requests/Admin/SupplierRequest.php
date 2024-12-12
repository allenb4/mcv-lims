<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        if(isset($this->supplier))
        {
            return [
                'name'=>[
                    'required',
                    Rule::unique('suppliers')->ignore($this->supplier)->whereNull('deleted_at')
                ],
                'email'=>[
                    'required',
                    'email',
                    Rule::unique('suppliers')->ignore($this->supplier)->whereNull('deleted_at')
                ],
                'phone'=>[
                    'required',
                    Rule::unique('suppliers')->ignore($this->supplier)->whereNull('deleted_at')
                ],
            ];
        }
        else{
            return [
                'name'=>[
                    'required',
                    Rule::unique('suppliers')->whereNull('deleted_at')
                ],
                'email'=>[
                    'required',
                    'email',
                    Rule::unique('suppliers')->whereNull('deleted_at')
                ],
                'phone'=>[
                    'required',
                    Rule::unique('suppliers')->whereNull('deleted_at')
                ],
            ];
        }
        
    }
}
