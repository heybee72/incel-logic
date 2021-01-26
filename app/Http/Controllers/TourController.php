<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class TourController extends Controller
{
    
public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'tour' =>'required',
        		'adult_price' =>'required',
        		'children_price' =>'required',
        		'rate' =>'required',
        		'country' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               

                	$tour             = new tour();
        			$tour->tour     = $request->get('tour');
        			$tour->adult_price     = $request->get('adult_price');
        			$tour->children_price     = $request->get('children_price');
        			$tour->rate     = $request->get('rate');
        			$tour->country     = $request->get('country');
                	$tour->save();


            		return response()->json(['tour'=>$tour, 'message'=>'tour Created Successfully'], 200);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


    public function index()
	{
		$tour = tour::all();

    	return response()->json(['tour'=>$tour, 'message'=>'tour fetched Successfully'], 200);
	}


	/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'tour' =>'required',
    		'adult_price' =>'required',
    		'children_price' =>'required',
    		'rate' =>'required',
    		'country' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$tour = tour::find($id);
    		$tour->tour     = $request->get('tour');
			$tour->adult_price     = $request->get('adult_price');
			$tour->children_price     = $request->get('children_price');
			$tour->rate     = $request->get('rate');
			$tour->country     = $request->get('country');
            
            if ($tour->save()) {
                return response()->json([
                    'tour'=>$tour, 
                    'message'=>'tour updated Successfully'
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
        $tour = tour::find($id);
        return response()->json(['tour'=>$tour, 'message'=>'tour fetched Successfully'], 200);
    }



/*DELETE DATA*/ 
    public function delete($id)
    {
    	$tour = tour::find($id);
        if ($tour == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $tour->delete();

        return response()->json([
            'message'=> 'tour Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 


}
