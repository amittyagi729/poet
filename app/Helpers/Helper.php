<?php 
namespace App\Helpers;
use App\Models\RoleUser;
use Config;
use Sentinel;
use App\Models\User;
use App\Models\Location;
use App\Models\Prompt;
use App\Models\Favouritepoem;
use App\Models\Personinfo;
use App\Models\Vote;
use App\Models\Poem;
use App\Models\Poemmatches;
use Activation;
use DB;



class Helper
{

    public static function getPercentage($number = null, $page = null){

          if ( $number > 0 ) {
           $ad = ($page / 100) * $number;
           return money_format("%i",$ad);
          } else {
            return "0.00";
          }
    }
	
	public static function ImgcleanName($string) {
	       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	       $string = time().'-'.$string;
	       return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
	}

  public static function getroles($id) {
         $role = RoleUser::where('user_id', $id)->first();
        return (isset($role) ? $role : '');
  }

  public static function getusers() {
         $users = User::all();
        return (isset($users) ? $users : '');
  }

  public static function getusername($uid) {
         $userdata = User::where('id', $uid)->first();
         return (isset($userdata) ? $userdata : '');
  }

  /* public static function getcityname($cid) {
         $cityname = Location::where('parent_id', $cid)->where('location_type', 2)->first();
        return (isset($cityname) ? $cityname : '');
  }*/
  
   public static function getcitynames($cid) {
         $cityname = Location::where('id', $cid)->where('location_type', 2)->first();
        return (isset($cityname) ? $cityname : '');
  }

  public static function getstate() {
          $country_id = Config::get('constants.US_COUNTRY');
         $statename = Location::where('location_type', 1)->where('parent_id', $country_id)->orderBy('name', 'ASC')->get();
        return (isset($statename) ? $statename : '');
  }

   public static function getpromptname($id) {
         $promptname = Prompt::find($id);
         $promptname = Prompt::withTrashed()->find($id);
        return (isset($promptname) ? $promptname : '');
  }
  
  public static function getfavourite($id) {
    if ($user = Sentinel::check()) {
        $user = Sentinel::getUser(); 
        $user_id = $user->id;
        $checkfav = Favouritepoem::where('poem_id', $id)->where('user_id', $user_id)->first();
        return (isset($checkfav) ? $checkfav : '');
      }

  }


    public static function getpersoninfo($uid, $pid) {
         $Personinfo = Personinfo::where('poem_id', $pid)->where('user_id', $uid)->first();
         return (isset($Personinfo) ? $Personinfo : '');
  }


     public static function getvotes($id) {
        if ($user = Sentinel::check()) {
            $user = Sentinel::getUser(); 
            $user_id = $user->id;
            $checkvote = Vote::where('poem_id', $id)->where('user_id', $user_id)->first();
            return (isset($checkvote) ? $checkvote : '');
          }

  }


 public static function getpoemlist($id) {
       
            $checkpoem = Poem::where('prompt_id', $id)->where('status',  0)->where('is_chapbook',  1)->get();
            return (isset($checkpoem) ? $checkpoem : '');
          

  }


 public static function userstatus($id) {
            $status = Activation::where('user_id', $id)->first();
          return (isset($status) ? $status : '');
        
}

public static function getuserpoem($id, $uid) {
       
            $checkuserpoem = Poem::where('prompt_id', $id)->where('user_id', $uid)->first();
            return (isset($checkuserpoem) ? $checkuserpoem : '');
          

  }

public static function getpoemname($id) {
            $checkuserpoem = Poem::where('id', $id)->first();
            return (isset($checkuserpoem) ? $checkuserpoem : '');
          

  }

  public static function getstatename($id) {
          $country_id = Config::get('constants.US_COUNTRY');
          $statenames = Location::where('location_type', 1)->where('parent_id', $country_id)->where('id', $id)->first();
         return (isset($statenames) ? $statenames : '');
  }




 

  public static function getvotecount($peomid, $promptid) {
          $votes = Vote::where('prompt_id', $promptid)->where('poem_id',  $peomid)->get();
         return (isset($votes) ? $votes : '');
  }

   public static function getpromptvotecount($promptid) {
          $promtvotes = Vote::where('prompt_id', $promptid)->get();
         return (isset($promtvotes) ? $promtvotes : '');
  }


  public static function getproxydata($pid) {
         $Personinfo = Personinfo::where('poem_id', $pid)->first();
         return (isset($Personinfo) ? $Personinfo : '');
  }

 /*  public static function activeaccount($id) {
         $activation = Activation::where('user_id','=', $id)->first();
         return (isset($activation) ? $activation : '');
  }*/

public static function userdelete($userid){
  $userCheck = User::withTrashed()->where('id', $userid)->first();
  return (isset($userCheck) ? $userCheck : '');
}


public static function poemmatchdata($id){
  $Poemmatches = Poemmatches::where('user_poem_id', $id)->first();
  return (isset($Poemmatches) ? $Poemmatches : '');
}




}
