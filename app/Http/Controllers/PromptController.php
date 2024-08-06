<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\Http\Requests;
use Redirect;
use Sentinel;
use App\Models\Prompt;
use App\Models\Poem;
use App\Models\Favouritepoem;
use App\Models\Poemmatches;
use App\Models\Personinfo;
use Carbon\Carbon;
use App\Models\Vote;
use Helper; 
use Lang;
use Datatables;
use Illuminate\Support\Facades\Input;
use Image;
use DB;
use Elibyy\TCPDF\Facades\TCPDF;

class PromptController extends Controller
{
   /*public function __construct()
    {
        $this->middleware('user');
    }*/

    public function promptdetails(){

              return view('user.prompt.prompts-details');  
     }

     public function promptlist(){
 
             $setmonths = Config::get('constants.setmonths');
             $promptlist = Prompt::whereYear('deadline_date', date('Y'))->get();
            return view('user.prompt.prompts-list', compact('promptlist', 'setmonths'));  
     }

     
      public function submitpoem($id){
               $prompt_id = base64_decode($id);
               $promptinfo = Prompt::where('id', $prompt_id)->first();
               $Poemlist = Poem::where('prompt_id', $prompt_id)->where('status',  0)->where('is_chapbook',  1)->orderBy('id',  'DESC')->paginate(20);
               $countpoem = Poem::where('prompt_id', $prompt_id)->where('status',  0)->where('is_chapbook',  1)->count();
               $user = Sentinel::getUser(); 
               $user_id = $user->id;
               $checkpoem_user = Poem::where('prompt_id', $prompt_id)->where('status',  0)->where('is_chapbook',  1)->where('user_id', $user_id)->get();
               $setmonths = Config::get('constants.setmonths');
              return view('user.prompt.submit-poem', compact('Poemlist', 'promptinfo', 'checkpoem_user', 'countpoem', 'setmonths'));  
     }


    

      public function favouritepoem(Request $request){
           
           if ($request->ajax()) {
                $poemid = $request->poem_id;
                 $user = Sentinel::getUser(); 
                 $user_id = $user->id;
                 $checkfav = Favouritepoem::where('poem_id', $poemid)->where('user_id', $user_id)->first();
                if ($checkfav) {
                   $favdata = Favouritepoem::where('poem_id', $poemid)->where('user_id', $user_id)->delete();
                  return response()->json([
                        'message'=>'warning',
                    ]);
                }else{
                    $favouritepoems = new Favouritepoem();
                    $favouritepoems->poem_id = $poemid;
                    $favouritepoems->user_id = $user_id;
                    $favouritepoems->save();
                    return response()->json([
                        'message'=>'success',
                    ]);
                  
                  }
            }
      }
      

       public function favorite_poem(){
             if ($user = Sentinel::check()) {
                $user = Sentinel::getUser(); 
                 $user_id = $user->id;
                $checkfavoritepoem  = Favouritepoem::with('Poem')->where('user_id', $user_id)->OrderBy('id',  'DESC')->get(); 
                return view('user.favorite.favorite-poems', compact('checkfavoritepoem'));  
              
            }
            
     }


         public function deletefavorite(Request $request){
             if ($user = Sentinel::check()) {
                $poemid = $request->poem_id;
                $user = Sentinel::getUser(); 
                $user_id = $user->id;
                $checkfav_delete  = Favouritepoem::where('poem_id', $poemid)->where('user_id', $user_id)->delete();
                if (!empty($checkfav_delete)) {
                    return response()->json([
                            'message'=>'success',
                        ]);
              } else {
                  return response()->json([
                        'message'=>'warning',
                    ]);
              }
              
            }
            
     }


        public function showpoem(Request $request){
           
           if ($request->ajax()) {
                $poemid = $request->poem_id;
                 $poemshow =  Poem::where('id', $poemid)->where('status',  0)->first();
                if (!empty($poemshow)) {
                   return view('user.prompt.response_poem', compact('poemshow'));
                }else{
                    return "warning";
                  }
            }
      }

    public function index(){
      
          if(Sentinel::check()){
            if(Sentinel::inRole('admin')){
             return Redirect::route("admin.dashboard")->with('success', 'Your account has been successfully signin.');
          } }

            $setmonths = Config::get('constants.setmonths');
             $month = date("m",strtotime(date("Y-m-d"))); 
             $promptlist = Prompt::whereYear('deadline_date', date('Y'))->get();
             return view('index', compact('promptlist', 'setmonths'));
          
      
    }


