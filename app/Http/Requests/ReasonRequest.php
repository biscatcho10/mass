<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReasonRequest extends FormRequest
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
            'task_id' => 'required|exists:tasks,id',
            'description' => 'required',
            "audio" => 'nullable|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,m4a,mp4a',
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



