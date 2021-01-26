<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Tour_booking;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TourBookingController extends Controller
{

/*----------User Add Tour Booking----------*/
	
	public function userAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'fullname' =>'required|string',
    		'selected_tour_id' =>'required',
    		'phone_number' =>'required',
    		'email' =>'required|email',
    		'country' =>'required',
    		'rate' =>'required',
    		'adult_price' =>'required',
    		'children_price' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {
    		
        	$tour_booking   = new Tour_booking();
        	$tour_booking->fullname     = $request->get('fullname');
        	$tour_booking->selected_tour_id     = $request->get('selected_tour_id');
        	$tour_booking->phone_number     = $request->get('phone_number');
        	$tour_booking->email     = $request->get('email');
        	$tour_booking->country     = $request->get('country');
        	$tour_booking->rate     = $request->get('rate');
        	$tour_booking->adult_price     = $request->get('adult_price');
        	$tour_booking->children_price     = $request->get('children_price');
        	$tour_booking->booked_by     = 'user';
        	$tour_booking->save();

        	// send mail to admin here 
        	
    		return response()->json(['tour_booking'=>$tour_booking, 'message'=>'Tour Booking Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }

/*----------./User Add Tour Booking----------*/



/*----------agent Add Tour Booking----------*/
	public function agentAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'fullname' =>'required|string',
    		'selected_tour_id' =>'required',
    		'phone_number' =>'required',
    		'email' =>'required|email',
    		'country' =>'required',
    		'rate' =>'required',
    		'adult_price' =>'required',
    		'children_price' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }
    		
        	$tour_booking   = new Tour_booking();
        	$tour_booking->fullname     = $request->get('fullname');
        	$tour_booking->selected_tour_id     = $request->get('selected_tour_id');
        	$tour_booking->phone_number     = $request->get('phone_number');
        	$tour_booking->email     = $request->get('email');
        	$tour_booking->country     = $request->get('country');
        	$tour_booking->rate     = $request->get('rate');
        	$tour_booking->adult_price     = $request->get('adult_price');
        	$tour_booking->children_price     = $request->get('children_price');
        	$tour_booking->booked_by     = 'agent';
        	$tour_booking->agent_id     = $agent->id;
        	$tour_booking->save();

        	// send mail to admin here 
        	
    		return response()->json(['tour_booking'=>$tour_booking, 'message'=>'Tour Booking Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add Tour Booking----------*/




/*----------Admin View all Tour Booking----------*/
    public function index()
	{
		$tourBooking = Tour_booking::all();
    	return response()->json(['tourBooking'=>$tourBooking, 'message'=>'Tour Bookings fetched Successfully'], 200);
	}

/*----------./Admin View all Tour Booking----------*/




/*----------Admin View all users Tour Booking----------*/
    
    public function userTourBookings()
	{
		$tourBooking = DB::select(
        'SELECT * From tour_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['user']
        );

    	return response()->json([
    		'tourBooking'=>$tourBooking, 
    		'message'=>'Tour Bookings for users fetched Successfully'
    	], 200);
	}

/*----------./Admin View all users Tour Booking----------*/


/*----------Admin View all Agent Tour Booking----------*/
    
    public function agentTourBookings()
	{
		$tourBooking = DB::select(
        'SELECT * From tour_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['agent']
        );

    	return response()->json([
    		'tourBooking'=>$tourBooking, 
    		'message'=>'Tour Bookings for users fetched Successfully'
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


		$tourBooking = DB::select(
        'SELECT * From tour_bookings 
            WHERE agent_id = ?  
            ORDER BY id DESC', [$agent->id]
        );

    	return response()->json([
    		'tourBooking'=>$tourBooking, 
    		'message'=>'Tour Bookings for '.$agent->name.' fetched Successfully'
    	], 200);
	}

/*----------./Agent View his bookings----------*/



/*----------Admin View single Tour Booking----------*/

	public function view($id)
    {
        $tourBooking = Tour_booking::find($id);
        return response()->json(['tourBooking'=>$tourBooking, 'message'=>'Tour Booking fetched Successfully'], 200);
    }

/*----------./Admin View single Tour Booking----------*/


/*DELETE DATA*/ 
	    public function delete($id)
	    {
	    	$tourBooking = Tour_booking::find($id);
	        if ($tourBooking == NULL) {
	            return response()->json([
	            'message'=> 'An error occurred!'
	        ], 500);
	        }
	            $tourBooking->delete();

	        return response()->json([
	            'message'=> 'Tour Booking Deleted Successfully!'
	        ], 200); 
	    }
/*DELETE DATA*/ 


}
