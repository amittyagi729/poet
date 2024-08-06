@extends('user.layouts.app')
{{-- Page title --}}
@section('title', 'Prompt list')
@section('content')
<nav class="navbar navbar-expand-lg cus-navbar">
   <div class="container-fluid d-block">
   <div class="breadcrumb-div new-prompt123-breadcrumb-div prompts-headresponsive">
      <ul class="bredcrumb-ul pb-0 textresponsive">
         <li class="newprompt123-head promptdetails-responsive homehead"> <a href="#" class="new-prompt123-heading"> Prompts list  </a> </li>
        <!--   <div style="width:50%; margin: auto;"> @include('notifications') </div> -->

         <li class="new-prompt123-dropdown">
            <div class="dropdown newprompts-details-dropdown">
               <button type="button" class="btn btn-primary dropdown-toggle newprompts-dropdown-icon" data-toggle="dropdown">
               Year: <?php echo date('Y');?>
               </button>
               <!-- <div class="dropdown-menu new-prompts123-dropdown-menu">
                  <a class="dropdown-item newprompts-dropdownmenu" href="#">2021</a>
                  
                  </div> -->
            </div>
         </li>
         <!-- added  -->
         <div class="promptsalert mt-3" style="width:50%; margin: auto;"> @include('notifications')</div>

         <!-- <div class="alert alert-primary mt-3" role="alert" style="width:50%; margin: auto;">This is a primary alert—check it out!</div> -->
      </ul>
   </div>
   <div class="d-flex justify-content-between align-items-center">
      <div class="navbar-wrapper back-div">
         <!-- <a href="#" class="color-success"> <i class="fa fa-chevron-left"></i> </a> -->
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      </button>
   </div>
</nav>
<div class="second-header hidemonth">
   <div class="container-fluid justify-content-between align-items-center newprompts123-datehead">
      <div class="navbar-wrapper">
         <div class="row mt-4 promptstage">
            <div class="col-md-6 col-sm-6 border-right"> 
               <span class="navbar-brand color-black promptdetails newprompts123-heading homemonth p-0 mr-0"></span>
            </div>
            <div class="col-md-6 col-sm-6">
               <span class="navbar-brand new-prompt123-subinfo pt-0 promptdetails-stages">Stage : <strong>Poem Submission</strong></span>
            </div>
            <div class="navbar-brand m-auto p-3 submittext">
               <h4 class="promptdetails-whitehead text-capitalize mb-0"><strong>Submit your poems for the following prompt</strong>
               </h4>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- End Navbar -->
