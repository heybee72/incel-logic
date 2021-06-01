<?php

namespace App\Http\Controllers;


use App\Models\Car_rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class CarRentalController extends Controller
{

   


	public function agentAdd(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'pickup_location' =>'required',
        		'destination' =>'required',
        		'no_of_passengers' =>'required',
        		'car_type' =>'required',
        		'journey_type' =>'required',
        		'traveller_id' =>'required',
        		'pickup_date' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {

               $agent = auth('agent-api')->setToken($request->bearerToken())->user();
	    		
		        if ($agent == NULL) {

		            return response()->json(['message'=>'Agent not found!'], 400);
		        }


                	$car_rental             = new car_rental();
        			$car_rental->pickup_location     = $request->get('pickup_location');
        			$car_rental->destination     = $request->get('destination');
        			$car_rental->no_of_passengers     = $request->get('no_of_passengers');
        			$car_rental->car_type     = $request->get('car_type');
        			$car_rental->journey_type     = $request->get('journey_type');
        			$car_rental->traveller_id     = $request->get('traveller_id');
        			$car_rental->pickup_date     = $request->get('pickup_date');
        			$car_rental->agent_id     = $agent->id;
                	$car_rental->save();


            		return response()->json(['car_rental'=>$car_rental, 'message'=>'car rental Created Successfully'], 200);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }

    public function userAdd(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'pickup_location' =>'required',
        		'destination' =>'required',
        		'no_of_passengers' =>'required',
        		'car_type' =>'required',
        		'journey_type' =>'required',
        		'pickup_date' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               
        		$user = auth()->setToken($request->bearerToken())->user();
	    		
		        if ($user == NULL) {

		            return response()->json(['message'=>'User not found!'], 400);
		        }

                	$car_rental             = new car_rental();
        			$car_rental->pickup_location     = $request->get('pickup_location');
        			$car_rental->destination     = $request->get('destination');
        			$car_rental->no_of_passengers     = $request->get('no_of_passengers');
        			$car_rental->car_type     = $request->get('car_type');
        			$car_rental->journey_type     = $request->get('journey_type');
        			$car_rental->traveller_id     = $request->get('traveller_id');
        			$car_rental->pickup_date     = $request->get('pickup_date');
        			$car_rental->user_id     = $user->id;

                	$car_rental->save();


            		return response()->json(['car_rental'=>$car_rental, 'message'=>'car rental Created Successfully'], 200);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }



 	public function agent()
	{
		$car_rental = DB::table('car_rentals')
            ->leftJoin('travellers', 'travellers.id', '=', 'car_rentals.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'car_rentals.agent_id')

            ->select(
                'car_rentals.id',

                'travellers.fullname',
                'travellers.email',

                'agents.name',
                'agents.email',
                'agents.phone',

                'pickup_location',
                'destination',
                'no_of_passengers',
                'car_type',
                'journey_type',
                'pickup_date',
                'cost',
                'payment_status',
                
                'car_rentals.created_at',
                'car_rentals.updated_at'
            )
            ->orderBy('car_rentals.id', 'desc')
            ->get();

    	return response()->json(['car_rental'=>$car_rental, 'message'=>'agent car rentals fetched Successfully'], 200);
	}


	public function user()
	{
		$car_rental = DB::table('car_rentals')
            ->leftJoin('users', 'users.id', '=', 'car_rentals.user_id')

            ->select(
                'car_rentals.id',
                
                'users.name',
                'users.email',
                'users.phone',

                'pickup_location',
                'destination',
                'no_of_passengers',
                'car_type',
                'journey_type',
                'pickup_date',
                'cost',
                'payment_status',
                
                'car_rentals.created_at',
                'car_rentals.updated_at'
            )
            ->orderBy('car_rentals.id', 'desc')
            ->get();

    	return response()->json(['car_rental'=>$car_rental, 'message'=>'car rentals fetched Successfully'], 200);
	}



    public function userView($id)
    {
        $car_rental = DB::table('car_rentals')
            ->leftJoin('users', 'users.id', '=', 'car_rentals.user_id')

            ->select(
                'car_rentals.id',
                
                'users.name',
                'users.email',
                'users.phone',

                'pickup_location',
                'destination',
                'no_of_passengers',
                'car_type',
                'journey_type',
                'pickup_date',
                'cost',
                'payment_status',
                
                'car_rentals.created_at',
                'car_rentals.updated_at'
            )
            ->take(1)
            ->where('car_rentals.id', $id)
            ->get();
        return response()->json(['car_rental'=>$car_rental, 'message'=>'car rental fetched Successfully'], 200);
    }



    public function agentView(Request $request)
    {

        $agent = auth('agent-api')->setToken($request->bearerToken())->user();
            
            if ($agent == NULL) {

                return response()->json(['message'=>'Agent not found!'], 400);
            }


        $car_rental = DB::table('car_rentals')
            ->leftJoin('travellers', 'travellers.id', '=', 'car_rentals.traveller_id')
            ->leftJoin('agents', 'agents.id', '=', 'car_rentals.agent_id')

            ->select(
                'car_rentals.id',

                'travellers.fullname',
                'travellers.email',

                'agents.name',
                'agents.email',
                'agents.phone',

                'pickup_location',
                'destination',
                'no_of_passengers',
                'car_type',
                'journey_type',
                'pickup_date',
                'cost',
                'payment_status',
                
                'car_rentals.created_at',
                'car_rentals.updated_at'
            )
            ->where('car_rentals.agent_id', $agent->id)
            ->get();
        return response()->json(['car_rental'=>$car_rental, 'message'=>'car rental fetched Successfully'], 200);
    }


  

/*DELETE DATA*/ 
    public function delete($id)
    {
    	$car_rental = car_rental::find($id);

        if ($car_rental == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $car_rental->delete();

        return response()->json([
            'message'=> 'car rental Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 

}
