<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class BannerController extends Controller
{

    public function index()
	{
		$banner = Banner::all();

    	return response()->json(['banner'=>$banner, 'message'=>'Banner fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
                'image' =>'required|mimes:jpeg,png,jpg,gif|max:2048',
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
                $uploadFolder = 'banner';
                 
                if ($image = $request->file('image')) {

                    $image_uploaded_path = $image->store($uploadFolder, 'public');

                	$banner             = new Banner();
                    $banner->image     = Storage::url($image_uploaded_path);
                	$banner->save();


            		return response()->json(['banner'=>$banner, 'message'=>'Banner Created Successfully'], 201);
                }else{
                    return response()->json(['message'=>'An error occurred!'], 500);
                }

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


    public function view($id)
    {
        $banner = banner::find($id);
        return response()->json(['banner'=>$banner, 'message'=>'banner fetched Successfully'], 200);
    }


    public function delete($id)
    {
    	$banner = banner::find($id);
    
        if ($banner == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }else{
            $image_path = public_path().'/'.$banner->image;
            if (unlink($image_path)) {
                $banner->delete();
            }else{
                $banner->delete();

            }

            return response()->json([
                'message'=> 'banner Deleted Successfully!'
            ], 200); 
        }
    }


}
