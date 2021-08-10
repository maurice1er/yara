<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\BaseController;
use App\Http\Resources\WorkspaceResource;
use App\Models\Components\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkspaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $workspaces = Workspace::all();
        return $this->sendResponse($workspaces, 'Workspaces loaded successfully.');
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
            'name' => 'required|unique:workspaces'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $workspace = Workspace::create($input); 
        return $this->sendResponse(new WorkspaceResource($workspace), 'Workspace created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workspace = Workspace::where('id',$id)->first(); 
        
        if(empty($workspace)){
            return $this->sendError('Workspace '.$id.' not found.');       
        }
        
        return $this->sendResponse(new WorkspaceResource($workspace), 'Workspace loaded successfully.');
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
        $workspace = Workspace::where('id',$id)->first(); 
        if(empty($workspace)){
            return $this->sendError('Workspace '.$id.' not found.');       
        }

        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:workspaces'    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $workspace->update($input);
        return $this->sendResponse(new WorkspaceResource($workspace), 'Workspace updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workspace = Workspace::where('id',$id)->first(); 
        if(empty($workspace)){
            return $this->sendError('Workspace '.$id.' not found.');       
        }
        $workspace->delete();
        return $this->sendResponse([], 'Workspace deleted successfully.');
    }
}
