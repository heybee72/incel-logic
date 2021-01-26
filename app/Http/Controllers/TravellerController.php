<?php

namespace App\Http\Controllers;

use App\Models\Traveller;
use App\Models\Agent;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TravellerController extends Controller
{
    
	public function add(Request $request){
    	$validator = Validator::make($request->all(), [

    		'title' =>'required',
    		'fullname' =>'required',
    		'email' =>'required|email',
    		'address' =>'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    		'dob' =>'required',
    		'country' =>'required',
    		'state' =>'required',
    		'city' =>'required',
    		'zip' =>'required',
    		'passport_number' =>'required',
    		'country_of_issue' =>'required',
    		'date_issue' =>'required',
    		'exp_date' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'agent not found!'], 400);
	        }
    		
        	$traveller   = new traveller();
        	$traveller->title     = $request->get('title');
        	$traveller->fullname     = $request->get('fullname');
        	$traveller->email     = $request->get('email');
        	$traveller->address     = $request->get('address');
        	$traveller->phone     = $request->get('phone');

        	$traveller->dob     = $request->get('dob');
        	$traveller->country     = $request->get('country');
        	$traveller->city     = $request->get('city');
        	$traveller->zip     = $request->get('zip');
        	$traveller->passport_number     = $request->get('passport_number');
        	$traveller->country_of_issue     = $request->get('country_of_issue');
        	$traveller->date_issue     = $request->get('date_issue');
        	$traveller->exp_date     = $request->get('exp_date');
        	$traveller->emergency_phone     = $request->get('emergency_phone');
        	$traveller->emergency_email     = $request->get('emergency_email');
        	$traveller->emergency_address     = $request->get('emergency_address');
        	$traveller->insurance_company     = $request->get('insurance_company');
        	$traveller->insurance_phone     = $request->get('insurance_phone');
        	$traveller->agent_id     = $agent->id;
        	$traveller->save();

        	// send mail to admin here 
        	
    		return response()->json(['traveller'=>$traveller, 'message'=>'mark up Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }


/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'title' =>'required',
    		'fullname' =>'required',
    		'email' =>'required|email',
    		'address' =>'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    		'dob' =>'required',
    		'country' =>'required',
    		'state' =>'required',
    		'city' =>'required',
    		'zip' =>'required',
    		'passport_number' =>'required',
    		'country_of_issue' =>'required',
    		'date_issue' =>'required',
    		'exp_date' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$traveller = traveller::find($id);
    		$traveller->title     = $request->get('title');
        	$traveller->fullname     = $request->get('fullname');
        	$traveller->email     = $request->get('email');
        	$traveller->address     = $request->get('address');
        	$traveller->phone     = $request->get('phone');

        	$traveller->dob     = $request->get('dob');
        	$traveller->country     = $request->get('country');
        	$traveller->city     = $request->get('city');
        	$traveller->zip     = $request->get('zip');
        	$traveller->passport_number     = $request->get('passport_number');
        	$traveller->country_of_issue     = $request->get('country_of_issue');
        	$traveller->date_issue     = $request->get('date_issue');
        	$traveller->exp_date     = $request->get('exp_date');
        	$traveller->emergency_phone     = $request->get('emergency_phone');
        	$traveller->emergency_email     = $request->get('emergency_email');
        	$traveller->emergency_address     = $request->get('emergency_address');
        	$traveller->insurance_company     = $request->get('insurance_company');
        	$traveller->insurance_phone     = $request->get('insurance_phone');
            
            if ($traveller->save()) {
                return response()->json([
                    'traveller'=>$traveller, 
                    'message'=>'traveller updated Successfully'
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

public function index()
	{
		$traveller = traveller::all();

    	return response()->json(['traveller'=>$traveller, 'message'=>'travellers fetched Successfully'], 200);
	}


public function view($id)
    {
        $traveller = traveller::find($id);
        return response()->json(['traveller'=>$traveller, 'message'=>'traveller fetched Successfully'], 200);
    }



public function agent_view(Request $request)
	{


		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
        if ($agent == NULL) {

            return response()->json(['message'=>'agent not found!'], 400);
        }


		$traveller = DB::select(
        'SELECT * From  travellers 
            WHERE agent_id = ?  
            ORDER BY id DESC', [$agent->id]
        );

    	return response()->json([
    		'traveller'=>$traveller, 
    		'message'=>'travellers for agent fetched Successfully'
    	], 200);
	}


/*DELETE DATA*/ 
    public function delete($id)
    {
    	$traveller = traveller::find($id);
        if ($traveller == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $traveller->delete();

        return response()->json([
            'message'=> 'traveller Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 

}