     public function mypoemmatch(){
                if ($user = Sentinel::check()) {
                  $userid = Sentinel::getUser()->id;
                  //$promptget =  Poem::where('user_id', '=', $userid)->get();
                   
                    $Poemmatches = new Poemmatches();

                    $getdata =  DB::table('poems')
                        ->join('persons_info', 'poems.user_id', '=', 'persons_info.user_id')
                            ->select('poems.id', 'poems.prompt_id','persons_info.poem_id','persons_info.user_id')
                           ->where('persons_info.user_id', $userid)->get();
          if(!$getdata->isEmpty()){
            foreach ($getdata as $key => $value) {
                           if(isset($value->poem_id) && !empty($value->poem_id)){
            
                               $res=Helper::getproxydata($value->id);
                              if(isset($res->name) && !empty($res->name)){

                                $prompt_id = $value->prompt_id;
                               $allprompt =  Poem::where('prompt_id', '=', $prompt_id)->get();
                               $checkpoem_user = Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->poem_id)->where('user_id', '=', $value->user_id)->first();
                               //$checkpoem_user = Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->poem_id)->where('user_id', '=', $value->user_id)->where('status', '=', 'M')->first();
                                if($checkpoem_user==''){
                               foreach ($allprompt as $key => $val) {
                                    if ($value->poem_id !=$val->id) {
                                        $Poemmatches->user_id = $value->user_id;
                                        $Poemmatches->user_poem_id = $value->poem_id;
                                        $Poemmatches->match_poem_id = $val->id;
                                        $Poemmatches->user_prompt_id = $value->prompt_id;
                                        $Poemmatches->status = 'M';
                                        $Poemmatches->save();
                                    }
                                }

                            }

                              }else{

                                $prompt_id = $value->prompt_id;
                                $allprompt =  Poem::where('prompt_id', '=', $prompt_id)->get();
                                $checkpoem_user = Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->poem_id)->where('user_id', '=', $value->user_id)->first();
                               //$checkpoem_user = Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->poem_id)->where('user_id', '=', $value->user_id)->where('status', '=', 'A')->first();
                                if($checkpoem_user==''){
                               foreach ($allprompt as $key => $val) {
                                    if ($value->poem_id !=$val->id) {
                                        $Poemmatches->user_id = $value->user_id;
                                        $Poemmatches->user_poem_id = $value->poem_id;
                                        $Poemmatches->match_poem_id = $val->id;
                                        $Poemmatches->user_prompt_id = $value->prompt_id;
                                        $Poemmatches->status = 'A';
                                        $Poemmatches->save();
                                    }
                                }

                            }

                              }

                             } 

                           }

                          } else {
                           // echo "NO";
                              $promptget =  Poem::where('user_id', '=', $userid)->get();
                                $Poemmatches = new Poemmatches();
                             foreach ($promptget as $key => $value) {
                                   $prompt_id = $value->prompt_id;
                                   $allprompt =  Poem::where('prompt_id', '=', $prompt_id)->get();
                                   //$proxyinfo =  Personinfo::where('poem_id', $value->id)->first();
                                   $checkpoem_user =  Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->id)->where('user_id', '=', $userid)->first();
                                    //$checkpoem_user =  Poemmatches::where('user_prompt_id', '=', $value->prompt_id)->where('user_poem_id', '=', $value->id)->where('user_id', '=', $userid)->where('status', '=', 'A')->first();
                                  if($checkpoem_user==''){
                                   foreach ($allprompt as $key => $val) {
                                        if ($value->id !=$val->id) {
                                          $Poemmatches->user_id = $value->user_id;
                                          $Poemmatches->user_poem_id = $value->id;
                                          $Poemmatches->match_poem_id = $val->id;
                                          $Poemmatches->user_prompt_id = $value->prompt_id;
                                          $Poemmatches->status = 'A';
                                          $Poemmatches->save();
                                        }
                                    }

                                   }

                             }
                          }


            

          }
 
                $checkpoemmatches  = Poemmatches::where('user_id', '=', $userid)->where('status', '=', 'A')->get(); 
               foreach ($checkpoemmatches as $key => $val) {
                $val->mpoems=  Poem::where('id', '=', $val->user_poem_id)->first();
                $val->mpoems1=  Poem::where('id', '=', $val->match_poem_id)->first();
                 
               }
              
                return view('user.prompt.mypoem-match', compact('checkpoemmatches'));  
         }




   public function showprompts(Request $request){
               $promptinfo = Prompt::where('id', $request->prompt_id)->first();
               
              return view('response_prompt', compact('promptinfo'));  
     }



      public function poemmatch($name, $poemid){
                   $poemlists = Poem::where('id',  base64_decode($poemid))->first();
                   return view('user.prompt.poem-match', compact('poemlists', 'name'));
              
         }

       
       public function savepdf($poemid) {
   
             $Poemlist = Poem::where('id',  $poemid)->first();

             if(isset($Poemlist->title) && !empty($Poemlist->title)) {
                    $poemname =substr($Poemlist->title,0,30);
               }
             
                  $pdf = new TCPDF();
                  
                  $pdf::SetTitle('Poem');
                /*  $pdf::SetPrintHeader(false);
                  $pdf::SetPrintFooter(false);*/
                  $pdf::AddPage();
          
                  $view = \View::make('user.prompt.poempdf', compact('Poemlist'));
                  $html_content = $view->render();
                  $pdf::writeHTML($html_content, true, false, true, false, '');
                  $pdf::Output($poemname.'.pdf', 'D');

                
    }



     public function listyear($year){

       $promptlist= Prompt::select('*')->whereYear('deadline_date', '=', $year)->get()->groupBy(function($date) {
            return Carbon::parse($date->deadline_date)->format('m');
          });

              return view('user.prompt.prompt-search', compact('year', 'promptlist'));  
     }
     public function previouspoem(){

              return view('user.prompt.previouspoem');  
     }

    
     public function myfavorite_poem(){

              return view('user.favorite.myfavorite-poems');  
     }



   
}
