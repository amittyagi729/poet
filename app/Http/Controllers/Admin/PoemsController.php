<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;
use Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Lang;
use Datatables;
use App\Models\Location;
use App\Models\Prompt;
use App\Models\Poem;
use App\Models\Personinfo;
use App\Models\Poemmatches;
use Reminder;
use Mail;
use App\Models\User;
use App\Models\Vote;
use URL;
use App\Models\RoleUser;
use App\Models\Role;
use Sentinel;
use Helper; 
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Input;
use Session;

class PoemsController extends Controller
{

   
    public function userchange(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $getdata = User::where('id',$user_id)->first();
            $user_id =  $getdata->id;
            if(!empty($getdata->first_name) || !empty($getdata->last_name) || !empty($getdata->pen_name)){
                    $first_name = $getdata->first_name;
                    $last_name = $getdata->last_name;
                    $pen_name = $getdata->pen_name;
            }else {
                $first_name = "";
                $last_name = "";
                $pen_name = "";
                
            }
            
            if(isset($getdata->city) && !empty($getdata->city)){
                    $city_name = $getdata->city;
                } else{
                    $city_name = "";
            }
        return response()->json([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'pen_name' => $pen_name,
            'city_name' => $city_name,
            'user_id' => $user_id,
            'message'=>'1',
        ]);

        }

    }

 
    public function create()
    {   
       return view('admin.poems.addpoem');
    }

    public function cityname(Request $request)
        {   
               if ($request->ajax()) {
                        $state_id = $request->state_id;
                        $subdata = Location::where('location_type', 2)->where('parent_id', $state_id)->get();
                        $html = '';
                        foreach ($subdata as $subname)
                        {
                            $html .= '<option value="' . $subname->id . '">' . $subname->name . '</option>';
                        }
                        return response()
                            ->json(['html' => $html]);
                          }

        }


   public function prompts(Request $request){
     if ($request->ajax()) {
         $id = $request->prompt_id;
        $prompts = Prompt::find($id);
        return response()->json([
            'promptname' => $prompts->title ,
            'prompt_id' => $prompts->id,
            'message'=>'1',
        ]);
     

    }

}

public function submission(Request $request){

     if ($request->ajax()) {
         /* $countpoem = Poem::where('prompt_id', $request->prompt_id)->where('user_id', $request->user_id)->where('status',  0)->count();
          if($countpoem == 1){ 
              return response()->json(['message'=>'warning']);
          } else {*/
           $user_id = $request->user_id; 
           $titles = $request->title; 
           $description = $request->description; 
           $backstory = $request->backstory;
           $sharedp = $request->sharedp;
           $chapbook = $request->chapbook;
           $prompt_id = $request->prompt_id;
           $getipaddaress = \Request::ip();

            $poem = new Poem();
            $poem->user_id = $user_id;
            $poem->prompt_id = $prompt_id;
            $poem->title = $titles;
            $poem->description = $description;
            $poem->backstory = $backstory;
            $poem->is_shared_publically = $sharedp;
            $poem->is_chapbook = $chapbook;
            $poem->ip_address = $getipaddaress;

            $savepoemData = $poem->save();

             if(isset($savepoemData)){
                       $lastinsert_ids = $poem->id;

                       User::where('id', $user_id)->update( ['first_name'=>$request->first_name, 'last_name' => $request->last_name, 'pen_name' =>  $request->pen_name, 'city' => $request->inputCity]);
                      
                if(!empty($request->person_name) && !empty($request->postal_code) && !empty($request->state_name) && !empty($request->city_name) && !empty($request->street)){

                            $personsinfo = new Personinfo();
                            $personsinfo->poem_id = $lastinsert_ids;
                            $personsinfo->user_id = $user_id;
                            $personsinfo->name = $request->person_name; 
                            $personsinfo->pen_name = $request->personPen;
                            $personsinfo->postal_code = $request->postal_code;
                            $personsinfo->state = $request->state_name;
                            $personsinfo->city = $request->city_name;
                            $personsinfo->street = $request->street;
                            $personsinfo->doc_number = $request->doc_number;
                            $personsinfo->ip_address = $getipaddaress; 
                            $personsinfo->email = "";
                            $personsinfo->institution_name = $request->Institution;
                            $proxydata = $personsinfo->save();
                            if(isset($proxydata)){
                                  $proxy_last_id = $personsinfo->id;
                           if(!empty($request->pemail)){
                             $random = rand();
                                $usercredentials = ['email' => $request->pemail, 'password' => $random];
                                $user = Sentinel::registerAndActivate($usercredentials);
                                if(isset($user) && !empty($user)){
                                    $lastid = $user->id;
                                    
                                     User::where('id',$lastid)->update(['first_name' => $request->person_name, 'pen_name' =>  $request->personPen, 'city' => $request->city_name]);

                                    $users = Sentinel::findById($lastid);
                                    $role = Sentinel::findRoleByName('User');
                                    $role->users()->attach($users);
                                    $this->updatepassword($lastid);
                                  /****************** Proxy Poem User update **************/
                                    $res = Poem::find($lastinsert_ids);
                                    $res->user_id = $lastid;
                                    $res->save();
                                  /****************** Proxy Poem  User update **************/

                                   /****************** Proxy user id update **************/
                                    $pdata = Personinfo::find($proxy_last_id);
                                    $pdata->user_id = $lastid;
                                    $pdata->save();
                                  /****************** Proxy user id update **************/
                                }

                              }
                          }


                        }

                   return response()->json(['message'=>'success']);

                  // }
       }
       

     }

}

    public function updatepassword($lastid){
          
           $sentinelUser = Sentinel::findById($lastid);
            $reminder = Reminder::exists($sentinelUser) ? : Reminder::create($sentinelUser);
            if($reminder == true){
                $oldreminder = Reminder::where('user_id', $sentinelUser->id)->where('completed', 0)->first();
                 $token = $oldreminder->code;
                 $userid=base64_encode($sentinelUser->id);
                 $link = url('/').'/proxy-password/'.$userid.'/'.$token;
                 $replace_with = array($sentinelUser->first_name, $link);
                 $template_name="proxy_password";
                 $email = $this->email($replace_with,$sentinelUser->email, $template_name);
            } else {
                $token = $reminder->code;
                $userid=base64_encode($sentinelUser->id);
                $link = url('/').'/proxy-password/'.$userid.'/'.$token;
                $replace_with = array($sentinelUser->first_name, $link);
                $template_name="proxy_password";
                $email = $this->email($replace_with,$sentinelUser->email, $template_name);
          }

    }

