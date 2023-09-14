<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'=> 'required|string|max:255',
            'mobile'=> 'required|numeric|digits:10|unique:users,mobile',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required',
            'position'=> 'required',
            'user_type' => 'required|in:employee,manager',
            'token_device' => 'required'
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



