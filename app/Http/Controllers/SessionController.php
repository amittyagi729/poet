<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\Request;
use App\Models\RoleUser;
use Activation;
use Reminder;
use Redirect;
use Sentinel;
use URL;
use View;
use Validator;
use Lang;


class SessionController extends Controller {
  
   /***************************** login **********************************/

    public function postLogin(Request $request) {

            $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
       
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withInput()->withErrors($validator);
        }
        try {

            $checkUser = User::where('email', $request->email)->withTrashed()->first();
                    if (empty($checkUser->deleted_at)) {
                        if (Sentinel::authenticate($request->only(['email', 'password']), $request->get('remember-me', false))) {
                            // Redirect to the dashboard page
                                 if(Sentinel::inRole('admin')){
                                   return Redirect::route("admin.dashboard")->with('success', trans('success'));
                               } else {
                                
                                  return Redirect::route("prompt-list")->with('success', trans('success'));
                               }
                        }

                  $message = trans('Email or password is incorrect.');
          } else{
            //$message = trans('Your account was deactivated by admin. Please contact administrator of A Line Meant.');
            $message = trans('Your account is closed by admin. Please contact administrator of A Line Meant.');
          }

        } catch (NotActivatedException $e) {
            $message = trans('Your account has not been activated.');
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $message = trans('Your account is blocked for '.$delay.' second(s).');
        }
        return Redirect::back()->withInput()->withErrors($message);

                


    }

   /***************************** login **********************************/



  public function login(){
    if(Sentinel::check()){
      if(Sentinel::inRole('admin')){
       return Redirect::route("admin.dashboard")->with('success', 'Your account has been successfully signin.');
    } else if(Sentinel::inRole('user')){
     
       return Redirect::route("prompt-list")->with('success', 'Your account has been successfully signin.');
    } 
      }else {
         return view('pages.login');
     }

  }

}
