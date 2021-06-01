<?php

namespace App\Http\Controllers;

use App\Models\Site_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class SiteDetailController extends Controller
{
     public function index()
	{
		$site_detail = site_detail::all();

    	return response()->json(['site_detail'=>$site_detail, 'message'=>'site detail fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'site_name' =>'required',
        		'address_1' =>'required',
        		'address_2' =>'required',
        		'phone_1' =>'required',
        		'phone_2' =>'required',
        		'privacy_policy' =>'required',
        		'service_policy' =>'required',
        		'refund_policy' =>'required',
        		'tac' =>'required',
        		'email' =>'required',
        		'facebook' =>'required',
        		'instagram' =>'required',
        		'twitter' =>'required',
        		'pintrest' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               

                	$site_detail             = new site_detail();
        			$site_detail->address_1     = $request->get('address_1');
        			$site_detail->address_2     = $request->get('address_2');
        			$site_detail->phone_1     = $request->get('phone_1');
        			$site_detail->phone_2     = $request->get('phone_2');
        			$site_detail->privacy_policy     = $request->get('privacy_policy');
        			$site_detail->service_policy     = $request->get('service_policy');
        			$site_detail->refund_policy     = $request->get('refund_policy');
        			$site_detail->tac     = $request->get('tac');
        			$site_detail->email     = $request->get('email');
        			$site_detail->facebook     = $request->get('facebook');
        			$site_detail->instagram     = $request->get('instagram');
        			$site_detail->twitter     = $request->get('twitter');
        			$site_detail->pintrest     = $request->get('pintrest');

                	$site_detail->save();


            		return response()->json(['site_detail'=>$site_detail, 'message'=>'site detail Created Successfully'], 201);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


/*Update Data*/ 
        
    public function update(Request $request, $id){

        try {
            
    		$site_detail = site_detail::find($id);
    		$site_detail->address_1     = $request->get('address_1');
			$site_detail->address_2     = $request->get('address_2');
			$site_detail->phone_1     = $request->get('phone_1');
			$site_detail->phone_2     = $request->get('phone_2');
			$site_detail->privacy_policy     = $request->get('privacy_policy');
			$site_detail->service_policy     = $request->get('service_policy');
			$site_detail->refund_policy     = $request->get('refund_policy');
			$site_detail->tac     = $request->get('tac');
			$site_detail->email     = $request->get('email');
			$site_detail->facebook     = $request->get('facebook');
			$site_detail->instagram     = $request->get('instagram');
			$site_detail->twitter     = $request->get('twitter');
			$site_detail->pintrest     = $request->get('pintrest');
            
            if ($site_detail->save()) {
                return response()->json([
                    'site_detail'=>$site_detail, 
                    'message'=>'site detail updated Successfully'
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

}
