<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class NewsController extends Controller
{

    public function index()
	{
		$news = news::all();

    	return response()->json(['news'=>$news, 'message'=>'news fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'title' =>'required',
	    		'content' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               

                	$news             = new news();
        			$news->title     = $request->get('title');
        			$news->content     = $request->get('content');
                	$news->save();


            		return response()->json(['news'=>$news, 'message'=>'news Created Successfully'], 201);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'title' =>'required',
	    	 'content' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$news = news::find($id);
    		$news->title     = $request->get('title');
			$news->content     = $request->get('content');
            
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

        } catch (Exception $e) {

            return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
            
        }

    }

/*Update Data*/ 



    public function view($id)
    {
        $news = news::find($id);
        return response()->json(['news'=>$news, 'message'=>'news fetched Successfully'], 200);
    }


/*DELETE DATA*/ 
    public function delete($id)
    {
    	$news = news::find($id);
        if ($news == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $news->delete();

        return response()->json([
            'message'=> 'news Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 

}
