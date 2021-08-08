<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource as UserResource;
use App\Models\User; 
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully');
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $provider='default')
    {
        // switch ($provider) {
        //     case 'google':
        //         $input['oauth_type'] = $provider;
        //         break;
            
        //     case 'github':
                
        //         $input['oauth_type'] = $provider;
        //         break;
        
        //     case 'linkedin':
                
        //         $input['oauth_type'] = $provider;
        //         break;
                        
        //     default:
            
        //         $input = $request->all(); 
        //         $validator = Validator::make($input, [
        //             'email' => 'required', 
        //             'password' => 'required', 
        //         ]);
                
        //         if($validator->fails()){
        //             return $this->sendError('Validation Error.', $validator->errors());       
        //         }

        //         $input['oauth_type'] = $provider;
        //         $input['password'] = bcrypt($input['password']);
        //         $user = User::create($input);
        //         break;
        // }
            
        $input = $request->all(); 
        $validator = Validator::make($input, [
            'email' => 'required', 
            'password' => 'required', 
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input['oauth_type'] = $provider;
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

   
        return $this->sendResponse(new UserResource($user), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    public function destroy(User $user)
    {
        $user->delete();
    }
}
