<?php

namespace App\Http\Controllers\Auths;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
{
    //
    public function forgot(Request $request) {

        $input = $request->all(); 

        $validator = Validator::make($input, [
            'email' => 'email|required', 
        ]);
        if($validator->fails()){return $this->sendError('Validation Error.', $validator->errors());}

        Password::sendResetLink($input);

        return response()->json(["msg" => 'Reset password link sent on your email id.']);
    }

    // 
    public function reset() {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
}
