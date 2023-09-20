<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TaskResource;


class NotificationResource extends JsonResource
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
            "title" => $this->title,
            "type"=> $this->type,
            "body"=> $this->body,
            "task"=> $this->task_id,
            // "user"=> UserResource::make($this->user)

        ];

    }
}
