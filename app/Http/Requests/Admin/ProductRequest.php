<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        if(isset($this->product))
        {
            return [
                'name'=>[
                    'required',
                    Rule::unique('products')->ignore($this->product)->whereNull('deleted_at')
                ],
                'sku'=>[
                    'required',
                    Rule::unique('products')->ignore($this->product)->whereNull('deleted_at')
                ],
            ];
        }
        else{
            return [
                'name'=>[
                    'required',
                    Rule::unique('products')->whereNull('deleted_at')
                ],
                'sku'=>[
                    'required',
                    Rule::unique('products')->whereNull('deleted_at')
                ],
            ];
        }
        
    }
}
