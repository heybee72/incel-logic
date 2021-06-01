<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Hotel_booking;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use SimpleXMLElement;

class HotelBookingController extends Controller
{

/**
 * @method search cities
 * @param country
 */
public function searchCities(Request $request)
{
//
	// $client = new Client();

	$validator = Validator::make($request->all(), [
		'country' =>'required'
	]);

	if ($validator->fails()) {
		return response()->json(["message"=> 'Country name is required'], 422);
	}

// 	$search = [
// 		'username'=>env('DOTW_USERNAME'),
// 		'password'=>md5(env('DOTW_PASSWORD')),
// 		'id'=>env('COMPANY_CODE'),
// 		'source'=>1,
// 		'product'=>'hotel',
// 		'request' => ['_attributes' => ['command'=>'searchhotels'],
// 			'bookingDetails'=>[

// 		'fromDate' => Carbon::CreateFromFormat('m/d/Y',$request->input('checkin'))->format('Y-m-d'),
// 		'toDate' => Carbon::CreateFromFormat('m/d/Y',$request->input('checkout'))->format('Y-m-d') ,
// 		'currency'=>520,
// 		'rooms'=>['_attributes' => ['no'=>$request->input('rooms')],
// 			'room'=>['_attributes' => ['runno'=>0],
// 				'adultsCode'=>$request->input('adults'),
// 				'children'=>['_attributes' => ['no'=>$request->input('children')],

// 			],
// 		'rateBasis'=>-1,
// 		'passengerNationality'=>192,
// 		'passengerCountryOfResidence'=>192,

// 		]]
// 			],
// 		'return'=>['filters'=>['_attributes' => ['xmlns:a'=>env('DOTW_FILTER_COMPLEX'),'xmlns:c'=>env('DOTW_FILTER_COMPLEX')],
// 	'city'=>$request->input('location'),
// 			'noPrice'=>'true'
// ],'fields'=>['field'=>['fullAddress','hotelName','cityName','description1','geoPoint','hotelPhone']],


		// ]]];
    // $xml = ArrayToXml::convert($search, 'customer');
     //   // 
     //   // $xml2 = simplexml_load_string($resp, null, LIBXML_NOCDATA);
    // $json = json_encode($xml2);
    // 
    $find_cities = [
    'username'=>env('DOTW_USERNAME'),
        'password'=>md5(env('DOTW_PASSWORD')),
        'id'=>env('COMPANY_CODE'),
        'source'=>1,
        'request' => ['_attributes' => ['command'=>'getallcities'],
            'return'=>[
                'filters'=>[
                'countryCode'=>[''],
                'countryName'=>[ $request->input('country') ],
            ],
            'fields'=>['field'=>['countryName','countryCode'],
                ],
            ]
   
        ]
    ];

	$xml = ArrayToXml::convert($find_cities, 'customer');

	$context = stream_context_create(array(
		'http' => array(
			'method'  => 'POST',
			'header'  => "Content-type: text/xml",
			'content' => $xml,
			'timeout' => 20,
		),
	));

    try{

    // $resp = file_get_contents('http://xmldev.dotwconnect.com/request.dotw', false, $context);
    	$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
    //        $resp=new SimpleXMLElement($resp);

    	// $resp=str_replace('%','',$resp);
    	// $resp=str_replace('&','',$resp);
    	// $resp=str_replace('<![CDATA[','',$resp);
    	// $resp=str_replace(']]>','',$resp);



    //        dd($resp);
    	

        $xml = simplexml_load_string($resp, null, LIBXML_NOCDATA);
        $json = json_encode($xml);
        // return $json;
    	$array = json_decode($json);
    	// $response=json_encode( $resp);
    	// $response=json_decode($response,TRUE);
    //        dd($response);
    //        dd();
   
    if (isset($array->cities->city)){
        return response()->json(['cities'=>$array->cities->city, 'message'=>'Success'], 200);
    }
    return response()->json(['cities'=>[], 'message'=>'Success'], 200);

}catch(\Throwable $e){

    return response()->json(['cities'=>[], 'message'=>'failed'], 500);
}
// return $array;
// return isset($array->cities->city) ? $array->cities->city : [];


}
/**
 * @method search cities
 */



/**
 * @method search hotels
 * @param country
 */
public function searchHotels(Request $request)
{
//
    // $client = new Client();

    $validator = Validator::make($request->all(), [
        'rooms' =>'required'
    ]);

    if ($validator->fails()) {
        return response()->json(["message"=> 'Country name is required'], 422);
    }

 $search = [
     'username'=>env('DOTW_USERNAME'),
     'password'=>md5(env('DOTW_PASSWORD')),
     'id'=>env('COMPANY_CODE'),
     'source'=>1,
     'product'=>'hotel',
     'request' => ['_attributes' => ['command'=>'searchhotels'],
         'bookingDetails'=>[

     'fromDate' => Carbon::CreateFromFormat('m/d/Y',$request->input('checkin'))->format('Y-m-d'),
     'toDate' => Carbon::CreateFromFormat('m/d/Y',$request->input('checkout'))->format('Y-m-d') ,
     'currency'=>520,
     'rooms'=>['_attributes' => ['no'=>$request->input('rooms')],
         'room'=>['_attributes' => ['runno'=>0],
             'adultsCode'=>$request->input('adults'),
             'children'=>['_attributes' => ['no'=>$request->input('children')],

         ],
     'rateBasis'=>-1,
     'passengerNationality'=>192,
     'passengerCountryOfResidence'=>192,

     ]]
         ],
     'return'=>['filters'=>['_attributes' => ['xmlns:a'=>env('DOTW_FILTER_COMPLEX'),'xmlns:c'=>env('DOTW_FILTER_COMPLEX')],
 'city'=>$request->input('location'),
         'noPrice'=>'true'
],'fields'=>['field'=>['fullAddress','hotelName','cityName','description1','geoPoint','hotelPhone']],


        ]]];
    $xml = ArrayToXml::convert($search, 'customer');

    $context = stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Content-type: text/xml",
            'content' => $xml,
            'timeout' => 20,
        ),
    ));

    try{

    // $resp = file_get_contents('http://xmldev.dotwconnect.com/request.dotw', false, $context);
        $resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
    //        $resp=new SimpleXMLElement($resp);

        // $resp=str_replace('%','',$resp);
        // $resp=str_replace('&','',$resp);
        // $resp=str_replace('<![CDATA[','',$resp);
        // $resp=str_replace(']]>','',$resp);



    //        dd($resp);
        

        $xml = simplexml_load_string($resp, null, LIBXML_NOCDATA);
        $json = json_encode($xml);
        // return $json;
        $array = json_decode($json);
        // $response=json_encode( $resp);
        // $response=json_decode($response,TRUE);
    //        dd($response);
    //        dd();
    // return $array;
    if (isset($array->hotels->hotel)){
        // array_map(function($v){
            
        // }, $array->hotels->hotel);
        return response()->json(['hotels'=>$array->hotels->hotel, 'message'=>'Success'], 200);
    }
    return response()->json(['hotels'=>[], 'message'=>'Success'], 200);

}catch(\Throwable $e){

    return response()->json(['hotels'=>[], 'message'=>'failed'], 500);
}
// return $array;
// return isset($array->cities->city) ? $array->cities->city : [];


}
/**
 * @method search search hotels
 */

