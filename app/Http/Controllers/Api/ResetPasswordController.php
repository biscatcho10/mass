<?php

namespace App\Http\Controllers\Api;

use Mail;
use App\Models\User;
use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\ResetPassRequest;



class ResetPasswordController extends BaseController
{

    public function checkEmailExist(CheckEmailRequest $request){
        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();
        $data = $request->all();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        // dd($data);
        $codeData = ResetCodePassword::create($data);
        

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response(['message' => trans('passwords.sent')], 200);
    }

    public function checkCode(CheckCodeRequest $request)
    {
       
        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if (is_null($passwordReset)) {
            
            return response(['message' => 'passwords.code_is_invalid'], 422);
        }
        
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => 'code_is_expire'], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => 'passwords.code_is_valid'
        ], 200);
    }

    

    public function resetPass(ResetPassRequest $request)
    {
        

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if (is_null($passwordReset)) {
            
            return response(['message' =>'code_is_invalid'], 422);
        }
        
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => 'code_is_expire'], 422);
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update(['password'=> bcrypt($request->password)]);

        // delete current code 
        // $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}