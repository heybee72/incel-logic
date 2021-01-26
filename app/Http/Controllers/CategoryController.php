<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class CategoryController extends Controller
{

    public function index()
	{
		$category = category::all();

    	return response()->json(['category'=>$category, 'message'=>'category fetched Successfully'], 200);
	}



	public function add(Request $request){
       
        	$validator = Validator::make($request->all(), [
        		'category' =>'required'
        	]);

        	if ($validator->fails()) {
        		return response()->json(["message"=> $validator->errors()], 422);
        	}
        	try {
               

                	$category             = new category();
        			$category->category     = $request->get('category');
                	$category->save();


            		return response()->json(['category'=>$category, 'message'=>'category Created Successfully'], 201);
             

        	} catch (Exception $e) {

        		return response()->json(['message'=>'An error occurred', 'error'=>$e->message()], 422);
        		
        	}
        
    }


/*Update Data*/ 
        
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'category' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }
        try {
            
    		$category = category::find($id);
    		$category->category     = $request->get('category');
            
            if ($category->save()) {
                return response()->json([
                    'category'=>$category, 
                    'message'=>'category updated Successfully'
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
        $category = category::find($id);
        return response()->json(['category'=>$category, 'message'=>'category fetched Successfully'], 200);
    }


/*DELETE DATA*/ 
    public function delete($id)
    {
    	$category = category::find($id);
        if ($category == NULL) {
            return response()->json([
            'message'=> 'An error occurred!'
        ], 500);
        }
            $category->delete();

        return response()->json([
            'message'=> 'category Deleted Successfully!'
        ], 200); 
    }
/*DELETE DATA*/ 

}
