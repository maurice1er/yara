<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\RuleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RuleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return $this->sendResponse($roles, 'Rules loaded successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:roles'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $role = Role::create($input); 
        return $this->sendResponse(new RuleResource($role), 'Rule created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::where('id',$id)->first(); 
        
        if(empty($role)){
            return $this->sendError('Rule '.$id.' not found.');       
        }
        
        return $this->sendResponse(new RuleResource($role), 'Rule loaded successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::where('id',$id)->first(); 
        if(empty($role)){
            return $this->sendError('Rule '.$id.' not found.');       
        }

        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:roles'    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $role->update($input);
        return $this->sendResponse(new RuleResource($role), 'Rule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::where('id',$id)->first(); 
        if(empty($role)){
            return $this->sendError('Rule '.$id.' not found.');       
        }
        $role->delete();
        return $this->sendResponse([], 'Rule deleted successfully.');
    }
    
}
