<?php

namespace App\Http\Controllers\Auths\AuthSocialite;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User; 
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleController extends BaseController
{
    public function hangleGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function hangleGoogleCallback($provider="google")
    {
        $u = Socialite::driver('google')->user();
        $userGoogle = User::where("oauth_id", $u->id)->first();

         
        if (isset($userGoogle)) {
            // Mise à jour des informations de l'utilisateur
            // auth()->login($userGoogle);
            return $this->sendResponse(new UserResource($userGoogle), 'User loaded successfully.');
        } else {
            # 3. Si l'utilisateur n'existe pas, on l'enregistre
            // Enregistrement de l'utilisateur
            $input = $u; 
            
            // dd($input);
            // $validator = Validator::make($input, [
            //     'name' => 'required',
            //     'email' => 'required',   
            //     'id' => 'required', 
            //     'token' => 'required', 
            //     'avatar' => 'required'
            // ]);

            // +token: "ya29.a0ARrdaM8q5mDtvdxvlHCnZxqS91sZIh8T9dCX8_G7i8KA3eQdNSg8vtUHn51QAw0KHYn1RdtfJjQyaRG7qZZQTJc5IL6HK-j7197u1iLwrLd77eUpg6p9ysheUEEOwVy9Hyc4JR5lOlJOG63z8s73iIQ5A ▶"
            // +refreshToken: null
            // +expiresIn: 3599
            // +id: "104044221395761162415"
            // +nickname: null
            // +name: "Armel Drey"
            // +email: "armeldrey@gmail.com"
            // +avatar: "https://lh3.googleusercontent.com/a/AATXAJyH4gg6A-H3O1_grA3s9vvoND7qW5APPCrWwsgS=s96-c"
            // +user: array:11 [▼
            //   "sub" => "104044221395761162415"
            //   "name" => "Armel Drey"
            //   "given_name" => "Armel"
            //   "family_name" => "Drey"
            //   "picture" => "https://lh3.googleusercontent.com/a/AATXAJyH4gg6A-H3O1_grA3s9vvoND7qW5APPCrWwsgS=s96-c"
            //   "email" => "armeldrey@gmail.com"
            //   "email_verified" => true
            //   "locale" => "fr"
            //   "id" => "104044221395761162415"
            //   "verified_email" => true
            //   "link" => null
            // ]
            // +"avatar_original": "https://lh3.googleusercontent.com/a/AATXAJyH4gg6A-H3O1_grA3s9vvoND7qW5APPCrWwsgS=s96-c"
            
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
                    'password' => bcrypt('passer@123')
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

        return $this->sendError('GoogleController Error.', $provider);       

    }
}
