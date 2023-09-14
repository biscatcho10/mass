<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TaskResource;


class ReasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[ 
            "id" => $this->id,
            "description"=> $this->description,
            "created_at"=> $this->created_at,
            "task"=> TaskResource::make($this->task),
            "auther"=> UserResource::make($this->auther),
            'audio' => $this->audio,
        ];
        
    }
}