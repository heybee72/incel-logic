<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Visa_application;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class VisaApplicationController extends Controller
{


/*----------User Add visa application Booking----------*/
	
	public function userAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'visa_type_id' =>'required',
    		'home_address' =>'required',
    		'destination_address' =>'required',
    		'duration' =>'required',
    		'remark' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {
    		
        	$visa_application   = new Visa_application();
        	$visa_application->user_id     = $request->get('user_id');
        	$visa_application->visa_type_id     = $request->get('visa_type_id');
        	$visa_application->home_address     = $request->get('home_address');
        	$visa_application->destination_address     = $request->get('destination_address');
        	$visa_application->duration     = $request->get('duration');
        	$visa_application->remark     = $request->get('remark');
        	$visa_application->booked_by     = 'user';
        	$visa_application->save();

        	//TODO: send mail to admin here 
        	
    		return response()->json([
    			'visa_application'=>$visa_application, 
    			'message'=>'Visa Application Created Successfully'
    		], 201);

    	} catch (Exception $e) {

    		return response()->json([
    			'message'=>'An error occurred', 
    			'error'=>$e->message()
    		], 422);
    		
    	}

    }

/*----------./User Add visa application Booking----------*/



/*----------agent Add visa application Booking----------*/
	public function agentAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'visa_type_id' =>'required',
    		'home_address' =>'required',
    		'destination_address' =>'required',
    		'duration' =>'required',
    		'traveller_id' =>'required',
    		'remark' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }
    		
        	$visa_application   = new visa_application();
        	$visa_application->user_id     = $request->get('user_id');
        	$visa_application->visa_type_id     = $request->get('visa_type_id');
        	$visa_application->home_address     = $request->get('home_address');
        	$visa_application->traveller_id     = $request->get('traveller_id');
        	$visa_application->destination_address     = $request->get('destination_address');
        	$visa_application->duration     = $request->get('duration');
        	$visa_application->remark     = $request->get('remark');
        	$visa_application->booked_by     = 'agent';
        	$visa_application->agent_id     = $agent->id;
        	$visa_application->save();

        	// send mail to admin here 
        	
    		return response()->json(['visa_application'=>$visa_application, 'message'=>'Visa Application Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add visa application Booking----------*/




/*----------Admin View all visa Booking----------*/
    public function index()
	{
		$visaBooking = visa_application::all();
    	return response()->json(['visaBooking'=>$visaBooking, 'message'=>'visa Bookings fetched Successfully'], 200);
	}

/*----------./Admin View all visa Booking----------*/




/*----------Admin View all users visa Booking----------*/
    
    public function userBookings()
	{
		$visaBooking = DB::select(
        'SELECT * From visa_applications 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['user']
        );

    	return response()->json([
    		'visaBooking'=>$visaBooking, 
    		'message'=>'visa Bookings for users fetched Successfully'
    	], 200);
	}

/*----------./Admin View all users Tour Booking----------*/


/*----------Admin View all Agent Tour Booking----------*/
    
    public function agentBookings()
	{
		$visaBooking = DB::select(
        'SELECT * From visa_applications 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['agent']
        );

    	return response()->json([
    		'visaBooking'=>$visaBooking, 
    		'message'=>'Visa Bookings for users fetched Successfully'
    	], 200);
	}

/*----------./Admin View all agent Tour Booking----------*/




/*----------Agent View his bookings----------*/
    
    public function agentView(Request $request)
	{

		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }


		$visaBooking = DB::select(
        'SELECT * From visa_applications 
            WHERE agent_id = ?  
            ORDER BY id DESC', [$agent->id]
        );

    	return response()->json([
    		'visaBooking'=>$visaBooking, 
    		'message'=>'Visa application Bookings for '.$agent->name.' fetched Successfully'
    	], 200);
	}

/*----------./Agent View his bookings----------*/



/*----------Admin View single Tour Booking----------*/

	public function view($id)
    {
        $visaBooking = visa_application::find($id);
        return response()->json(['visaBooking'=>$visaBooking, 'message'=>'Visa Booking fetched Successfully'], 200);
    }

/*----------./Admin View single Tour Booking----------*/


/*DELETE DATA*/ 
	    public function delete($id)
	    {
	    	$visaBooking = visa_application::find($id);
	        if ($visaBooking == NULL) {
	            return response()->json([
	            'message'=> 'An error occurred!'
	        ], 500);
	        }
	            $visaBooking->delete();

	        return response()->json([
	            'message'=> 'Visa Booking Deleted Successfully!'
	        ], 200); 
	    }
/*DELETE DATA*/ 


}
