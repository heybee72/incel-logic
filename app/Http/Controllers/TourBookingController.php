<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Tour_booking;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TourBookingController extends Controller
{

/*----------User Add Tour Booking----------*/
	
	public function userAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'selected_tour_id' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

        $user = auth()->setToken($request->bearerToken())->user();
    		
        	$tour_booking   = new Tour_booking();
        	$tour_booking->user_id     = $user->id;
        	$tour_booking->selected_tour_id     = $request->get('selected_tour_id');
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
    		'traveller_id' =>'required|string',
    		'selected_tour_id' =>'required',
    		
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
        	$tour_booking->traveller_id     = $request->get('traveller_id');
        	$tour_booking->selected_tour_id     = $request->get('selected_tour_id');
        	
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

		$tourBooking = DB::table('tour_bookings')
            ->leftJoin('tours', 'tours.id', '=', 'tour_bookings.selected_tour_id')
            ->leftJoin('users', 'users.id', '=', 'tour_bookings.user_id')
            ->leftJoin('travellers', 'travellers.id', '=', 'tour_bookings.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'tour_bookings.agent_id')

            ->select(
                'tour_bookings.id',
                'travellers.fullname',
                'travellers.email',

                'agents.name',
                'agents.email',
                'agents.phone',

                'users.name',
                'users.email',
                'users.phone',

                'tours.tour',
                'tours.adult_price',
                'tours.children_price',
                'tours.rate',
                'tours.country',
                
                'tour_bookings.created_at',
                'tour_bookings.updated_at'
            )
            ->orderBy('tour_bookings.id', 'desc')
            ->get();

            return response()->json([
                 'tourBooking'=>$tourBooking, 
                 'message'=>'Tour Bookings fetched Successfully'
             ], 200);
	}

/*----------./Admin View all Tour Booking----------*/







/*----------Agent View his bookings----------*/
    
    public function agentView(Request $request)
	{
        
		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }


           $tourBooking = DB::table('tour_bookings')
            ->leftJoin('travellers', 'travellers.id', '=', 'tour_bookings.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'tour_bookings.agent_id')
            ->leftJoin('tours', 'tours.id', '=', 'tour_bookings.selected_tour_id')

            ->select(
                'tour_bookings.id',
                'travellers.fullname',
                'travellers.email',
                'travellers.address',
                'travellers.phone',
                'travellers.dob',
                'travellers.country',
                'travellers.state',
                'travellers.city',
                'travellers.passport_number',
                'travellers.country_of_issue',

                'agents.name',
                'agents.email',
                'agents.phone',
                'agents.username',
                'agents.company',
                'agents.country',
                'agents.business_address',

                'tours.tour',
                'tours.adult_price',
                'tours.children_price',
                'tours.rate',
                'tours.country',


                'tour_bookings.created_at',
                'tour_bookings.updated_at'
            )
            ->where('tour_bookings.agent_id', $agent->id)
            ->orderBy('tour_bookings.id', 'desc')
            ->get();


    	return response()->json([
    		'tourBooking'=>$tourBooking, 
    		'message'=>'Tour Bookings for '.$agent->name.' fetched Successfully'
    	], 200);
	}

/*----------./Agent View his bookings----------*/



/*----------Admin View single Tour Booking----------*/

	public function view($id)
    {
        $tourBooking = $tourBooking = DB::table('tour_bookings')
            ->leftJoin('travellers', 'travellers.id', '=', 'tour_bookings.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'tour_bookings.agent_id')
            ->leftJoin('users', 'users.id', '=', 'tour_bookings.user_id')
            ->leftJoin('tours', 'tours.id', '=', 'tour_bookings.selected_tour_id')

            ->select(
                'tour_bookings.id',
                'travellers.fullname',
                'travellers.email',
                'travellers.address',
                'travellers.phone',
                'travellers.dob',
                'travellers.country',
                'travellers.state',
                'travellers.city',
                'travellers.passport_number',
                'travellers.country_of_issue',

                'agents.name',
                'agents.email',
                'agents.phone',
                'agents.username',
                'agents.company',
                'agents.country',
                'agents.business_address',

                'tours.tour',
                'tours.adult_price',
                'tours.children_price',
                'tours.rate',
                'tours.country',

                'users.name',
                'users.email',
                'users.phone',
                'users.country',
               
                'tour_bookings.created_at',
                'tour_bookings.updated_at'
            )
            ->take(1)
            ->where('tour_bookings.id', $id)
            ->get();

        return response()->json([
            'tourBooking'=>$tourBooking, 
            'message'=>'Tour Booking fetched Successfully'
        ], 200);
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
