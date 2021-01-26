<?php

namespace App\Http\Controllers;

use App\Models\Visa_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class VisaTypeController extends Controller
{
     public function index()
	{
		$visa_type = visa_type::all();

    	return response()->json(['visa_type'=>$visa_type, 'message'=>'visa type fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'name' =>'required',
        		'country' =>'required',
        		'duration' =>'required',
        		'processing_time' =>'required',
        		'price' =>'required',
        		'description' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
                	$visa_type             = new visa_type();
        			$visa_type->name     = $request->get('name');
        			$visa_type->country     = $request->get('country');
        			$visa_type->duration     = $request->get('duration');
        			$visa_type->processing_time     = $request->get('processing_time');
        			$visa_type->price     = $request->get('price');
        			$visa_type->description     = $request->get('description');
                	$visa_type->save();


            		return response()->json(['visa_type'=>$visa_type, 'message'=>'visa type Created Successfully'], 201);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' =>'required',
    		'country' =>'required',
    		'duration' =>'required',
    		'processing_time' =>'required',
    		'price' =>'required',
    		'description' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$visa_type = visa_type::find($id);
    		$visa_type->name     = $request->get('name');
			$visa_type->country     = $request->get('country');
			$visa_type->duration     = $request->get('duration');
			$visa_type->processing_time     = $request->get('processing_time');
			$visa_type->price     = $request->get('price');
			$visa_type->description     = $request->get('description');
            
            if ($visa_type->save()) {
                return response()->json([
                    'visa_type'=>$visa_type, 
                    'message'=>'visa type updated Successfully'
                ], 201);
            }else{

                return response()->json([
                    'message'=>'An error occured!'
                ], 501);

            }

        } catch (Exception $e) {

            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
            
        }

    }

/*Update Data*/ 



    public function view($id)
    {
        $visa_type = visa_type::find($id);
        return response()->json(['visa_type'=>$visa_type, 'message'=>'visa type fetched Successfully'], 200);
    }


/*DELETE DATA*/ 
    public function delete($id)
    {
    	$visa_type = visa_type::find($id);
        if ($visa_type == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $visa_type->delete();

        return response()->json([
            'message'=> 'visa type Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 
}
