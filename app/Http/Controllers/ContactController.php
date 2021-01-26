<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class ContactController extends Controller
{

    public function index()
	{
		$contact = contact::all();

    	return response()->json(['contact'=>$contact, 'message'=>'contact fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
                'name' =>'required',
        		'email' =>'required|email',
	    		'content' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               

                	$contact             = new contact();
                    $contact->name     = $request->get('name');
        			$contact->email     = $request->get('email');
        			$contact->content     = $request->get('content');
                	$contact->save();


            		return response()->json(['contact'=>$contact, 'message'=>'contact Created Successfully'], 201);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }




    public function view($id)
    {
        $contact = contact::find($id);
        return response()->json(['contact'=>$contact, 'message'=>'contact fetched Successfully'], 200);
    }


/*DELETE DATA*/ 
    public function delete($id)
    {
    	$contact = contact::find($id);
        if ($contact == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $contact->delete();

        return response()->json([
            'message'=> 'contact Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 

}
