<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Task;

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
        $data = [
            "id" => $this->id,
            "title" => $this->title,
            "type" => $this->type,
            "body" => $this->body,
            "task" => $this->task_id,
            // "user"=> UserResource::make($this->user)
        ];


        if ($this->type == 'pending' && $task = Task::find($this->task_id)) {
            $data['start_date'] = optional($task)->start_date;
            $data['end_date'] = optional($task)->end_date;
        }

        return $data;
    }
}
