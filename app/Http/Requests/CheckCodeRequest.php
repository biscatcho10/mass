<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CheckCodeRequest extends FormRequest
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
            'code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'code.required'=> 'code is required', 
        ];
    }

    

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        
    }
}



