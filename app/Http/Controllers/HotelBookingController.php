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
use Mtownsend\XmlToArray\XmlToArray;

class HotelBookingController extends Controller
{

	public function searchCities(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'country' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(["message" => 'Country name is required'], 422);
		}

		$find_cities = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'request' => [
				'_attributes' => ['command' => 'getallcities'],
				'return' => [
					'filters' => [
						'countryCode' => [''],
						'countryName' => [$request->input('country')],
					],
					'fields' => [
						'field' => ['countryName', 'countryCode'],
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

		try {
			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
	
			$xml = simplexml_load_string($resp, null, LIBXML_NOCDATA);
			$json = json_encode($xml);
			// return $json;
			$array = json_decode($json);
			

			if (isset($array->cities->city)) {
				return response()->json(['cities' => $array->cities->city, 'message' => 'Success'], 200);
			}
			return response()->json(['cities' => [], 'message' => 'Success'], 200);
		} catch (\Throwable $e) {

			return response()->json(['cities' => [], 'message' => 'failed'], 500);
		}
		// return $array;
		// return isset($array->cities->city) ? $array->cities->city : [];


	}
	
	public function getcurrenciesids(Request $request)
	{	
		$getcurrenciesids = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'request' => [
				'_attributes' => ['command' => 'getcurrenciesids'],
			]
		];

		$xml = ArrayToXml::convert($getcurrenciesids, 'customer');

		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'GET',
				'header'  => "Content-type: text/xml",
				'content' => $xml,
				'timeout' => 20,
			),
		));

		try {
			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
			$array = XmlToArray::convert($resp);
			return response()->json(['currency' => $array['currency']['option'], 'message' => 'Success'], 200);
				
		} catch (\Throwable $e) {

			return response()->json(['data' => [], 'message' => 'failed'], 500);
		}


	}

	public function getallcountries(Request $request)
	{	
		$getallcountries = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'request' => [
				'_attributes' => ['command' => 'getallcountries'],
			]
		];

		$xml = ArrayToXml::convert($getallcountries, 'customer');

		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'GET',
				'header'  => "Content-type: text/xml",
				'content' => $xml,
				'timeout' => 20,
			),
		));

		try {
			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
			$array = XmlToArray::convert($resp);
			return response()->json(['countries' => $array['countries']['country'], 'message' => 'Success'], 200);
				
		} catch (\Throwable $e) {

			return response()->json(['data' => [], 'message' => 'failed'], 500);
		}


	}

	public function getratebasisids(Request $request)
	{	
		$getratebasisids = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'request' => [
				'_attributes' => ['command' => 'getratebasisids'],
			]
		];

		$xml = ArrayToXml::convert($getratebasisids, 'customer');

		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'GET',
				'header'  => "Content-type: text/xml",
				'content' => $xml,
				'timeout' => 20,
			),
		));

		try {
			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);
			 $array = XmlToArray::convert($resp);
			return response()->json(['ratebasis' => $array['ratebasis']['option'], 'message' => 'Success'], 200);
				
		} catch (\Throwable $e) {

			return response()->json(['data' => [], 'message' => 'failed'], 500);
		}


	}


	public function searchHotels(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'rooms' => 'required',
			'checkin' =>'required',
			'checkout' =>'required',
			'currency' =>'required',
			'city' =>'required',

			'adults' =>'required',
			'child' =>'required',
			'rateBasis' =>'required',
			'passengerNationality' =>'required',
			'passengerCountryOfResidence' =>'required',
			

		]);

		if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}

		$rooms     = $request->get('rooms');
		$city     = $request->get('city');
		$fromDate     = $request->get('checkin');
		$toDate     = $request->get('checkout');
		$currency     = $request->get('currency');
		$adults     = $request->get('adults');
		$child     = $request->get('child');
		$childAge     = $request->get('$childAge');
		$passengerNationality     = $request->get('passengerNationality');
		$passengerCountryOfResidence     = $request->get('passengerCountryOfResidence');
		$rateBasis     = $request->get('rateBasis');

		$search = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'product' => 'hotel',
			'request' => [
				'_attributes' => ['command' => 'searchhotels'],
				'bookingDetails' => [
					'fromDate' => Carbon::CreateFromFormat('Y-m-d', $fromDate)->format('Y-m-d'),
					'toDate' => Carbon::CreateFromFormat('Y-m-d', $toDate)->format('Y-m-d'),
					'currency' => $currency,
					'rooms' => [
						'_attributes' => ['no' => $rooms],
						'room' => [
							'_attributes' => ['runno' => 0],
							'adultsCode' => $adults,
							'children' => [
								'_attributes' => ['no' => $child],

							],
							'rateBasis' => $rateBasis,
							'passengerNationality' => $passengerNationality,
							'passengerCountryOfResidence' => $passengerCountryOfResidence,

						]
					]
				],
				'return' => [
					'filters' => [
						'_attributes' => ['xmlns:a' => env('DOTW_FILTER_COMPLEX'), 'xmlns:c' => env('DOTW_FILTER_COMPLEX')],
						'city' => $city,
						'noPrice' => 'true'
					], 'fields' => 
						[
							'field' => 	
							[
								'fullAddress', 
								'hotelName', 
								'cityName', 
								'description1', 
								'geoPoint', 
								'hotelPhone', 
								'countryName', 
								'amenitie', 
								'hotelPhone', 
								'images',
								'rating',
								'hotelCheckIn', 
								'hotelCheckOut',
								'transportation',
								'hotelPreference'
							]
						],


				]
			]
		];
		$xml = ArrayToXml::convert($search, 'customer');

		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => "Content-type: text/xml",
				'content' => $xml,
				'timeout' => 20,
			),
		));

		try {

			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);

			$array = XmlToArray::convert($resp);
				return response()->json(['hotels' => $array['hotels']['hotel'], 'message' => 'Success'], 200);

		} catch (\Throwable $e) {

			return response()->json(['hotels' => [], 'message' => 'failed'], 500);
		}
	
	}
	

	public function searchRooms(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'rooms' => 'required',
			'checkin' =>'required',
			'checkout' =>'required',
			'currency' =>'required',
			'hotel_id' =>'required',
			'adults' =>'required',
			'child' =>'required',
			'rateBasis' =>'required',
			'passengerNationality' =>'required',
			'passengerCountryOfResidence' =>'required',
			

		]);

		if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}

		$rooms     = $request->get('rooms');
		$checkin     = $request->get('checkin');
		$checkout     = $request->get('checkout');
		$currency     = $request->get('currency');
		$adults     = $request->get('adults');
		$child     = $request->get('child');
		$hotel_id     = $request->get('hotel_id');
		$passengerNationality     = $request->get('passengerNationality');
		$passengerCountryOfResidence     = $request->get('passengerCountryOfResidence');
		$rateBasis     = $request->get('rateBasis');

		$search = [
			'username' => env('DOTW_USERNAME', "INCEL TOURISM"),
			'password' => md5(env('DOTW_PASSWORD', 'Teams007@')),
			'id' => env('COMPANY_CODE', "1584165"),
			'source' => 1,
			'product' => 'hotel',
			'request' => [
				'_attributes' => ['command' => 'getrooms'],
				'bookingDetails' => [

					'fromDate' => Carbon::CreateFromFormat('Y-m-d', $fromDate)->format('Y-m-d'),
					'toDate' => Carbon::CreateFromFormat('Y-m-d', $toDate)->format('Y-m-d'),
					'currency' => $currency,
					'rooms' => [
						'_attributes' => ['no' => $rooms],
						'room' => [
							'_attributes' => ['runno' => 0],
							'adultsCode' => $adults,
							'children' => [
								'_attributes' => ['no' => $child],
                                // 'child' => $request->input('children')
							],
							'rateBasis' => $rateBasis,
							'passengerNationality' => $passengerNationality,
							'passengerCountryOfResidence' => $passengerCountryOfResidence,

						]
					],
					'productId' =>$hotel_id,
				],
				
			]
		];
		 $xml = ArrayToXml::convert($search, 'customer');

		$context = stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => "Content-type: text/xml",
				'content' => $xml,
				'timeout' => 20,
			),
		));

		try {

			$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);


			$xml = simplexml_load_string($resp, null, LIBXML_NOCDATA);
			$json = json_encode($xml);
			
			return $array = json_decode($json, true);
	
			if (isset($array['hotel'])) {
				
				$data = [
					'hotel_id' => $array['hotel']['@attributes']['id'],
					'hotel_name' => $array['hotel']['@attributes']['name'],
					'allowBook' => $array['hotel']['allowBook'],
					'rooms' =>  array_map(function ($room) {
						$basis = $room['rateBases']['rateBasis'];
						return [
							'room_code' => $room['@attributes']['roomtypecode'],
							'room_name' => $room['name'],
							'room_twin' => $room['twin'],
							'room_info' => $room['roomInfo'],
							'rates' => array_key_exists(0, $basis) 
							? array_map(function ($rate) {
								return [
									'status' => $rate['status'],
									'isBookable' => $rate['isBookable'],
									'description' => $rate['@attributes']['description'],
									'amount' => $rate['total'],
									'tariffNotes' => str_replace("\n", '<br/>', $rate['tariffNotes']),
								];
							}, $basis) 
							: [
								'status' => $basis['status'],
								'isBookable' => $basis['isBookable'],
								'description' => $basis['@attributes']['description'],
								'amount' => $basis['total'],
								'tariffNotes' => str_replace("\n", '<br/>', $basis['tariffNotes']),
							]
						    	
						];
					}, 
					$array['hotel']['rooms']['room']['roomType'])

				];
				return response()->json($data, 200);
			}
		} catch (\Throwable $e) {

			return response()->json(['hotels' => [], 'message' => $e->getMessage()], 500);
		}


	}


	// public function confirmbooking(Request $request)
	// {
	// 	$validator = Validator::make($request->all(), [
	// 		'rooms' => 'required',
	// 		'checkin' =>'required',
	// 		'checkout' =>'required',
	// 		'currency' =>'required',
	// 		'city' =>'required',
	// 		'adults' =>'required',
	// 		'child' =>'required',
	// 		'rateBasis' =>'required',
	// 		'passengerNationality' =>'required',
	// 		'passengerCountryOfResidence' =>'required',		
	// 	]);

	// 	if ($validator->fails()) {
    // 		return response()->json(["message"=> $validator->errors()], 422);
    // 	}

	// 	$rooms     = $request->get('rooms');
	// 	$city     = $request->get('city');
	// 	$fromDate     = $request->get('checkin');
	// 	$toDate     = $request->get('checkout');
	// 	$currency     = $request->get('currency');
	// 	$adults     = $request->get('adults');
	// 	$child     = $request->get('child');
	// 	$childAge     = $request->get('$childAge');
	// 	$passengerNationality     = $request->get('passengerNationality');
	// 	$passengerCountryOfResidence     = $request->get('passengerCountryOfResidence');
	// 	$rateBasis     = $request->get('rateBasis');

	// 	$search = 		
	// 	'
	// 	<customer>  
	// 		<username>'.env('DOTW_USERNAME', "INCEL TOURISM").'</username>  
	// 		<password>'.md5(env('DOTW_PASSWORD', 'Teams007@')).'</password>  
	// 		<id>'.env('COMPANY_CODE', "1584165").'</id>  
	// 		<source>1</source>  
	// 		<product>hotel</product>  
	// 		<request command="confirmbooking">  
	// 			<bookingDetails>  
	// 				<parent></parent>  
	// 				<bookingCode></bookingCode>  
	// 				<addToBookedItn></addToBookedItn>  
	// 				<bookedItnParent></bookedItnParent>  
	// 				<fromDate></fromDate>  
	// 				<toDate></toDate>  
	// 				<currency></currency>  
	// 				<productId></productId>  
	// 				<customerReference></customerReference>  
	// 				<rooms no="">  
	// 					<room runno="">  
	// 						<roomTypeCode></roomTypeCode>  
	// 						<selectedRateBasis></selectedRateBasis>  
	// 						<allocationDetails></allocationDetails>  
	// 						<adultsCode></adultsCode>  
	// 						<actualAdults></actualAdults>  
	// 						<children no="">  
	// 							<child runno=""></child>  
	// 						</children>  
	// 						<actualChildren no="">  
	// 							<actualChild runno="0"></actualChild>  
	// 						</actualChildren>  
	// 						<extraBed></extraBed>  
	// 						<passengerNationality></passengerNationality>  
	// 						<passengerCountryOfResidence></passengerCountryOfResidence>  
	// 						<selectedExtraMeals>  
	// 							<mealPlanDate mealplandatetime="">  
	// 								<mealPlan applicablefor="" childage=""  ispassenger="" mealscount=""  passengernumber="" runno="">  
	// 									<meal runno="">  
	// 										<mealTypeCode></mealTypeCode>  
	// 										<units></units>  
	// 										<mealPrice></mealPrice>  
	// 									</meal>  
	// 								</mealPlan>  
	// 							</mealPlanDate>  
	// 						</selectedExtraMeals>  
	// 						<passengersDetails>  
	// 							<passenger leading="">  
	// 								<salutation></salutation>  
	// 								<firstName></firstName>  
	// 								<lastName></lastName>  
	// 							</passenger>  
	// 						</passengersDetails>  
	// 						<specialRequests count="">  
	// 							<req runno=""></req>  
	// 						</specialRequests>  
	// 						<beddingPreference></beddingPreference>  
	// 					</room>  
	// 				</rooms>  
	// 			</bookingDetails>  
	// 		</request>  
	// 	</customer> 
		
	// 	';

	// 	$context = stream_context_create(array(
	// 		'http' => array(
	// 			'method'  => 'POST',
	// 			'header'  => "Content-type: text/xml",
	// 			'content' => $xml,
	// 			'timeout' => 20,
	// 		),
	// 	));

	// 	try {

	// 		$resp = file_get_contents('http://xmldev.dotwconnect.com/gatewayV4.dotw', false, $context);

	// 		$array = XmlToArray::convert($resp);
	// 			return response()->json(['hotels' => $array['hotels']['hotel'], 'message' => 'Success'], 200);

	// 	} catch (\Throwable $e) {

	// 		return response()->json(['hotels' => [], 'message' => 'failed'], 500);
	// 	}
	
	// }



	/*----------User Add Hotel Booking----------*/

	public function userAdd(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'location' => 'required',
			'check_in' => 'required',
			'check_out' => 'required',
			'rooms' => 'required',
			'no_children' => 'required',
			'residency' => 'required',
			'nationality' => 'required',
			'no_adult' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(["message" => $validator->errors()], 422);
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
				'hotel_booking' => $hotel_booking,
				'message' => 'Hotel booking Created Successfully'
			], 201);
		} catch (Exception $e) {

			return response()->json([
				'message' => 'An error occurred',
				'error' => $e->message()
			], 422);
		}
	}

	/*----------./User Add Hotel booking ----------*/



	/*----------agent Add Hotel  Booking----------*/
	public function agentAdd(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'location' => 'required',
			'check_in' => 'required',
			'check_out' => 'required',
			'rooms' => 'required',
			'no_children' => 'required',
			'residency' => 'required',
			'nationality' => 'required',
			'no_adult' => 'required',
			'traveller_id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(["message" => $validator->errors()], 422);
		}
		try {

			$agent = auth('agent-api')->setToken($request->bearerToken())->user();

			if ($agent == NULL) {

				return response()->json(['message' => 'Agent not found!'], 400);
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

			return response()->json(['hotel_booking' => $hotel_booking, 'message' => 'Hotel booking Created Successfully'], 201);
		} catch (Exception $e) {

			return response()->json(['message' => 'An error occurred', 'error' => $e->message()], 422);
		}
	}
	/*----------./agent Add Hotel booking----------*/




	/*----------Admin View all hotel Booking----------*/
	public function index()
	{
		$hotelBooking = hotel_booking::all();
		return response()->json(['hotelBooking' => $hotelBooking, 'message' => 'hotel Bookings fetched Successfully'], 200);
	}

	/*----------./Admin View all hotel Booking----------*/




	/*----------Admin View all users hotel Booking----------*/

	public function userBookings()
	{
		$hotelBooking = DB::select(
			'SELECT * From hotel_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC',
			['user']
		);

		return response()->json([
			'hotelBooking' => $hotelBooking,
			'message' => 'hotel Bookings for users fetched Successfully'
		], 200);
	}

	/*----------./Admin View all users Tour Booking----------*/


	/*----------Admin View all Agent Tour Booking----------*/

	public function agentBookings()
	{
		$hotelBooking = DB::select(
			'SELECT * From hotel_bookings 
            WHERE booked_by = ?  
            ORDER BY id DESC',
			['agent']
		);

		return response()->json([
			'hotelBooking' => $hotelBooking,
			'message' => 'hotel Bookings for users fetched Successfully'
		], 200);
	}

	/*----------./Admin View all agent Tour Booking----------*/




	/*----------Agent View his bookings----------*/

	public function agentView(Request $request)
	{

		$agent = auth('agent-api')->setToken($request->bearerToken())->user();

		if ($agent == NULL) {

			return response()->json(['message' => 'Agent not found!'], 400);
		}


		$hotelBooking = DB::select(
			'SELECT * From hotel_bookings 
            WHERE agent_id = ?  
            ORDER BY id DESC',
			[$agent->id]
		);

		return response()->json([
			'hotelBooking' => $hotelBooking,
			'message' => 'Hotel booking Bookings for ' . $agent->name . ' fetched Successfully'
		], 200);
	}

	/*----------./Agent View his bookings----------*/



	/*----------Admin View single Tour Booking----------*/

	public function view($id)
	{
		$hotelBooking = hotel_booking::find($id);
		return response()->json(['hotelBooking' => $hotelBooking, 'message' => 'hotel Booking fetched Successfully'], 200);
	}

	/*----------./Admin View single Tour Booking----------*/


	/*DELETE DATA*/
	public function delete($id)
	{
		$hotelBooking = hotel_booking::find($id);
		if ($hotelBooking == NULL) {
			return response()->json([
				'message' => 'An error occurred!'
			], 500);
		}
		$hotelBooking->delete();

		return response()->json([
			'message' => 'hotel Booking Deleted Successfully!'
		], 200);
	}
	/*DELETE DATA*/
}
