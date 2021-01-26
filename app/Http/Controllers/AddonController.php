<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AddonController extends Controller
{

    public function index()
	{
		$addon = addon::all();

    	return response()->json(['addon'=>$addon, 'message'=>'addon fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'title' =>'required',
	    		'vacation_id' =>'required',
	    		'price' =>'required',
                'image' =>'required|mimes:jpeg,png,jpg,gif|max:2048',
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
                $uploadFolder = 'addon';
                 
                if ($image = $request->file('image')) {

                    $image_uploaded_path = $image->store($uploadFolder, 'public');

                    $admin = auth('admin-api')->setToken($request->bearerToken())->user();
            
                    if ($admin == NULL) {

                        return response()->json(['message'=>'admin not found!'], 400);
                    }


                	$addon             = new addon();
        			$addon->title     = $request->get('title');
        			$addon->vacation_id     = $request->get('vacation_id');
        			$addon->price     = $request->get('price');
                    $addon->image     = Storage::url($image_uploaded_path);
        			$addon->admin_id     = $admin->id;

                	$addon->save();

            		return response()->json(['addon'=>$addon, 'message'=>'addon Created Successfully'], 201);
                }else{
                    return response()->json(['message'=>'An error occurred!'], 500);
                }

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }



    public function view($id)
    {
        $addon = addon::find($id);
        return response()->json(['addon'=>$addon, 'message'=>'addon fetched Successfully'], 200);
    }


    public function viewByVacation($id)
    {
        $addon = DB::select(
        'SELECT * From addons 
            WHERE vacation_id = ?  
            ORDER BY id DESC', [$id]
        );
        return response()->json(['addon'=>$addon, 'message'=>'addon fetched Successfully'], 200);
    }


    public function delete($id)
    {
    	$addon = addon::find($id);
    
        if ($addon == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }else{
           return response()->json([
                'message'=> 'addon Deleted Successfully!'
            ], 200); 
        }
    }


}
