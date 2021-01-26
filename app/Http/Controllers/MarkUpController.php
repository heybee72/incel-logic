<?php

namespace App\Http\Controllers;

use App\Models\Mark_up;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MarkUpController extends Controller
{

    public function index()
	{
		$mark_up = DB::table('mark_ups')
            ->leftJoin('admins', 'admins.id', '=', 'mark_ups.admin_id')
            ->leftJoin('mark_up_types', 'mark_up_types.slug', '=', 'mark_ups.markup')

            ->select('mark_ups.id','markup','mark_up_types.markup_type','agent_absolute_value','agent_percentage_value','customer_absolute_value','customer_percentage_value','agent_selected','customer_selected','name','email','mark_ups.created_at','mark_ups.updated_at')
            ->orderBy('mark_ups.id', 'desc')
            ->get();

    	return response()->json(['mark_up'=>$mark_up, 'message'=>'mark_up fetched Successfully'], 200);
	}



/*----------Add mark_up Booking----------*/
	public function add(Request $request){
    	$validator = Validator::make($request->all(), [
    		'markup' =>'required|string',
    		'agent_absolute_value' =>'required',
    		'agent_percentage_value' =>'required',
    		'customer_absolute_value' =>'required',
    		'customer_percentage_value' =>'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$admin = auth('admin-api')->setToken($request->bearerToken())->user();
    		
	        if ($admin == NULL) {

	            return response()->json(['message'=>'admin not found!'], 400);
	        }
    		
        	$mark_up   = new mark_up();
        	$mark_up->markup     = $request->get('markup');
        	$mark_up->agent_absolute_value     = $request->get('agent_absolute_value');
        	$mark_up->agent_percentage_value     = $request->get('agent_percentage_value');
        	$mark_up->customer_absolute_value     = $request->get('customer_absolute_value');
        	$mark_up->customer_percentage_value     = $request->get('customer_percentage_value');
        	$mark_up->admin_id     = $admin->id;
        	$mark_up->save();

        	// send mail to admin here 
        	
    		return response()->json(['mark_up'=>$mark_up, 'message'=>'mark up Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./Add mark_up Booking---------*/



    public function view($slug)
    {
        $mark_up = DB::table('mark_ups')
            ->leftJoin('admins', 'admins.id', '=', 'mark_ups.admin_id')
            ->leftJoin('mark_up_types', 'mark_up_types.slug', '=', 'mark_ups.markup')

            ->select('mark_ups.id','mark_up_types.markup_type','agent_absolute_value','agent_percentage_value','customer_absolute_value','customer_percentage_value','agent_selected','customer_selected','name','email','mark_ups.created_at','mark_ups.updated_at')
            ->orderBy('mark_ups.id', 'desc')
            ->take(1)
            ->where('mark_ups.markup', $slug)
            ->get();
        return response()->json(['mark_up'=>$mark_up, 'message'=>'mark up fetched Successfully'], 200);
    }



/*Update Data*/ 
        
    public function update_agent(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'agent_selected' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$mark_up = mark_up::find($id);
    		$mark_up->agent_selected     = $request->get('agent_selected');
            
            if ($mark_up->save()) {
                return response()->json([
                    'mark_up'=>$mark_up, 
                    'message'=>'mark up for agent updated to '.$mark_up->agent_selected
                ], 201);
            }else{

                return response()->json([
                    'message'=>'An error occured!'
                ], 501);

            }

        } catch (Exception $e) {

            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
            
        }

    }


    public function update_customer(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'customer_selected' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$mark_up = mark_up::find($id);
    		$mark_up->customer_selected     = $request->get('customer_selected');
            
            if ($mark_up->save()) {
                return response()->json([
                    'mark_up'=>$mark_up, 
                    'message'=>'mark up for customer updated to '.$mark_up->customer_selected
                ], 201);
            }else{

                return response()->json([
                    'message'=>'An error occured!'
                ], 501);

            }

        } catch (Exception $e) {

            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
            
        }

    }

/*Update Data*/ 


    public function view_selected_agent_value($slug)
    {
        $mark_up = DB::table('mark_ups')
            ->select('agent_absolute_value', 'agent_percentage_value', 'agent_selected')
            ->orderBy('mark_ups.id', 'desc')
            ->take(1)
            ->where('mark_ups.markup', $slug)
            ->first(); 
            
            if ($mark_up->agent_selected == 'absolute_value') {
            	return response()->json([
            		'mark_up'=>$mark_up->agent_absolute_value, 
            		'type'=> $mark_up->agent_selected,
        			'message'=>'mark up fetched Successfully'
        		], 200);

    		}else{
    			return response()->json([
        			'mark_up_in_percent'=>$mark_up->agent_percentage_value, 
        			'type'=> $mark_up->agent_selected,
        			'message'=>'mark up fetched Successfully'
    			], 200);
    		}
    }


    public function view_selected_customer_value($slug)
    {
        $mark_up = DB::table('mark_ups')
            ->select('customer_absolute_value', 'customer_percentage_value', 'customer_selected')
            ->orderBy('mark_ups.id', 'desc')
            ->take(1)
            ->where('mark_ups.markup', $slug)
            ->first(); 
            
            if ($mark_up->customer_selected == 'absolute_value') {
            	return response()->json([
            		'mark_up'=>$mark_up->customer_absolute_value, 
            		'type'=> $mark_up->customer_selected,
        			'message'=>'mark up fetched Successfully'
        		], 200);

    		}else{
    			return response()->json([
        			'mark_up_in_percent'=>$mark_up->customer_percentage_value, 
        			'type'=> $mark_up->customer_selected,
        			'message'=>'mark up fetched Successfully'
    			], 200);
    		}
    }






}

