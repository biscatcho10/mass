<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService  
{
    protected $taskRepo;

    public function __construct (TaskRepository $taskRepo) {
        $this->taskRepo = $taskRepo;
    }


    public function getAllTasks(){        
        return $this->taskRepo->getAllTasks();
    }

    public function getTask($id){        
        return $this->taskRepo->getTask($id);
    }

    public function createTask($request){     
        return $this->taskRepo->createTask($request);
    }

    public function updateTask($request ,$id){
        return $this->taskRepo->updateTask($request , $id);
    }

    public function destoryTask($id){        
        return $this->taskRepo->destoryTask($id);
    }

    public function markTask($id){        
        return $this->taskRepo->markTask($id);
    }

    public function unmarkTask($id){    
        return $this->taskRepo->unmarkTask($id);
    }

    public function getReasonTask(){        
        return $this->taskRepo->getReasonTask();
    }

    public function filterTaskByStatus($id){        
        return $this->taskRepo->filterTaskByStatus($id);
    }

    public function createResonForTask($data){        
        return $this->taskRepo->createResonForTask($data);
    }
   
}
 