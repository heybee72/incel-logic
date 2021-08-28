<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AgentAuthController extends Controller 
{
    public function __construct(){
    	$this->middleware('jwt.verifyAgent:api', ['except' => ['login','register','index','updateAgentStatus'] ]);
    }



    public function index()
    {
        $agent = Agent::all();

        return response()->json(['agent'=>$agent, 'message'=>'Agents fetched Successfully'], 200);
    }


    public function updateAgentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }

        $agent = agent::find($id);

        if ($agent->approved == 'no') {
            
            $agentStatus = DB::update(
                'UPDATE agents SET approved = ?  WHERE id = ? ', ['yes', $id]
            );

            return response()->json([
                'message'=>$agentStatus, 
                'message'=>'Agent has been approved'
            ], 200);

        }elseif ($agent->approved == 'yes') {

            $agentStatus = DB::update(
                'UPDATE agents SET approved = ?  WHERE id = ? ', ['no', $id]
            );

            return response()->json([
                'message'=>$agentStatus, 
                'message'=>'Agent has been deactivated'
            ], 200);

        }

        

    }


/*-----Start of login API------*/ 
    public function login(Request $request){

    	try{
            $validator = Validator::make($request->all(),[
                'email'    => 'required|email',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {

                return response()->json($validator->errors(), 400);
            }

            if (! $token = auth('agent-api')->attempt($validator->validated())) {
                    return response()->json(['error' => 'invalid login credentials'], 400);
                }

            $token_validity = 24 * 60;

            auth('agent-api')->factory()->setTTL($token_validity);



            if (!$token = auth('agent-api')->attempt($validator->validated())) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid Login Details'
                ], 401);
            }
            $agent               = new Agent;
            $agent->email         = $request->get('email');
            $agent = DB::select('SELECT * From agents where email = ? AND approved = ? ', [$agent->email, 'yes']); 

            if (!$agent) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Account not approved yet!'
                ], 401);
            }else{
    	       return $this->respondWithToken($token, "Agent logged in Successfully!");	
            }

                 
        }catch(\Exception $e){

            return response()->json([
                'message' => 'An Error Occured']);
        }

    }
/*-----./End of login API------*/ 



/*-----Start of registration API------*/ 
    public function register(Request $request){
    	$validator = Validator::make($request->all(), [
    		'name'        =>'required|string|between:2,100',
    		'email'       =>'required|email|unique:agents',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    		'password'    => 'required|min:6',
            'profile_image' =>'required|mimes:png,jpg,jpeg,gif|max:2048',
            'username'        =>'required|string|between:2,225',
            'company'        =>'required|string|between:2,225',
            'country'        =>'required|string|between:2,225',
            'business_address' =>'required|string|between:2,225',

    	]);

    	if ($validator->fails()) {
    		return response()->json(["message"=> $validator->errors()], 422);
    	}
        $uploadFolder = 'profiles';

        if ($image = $request->file('profile_image')) {
           $image_uploaded_path = $image->store($uploadFolder, 'public'); 

            $agent               = new Agent;
            $agent->name         = $request->get('name');
            $agent->email        = $request->get('email');
            $agent->phone        = $request->get('phone');
            $agent->username     = $request->get('username');
            $agent->company        = $request->get('company');
            $agent->country        = $request->get('country');
            $agent->business_address    = $request->get('business_address');
            $agent->branches        = $request->get('branches');
            $plainPassword      = $request->get('password');
            $agent->password     = Hash::make($plainPassword);
            $agent->api_token    = Str::random(60);


            $agent->profile_image     = Storage::url($image_uploaded_path);
            $agent->save();
            
            return response()->json(['agent'=>$agent, 'message'=>'Agent Created Successfully'], 200);

        }else{
            return response()->json(['message'=>'An error occurred!'], 500);
        }





    }
/*-----./End of registration API------*/ 


/*-------Profile---------*/ 
    public function profile(){
        
    	return response()->json([
            'agent'=>auth('agent-api')->user(), 
            'message'=> 'Agent found Successfully!'
        ], 200); 
    }
/*--------./Profile--------*/ 



/*--------Logout--------*/ 

    public function logout(){
    	
    	auth('agent-api')->logout();

    	return response()->json([
            'message'=>'Agent logged out Successfully'
        ],200);
    }
/*-------./Logout---------*/ 


/*--------Delete--------*/ 

     public function delete($id){
            
            $agent = Agent::find($id);
            $agent->delete();

        return response()->json([
            'message'=> 'Agent Deleted Successfully!'
        ], 200); 
     }
/*--------./Delete--------*/ 


/*-----Refresh API------*/ 
    public function refresh(){

    	return $this->respondWithToken(
            auth('agent-api')->refresh(), 
            "Admin Token refreshed Successfully"
        );
    }
/*-----./Refresh API------*/ 



    protected function respondWithToken($token, $message){
    	return response()->json([
    		'token'           => $token,
    		'token_type'      =>'bearer',
    		'token_validity'  => auth('agent-api')->factory()->getTTL() * 60,
            'message'         =>$message
    	]);
    }

    
}
