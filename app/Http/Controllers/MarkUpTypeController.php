<?php

namespace App\Http\Controllers;

use App\Models\Mark_up_type;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MarkUpTypeController extends Controller
{

    public function index()
	{
		$mark_up_type = DB::table('mark_up_types')
            ->leftJoin('admins', 'admins.id', '=', 'mark_up_types.admin_id')

            ->select('mark_up_types.id','markup_type','slug','name','email','mark_up_types.created_at','mark_up_types.updated_at')
            ->orderBy('mark_up_types.id', 'desc')
            ->get();

    	return response()->json(['mark_up_type'=>$mark_up_type, 'message'=>'markup type fetched Successfully'], 200);
	}



/*----------agent Add mark_up_type Booking----------*/
	public function add(Request $request){
    	$validator = Validator::make($request->all(), [
    		'markup_type' =>'required|string'
    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
    	try {

    		$admin = auth('admin-api')->setToken($request->bearerToken())->user();
    		
	        if ($admin == NULL) {

	            return response()->json(['message'=>'admin not found!'], 400);
	        }
    		
        	$mark_up_type   = new mark_up_type();
        	$mark_up_type->markup_type     = $request->get('markup_type');
        	$mark_up_type->slug  = Str::slug($mark_up_type->markup_type);
        	$mark_up_type->admin_id     = $admin->id;
        	$mark_up_type->save();

        	// send mail to admin here 
        	
    		return response()->json(['mark_up_type'=>$mark_up_type, 'message'=>'mark_up type Created Successfully'], 201);

    	} catch (Exception $e) {

    		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
    		
    	}

    }
/*----------./agent Add mark_up_type Booking---------*/



    public function view($slug)
    {
        $mark_up_type = DB::table('mark_up_types')
            ->leftJoin('admins', 'admins.id', '=', 'mark_up_types.admin_id')

            ->select('mark_up_types.id','markup_type','slug','name','email','mark_up_types.created_at','mark_up_types.updated_at')
            ->orderBy('mark_up_types.id', 'desc')
            ->where('mark_up_types.slug', $slug)
            ->get();
        return response()->json(['mark_up_type'=>$mark_up_type, 'message'=>'mark up type fetched Successfully'], 200);
    }

}
