<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function getAllUsers();
    public function createUser($data);
    public function userExist($data);
    public function updateUser($data);   
    public function logout();
    public function deleteAcc();

    

}
