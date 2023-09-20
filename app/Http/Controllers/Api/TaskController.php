<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\TaskResource;
use App\Http\Resources\ReasonResource;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends BaseController
{

    protected $taskService;

    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function index(){
        $data = $this->taskService->getAllTasks();
        return $this->sendResponse(TaskResource::collection($data), 'tasks list successfully.');
    }

    public function show($id){
        $data = $this->taskService->getTask($id);
        return $this->sendResponse(TaskResource::make($data), 'tasks list successfully.');
    }

    public function create(TaskRequest $request){
        $data = $this->taskService->createTask($request->all());
        return $this->sendResponse(TaskResource::make($data), 'tasks craeted successfully.');

    }

    public function update(UpdateTaskRequest $request, $id){
        $data = $this->taskService->updateTask($request['status'],$id);
        return $this->sendResponse(TaskResource::make($data), 'task updated successfully.');
    }

    public function destroy($id) {
        $data = $this->taskService->destoryTask($id);
        return $this->sendResponse($data, 'task deleted successfully.');
    }

    public function mark($id){
        $data = $this->taskService->markTask($id);
        return $this->sendResponse(TaskResource::make($data), 'tasks marked successfully.');
    }

    public function unmark($id){
        $data = $this->taskService->unmarkTask($id);
        return $this->sendResponse(TaskResource::make($data), 'tasks unmarked successfully.');
    }

    public function get_task_reason(){
        $data = $this->taskService->getReasonTask();
        return $this->sendResponse(ReasonResource::collection($data)->resolve(), 'tasks reasons list successfully.');
    }

    public function filterTasks($status){
        $data = $this->taskService->filterTaskByStatus($status);
        return $this->sendResponse(TaskResource::collection($data), 'tasks list successfully.');
    }
}
