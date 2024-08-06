<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Config;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Illuminate\Http\Request;
use App\Models\Prompt;
use Carbon\Carbon;
use Lang;
use Datatables;
use Illuminate\Support\Facades\Input;
use Image;
use DB;



class PromptsController extends Controller
{
    
    public function index(Request $request)
    {   
        $setmonths = Config::get('constants.setmonths');
        $month = date("m",strtotime(date("Y-m-d"))); 
        $promptlist = Prompt::OrderBy('deadline_date',  'DESC')->paginate(24);
       // $currentmonths = Prompt::whereMonth('deadline_date', $month)->whereYear('deadline_date', date('Y'))->OrderBy('deadline_date',  'ASC')->get();
        return view('admin.prompts.index', compact('promptlist', 'setmonths'));

   
    }

    public function create()
    {
        return view('admin.prompts.addprompt');
    }

   
    public function store(Request $request)
    {  
        $month = date("m",strtotime($request->date)); 
        $year = date('Y', strtotime($request->date));
        $Promtmonthdata = Prompt::whereMonth('deadline_date', $month)->whereYear('deadline_date', $year)->count();
      /*  print_r($Promtmonthdata);
        die();*/
     if(!empty($Promtmonthdata) && $Promtmonthdata >= 2){
         $warning = Lang::get('Only two prompts can be uploaded in a month.');
                return  redirect()->back()->with('warning', $warning);
  }else{
      $Promtyears= Prompt::select('*')->whereYear('deadline_date', $year)->count();
       if(!empty($Promtyears) && $Promtyears == 24){
           
            $warning = Lang::get('Only 24 prompts can be uploaded in a year.');
                return  redirect()->back()->with('warning', $warning);
       } else{
        $data = $request->input();
        $promptcontent = new Prompt();
        $promptcontent->title = $data['title'];
        $promptcontent->description = $data['description'];
        $promptcontent->requirements = "";
        $promptcontent->deadline_date = date("Y-m-d", strtotime($data['date']));
        $promptcontent->original_piece = $data['original_piece'];
        if(isset($data['word_limit']) && !empty($data['word_limit'])){
          $promptcontent->word_limit = $data['word_limit'];
        } else{
          $promptcontent->word_limit = 800;
        }
        $promptcontent->author_bio = $data['authordetails'];
        $promptcontent->contact = $data['Contactdetail'];
        $promptcontent->status = 0;
        $addimg = $request->input('file'); 
        $promptcontent->image =$addimg;
       if(!empty($request->input('authorphoto'))) {
              $fileName =  $request->input('authorphoto');
              $promptcontent->author_photo =$fileName;
        }

        $promptcontent->save();
        $success = Lang::get('Prompt has been successfully saved.');
        return  redirect('admin/prompts')->with('success', $success);
       
    }
 }

}

    public function edit($id)
    {
        $prompts = Prompt::where('id',base64_decode($id))->first();
        
        return view('admin.prompts.edit', compact('prompts'));
    }


