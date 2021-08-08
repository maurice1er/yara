<?php

namespace App\Http\Controllers\AuthSocialite;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User; 
use Laravel\Socialite\Facades\Socialite;

class LinkedinController extends BaseController
{
    public function hangleLinkedinRedirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function hangleLinkedinCallback($provider="linkedin")
    {
        $u = Socialite::driver('linkedin')->user();
        $userLinkedin = User::where("oauth_id", $u->id)->first();

        dd($u);
        if (isset($userLinkedin)) { 
            return $this->sendResponse(new UserResource($userLinkedin), 'User loaded successfully.');
        } else { 
            $input = $u; 
          
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

        return $this->sendError('LinkedinController Error.', $provider);       

    }
}
