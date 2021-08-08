<?php

namespace App\Http\Controllers\AuthSocialite;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User; 
use Laravel\Socialite\Facades\Socialite;

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
                    'oauth_type' => $provider,
                    'api_token' => $input->token,
                    'profile_photo_url' => $input->avatar,
                    'password' => bcrypt('passer@123')
                ]);
            } catch (\Exception $e) {
                dd($e);
            }

            return $this->sendResponse(new UserResource($user), 'User created successfully.');

        }

        return $this->sendError('GithubController Error.', $provider);       

    }
}
