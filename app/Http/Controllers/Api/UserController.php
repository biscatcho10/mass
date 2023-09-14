<?php
   
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Api\BaseController as BaseController;
   
class UserController extends BaseController
{
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @param  userService  $users
     * @return void
     */

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * Display a listing of the User combine transactions.
     *
     * @return JsonResponse
     */

    public function index(){
        $users = $this->userService->getAllUsers(); 
        return $this->sendResponse(UserResource::collection($users), 'Users list successfully.');
    }
    
    public function show($id){
        $user = $this->userService->getUser($id); 
        return $this->sendResponse(UserResource::make($user), 'User display  successfully.');
    }

    public function showProfile(UpdateUserRequest $request){
        return $this->sendResponse(UserResource::make(Auth::user()), 'User display  successfully.');
    }

    public function updateProfile(UpdateUserRequest $request){
        $user = $this->userService->updateUser($request->all()); 
        return $this->sendResponse(UserResource::make($user), 'User updated successfully.');
    }

}