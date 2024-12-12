<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'branch_id'=>'required',
            'products'=>'required',
            'subtotal'=>'required|numeric',
            'tax'=>'required|numeric',
            'total'=>'required|numeric',
            'paid'=>'required|numeric',
            'due'=>'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'products.required'=>__('Please select at least one product')
        ];
    }
}
