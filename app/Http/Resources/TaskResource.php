<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
 
class TaskResource extends JsonResource
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
            'title' => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "assign_to"=> $this->assign,
            "start_date"=> $this->start_date,
            "end_date"=> $this->end_date,
            "created_by"=> $this->auther,
            'attachments' => $this->attachment,
            'audio' => $this->audio,
            'is_mark' => $this->is_mark ?? False,
            
            
            
        ];
        
    }
    
}