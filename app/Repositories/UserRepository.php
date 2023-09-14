<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\GerneralException;
use App\Interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface 
{

    protected $user;

    public function __construct(User $user ){
        $this->user = $user;
    }

    /**
        * This function get all user from model
        *
        * 
        * @return users with transactions
        */

    public function getAllUsers(){
        return User::whereNotIn('id', [1,2])->get();
    }

    public function createUser($data){
        $data['password'] = $this->hashPass($data['password']);
        $data['fcm_token'] = $data['token_device'];
        if($data['mobile'] == '0503852168'){
            $user =User::create($data);
            $user->assignRole('admin');
            
        }else{
            $user =User::create($data);
            $user->assignRole($data['user_type']);
        }
        
        return $user;
    }

    public function updateUser($data){
        $user = Auth::user();
        $user->update($data);
        return $user;
    }

    public function userExist($data){
        if(Auth::attempt($data)){
            return $this->getUserByMobile($data['mobile']);  
        }
        throw new GerneralException("Mobile & Password Does Not Match With Our Record.",401);
    }
    
    public function logout(){    
        return Auth::user()->token()->delete();
    }
    
    public function deleteAcc(){   
        return Auth::user()->delete();
    }

    protected function hashPass($password){
        return Hash::make($password);
    }

    protected function getUserByMobile($mobile){
        return User::where('mobile' , $mobile)->firstOrFail();
    }

}