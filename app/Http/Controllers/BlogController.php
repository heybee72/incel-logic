<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class BlogController extends Controller
{

    public function index()
	{
		$blog = blog::all();

    	return response()->json(['blog'=>$blog, 'message'=>'blog fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'title' =>'required',
	    		'content' =>'required',
	    		'category_id' =>'required',
                'image' =>'required|mimes:jpeg,png,jpg,gif|max:2048',
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
                $uploadFolder = 'blog';
                 
                if ($image = $request->file('image')) {

                    $image_uploaded_path = $image->store($uploadFolder, 'public');

                    $admin = auth('admin-api')->setToken($request->bearerToken())->user();
            
                    if ($admin == NULL) {

                        return response()->json(['message'=>'admin not found!'], 400);
                    }

                	$blog             = new blog();
        			$blog->title     = $request->get('title');
        			$blog->content     = $request->get('content');
        			$blog->category_id     = $request->get('category_id');
                    $blog->image     = Storage::url($image_uploaded_path);
                    $blog->author     = $admin->id;

                	$blog->save();


            		return response()->json(['blog'=>$blog, 'message'=>'blog Created Successfully'], 201);
                }else{
                    return response()->json(['message'=>'An error occurred!'], 500);
                }

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }



/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'title' =>'required',
    		'content' =>'required',
    		'category_id' =>'required',
            'image' =>'required|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
                $uploadFolder = 'blog';
            if ($image = $request->file('image')) {
            	
            	$blog = blog::find($id);

            	$blog->title     = $request->get('title');
    			$blog->content     = $request->get('content');
    			$blog->category_id     = $request->get('category_id');
                $blog->image     = Storage::url($image_uploaded_path);

                if ($news->save()) {
	                return response()->json([
	                    'news'=>$news, 
	                    'message'=>'news updated Successfully'
	                ], 201);
	            }else{
	                return response()->json([
	                    'message'=>'An error occured!'
	                ], 501);
	           }
            }
        } catch (Exception $e) {

            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
            
        }

    }
    
/*Update Data*/ 

    public function view($id)
    {
        $blog = blog::find($id);
        return response()->json(['blog'=>$blog, 'message'=>'blog fetched Successfully'], 200);
    }


    public function delete($id)
    {
    	$blog = blog::find($id);
    
        if ($blog == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }else{
            $image_path = public_path().'/'.$blog->image;
            if (unlink($image_path)) {
                $blog->delete();
            }else{
                $blog->delete();

            }

            return response()->json([
                'message'=> 'blog Deleted Successfully!'
            ], 200); 
        }
    }


}
