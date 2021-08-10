<?php

namespace App\Http\Controllers\Auths;

use Illuminate\Http\Request; 
use App\Http\Resources\UserResource as UserResource;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends BaseController
{ 
    /**
     * register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, $provider='default')
    {    
        $input = $request->all(); 

        $validator = Validator::make($input, [
            'email' => 'required', 
            'password' => 'required|min:8|unique:users'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input['oauth_type'] = $provider;
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        // Assign default rule to user
        $role = Role::where('name','guest')->first();
        if (empty($role)) {
            $role = Role::create(['name' => 'guest']);
        }
        $user->assignRole($role);
  
        return $this->sendResponse(new UserResource($user), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return $this->sendResponse($credentials, 'User login successfully.');
        }

        return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    } 

    // Logout method
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return $this->sendResponse([], 'User logout successfully.');
    }


}
