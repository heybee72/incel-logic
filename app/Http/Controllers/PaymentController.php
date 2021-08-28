<?php

namespace App\Http\Controllers;

use App\Models\Hotel_booking;
use App\Models\Flight;
use App\Models\Tour_booking;
use App\Models\Visa_application;
use App\Models\Car_rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;


class PaymentController extends Controller
{
    
    public function validatePayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payment_type' =>'required',
            'user_type' =>'required',
            'reference' =>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }else{

            try {
                
                $reference    = $request->get('reference');

                $curl = curl_init();
              
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 200,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Bearer sk_test_4a0d944eb0343f43dcc13e7aeba6f7dd14c87349",
                      "Cache-Control: no-cache",
                    ),
                  ));
              
              $response = curl_exec($curl);
              $err = curl_error($curl);
              curl_close($curl);
              
              if ($err) {
                return "cURL Error #:" . $err;
              } else {
                
                $response = json_decode($response);

                if ($response->status == false) {
                    return response()->json([
                        'data'=>$response->message
                    ], 200);

                }elseif ($response->status == true) {

    		    $agent = auth('agent-api')->setToken($request->bearerToken())->user()->id;

                $payment_type = $request->get('payment_type');
                $user_type = $request->get('user_type');

                if($payment_type == 'flight'){
                    //TODO  update flight booking

                    $flight = DB::select('SELECT * FROM flights  WHERE  agent_id = ? AND payment_status = ? ORDER BY id DESC LIMIT 1 ', [$agent, 'pending']);
                    $count =  count($flight);

                    if($count == 0){
                        return response()->json([
                            'message'=>'Booking ID Not Found'
                        ], 501);
                    }

                    $lastInsertId = $flight[0]->id;

                    $update = DB::update('UPDATE flights SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                    $data = flight::find($lastInsertId);

                    if($update){

                    // return true
                    return response()->json([
                        'data'=>$data, 
                        'message'=>'Payment Successful'
                    ], 200);

                    }else{
                    //  return false
                        return response()->json([
                            'message'=>'An error occured!'
                        ], 501);
                    }

                }else{
                    if($payment_type == 'tour'){
                        $tour = DB::select('SELECT * FROM tour_bookings  WHERE  agent_id = ? AND payment_status = ? ORDER BY id DESC LIMIT 1 ', [$agent, 'pending']);
    
                         $count =  count($tour);
                         if($count == 0){
                            return response()->json([
                                'message'=>'Booking ID Not Found'
                            ], 501);
                         }

                         $lastInsertId = $tour[0]->id;

                         $update = DB::update('UPDATE tour_bookings SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                         $data = tour_booking::find($lastInsertId);

                         if($update){

                            // return true
                            return response()->json([
                                'data'=>$data, 
                                'message'=>'Payment Successful'
                            ], 200);

                         }else{
                            //  return false
                                return response()->json([
                                    'message'=>'An error occured!'
                                ], 501);
                         }


                    }else{
                        if($payment_type == 'hotel'){
                            $hotel = DB::select('SELECT * FROM hotel_bookings  WHERE  agent_id = ? AND payment_status = ? ORDER BY id DESC LIMIT 1 ', [$agent, 'pending']);

                            $count =  count($hotel);

                            if($count == 0){
                                return response()->json([
                                    'message'=>'Booking ID Not Found'
                                ], 501);
                            }

                            $lastInsertId = $hotel[0]->id;

                         $update = DB::update('UPDATE hotel_bookings SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                         $data = hotel_booking::find($lastInsertId);

                         if($update){

                            // return true
                            return response()->json([
                                'data'=>$data, 
                                'message'=>'Payment Successful'
                            ], 200);

                         }else{
                            //  return false
                                return response()->json([
                                    'message'=>'An error occured!'
                                ], 501);
                         }

                        }else{
                            if($payment_type == 'visa'){
                                $visa = DB::select('SELECT * FROM visa_applications  WHERE  agent_id = ? AND payment_status = ? ORDER BY id DESC LIMIT 1 ', [$agent, 'pending']);

                                $count =  count($visa);

                                if($count == 0){
                                    return response()->json([
                                        'message'=>'Booking ID Not Found'
                                    ], 501);
                                }
                                $lastInsertId = $visa[0]->id;

                                $update = DB::update('UPDATE visa_applications SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                                $data = visa_application::find($lastInsertId);

                                if($update){

                                    // return true
                                    return response()->json([
                                        'data'=>$data, 
                                        'message'=>'Payment Successful'
                                    ], 200);

                                }else{
                                    //  return false
                                        return response()->json([
                                            'message'=>'An error occured!'
                                        ], 501);
                                }

                            }else{
                                if($payment_type == 'car_rental'){
                                    $car_rental = DB::select('SELECT * FROM car_rentals  WHERE  agent_id = ? AND payment_status = ? ORDER BY id DESC LIMIT 1 ', [$agent, 'pending']);

                                    $count =  count($car_rental);

                                    if($count == 0){
                                        return response()->json([
                                            'message'=>'Booking ID Not Found'
                                        ], 501);
                                    }

                                    $lastInsertId = $car_rental[0]->id;

                                    

                                    $update = DB::update('UPDATE car_rentals SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                                    $data = car_rental::find($lastInsertId);

                                    if($update){

                                        // return true
                                        return response()->json([
                                            'data'=>$data, 
                                            'message'=>'Payment Successful'
                                        ], 200);

                                    }else{
                                        //  return false
                                            return response()->json([
                                                'message'=>'An error occured!'
                                            ], 501);
                                    }

                                }else{
                                    // an error occured
                                    return response()->json([
                                        'message'=>'An error occured!'
                                    ], 500);
                                }
                            }
                        }
                    }
                }
            }else{

                return response()->json([
                    'data'=>$response->message
                ], 200);
            }

                    
                
                  
                
              }


        } catch (Exception $e) {
            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        }

        }


    }



    public function validatePendingPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payment_type' =>'required',
            'user_type' =>'required',
            'reference' =>'required',
            'id' =>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }else{

            try {
                
                $reference    = $request->get('reference');

                $curl = curl_init();
              
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 200,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Bearer sk_test_4a0d944eb0343f43dcc13e7aeba6f7dd14c87349",
                      "Cache-Control: no-cache",
                    ),
                  ));
              
              $response = curl_exec($curl);
              $err = curl_error($curl);
              curl_close($curl);
              
              if ($err) {
                return "cURL Error #:" . $err;
              } else {
                
                $response = json_decode($response);

                if ($response->status == false) {
                    return response()->json([
                        'data'=>$response->message
                    ], 200);

                }elseif ($response->status == true) {

    		    $agent = auth('agent-api')->setToken($request->bearerToken())->user()->id;

                $payment_type = $request->get('payment_type');
                $user_type = $request->get('user_type');
                $id = $request->get('id');

                if($payment_type == 'flight'){
                    //TODO  update flight booking

                    $flight = DB::select('SELECT * FROM flights  WHERE  agent_id = ? AND payment_status = ? AND id = ? ', [$agent, 'pending', $id]);
                    $count =  count($flight);

                    if($count == 0){
                        return response()->json([
                            'message'=>'Booking ID Not Found'
                        ], 501);
                    }

                    $lastInsertId = $flight[0]->id;

                    $update = DB::update('UPDATE flights SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                    $data = flight::find($lastInsertId);

                    if($update){

                    // return true
                    return response()->json([
                        'data'=>$data, 
                        'message'=>'Payment Successful'
                    ], 200);

                    }else{
                    //  return false
                        return response()->json([
                            'message'=>'An error occured!'
                        ], 501);
                    }

                }else{
                    if($payment_type == 'tour'){
                        $tour = DB::select('SELECT * FROM tour_bookings  WHERE  agent_id = ? AND payment_status = ? AND id = ? ', [$agent, 'pending', $id]);
    
                         $count =  count($tour);
                         if($count == 0){
                            return response()->json([
                                'message'=>'Booking ID Not Found'
                            ], 501);
                         }

                         $lastInsertId = $tour[0]->id;

                         $update = DB::update('UPDATE tour_bookings SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                         $data = tour_booking::find($lastInsertId);

                         if($update){

                            // return true
                            return response()->json([
                                'data'=>$data, 
                                'message'=>'Payment Successful'
                            ], 200);

                         }else{
                            //  return false
                                return response()->json([
                                    'message'=>'An error occured!'
                                ], 501);
                         }


                    }else{
                        if($payment_type == 'hotel'){
                            $hotel = DB::select('SELECT * FROM hotel_bookings  WHERE  agent_id = ? AND payment_status = ? AND id = ? ', [$agent, 'pending', $id]);

                            $count =  count($hotel);

                            if($count == 0){
                                return response()->json([
                                    'message'=>'Booking ID Not Found'
                                ], 501);
                            }

                            $lastInsertId = $hotel[0]->id;

                         $update = DB::update('UPDATE hotel_bookings SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                         $data = hotel_booking::find($lastInsertId);

                         if($update){

                            // return true
                            return response()->json([
                                'data'=>$data, 
                                'message'=>'Payment Successful'
                            ], 200);

                         }else{
                            //  return false
                                return response()->json([
                                    'message'=>'An error occured!'
                                ], 501);
                         }

                        }else{
                            if($payment_type == 'visa'){
                                $visa = DB::select('SELECT * FROM visa_applications  WHERE  agent_id = ? AND id = ? ', [$agent, 'pending', $id]);

                                $count =  count($visa);

                                if($count == 0){
                                    return response()->json([
                                        'message'=>'Booking ID Not Found'
                                    ], 501);
                                }
                                $lastInsertId = $visa[0]->id;

                                $update = DB::update('UPDATE visa_applications SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                                $data = visa_application::find($lastInsertId);

                                if($update){

                                    // return true
                                    return response()->json([
                                        'data'=>$data, 
                                        'message'=>'Payment Successful'
                                    ], 200);

                                }else{
                                    //  return false
                                        return response()->json([
                                            'message'=>'An error occured!'
                                        ], 501);
                                }

                            }else{
                                if($payment_type == 'car_rental'){
                                    $car_rental = DB::select('SELECT * FROM car_rentals  WHERE  agent_id = ? AND id = ? ', [$agent, 'pending', $id]);

                                    $count =  count($car_rental);

                                    if($count == 0){
                                        return response()->json([
                                            'message'=>'Booking ID Not Found'
                                        ], 501);
                                    }

                                    $lastInsertId = $car_rental[0]->id;

                                    

                                    $update = DB::update('UPDATE car_rentals SET payment_status = ?  WHERE id =?  AND  agent_id = ? ', ['paid',$lastInsertId, $agent]);

                                    $data = car_rental::find($lastInsertId);

                                    if($update){

                                        // return true
                                        return response()->json([
                                            'data'=>$data, 
                                            'message'=>'Payment Successful'
                                        ], 200);

                                    }else{
                                        //  return false
                                            return response()->json([
                                                'message'=>'An error occured!'
                                            ], 501);
                                    }

                                }else{
                                    // an error occured
                                    return response()->json([
                                        'message'=>'An error occured!'
                                    ], 500);
                                }
                            }
                        }
                    }
                }
            }else{

                return response()->json([
                    'data'=>$response->message
                ], 200);
            }

                    
                
                  
                
              }


        } catch (Exception $e) {
            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        }

        }


    }


}
