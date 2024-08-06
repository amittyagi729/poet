<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Config;
use Lang;
use Datatables;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Location;
use Reminder;
use Mail;
use App\Models\User;
use URL;
use Sentinel;
use Activation;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller
{
   
    public function index()
    {
        
         $user =  User::all();
         return view('admin.users.index', compact('user'));

    }

    public function create()
    {    

       $roles =  Role::orderBy('id', 'DESC')->get();
       return view('admin.users.adduser', compact('roles'));
    }
    public function store(Request $request)
    {  
        
        $this->validate($request, [
                        'email'  => 'required',
                        'password'  => 'required',
                        'repeat_password' => 'required_with:password|same:password'
                        ]);
                  
                   $pen_name = $request->pen_name; 
                   $email = $request->email;
                  if(isset($pen_name) && !empty($pen_name)){
                    $checkpen_name = User::where('pen_name', $pen_name)->first();
                      if ($checkpen_name) {
                          return redirect()->back()->with('warning',  'This pen name is already registered with us.');
                          
                      } 

                  }else{
                     $lname=$request->last_name;
                     $pen_name = $request->first_name.''.$lname[0];
                  }
               
                   $checkUser = User::where('email', $email)->first();
                    if ($checkUser) {
                        return redirect()->back()->with('warning',  'This email is already registered with us.');
                        
                    }
                     
                    $checkUsers = User::where('email', $request->email)->withTrashed()->first();
                    if (!empty($checkUsers->deleted_at)) {
                     return redirect()->back()->with('warning',  'This email is already registered with us.');
                    }

                    $usercredentials = ['email' => $email, 'password' => $request->password, 'first_name' => $request->first_name, 'last_name' => $request->last_name];
                    $user = Sentinel::registerAndActivate($usercredentials);
                    if(isset($user) && !empty($user)){
                        $lastid = $user->id;
                        User::where('id',$lastid)->update(['pen_name' => $pen_name, 'incarcerated_history'=>$request->history, 'city'=>$request->city]);
                        
                          $users = Sentinel::findById($lastid);
                          $role = Sentinel::findRoleByName($request->account);
                          $role->users()->attach($users);

                          $template_name="registration_welcome_email";
                          $replace_with = array($pen_name);
                          $email = $this->email($replace_with, $email,  $template_name);
                    }

                    $success = Lang::get('User has been successfully saved.');
                     return  redirect('admin/users')->with('success', $success);
       
    }
    


      public function delete(Request $request)
    {
        if (!empty($request->user_id)) {
             $userdelete = User::find($request->user_id)->delete();
             $roleuser  = RoleUser::where('user_id', $request->user_id)->delete();
             $user = Sentinel::findById($request->user_id);
               // Activation::remove($user);
             if (!empty($userdelete)) {
              return response()->json([
                        'message'=>'success',
                    ]);
             }
        }
    }


    public function edit($id)
    {
        $users = Sentinel::findById(base64_decode($id));
        //$roles =  Role::orderBy('id', 'DESC')->get();
        $roles =  RoleUser::where('user_id', base64_decode($id))->first();
        return view('admin.users.edit', compact('users', 'roles'));
    }


     public function update(Request $request){
           
           $data = $request->all();
            
            if(!empty($request->pen_name)){
              $mypen_name = $request->pen_name;
            $pen_name = User::where('pen_name', $request->pen_name)->where('id', '!=', $request->id)->first();
                    if ($pen_name) {
                          return response()->json(['message'=>'warning']);
                    }
                  }else{
                     $lname=$request->last_name;
                     $mypen_name = $request->first_name.''.$lname[0];
                  }
     

                  if(!empty($request->email)){
                        $checkUser = User::where('email', $request->email)->where('id', '!=', $request->id)->first();
                                if ($checkUser) {
                                      return response()->json(['message'=>'emailwarning']);
                                } 
                              }
            if(isset($request->newpassword) && !empty($request->newpassword)){
                $password = Hash::make($request->newpassword);
              } else{
                 $password = $request->oldpassword;
              }
            
           $savedata = User::where('id', $request->id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'pen_name' => $mypen_name,'email' => $request->email, 'city' => $request->city, 'incarcerated_history' => $request->history,'password'=>$password]);
           
          RoleUser::where('user_id', $request->id)->update(['role_id'=>  $request->account]);
           if(isset($savedata) && !empty($savedata)){
           return response()->json(['message'=>'success']);
         }
           //return  redirect('admin/users')->with('success', $success);

    }


public function status(Request $request){
   if ($request->ajax()) {
     $userid = $request->userid;
     $user = Sentinel::findById($userid);
     if($request->status == 1){
        Activation::remove($user);
        return response()->json(['message'=>'success1']);
     }else if($request->status == 0){
              $activation = Activation::create($user);
              Activation::remove($user);
             Activation::complete($user, $activation->code);
        return response()->json(['message'=>'success']);
     }

}

}


public function checkemailexisted(Request $request){
  if(isset($request->reguseremail) && !empty($request->reguseremail)){
    //echo $request->reguseremail;
        $existeds = User::where('email', $request->reguseremail)->withTrashed()->first();
         if(isset($existeds) && !empty($existeds)){
            return 1 ;
        }else{
            return 0 ;
        }
        $existed =  User::where('email','=', $request->reguseremail)->first();
        if(isset($existed) && !empty($existed)){
            return 1 ;
        }else{
            return 0 ;
        }
      }

    }
    public function checkpenname(Request $request){
       if(isset($request->pen_name) && !empty($request->pen_name)){
        //echo $request->pen_name;
        $existeds = User::where('pen_name', $request->pen_name)->withTrashed()->first();
         if(isset($existeds) && !empty($existeds)){
            return 1 ;
        }else{
            return 0 ;
        }
          $existed =  User::where('pen_name',$request->pen_name)->first();
          if(isset($existed) && !empty($existed)){
              return 1 ;
          }else{
              return 0 ;
          }

        }
  }

}
