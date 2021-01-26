<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Flight;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{

/*----------User Add Flight Booking----------*/
	
	public function userAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'flying_from' =>'required|string',
    		'flying_to' =>'required',
    		'flight_class' =>'required',
    		'departure_date' =>'required',
    		'no_of_passengers' =>'required',
    		'no_of_adult' =>'required',
    		'no_of_children' =>'required',
    		'no_of_infant' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {
    		
        	$flight   = new Flight();
        	$flight->flying_from     = $request->get('flying_from');
        	$flight->flying_to     = $request->get('flying_to');
        	$flight->flight_class     = $request->get('flight_class');
        	$flight->departure_date     = $request->get('departure_date');
        	$flight->no_of_passengers     = $request->get('no_of_passengers');
        	$flight->no_of_adult     = $request->get('no_of_adult');
        	$flight->no_of_children     = $request->get('no_of_children');
        	$flight->no_of_infant     = $request->get('no_of_infant');
        	$flight->booked_by     = 'user';
        	$flight->save();

        	// send mail to admin here 
        	
    		return response()->json(['flight'=>$flight, 'message'=>'Flight Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }

/*----------./User Add Flight Booking----------*/


/*----------agent Add Flight Booking----------*/
	public function agentAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'flying_from' =>'required|string',
    		'flying_to' =>'required',
    		'traveller_id' =>'required',
    		'flight_class' =>'required',
    		'departure_date' =>'required',
    		'no_of_passengers' =>'required',
    		'no_of_adult' =>'required',
    		'no_of_children' =>'required',
    		'no_of_infant' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }
    		
        	$flight   = new Flight();
        	$flight->flying_from     = $request->get('flying_from');
        	$flight->flying_to     = $request->get('flying_to');
        	$flight->flight_class     = $request->get('flight_class');
        	$flight->departure_date     = $request->get('departure_date');
        	$flight->no_of_passengers     = $request->get('no_of_passengers');
        	$flight->no_of_adult     = $request->get('no_of_adult');
        	$flight->no_of_children     = $request->get('no_of_children');
        	$flight->no_of_infant     = $request->get('no_of_infant');
        	$flight->booked_by     = 'agent';
        	$flight->agent_id     = $agent->id;
        	$flight->save();

        	// send mail to admin here 
        	
    		return response()->json(['flight'=>$flight, 'message'=>'Flight Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add flight Booking---------*/


/*----------Admin View all Flight Booking----------*/
    public function index()
	{
		$flight = Flight::all();
    	return response()->json(['flight'=>$flight, 'message'=>'Flights fetched Successfully'], 200);
	}

/*----------./Admin View all Flight Booking----------*/


/*----------Admin View single flight Booking----------*/

	public function view($id)
    {
        $flight = Flight::find($id);
        return response()->json(['flight'=>$flight, 'message'=>'Flight fetched Successfully'], 200);
    }

/*----------./Admin View single flight Booking----------*/


/*----------Admin View all users Flight Booking----------*/
    
    public function userBookings()
	{
		$flightBooking = DB::select(
        'SELECT * From  flights 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['user']
        );

    	return response()->json([
    		'flightBooking'=>$flightBooking, 
    		'message'=>'Flight Bookings for users fetched Successfully'
    	], 200);
	}

/*----------./Admin View all users flight Booking----------*/


/*----------Admin View all users Flight Booking----------*/
    
    public function agentBookings()
	{
		$flightBooking = DB::select(
        'SELECT * From  flights 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['agent']
        );

    	return response()->json([
    		'flightBooking'=>$flightBooking, 
    		'message'=>'Flight Bookings for agent fetched Successfully'
    	], 200);
	}

/*----------./Admin View all users flight Booking----------*/



/*----------Agent View his bookings----------*/
    
    public function agentView(Request $request)
	{

		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }


		$flight = DB::select(
        'SELECT * From flights 
            WHERE agent_id = ?  
            ORDER BY id DESC', [$agent->id]
        );

    	return response()->json([
    		'flight'=>$flight, 
    		'message'=>'Flight Bookings for '.$agent->name.' fetched Successfully'
    	], 200);
	}

/*----------./Agent View his bookings----------*/


/*---------DELETE DATA-------------*/ 
	    public function delete($id)
	    {
	    	$flight = Flight::find($id);
	        if ($flight == NULL) {
	            return response()->json([
	            'message'=> 'An error occurred!'
	        ], 500);
	        }
	            $flight->delete();

	        return response()->json([
	            'message'=> 'Flight Booking Deleted Successfully!'
	        ], 200); 
	    }
/*----------./DELETE DATA----------*/ 

}