    public function update($id, Request $request){

    // $month = date("m",strtotime($request->date)); 
        //$Promtmonthdata = Prompt::whereMonth('deadline_date', $month)->whereYear('deadline_date', Carbon::now()->year)->get();
     //if(!empty(count($Promtmonthdata)) && count($Promtmonthdata) == 2){
        // $warning = Lang::get('Only two prompts can be uploaded in a month.');
             //   return  redirect()->back()->with('warning', $warning);
      //}else{
     
        $data = $request->input();
        $promptcontent = Prompt::find($id);
        $promptcontent->title = $data['title'];
        $promptcontent->description = $data['description'];
        $promptcontent->requirements = "";
        $promptcontent->deadline_date = date("Y-m-d", strtotime($data['date']));
        $promptcontent->original_piece = $data['original_piece'];
      
        if(isset($data['word_limit']) && !empty($data['word_limit'])){
          $promptcontent->word_limit = $data['word_limit'];
        } else{
          $promptcontent->word_limit = 800;
        }
        $promptcontent->author_bio = $data['authordetails'];
        $promptcontent->contact = $data['Contactdetail'];

         $addimg = $request->input('file'); 
        if(isset($addimg) && !empty($addimg)){
            $destinationPath = public_path('upload/prompts/');
            $images =  $request->input('file');
            if(!empty($request->oldimg)){
                  unlink($destinationPath.'/'.$request->oldimg);
              }
            } else{ $images = $request->oldimg; }

        $promptcontent->image =$images;

          if(!empty($request->input('authorphoto'))) {
              $destinationPath = public_path('upload/author_photo/');
              $fileName =  $request->input('authorphoto');
              $promptcontent->author_photo =$fileName;
              if(!empty($request->oldphoto)){
                  unlink($destinationPath.'/'.$request->oldphoto);
              }
            }else{
                    $promptcontent->author_photo = $request->oldphoto;
            }


        $promptcontent->save();
        $success = Lang::get('Prompt has been successfully updated.');
        return  redirect('admin/prompts')->with('success', $success);
        //}
      

    }


    public function delete($id)
    {
        if (!empty($id)) {
              $promptcontent =  Prompt::find($id)->delete();
             return Redirect::route('admin.prompts.index')->with('success', "Prompt has been successfully deleted.");
        }
    }
    

    public function storeMedia(Request $request){

                  $image = $request->file('file');
                  $filename= time().'.'.$image->extension();
                  //For thumbanail
                  $filePaththumb = public_path('upload/prompts/');
                  $imgone = Image::make($image->path());
                  $imgone->fit(160, 160)->save($filePaththumb.'/'.$filename,70);
                  return response()->json([
                  'name'          => $filename,
                  'original_name' => $filename,
              ]);
                                                      

  }

public function storeMediaremove(Request $request){
   $remove = $request->id;
   
   $path = 'public/upload/prompts/'.$remove;
   if($path){
       unlink($path);
   }

}

 public function authorMedia(Request $request){

                  $image = $request->file('file');
                  $filename= time().'.'.$image->extension();
                  //For thumbanail
                  $filePaththumb = public_path('upload/author_photo/');
                  $imgone = Image::make($image->path());
                  $imgone->fit(160, 160)->save($filePaththumb.'/'.$filename,70);
                  return response()->json([
                  'name'          => $filename,
                  'original_name' => $filename,
              ]);
                                                      

  }

  public function authorremoveimage(Request $request){
    $remove = $request->id;
    $filePaththumb = public_path('upload/author_photo/');
    $path = $filePaththumb.$remove;
   if($path){
       unlink($path);
   }
 }

 public function checkdate(Request $request){
        $month = date("m",strtotime($request->date)); 
        $year = date('Y', strtotime($request->date));
        $Promtmonthdata = Prompt::whereMonth('deadline_date', $month)->whereYear('deadline_date', $year)->get();
        //echo  count($Promtmonthdata) ; 
     if(!empty(count($Promtmonthdata)) && count($Promtmonthdata) >=  2){
        return 1 ;
    } else{
       $existed =  Prompt::where('deadline_date', date("Y-m-d", strtotime($request->date)))->first();
        if($existed){
            return 2 ;
        }else{
            return 0 ;
        }

    }
   
}

public function searchprompt(Request $request){

        $setmonths = Config::get('constants.setmonths');
        $year =  $request->year;
        $month =  $request->month;
        $search ="";
        if(!empty($month) && !empty($year)){
           $promptlist = Prompt::whereMonth('deadline_date', $month)->whereYear('deadline_date', $request->year)->get();
        }else{
            $promptlist = Prompt::whereYear('deadline_date', $request->year)->get();
        }
        
        return view('admin.prompts.index', compact('promptlist', 'setmonths', 'search', 'month', 'year'));
}


}