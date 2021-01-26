<?php

namespace App\Http\Controllers;

use App\Models\Mark_up_type;
use App\Models\Vacation;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VacationController extends Controller
{

    public function index()
	{
		$vacation = vacation::all();

    	return response()->json(['vacation'=>$vacation, 'message'=>'vacation fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'title' =>'required',
	    		'description' =>'required',
	    		'price' =>'required',
                'image' =>'required|mimes:jpeg,png,jpg,gif|max:2048',
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
                $uploadFolder = 'vacation';
                 
                if ($image = $request->file('image')) {

                    $image_uploaded_path = $image->store($uploadFolder, 'public');

                    $admin = auth('admin-api')->setToken($request->bearerToken())->user();
            
                    if ($admin == NULL) {

                        return response()->json(['message'=>'admin not found!'], 400);
                    }


                	$vacation             = new vacation();
        			$vacation->title     = $request->get('title');
        			$vacation->description     = $request->get('description');
        			$vacation->price     = $request->get('price');
                    $vacation->image     = Storage::url($image_uploaded_path);
        			$vacation->admin_id     = $admin->id;

                	$vacation->save();

        			$mark_up_type   = new mark_up_type();
        			$mark_up_type->markup_type     = $request->get('title');
		        	$mark_up_type->slug  = Str::slug($mark_up_type->markup_type);
		        	$mark_up_type->admin_id     = $admin->id;
		        	$mark_up_type->save();



            		return response()->json(['vacation'=>$vacation, 'message'=>'vacation Created Successfully'], 201);
                }else{
                    return response()->json(['message'=>'An error occurred!'], 500);
                }

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }



    public function view($id)
    {
        $vacation = vacation::find($id);
        return response()->json(['vacation'=>$vacation, 'message'=>'vacation fetched Successfully'], 200);
    }


    public function delete($id)
    {
    	$vacation = vacation::find($id);
    
        if ($vacation == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }else{
            // $image_path = public_path().'/'.$vacation->image;
            // if (unlink($image_path)) {
            //     $vacation->delete();
            // }else{
            //     $vacation->delete();

            // }

            return response()->json([
                'message'=> 'vacation Deleted Successfully!'
            ], 200); 
        }
    }


}
