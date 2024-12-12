<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'date'=>'required|date_format:Y-m-d',
            'from_branch_id'=>'required',
            'to_branch_id'=>'required',
            'products'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'products.required'=>__('Please select at least one product')
        ];
    }
}
