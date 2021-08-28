<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Additional_document;
use App\Models\Visa_application;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    		'country' =>'required',
    		'nationality_id' =>'required',
    		'traveller' =>'required',
    		'visatype' =>'required',
            'passporttype' =>'required',
            'language' =>'required',
    		'profession' =>'required',
    		'processing' =>'required',
    		'maritalstatus' =>'required',
    		'gendertype' =>'required',
    		'religion' =>'required',
    		'groupmembership' =>'required',
    		'agent_id' =>'required',
    		'first_name_eng' =>'required',
    		'middle_name_eng' =>'required',
    		'last_name_eng' =>'required',
    		'issue_date' =>'required',
    		'expiry_date' =>'required',
    		'father_name_eng' =>'required',
    		'mother_name_eng' =>'required',
    		'birth_place_eng' =>'required',
    		'birth_date' =>'required',
    		'email' =>'required',
    		'mobile' =>'required',
    		'passport' =>'required',
    		'attachments' =>'required',
    		'attachment_blobs' =>'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }


			



            //   $uploadFolder = 'visa_applications';
                 
            //     if ($image_1 = $request->file('passport') AND $image_2 = $request->file('data_page')) {

            //         $image_1_uploaded_path = $image_1->store($uploadFolder, 'public');
            //         $image_2_uploaded_path = $image_2->store($uploadFolder, 'public');
                    

            //     	$visa_application   = new visa_application();

            //     	$visa_application->user_id     = $request->get('user_id');
            //     	$visa_application->visa_type_id     = $request->get('visa_type_id');
            //     	$visa_application->home_address     = $request->get('home_address');
            //     	$visa_application->destination_address     = $request->get('destination_address');
            //     	$visa_application->traveller_id     = $request->get('traveller_id');
            //     	$visa_application->remark     = $request->get('remark');
            //     	$visa_application->booked_by     = 'agent';
            //     	$visa_application->agent_id     = $agent->id;

            //         $visa_application->passport     = Storage::url($image_1_uploaded_path);
            //         $visa_application->data_page     = Storage::url($image_2_uploaded_path);

            //     	$visa_application->save();

            //         $last_visa_id = Visa_application::latest()->first()->id;


            //         $additional_document   = new Additional_document();


            //         $files = $request->file('attachment');
            //         if($request->hasFile('attachment'))
            //         {
            //             $types = explode(',' , $request->get('document_type'));
            //             foreach ($files as $file) {
            //                 foreach ($types as $type) {
            //                     $path = $file->store($uploadFolder, 'public');
            //                     $additional_document->document_type = $type;
            //                     $additional_document->image = Storage::url($path);
            //                     $additional_document->visa_application_id = $last_visa_id;
            //                     $additional_document->agent_id = $agent->id;
            //                     $additional_document->save();
            //                 }
            //             }
            //         }


            //     	// send mail to admin here 
                
    		//       return response()->json(['visa_application'=>$visa_application, 'message'=>'Visa Application Created Successfully'], 201);
            //     } 

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add visa application Booking----------*/




/*----------Admin View all visa Booking----------*/
    public function index()
	{
        // return "qwert";
		$visaBooking = DB::table('visa_applications')
            ->leftJoin('travellers', 'travellers.id', '=', 'visa_applications.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'visa_applications.agent_id')
            ->leftJoin('users', 'users.id', '=', 'visa_applications.user_id')
            ->leftJoin('visa_types', 'visa_types.id', '=', 'visa_applications.visa_type_id')

            ->select(
                'visa_applications.id',
                'visa_applications.home_address',
                'visa_applications.destination_address',
                'visa_applications.remark',
                'visa_applications.passport',
                'visa_applications.data_page',
                'visa_applications.booked_by',

                'travellers.fullname',
                'travellers.email',

                'agents.name',
                'agents.email',
                'agents.phone',


                'users.name',
                'users.email',
                'users.phone',


                'visa_types.name',
                'visa_types.country',
                'visa_types.duration',
                'visa_types.processing_time',
                'visa_types.price',
                
                
                'visa_applications.created_at',
                'visa_applications.updated_at'
            )
            ->orderBy('visa_applications.id', 'desc')
            ->get();;
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
