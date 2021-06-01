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
            'firstname' =>'required',
            'middlename' =>'required',
    		'lastname' =>'required',
            'email' =>'required|email|unique:travellers',
    		'address' =>'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'yob' =>'required',
            'mob' =>'required',
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
            $traveller->firstname     = $request->get('firstname');
            $traveller->middlename     = $request->get('middlename');
        	$traveller->lastname     = $request->get('lastname');
        	$traveller->email     = $request->get('email');
        	$traveller->address     = $request->get('address');
        	$traveller->phone     = $request->get('phone');

            $traveller->yob     = $request->get('yob');
            $traveller->mob     = $request->get('mob');
        	$traveller->dob     = $request->get('dob');

            $traveller->country     = $request->get('country');
        	$traveller->state     = $request->get('state');
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
            'firstname' =>'required',
            'middlename' =>'required',
            'lastname' =>'required',
            'email' =>'required|email|unique:travellers',
            'address' =>'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'yob' =>'required',
            'mob' =>'required',
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
        	$traveller->firstname     = $request->get('firstname');
            $traveller->middlename     = $request->get('middlename');
            $traveller->lastname     = $request->get('lastname');
            $traveller->email     = $request->get('email');
            $traveller->address     = $request->get('address');
            $traveller->phone     = $request->get('phone');

            $traveller->yob     = $request->get('yob');
            $traveller->mob     = $request->get('mob');
            $traveller->dob     = $request->get('dob');
            $traveller->country     = $request->get('country');
        	$traveller->state     = $request->get('state');
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



/*-------Update profile image api-------*/ 
    public function profile_image_update(Request $request){

        $user = auth('agent-api')->setToken($request->bearerToken())->user();


            $id    = $request->get('id');
       
            $found = Traveller::find($id);

        if ($user == NULL) {
            return response()->json([
                'message'=> 'Agent not found!'
            ], 401); 

        }else{

            $validator = Validator::make($request->all(), [
                'profile_image' =>'required|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(["message"=> $validator->errors()], 422);
            }

            $uploadFolder = 'travellers';

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');

                    $image_uploaded_path = $image->store($uploadFolder, 'public');
                    $found->profile_image     = Storage::url($image_uploaded_path);

                $found->save();
                return "<img src='http://127.0.0.1:8000".$found->profile_image."'>";
                return response()->json([
                    'traveller'=>$found->profile_image, 
                    // 'message'=> 'traveller\'s profile updated Successfully!'
                ], 200);     
            }else{
                return response()->json(['message'=>'An error occurred!'], 500);
            }
        }
        
    }
/*------./Updateprofile image api---------*/ 

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
