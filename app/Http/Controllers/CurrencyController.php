<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{

    public function index()
	{
		$currency = DB::table('currencies')
            ->leftJoin('admins', 'admins.id', '=', 'currencies.admin_id')
            ->leftJoin('currency_types', 'currency_types.slug', '=', 'currencies.currency')

            ->select('currencies.id','amount','currency','name','email','currencies.created_at','currencies.updated_at')
            ->orderBy('currencies.id', 'desc')
            ->get();

    	return response()->json(['currency'=>$currency, 'message'=>'currency fetched Successfully'], 200);
	}



/*----------Add currency Booking----------*/
	public function add(Request $request){
    	$validator = Validator::make($request->all(), [
    		'currency' =>'required|string',
    		'amount' =>'required|string',
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$admin = auth('admin-api')->setToken($request->bearerToken())->user();
    		
	        if ($admin == NULL) {

	            return response()->json(['message'=>'admin not found!'], 400);
	        }
    		
        	$currency   = new currency();
        	$currency->currency     = $request->get('currency');
        	$currency->amount     = $request->get('amount');
        	$currency->admin_id     = $admin->id;
        	$currency->save();

        	// send mail to admin here 
        	
    		return response()->json(['currency'=>$currency, 'message'=>'currency type Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./Add currency Booking---------*/



    public function view($slug)
    {
        $currency = DB::table('currencies')
            ->leftJoin('admins', 'admins.id', '=', 'currencies.admin_id')
            ->leftJoin('currency_types', 'currency_types.slug', '=', 'currencies.currency')

            ->select('currencies.id','amount','currency','name','email','currencies.created_at','currencies.updated_at')
            ->orderBy('currencies.id', 'desc')
            ->take(1)
            ->where('currencies.currency', $slug)
            ->get();
        return response()->json(['currency'=>$currency, 'message'=>'currency type fetched Successfully'], 200);
    }

}
