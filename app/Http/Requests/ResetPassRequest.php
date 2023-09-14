<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ResetPassRequest extends FormRequest
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
            'password' => 'required',
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



