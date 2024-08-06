<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\EmailTemplate;
use Mail;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function email($replace_with,$to,$template_name, $subject = null, $replace_withs = null)
	{	

	    $template = EmailTemplate::where('alias', '=', $template_name)->get();
	    $template  =  json_decode( json_encode($template),true);
	    if(empty($template_name))
	    {
	        return true;
	    }
	    
		 if($subject == null){
		 	  $subject=$template[0]['subject'];
		 }
	    $vars=$template[0]['allowed_vars'];
	    $template_data=$template[0]['description'];
		
	    $template_from=$template[0]['from'];

	    $replace_field=[];
	    $row = explode(',', $vars);
	    foreach ($row as $rw)
	    {
	        $replace_field[]=$rw;
	    }	      
	    if (!empty($replace_withs)) {
	    	$msg=str_replace($replace_field,$replace_withs,$template_data);
	    }else{
	    	$msg=str_replace($replace_field,$replace_with,$template_data);
	    }
	    $email=$to;

	    $messageData = ['msg' => $msg ];
	    Mail::send('emails.email',$messageData, function($message) use ($email,$subject,$template_from){
	                            $message->to($email)->subject($subject)->from($template_from,'ALM');
	                        }); 
	    return true;
	}
	
}
