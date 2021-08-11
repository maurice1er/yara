<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlgorithmResource;
use App\Models\Components\Algorithm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlgorithmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $algorithms = Algorithm::all();
        return $this->sendResponse($algorithms, 'Algorithms loaded successfully.');
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
            'name' => 'required|unique:algorithms'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $algorithm = Algorithm::create($input); 
        return $this->sendResponse(new AlgorithmResource($algorithm), 'Algorithm created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $algorithm = Algorithm::where('id',$id)->first(); 
        
        if(empty($algorithm)){
            return $this->sendError('Algorithm '.$id.' not found.');       
        }
        
        return $this->sendResponse(new AlgorithmResource($algorithm), 'Algorithm loaded successfully.');
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
        $algorithm = Algorithm::where('id',$id)->first(); 
        if(empty($algorithm)){
            return $this->sendError('Algorithm '.$id.' not found.');       
        }

        $input = $request->all(); 
        $validator = Validator::make($input, [
            'name' => 'required|unique:algorithms'    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $algorithm->update($input);
        return $this->sendResponse(new AlgorithmResource($algorithm), 'Algorithm updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $algorithm = Algorithm::where('id',$id)->first(); 
        if(empty($algorithm)){
            return $this->sendError('Algorithm '.$id.' not found.');       
        }
        $algorithm->delete();
        return $this->sendResponse([], 'Algorithm deleted successfully.');
    }
}
