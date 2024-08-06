<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use App\Models\RoleUser; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Helpers\Helper;
use URL;

class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|string|email|max:255', 
            'password' => 'required', 
        ]);
    		if ($validator->fails()) { 
                return response()->json(['error'=>true,"message"=>"Invalid Parameters"], 401);            
         }
    		if(Auth::attempt(['email' => request('email'), 'password' => request('password') ])){
    			$user = Auth::user();
    			$checkRole = RoleUser::where('user_id',$user->id)->where('role_id',5)->count();
    			if($checkRole == 0){
    				Auth::logout();
    				return response()->json(['error'=>true,"message"=>"You have no access to login in this App."], 401);  
    			}
    			if(Auth::user()->status == 2){
	             Auth::user()->AauthAcessToken()->delete(); 
	             Auth::logout();
	             return response()->json(['error'=>true,"message"=>"Your account is deactivated by the case manager."], 401);  
	         }
    			$token =  $user->createToken('TheWayOutApp')->accessToken;
    			return response()->json(['token' => $token,"error"=>false]); 
    			
    		}else{
    			return response()->json(['error'=>true,"message"=>"Invalid Login Details"], 401);        
    		}
        
     }
    
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['content' => $user,"error"=>false]); 
    } 	
    
    

}