<div class="container-fluid">
   <div class="row mb-7">
      @foreach ($promptlist as $key=>$prompts)
      <?php    ///$CurrentDate = date('Y-m-d');
                //$search_Date = date('Y-m-01', strtotime($prompts->deadline_date)); 
                //$deadline_date = date('Y-m-d', strtotime($prompts->deadline_date));

           /**************************  Module 1-month and 2-days ***********************/
                      $deadline_date = $prompts->deadline_date;
                     if ($setmonths == "2 day")
                     {
                         $CurrentDate = date('Y-m-d');

                         $firts_date_month = date("Y-m-d", strtotime($deadline_date . "-$setmonths"));
                         $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths"));
                         $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths"));
                         $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                     }
                     else if ($setmonths == "1 month")
                     {

                         $CurrentDate = date('Y-m-d');
                         $cmonth = date('m', strtotime($deadline_date));
                         $cyear = date('Y', strtotime($deadline_date));
                         $countdays = cal_days_in_month(CAL_GREGORIAN, $cmonth, $cyear);

                         if ($countdays == 31)
                         {
                              $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                              $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths-1 days"));
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths+1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 30)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 1 days"));
                             $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths-1 days"));
                             $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 28 || $countdays == 29)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             if($countdays == 28){
                                $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 3 days"));
                             } else if($countdays == 29){
                               $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 2 days"));
                             }
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date. "+$setmonths-1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }

                     }
      /**************************  Module 1-month and 2-days ***********************/

         if (($CurrentDate >= $firts_date_month) && ($CurrentDate <= $end_date_poem_submission)){ ?>
      <div class="col-md-6 col-lg-6 mt-4">
         <div class="prompt-detail123-columnbox promptlistcss">
            <div class="row promptlistcss" >
               <div class="col-lg-4 new-prompt123-contentcol m-auto">
                  <div class="edit-imgs new-prompt123-images">
                    <?php 
                        if (Sentinel::check()) { 
                        ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}" class="prompt-details-submitbtns">
                     <?php } else { ?>
                     <a href="{{ url('login')}}"  class="prompt-details-submitbtns">
                     <?php } ?>
                     @if(isset($prompts->image) && !empty($prompts->image))
                     <img src="{{ URL::asset('/upload/prompts')}}/<?php echo $prompts->image; ?>" class="img-responsive pr-images new-promts123img"/>
                     @else
                     <img src="{{ URL::asset('/upload/prompts/dummy_image.png')}}" class="img-responsive pr-images new-promts123img" />
                     @endif
                     </a>
                  </div>
               </div>
               <div class="col-lg-8 newprompt123-description">
                  <div class="prompt-details123-imagecontent">
                    <?php 
                        if (Sentinel::check()) { 
                        ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}" class="prompt-details-submitbtns">
                     <?php } else { ?>
                     <a href="{{ url('login')}}"  class="prompt-details-submitbtns">
                     <?php } ?>
                        <p>{{ $prompts->title }}</p>
                     </a>
                     <input type="hidden" value='{{ date("F",strtotime($prompts->deadline_date)) }}' id="monthget">
                     <input type="hidden" value="{{ $prompts->word_limit }}" id="wlimit{{$prompts->id}}" class="wordslimit">
                     <?php $poemslist =Helper::getpoemlist($prompts->id); 
                        if(isset($poemslist) &&!empty($poemslist)){
                            if(count($poemslist) > 0) {
                         ?>
                     <span class="prompt-details123-btn"> <?php echo count($poemslist); ?></span>
                     <?php } else {?>
                          <span class="prompt-details123-btn">0</span>

                        <?php } } ?>
                     <?php if ($user = Sentinel::check())  { 
                        $userid =Sentinel::getUser()->id;
                         $getpoem =Helper::getuserpoem($prompts->id, $userid); 
                         if(!empty($getpoem->user_id)){
                        ?>
                     <a href="javascript:void(0);" class="prompt-details-submitbtn pop-on-hover isDisabled">Submit poems</a> 
                     <?php } else { ?>
                     <a href="javascript:void(0);" class="prompt-details-submitbtn pop-on-hover poempagerlink" data-id="{{ $prompts->id }}" data-pid="<?php echo base64_encode($prompts->id);?>" data-pname="<?php echo $prompts->title;?>">Submit poems</a>
                     <?php }?>
                     <?php } else { ?>
                     <a href="{{ url('login')}}" class="prompt-details-submitbtn pop-on-hover">Submit poem</a>
                     <?php } ?>
                  </div>
                  <?php  if ($user = Sentinel::check())  { 
                     $getpoem =Helper::getuserpoem($prompts->id, $userid); 
                     if(!empty($getpoem->user_id)){
                     ?>
                  <span class="poemsub mb-0">Poem Submitted</span>
                  <?php }}   ?>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      @endforeach
   </div>