/*----------User Add Hotel Booking----------*/
	
	public function userAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'location' =>'required',
    		'check_in' =>'required',
    		'check_out' =>'required',
    		'rooms' =>'required',
    		'no_children' =>'required',
    		'residency' =>'required',
    		'nationality' =>'required',
    		'no_adult' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {
    		
        	$hotel_booking   = new hotel_booking();

        	$hotel_booking->user_id     = $request->get('user_id');
        	$hotel_booking->location    = $request->get('location');
        	$hotel_booking->check_in    = $request->get('check_in');
        	$hotel_booking->check_out   = $request->get('check_out');
        	$hotel_booking->rooms     = $request->get('rooms');
        	$hotel_booking->no_children    = $request->get('no_children');
        	$hotel_booking->residency     = $request->get('residency');
        	$hotel_booking->nationality    = $request->get('nationality');
        	$hotel_booking->no_adult     = $request->get('no_adult');
        	$hotel_booking->booked_by     = 'user';
        	$hotel_booking->save();

        	//TODO: send mail to admin here 
        	
    		return response()->json([
    			'hotel_booking'=>$hotel_booking, 
    			'message'=>'Hotel booking Created Successfully'
    		], 201);

    	} catch (Exception $e) {

    		return response()->json([
    			'message'=>'An error occurred', 
    			'error'=>$e->message()
    		], 422);
    		
    	}

    }

