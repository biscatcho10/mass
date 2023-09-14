<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Http\Resources\UserResource;



class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService){     
        $this->userService = $userService;
    }
    
    public function register(RegisterRequest $request){
        $user = $this->userService->createUser($request->all());
        return response()->json([
            'status' =>true,
            'token' => $user->createToken('API TOKEN')->accessToken,
            'message' => 'User Logged In Successfully' ,
            'user' => UserResource::make($user),
            
        ],200);
    }

    public function login(LoginRequest $request){
        $exist = $this->userService->userExist($request->only(['mobile', 'password']));
        $this->saveToken($exist, $request->token_device);
        return response()->json([
                'status' =>true,
                'token' => $exist->createToken('API TOKEN')->accessToken,
                'message' => 'User Logged In Successfully' ,
                'user' => UserResource::make($exist),
                
            ],200);        
    }
    
    public function logout(){
        $this->userService->logout();
        return response()->json([
            'status' =>true,
            'message' => 'logout successfully '
        ],200);
    }
      
    public function deleteAccount(){
        $this->userService->deleteAcc();
        return response()->json([
            'status' =>true,
            'message' => 'delete successfully '
        ],200);
    }
    

    protected function saveToken($user , $token_device){    
        $user->fcm_token = $token_device;
        $user->save();
        return true;
    }
}
