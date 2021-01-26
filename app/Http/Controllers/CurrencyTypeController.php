<?php

namespace App\Http\Controllers;

use App\Models\Currency_type;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CurrencyTypeController extends Controller
{

    public function index()
	{
		$currency_type = DB::table('currency_types')
            ->leftJoin('admins', 'admins.id', '=', 'currency_types.admin_id')

            ->select('currency_types.id','currency_type','slug','name','email','currency_types.created_at','currency_types.updated_at')
            ->orderBy('currency_types.id', 'desc')
            ->get();

    	return response()->json(['currency_type'=>$currency_type, 'message'=>'currency_type fetched Successfully'], 200);
	}



/*----------agent Add currency_type Booking----------*/
	public function add(Request $request){
    	$validator = Validator::make($request->all(), [
    		'currency_type' =>'required|string'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$admin = auth('admin-api')->setToken($request->bearerToken())->user();
    		
	        if ($admin == NULL) {

	            return response()->json(['message'=>'admin not found!'], 400);
	        }
    		
        	$currency_type   = new currency_type();
        	$currency_type->currency_type     = $request->get('currency_type');
        	$currency_type->slug  = Str::slug($currency_type->currency_type);
        	$currency_type->admin_id     = $admin->id;
        	$currency_type->save();

        	// send mail to admin here 
        	
    		return response()->json(['currency_type'=>$currency_type, 'message'=>'currency type Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add currency_type Booking---------*/



    public function view($slug)
    {
        $currency_type = DB::table('currency_types')
            ->leftJoin('admins', 'admins.id', '=', 'currency_types.admin_id')

            ->select('currency_types.id','currency_type','slug','name','email','currency_types.created_at','currency_types.updated_at')
            ->orderBy('currency_types.id', 'desc')
            ->where('currency_types.slug', $slug)
            ->get();
        return response()->json(['currency_type'=>$currency_type, 'message'=>'currency type fetched Successfully'], 200);
    }

}
