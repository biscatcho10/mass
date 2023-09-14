<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService  
{
    
    protected $userRepo;

    public function __construct (UserRepository $userRepo) {

        $this->userRepo = $userRepo;
    }


    public function getAllUsers(){
        
        return $this->userRepo->getAllUsers();
    }


    public function createUser($data){
        
        return $this->userRepo->createUser($data);
    }

    public function updateUser($data){
        return $this->userRepo->updateUser($data);
    }

    public function userExist($data){
        
        return $this->userRepo->userExist($data);
    }
    
    public function logout(){
        
     return $this->userRepo->logout();
    
    }
    
    public function deleteAcc(){
        
     return $this->userRepo->deleteAcc();
    }
    
    
    
}
 