public function poemshow($id){
               $setmonths = Config::get('constants.setmonths');
               $prompt_id = base64_decode($id);
               $promptinfo = Prompt::where('id', $prompt_id)->first();
               $Poemlist = Poem::where('prompt_id', $prompt_id)->where('status',  0)->where('is_chapbook',  1)->orderBy('id',  'DESC')->paginate(20);
               $countpoem = Poem::where('prompt_id', $prompt_id)->where('status',  0)->where('is_chapbook',  1)->count();
               return view('admin.poems.showpoem', compact('Poemlist', 'promptinfo', 'countpoem', 'setmonths'));
}



 public function singlepoem(Request $request){
           
           if ($request->ajax()) {
                $poemid = $request->poem_id;
                 $poemshow =  Poem::where('id', $poemid)->where('status',  0)->first();
                if (!empty($poemshow)) {
                   return view('admin.poems.response_poem', compact('poemshow'));
                }else{
                    return "warning";
                  }
            }
      }


    public function delete($id)
    {
        if (!empty($id)) {
              $poemscontent =  Poem::find($id)->delete();
             return redirect()->back()->with('success', "Poem has been successfully deleted.");
        }
    }


        public function vote(Request $request){
           
           if ($request->ajax()) {
                $poemid = $request->poem_id;
                 $user = Sentinel::getUser(); 
                 $user_id = $user->id;
                 $getipaddaress = \Request::ip();
                 $votecount = Vote::where('prompt_id', $request->prompt_id)->where('user_id', $user_id)->count();
                 
                 if($request->likey == 1){

                    if(!empty($votecount) && $votecount == 5){
                      return response()->json([
                          'message'=>'info',
                          'count'=>$votecount,
                      ]);

                       } 

                 }

                 $checkvote = Vote::where('poem_id', $poemid)->where('user_id', $user_id)->first();
                if ($checkvote) {
                   $votedata = Vote::where('poem_id', $poemid)->where('user_id', $user_id)->delete();
                  return response()->json([
                        'message'=>'warning','count'=>4-$votecount,
                    ]);
                }else{
                    $Votes = new Vote();
                    $Votes->poem_id = $poemid;
                    $Votes->user_id = $user_id;
                    $Votes->vote_types = 1; 
                    $Votes->ip_address = $getipaddaress;
                    $Votes->prompt_id = $request->prompt_id;
                    $Votes->save();
                    return response()->json([
                        'message'=>'success','count'=>4-$votecount,
                    ]);
                  
                  }
            }
      }
   
   public function votingshow($poemid, $promptid){
   
      $poemvote = Poem::where('prompt_id', $promptid)->where('id',  $poemid)->first();
      $votes = Vote::where('prompt_id', $promptid)->where('poem_id',  $poemid)->get();
       return view('admin.poems.votingshow', compact('poemvote', 'votes'));
   }
  
    public function getvotecounts(Request $request){
      $votes = Vote::where('poem_id',  $request->poem_id)->count();
       return response()->json([
            'vcount' => $votes,
            'message'=>'success',
        ]);

  }
 public function poemlist($poemid, $promptid, $userid){
                   
                   $prompt_id = base64_decode($promptid);
                     //echo "</br>";
                    $poemids = base64_decode($poemid);
                    $userid = base64_decode($userid);
                    $poemlists = Poem::where('prompt_id', base64_decode($promptid))->where('id',  base64_decode($poemid))->first();
             
                   $manualpoem = Poemmatches::where('user_prompt_id', '=', $prompt_id)->where('user_poem_id', '=', $poemids)->where('status', '=', 'M')->first();
                   
        
                   $proxyinfo =  Personinfo::where('poem_id', base64_decode($poemid))->first();
                         
                    $value =  Poem::where('id', '=', base64_decode($poemid))->first();
                   
                     $prompt_id = $value->prompt_id;
                    $allprompt =  Poem::where('prompt_id', base64_decode($promptid))->get();

                       $checkpoem_user =  Poemmatches::where('user_poem_id', $proxyinfo->poem_id)->where('user_id', '=', $proxyinfo->user_id)->where('status', '=', 'M')->first();
                      // $checkpoem_user =  Poemmatches::where('user_poem_id', $proxyinfo->poem_id)->where('user_id', '=', $proxyinfo->user_id)->first();
                       if ($checkpoem_user == '') {
                         $Poemmatches = new Poemmatches();

                     foreach ($allprompt as $key => $val) {
                        
                          if ($value->id !=$val->id) {
                            $Poemmatches->user_id = $value->user_id;
                            $Poemmatches->user_poem_id = $proxyinfo->poem_id;
                            $Poemmatches->match_poem_id = $val->id;
                            $Poemmatches->user_prompt_id = $value->prompt_id;
                            $Poemmatches->status = 'M';
                            $Poemmatches->save();
                         }
                      }

                     }

             

                   return view('admin.poems.singlepoem_preview', compact('poemlists', 'manualpoem'));
                   
      }


