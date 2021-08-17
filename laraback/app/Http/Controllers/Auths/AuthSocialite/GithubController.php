<?php

namespace App\Http\Controllers\Auths\AuthSocialite;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GithubController extends BaseController
{
    public function hangleGithubRedirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function hangleGithubCallback($provider="github")
    {
        $u = Socialite::driver('github')->user();
        $userGithub = User::where("oauth_id", $u->id)->first();
 
        if (isset($userGithub)) { 
            return $this->sendResponse(new UserResource($userGithub), 'User loaded successfully.');
        } else { 
            $input = $u; 
            
            // dd($input);
             
            // if($validator->fails()){
            //     return $this->sendError('Validation Error.', $validator->errors());       
            // }
            
            try {
                $user = User::create([
                    'name' => $input->name,
                    'email' => $input->email,
                    'oauth_id' => $input->id,
                    'oauth_provider' => $provider,
                    'api_token' => $input->token,
                    'profile_photo_url' => $input->avatar,
                    'password' => Hash::make('passer@123')
                ]);
                // crate token
                $accessToken = $user->createToken('authToken')->accessToken; 
                
                // Assign default rule to user
                $role = Role::where('name','guest')->first();
                if (empty($role)) {
                    $role = Role::create(['name' => 'guest']);
                }
                $user->assignRole($role);
            } catch (\Exception $e) {
                dd($e);
            }

            return $this->sendResponse(['user' => new UserResource($user), 'token' => $accessToken], 'User created successfully.');
            // return $this->sendResponse(new UserResource($user), 'User created successfully.');

        }

        return $this->sendError('GithubController Error.', $provider);       

    }
}