</div>
<div class="promptshowmodel"></div>
<div class="second-header hidevote">
   <div class="container-fluid justify-content-between align-items-center newprompts123-datehead new-prompt123-col-head">
      <div class="navbar-wrapper">
         <div class="row">
            <div class="col-md-6 col-sm-6 mt-5 border-right"> 
               <span class="navbar-brand color-black promptdetails newprompts123-heading homemonth1 p-0" ></span>
            </div>
            <div class="col-md-6 col-sm-6">
               <span class="navbar-brand new-prompt123-subinfo pt-0 mt-5 promptdetails-stages">Stage : <strong>Voting</strong></span>
            </div>
            <div class="navbar-brand m-auto p-3 submittext">
               <h4 class="promptdetails-whitehead text-capitalize mb-0 homevoting"><strong>Vote for your favorite poems</strong>
               </h4>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid">
   <div class="row">
      @foreach ($promptlist as $key=>$prompts)
          <?php       
                  /**************************  Module 1-month and 2-days ***********************/
                      $deadline_date = $prompts->deadline_date;
                     if ($setmonths == "2 day")
                     {
                         $CurrentDate = date('Y-m-d');

                         $firts_date_month = date("Y-m-d", strtotime($deadline_date . "-$setmonths"));
                         $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths"));
                         $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths"));
                         $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                     }
                     else if ($setmonths == "1 month")
                     {

                         $CurrentDate = date('Y-m-d');
                         $cmonth = date('m', strtotime($deadline_date));
                         $cyear = date('Y', strtotime($deadline_date));
                         $countdays = cal_days_in_month(CAL_GREGORIAN, $cmonth, $cyear);

                         if ($countdays == 31)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                              $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths-1 days"));
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths+1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 30)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 1 days"));
                             $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths-1 days"));
                             $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 28 || $countdays == 29)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             if($countdays == 28){
                                $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 3 days"));
                             } else if($countdays == 29){
                               $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 2 days"));
                             }
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date. "+$setmonths-1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }

                     }
      /**************************  Module 1-month and 2-days ***********************/

                      if (($CurrentDate > $end_date_poem_submission) && ($CurrentDate <= $end_vote_date)){?>
      <div class="col-md-6 col-lg-6 mt-4">
         <div class="prompt-detail123-columnbox">
            <div class="row">
               <div class="col-lg-4 new-prompt123-contentcol">
                  <div class="edit-imgs new-prompt123-images">
                     <?php 
                        if (Sentinel::check()) { 
                        ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}" class="prompt-details-submitbtns">
                     <?php } else { ?>
                     <a href="{{ url('login')}}"  class="prompt-details-submitbtns">
                     <?php } ?>
                     @if(isset($prompts->image) && !empty($prompts->image))
                     <img src="{{ URL::asset('/upload/prompts')}}/<?php echo $prompts->image; ?>" class="img-responsive pr-images new-promts123img"/>
                     @else
                     <img src="{{ URL::asset('/upload/prompts/dummy_image.png')}}" class="img-responsive pr-images new-promts123img" />
                     @endif
                     </a>
                  </div>
               </div>
               <div class=" col-lg-8 newprompt123-description">
                  <div class="prompt-details123-imagecontent">
                     <p  class="hometitle">{{ $prompts->title }}</p>
                     <input type="hidden" value='{{ date("F",strtotime($prompts->deadline_date)) }}' id="voteget">
                     <?php $poemslist =Helper::getpoemlist($prompts->id); 
                        if(isset($poemslist) &&!empty($poemslist)){
                            if(count($poemslist) > 0) {
                         ?>
                     <span class="prompt-details123-btn"> <?php echo count($poemslist); ?></span>
                       <?php } //else {  ?>
                     <!--    <span class="prompt-details123-btn">0</span> -->
                     <?php }// } ?>
                     <?php 
                        if (!Sentinel::check()) { 
                        ?>
                     <a href="{{ url('login')}}" class="prompt-details-submitbtn pop-on-hover">Submit vote</a>
                     <?php } else { ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}" class="prompt-details-submitbtn pop-on-hover">Submit vote</a>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      @endforeach
   </div>