public function createPDF($poemid, $promptid) {
   
             $Poemlist = Poem::where('prompt_id', $promptid)->where('id',  $poemid)->first();
              $manualpoem = Poemmatches::where('user_prompt_id', '=', $promptid)->where('user_poem_id', '=', $poemid)->where('status', '=', 'M')->first();
               
           /*   echo "<pre>";
              print_r($manualpoem);

              die();*/
              
                
             if(isset($Poemlist->user_id) && !empty($Poemlist->user_id) && isset($Poemlist->id) && !empty($Poemlist->id)) {
                     if(!empty($manualpoem->match_poem_id)){
                     $poemnames = Helper::getpoemname($manualpoem->match_poem_id); 
                     $poemname =substr($poemnames->title,0,30);
                  }
                    $getpersoninfo = Helper::getpersoninfo($Poemlist->user_id, $Poemlist->id);
                 if(!empty($getpersoninfo->name)) { 
                     $pdfname=$getpersoninfo->name."_".$poemname;
                 }
               }
             
                  $pdf = new TCPDF();
                  $pdf::AddPage();
             
                  //$pdf::setPageMark();
                  $view = \View::make('admin.poems.pdfview', compact('Poemlist', 'manualpoem'));
                  $html_content = $view->render();
                  $pdf::writeHTML($html_content, true, false, true, false, '');
                 // $pdf::lastPage();
                
                  $pdf::Output($pdfname.'.pdf', 'D');
                
    }
    

     public function removeimage(Request $request){
           
           if ($request->ajax()) {
                $prompt_id = $request->prompt_id;
                 $prompthow =  Prompt::where('id', $prompt_id)->update(['image' => '']);
                if (!empty($prompthow)) {
                   if(file_exists(public_path('upload/prompts/'.$request->imageget))){
                      $file_path = public_path().'/upload/prompts/'.$request->imageget;
                       unlink($file_path); 
                       
                     return response()->json([
                        'message'=>'success',
                    ]);
                }

                }else{
                   return response()->json([
                        'message'=>'warning',
                    ]);
                  }
            }
      }

      public function authoreimage(Request $request){
           
           if ($request->ajax()) {
                $prompt_id = $request->prompt_id;
                 $prompthow =  Prompt::where('id', $prompt_id)->update(['author_photo' => '']);
                if (!empty($prompthow)) {
                   if(file_exists(public_path('upload/author_photo/'.$request->oldphoto))){
                      $file_path = public_path().'/upload/author_photo/'.$request->oldphoto;
                       unlink($file_path); 
                       
                     return response()->json([
                        'message'=>'success',
                    ]);
                }

                }else{
                   return response()->json([
                        'message'=>'warning',
                    ]);
                  }
            }
      }



      public function winnershow(Request $request){
           if ($request->ajax()) {
               $poemid = $request->poemid;
             if($request->status == 1){
                 Poem::where('id', $poemid)->update(['is_winner' =>0]);
                 return response()->json(['message'=>'success1']);
             }else if($request->status == 0){
                 Poem::where('id', $poemid)->update(['is_winner' =>1]);
                return response()->json(['message'=>'success']);
             }

        }

}

 
     
}
