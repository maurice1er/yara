<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\BaseController;
use App\Http\Resources\DataResource;
use App\Models\Components\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Data::all();
        return $this->sendResponse($datas, 'Datas loaded successfully.');
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
            'name' => 'required|unique:datas'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data = Data::create($input); 
        return $this->sendResponse(new DataResource($data), 'Data created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Data::where('id',$id)->first(); 
        
        if(empty($data)){
            return $this->sendError('Data '.$id.' not found.');       
        }
        
        return $this->sendResponse(new DataResource($data), 'Data loaded successfully.');
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
        $data = Data::where('id',$id)->first(); 
        if(empty($data)){
            return $this->sendError('Data '.$id.' not found.');       
        }

        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:datas'    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $data->update($input);
        return $this->sendResponse(new DataResource($data), 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Data::where('id',$id)->first(); 
        if(empty($data)){
            return $this->sendError('Data '.$id.' not found.');       
        }
        $data->delete();
        return $this->sendResponse([], 'Data deleted successfully.');
    }
}
