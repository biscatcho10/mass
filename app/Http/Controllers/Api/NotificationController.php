<?php

namespace App\Http\Controllers\Api;


use App\Models\NotificationLog;
use App\Http\Resources\NotificationResource;
use App\Http\Controllers\Api\BaseController as BaseController;

class NotificationController extends BaseController
{
    public function index(){

        $data = NotificationLog::all();
        return $this->sendResponse(NotificationResource::collection($data), 'notifcation list successfully.'); 
    }
}