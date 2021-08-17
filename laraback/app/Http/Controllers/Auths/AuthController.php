<?php

namespace App\Http\Controllers\Auths;

use Illuminate\Http\Request; 
use App\Http\Resources\UserResource as UserResource;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends BaseController
{

    public function register(Request $request, $provider='default')
    {    
        $input = $request->all(); 

        $validator = Validator::make($input, [
            'email' => 'email|required|unique:users', 
            'password' => 'required|confirmed|min:8'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input['oauth_provider'] = $provider;
        $input['password'] = Hash::make($input['password']);
        // create user
        $user = User::create($input);
        $user->createToken('authToken')->accessToken;
        $user->sendEmailVerificationNotification();
        // $user = User::create($input)->sendEmailVerificationNotification();
         
        // create token if user email verify
        if(!$user){
            return $this->sendError('Unauthorised.', ['error'=>auth()->user()],401);
        }
        $accessToken = $user->createToken('authToken')->accessToken;
 
        // Assign default rule to user
        $role = Role::where('name','guest')->first();
        if (empty($role)) {
            $role = Role::create(['name' => 'guest']);
        }
        $user->assignRole($role);
  
        return $this->sendResponse(['user' => new UserResource($user), 'token' => $accessToken], 'User created successfully; verify your email address.');
    }

    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);
   
        // return ['info' => auth()->attempt($credentials)]; true or false
        if (!auth()->attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }

        $user = Auth::user();

        // check if user email address is verified: true or false
        if (!$user->hasVerifiedEmail()) {
            return $this->sendError('Unauthorised.', ['error'=>'Your email address is not verified'], 401);
        }
        
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        Session::put('api_token', $accessToken);

        return $this->sendResponse(['user' => $user, 'token' => $accessToken], 'User login successfully.');
    }

    // Logout method
    public function logout() {
        Session::flush();
        Auth::logout();
        return $this->sendResponse([], 'User logout successfully.');
    }


}
