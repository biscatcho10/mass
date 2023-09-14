<?php

namespace App\Interfaces;

interface TaskRepositoryInterface 
{
    public function getAllTasks();
    public function getTask($id);
    public function createTask($data);
    public function updateTask($data , $id);
    public function destoryTask($id);
    public function markTask($id); 
    public function unmarkTask($id); 
    public function getReasonTask();
    public function filterTaskByStatus($id);
    public function createResonForTask($data);
}