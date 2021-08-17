<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return $this->sendResponse($permissions, 'Permissions loaded successfully.');

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
            'name' => 'required|unique:permissions'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $permission = Permission::create($input); 
        return $this->sendResponse(new PermissionResource($permission), 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::where('id',$id)->first(); 
        
        if(empty($permission)){
            return $this->sendError('Permission '.$id.' not found.');       
        }
        
        return $this->sendResponse(new PermissionResource($permission), 'Permission loaded successfully.');
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
        $permission = Permission::where('id',$id)->first(); 
        if(empty($permission)){
            return $this->sendError('Permission '.$id.' not found.');       
        }

        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:permissions'    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $permission->update($input);
        return $this->sendResponse(new PermissionResource($permission), 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::where('id',$id)->first(); 
        if(empty($permission)){
            return $this->sendError('Permission '.$id.' not found.');       
        }
        $permission->delete();
        return $this->sendResponse([], 'Permission deleted successfully.');
    }
}
