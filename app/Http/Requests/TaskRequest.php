<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => 'required',
            'assign_to' =>  'required|exists:users,id',
            'start_date' => 'required',
            'end_date' => 'required',
            "attachments"    => "array",
            // "audio" => 'nullable|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,m4a,mp4a',
            // "attachments.*"  => "nullable|mimes:doc,pdf,docx,zip",
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



