<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ReasonResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\ReasonRequest;
use App\Services\TaskService;

class ReasonController extends BaseController
{
    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function create(ReasonRequest $request){
        $data = $this->taskService->createResonForTask($request->all()); 
        return $this->sendResponse(ReasonResource::make($data), 'reason send successfully.'); 
    }

}