/*----------./User Add Hotel booking ----------*/



/*----------agent Add Hotel  Booking----------*/
	public function agentAdd(Request $request){
    	$validator = Validator::make($request->all(), [
    		'location' =>'required',
    		'check_in' =>'required',
    		'check_out' =>'required',
    		'rooms' =>'required',
    		'no_children' =>'required',
    		'residency' =>'required',
    		'nationality' =>'required',
    		'no_adult' =>'required',
    		'traveller_id' =>'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$agent = auth('agent-api')->setToken($request->bearerToken())->user();
    		
	        if ($agent == NULL) {

	            return response()->json(['message'=>'Agent not found!'], 400);
	        }
    		
        	$hotel_booking   = new hotel_booking();
        	$hotel_booking->location    = $request->get('location');
        	$hotel_booking->check_in    = $request->get('check_in');
        	$hotel_booking->check_out   = $request->get('check_out');
        	$hotel_booking->rooms     = $request->get('rooms');
        	$hotel_booking->no_children    = $request->get('no_children');
        	$hotel_booking->residency     = $request->get('residency');
        	$hotel_booking->nationality    = $request->get('nationality');
        	$hotel_booking->no_adult     = $request->get('no_adult');
        	$hotel_booking->traveller_id     = $request->get('traveller_id');
        	$hotel_booking->agent_id     = $agent->id;
        	$hotel_booking->booked_by     = 'agent';
        	$hotel_booking->save();

        	// send mail to admin here 
        	
    		return response()->json(['hotel_booking'=>$hotel_booking, 'message'=>'Hotel booking Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add Hotel booking----------*/




/*----------Admin View all hotel Booking----------*/
    public function index()
	{
		$hotelBooking = hotel_booking::all();
    	return response()->json(['hotelBooking'=>$hotelBooking, 'message'=>'hotel Bookings fetched Successfully'], 200);
	}

/*----------./Admin View all hotel Booking----------*/




/*----------Admin View all users hotel Booking----------*/
    
    public function userBookings()
	{
		$hotelBooking = DB::select(
        'SELECT * From hotel_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['user']
        );

    	return response()->json([
    		'hotelBooking'=>$hotelBooking, 
    		'message'=>'hotel Bookings for users fetched Successfully'
    	], 200);
	}

/*----------./Admin View all users Tour Booking----------*/


/*----------Admin View all Agent Tour Booking----------*/
    
    public function agentBookings()
	{
		$hotelBooking = DB::select(
        'SELECT * From hotel_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC', ['agent']
        );

    	return response()->json([
    		'hotelBooking'=>$hotelBooking, 
    		'message'=>'hotel Bookings for users fetched Successfully'
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


		$hotelBooking = DB::select(
        'SELECT * From hotel_bookings 
            WHERE agent_id = ?  
            ORDER BY id DESC', [$agent->id]
        );

    	return response()->json([
    		'hotelBooking'=>$hotelBooking, 
    		'message'=>'Hotel booking Bookings for '.$agent->name.' fetched Successfully'
    	], 200);
	}

/*----------./Agent View his bookings----------*/



/*----------Admin View single Tour Booking----------*/

	public function view($id)
    {
        $hotelBooking = hotel_booking::find($id);
        return response()->json(['hotelBooking'=>$hotelBooking, 'message'=>'hotel Booking fetched Successfully'], 200);
    }

/*----------./Admin View single Tour Booking----------*/


/*DELETE DATA*/ 
	    public function delete($id)
	    {
	    	$hotelBooking = hotel_booking::find($id);
	        if ($hotelBooking == NULL) {
	            return response()->json([
	            'message'=> 'An error occurred!'
	        ], 500);
	        }
	            $hotelBooking->delete();

	        return response()->json([
	            'message'=> 'hotel Booking Deleted Successfully!'
	        ], 200); 
	    }
/*DELETE DATA*/ 

}
