<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReasonController;
use App\Http\Controllers\Api\NotificationController;


Route::get('/test', function(){
    return 'hello';
});



Route::get('/welcome', function(){
    return request()->ip() . ' hello form auth api';
});
    

 
Route::post('register',[AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);  

Route::get('remove_account', [AuthController::class,'deleteAccount']);
//reset password
Route::post('password/email',[ResetPasswordController::class,'checkEmailExist']);
Route::post('password/code/check', [ResetPasswordController::class,'checkCode']);
Route::post('password/reset', [ResetPasswordController::class,'resetPass']);


// Route::post('store/task', [TaskController::class,'store']); 

Route::middleware('auth:api')->group( function () {
    Route::get('logout', [AuthController::class,'logout']);
    Route::get('users', [UserController::class, 'index'])->middleware('role:manager|admin'); 
    Route::post('profile', [UserController::class, 'updateProfile']);
    Route::get('profile', [UserController::class, 'showProfile']);
    //tasks
    
    Route::get('tasks', [TaskController::class, 'index']); 
    Route::post('tasks', [TaskController::class, 'create'])->middleware('role:manager|admin'); 
    
    Route::put('task/{id}', [TaskController::class, 'update']);
    Route::delete('task/{id}', [TaskController::class, 'destroy']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::get('task/{id}/mark', [TaskController::class, 'mark']); 
    Route::get('task/{id}/unmark', [TaskController::class, 'unmark']); 
    
    Route::get('filter/{status}', [TaskController::class, 'filterTasks']); 
    //Reasons..
    Route::get('reasons', [TaskController::class, 'get_task_reason'])->middleware('role:manager|admin'); 
    Route::post('reasons', [ReasonController::class, 'create']);
    Route::get('notifications', [NotificationController::class, 'index']);
});