</div>
<div class="second-header hidewinner">
   <div class="container-fluid justify-content-between align-items-center newprompts123-datehead new-prompt123-col-head">
      <div class="navbar-wrapper">
         <div class="row">
            <div class="col-md-6 col-sm-6 mt-5 p-2 mb-5 border-right"> 
               <span class="navbar-brand color-black promptdetails newprompts123-heading homemonth2 p-0" ></span>
            </div>
            <div class="col-md-6 col-sm-6 p-2">
               <span class="navbar-brand pl-2p new-prompt123-subinfo pt-0 mt-5 promptdetails-stages">Stage : <strong>Winners Announced</strong></span>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid">
   <div class="row">
      @foreach ($promptlist as $key=>$prompts)
      <?php        
               /**************************  Module 1-month and 2-days ***********************/
                      $deadline_date = $prompts->deadline_date;
                     if ($setmonths == "2 day")
                     {
                         $CurrentDate = date('Y-m-d');

                         $firts_date_month = date("Y-m-d", strtotime($deadline_date . "-$setmonths"));
                         $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths"));
                         $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths"));
                         $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                     }
                     else if ($setmonths == "1 month")
                     {

                         $CurrentDate = date('Y-m-d');
                         $cmonth = date('m', strtotime($deadline_date));
                         $cyear = date('Y', strtotime($deadline_date));
                         $countdays = cal_days_in_month(CAL_GREGORIAN, $cmonth, $cyear);

                         if ($countdays == 31)
                         {
                              $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                              $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths-1 days"));
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths+1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 30)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 1 days"));
                             $winnerdate = date("Y-m-d", strtotime($end_vote_date . "+$setmonths-1 days"));
                             $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }
                         else if ($countdays == 28 || $countdays == 29)
                         {
                             $firts_date_month = date('Y-m-01', strtotime($deadline_date));
                             if($countdays == 28){
                                $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 3 days"));
                             } else if($countdays == 29){
                               $end_vote_date = date("Y-m-d", strtotime($deadline_date . "+$setmonths+ 2 days"));
                             }
                              $winnerdate = date("Y-m-d", strtotime($end_vote_date. "+$setmonths-1 days"));
                              $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
                         }

                     }
      /**************************  Module 1-month and 2-days ***********************/
       
         
        if (($CurrentDate > $end_vote_date) && ($CurrentDate <= $winnerdate)){   ?>
      <div class="col-md-6 col-lg-6 mt-4">
         <div class="prompt-detail123-columnbox">
            <div class="row">
               <div class="col-lg-4 new-prompt123-contentcol">
                  <div class="edit-imgs new-prompt123-images">
                     <?php 
                        if (!Sentinel::check()) { 
                        ?>
                     <a href="{{ url('login')}}">
                     <?php } else { ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}">
                     <?php } ?>
                     @if(isset($prompts->image) && !empty($prompts->image))
                     <img src="{{ URL::asset('/upload/prompts')}}/<?php echo $prompts->image; ?>" class="img-responsive pr-images new-promts123img"/>
                     @else
                     <img src="{{ URL::asset('/upload/prompts/dummy_image.png')}}" class="img-responsive pr-images new-promts123img" />
                     @endif
                     </a>
                  </div>
               </div>
               <div class=" col-lg-8 newprompt123-description">
                  <div class="prompt-details123-imagecontent">
                     <p class="hometitle">{{ $prompts->title }}</p>
                     <input type="hidden" value='{{ date("F",strtotime($prompts->deadline_date)) }}' id="winnerget">
                     <?php $poemslist =Helper::getpoemlist($prompts->id); 
                        if(isset($poemslist) &&!empty($poemslist)){
                            if(count($poemslist) > 0) {
                         ?>
                     <span class="prompt-details123-btn"> <?php echo count($poemslist); ?></span>
                      <?php } //else {  ?>
                     <!--    <span class="prompt-details123-btn">0</span> -->
                     <?php }// } ?>
                     <?php 
                        if (!Sentinel::check()) { 
                        ?>
                     <a href="{{ url('login')}}" class="prompt-details-submitbtn pop-on-hover">Winners</a>
                     <?php } else { ?>
                     <a href="{{ route('user.prompt.submitpoem',  base64_encode($prompts->id))}}" class="prompt-details-submitbtn pop-on-hover">Winners</a>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      @endforeach
   </div>
</div>
<div class="new-rompt123- new-prompt123-subdiv">
</div>

@include('user.prompt.poem-submit')
<!------------ Preview Model ---------------->
<div class="modal mypoempreview" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg mypoempreviews">
      <div class="modal-content" id="htmlpreview">
      </div>
   </div>
</div>
<!------------ Preview Model ---------------->

@endsection
