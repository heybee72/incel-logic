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
    		// 'selected_tour_id' =>'required',
            'uniqueNo' => 'required',
            'serviceUniqueId' => 'required',
            'tourId' => 'required',
            'tourTitle' => 'required',
            'tourPrice' => 'required',
            'optionId'=> 'required',
            'tourDate'=> 'required',
            'timeSlotId'=> 'required',
            'startTime'=> 'required',
            'transferId'=> 'required',
            'pickup'=> 'required',
            'adult' => 'required',
            'child' => 'required',
            'infant' => 'required',
            'adultRate' => 'required',
            'childRate' => 'required',
            'serviceTotal' => 'required',
            'serviceType' => 'required',
            'prefix' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'nationality' => 'required',
            'message' => 'required',
            'leadPassenger' => 'required',
            'paxType' => 'required',
            'clientReferenceNo' => 'required',
    		'traveller_id' =>'required',
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
        	$tour_booking->booked_by     = 'agent';
        	$tour_booking->agent_id     = $agent->id;
        	$tour_booking->message     = $request->get('message');
        	$tour_booking->nationality     = $request->get('nationality');
        	$tour_booking->mobile     = $request->get('mobile');
        	$tour_booking->email     = $request->get('email');
        	$tour_booking->lastName     = $request->get('lastName');
        	$tour_booking->firstName     = $request->get('firstName');
        	$tour_booking->prefix     = $request->get('prefix');
        	$tour_booking->serviceType     = $request->get('serviceType');
        	$tour_booking->serviceTotal     = $request->get('serviceTotal');
        	$tour_booking->childRate     = $request->get('childRate');
        	$tour_booking->adultRate     = $request->get('adultRate');
        	$tour_booking->infant     = $request->get('infant');
        	$tour_booking->child     = $request->get('child');
        	$tour_booking->adult     = $request->get('adult');
        	$tour_booking->pickup     = $request->get('pickup');
        	$tour_booking->transferId     = $request->get('transferId');
        	$tour_booking->startTime     = $request->get('startTime');
        	$tour_booking->timeSlotId     = $request->get('timeSlotId');
        	$tour_booking->tourDate     = $request->get('tourDate');
        	$tour_booking->optionId     = $request->get('optionId');
        	$tour_booking->tourPrice     = $request->get('tourPrice');
        	$tour_booking->tourTitle     = $request->get('tourTitle');
        	$tour_booking->tourId     = $request->get('tourId');
        	$tour_booking->serviceUniqueId     = $request->get('serviceUniqueId');
        	$tour_booking->uniqueNo     = $request->get('uniqueNo');
        	$tour_booking->leadPassenger     = $request->get('leadPassenger');
        	$tour_booking->paxType     = $request->get('paxType');
        	$tour_booking->clientReferenceNo     = $request->get('clientReferenceNo');
        	$tour_booking->payment_status     = 'pending';


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
            ->leftJoin('users', 'users.id', '=', 'tour_bookings.user_id')
            ->leftJoin('agents', 'agents.id', '=', 'tour_bookings.agent_id')

            ->select(
                'tour_bookings.id',
                'tour_bookings.user_id',
                'tour_bookings.traveller_id',
                'tour_bookings.booked_by',
                'tour_bookings.agent_id',
                'tour_bookings.message',
                'tour_bookings.nationality',
                'tour_bookings.mobile',
                'tour_bookings.email',
                'tour_bookings.lastName',
                'tour_bookings.firstName',
                'tour_bookings.prefix',
                'tour_bookings.serviceType',
                'tour_bookings.serviceTotal',
                'tour_bookings.childRate',
                'tour_bookings.adultRate',
                'tour_bookings.infant',
                'tour_bookings.child',
                'tour_bookings.adult',
                'tour_bookings.pickup',
                'tour_bookings.transferId',
                'tour_bookings.startTime',
                'tour_bookings.timeSlotId',
                'tour_bookings.tourDate',
                'tour_bookings.optionId',
                'tour_bookings.tourPrice',
                'tour_bookings.tourTitle',
                'tour_bookings.tourId',
                'tour_bookings.serviceUniqueId',
                'tour_bookings.uniqueNo',
                'tour_bookings.leadPassenger',
                'tour_bookings.paxType',
                'tour_bookings.clientReferenceNo',
                'tour_bookings.payment_status',
                

                'agents.name',
                'agents.email',
                'agents.phone',

                'users.name',
                'users.email',
                'users.phone',
 
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

            ->select(
                'tour_bookings.id',

                'travellers.id AS travellers_id',
                'travellers.firstname',
                'travellers.middlename',
                'travellers.lastname',
                'travellers.email',
                'travellers.address',
                'travellers.phone',
                'travellers.dob',
                'travellers.yob',
                'travellers.mob',
                'travellers.country',
                'travellers.profile_image',
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

                'tour_bookings.id',
                'tour_bookings.user_id',
                'tour_bookings.traveller_id',
                'tour_bookings.booked_by',
                'tour_bookings.agent_id',
                'tour_bookings.message',
                'tour_bookings.nationality',
                'tour_bookings.mobile',
                'tour_bookings.email',
                'tour_bookings.lastName',
                'tour_bookings.firstName',
                'tour_bookings.prefix',
                'tour_bookings.serviceType',
                'tour_bookings.serviceTotal',
                'tour_bookings.childRate',
                'tour_bookings.adultRate',
                'tour_bookings.infant',
                'tour_bookings.child',
                'tour_bookings.adult',
                'tour_bookings.pickup',
                'tour_bookings.transferId',
                'tour_bookings.startTime',
                'tour_bookings.timeSlotId',
                'tour_bookings.tourDate',
                'tour_bookings.optionId',
                'tour_bookings.tourPrice',
                'tour_bookings.tourTitle',
                'tour_bookings.tourId',
                'tour_bookings.serviceUniqueId',
                'tour_bookings.uniqueNo',
                'tour_bookings.leadPassenger',
                'tour_bookings.paxType',
                'tour_bookings.clientReferenceNo',
                'tour_bookings.payment_status',


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

            ->select(

                'tour_bookings.id',
                'tour_bookings.user_id',
                'tour_bookings.traveller_id',
                'tour_bookings.booked_by',
                'tour_bookings.agent_id',
                'tour_bookings.message',
                'tour_bookings.nationality',
                'tour_bookings.mobile',
                'tour_bookings.email',
                'tour_bookings.lastName',
                'tour_bookings.firstName',
                'tour_bookings.prefix',
                'tour_bookings.serviceType',
                'tour_bookings.serviceTotal',
                'tour_bookings.childRate',
                'tour_bookings.adultRate',
                'tour_bookings.infant',
                'tour_bookings.child',
                'tour_bookings.adult',
                'tour_bookings.pickup',
                'tour_bookings.transferId',
                'tour_bookings.startTime',
                'tour_bookings.timeSlotId',
                'tour_bookings.tourDate',
                'tour_bookings.optionId',
                'tour_bookings.tourPrice',
                'tour_bookings.tourTitle',
                'tour_bookings.tourId',
                'tour_bookings.serviceUniqueId',
                'tour_bookings.uniqueNo',
                'tour_bookings.leadPassenger',
                'tour_bookings.paxType',
                'tour_bookings.clientReferenceNo',
                'tour_bookings.payment_status',

                
                'travellers.id AS travellers_id',
                'travellers.firstname',
                'travellers.middlename',
                'travellers.lastname',
                'travellers.email',
                'travellers.address',
                'travellers.phone',
                'travellers.dob',
                'travellers.yob',
                'travellers.mob',
                'travellers.country',
                'travellers.profile_image',
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
