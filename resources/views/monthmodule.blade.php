<?php
/**************************  Module 1-month and 2-days ***********************/

$deadline_date = "2021-11-03";
//$deadline_date = $prompts->deadline_date;
if ($setmonths == "2 day")
{
    $CurrentDate = date('Y-m-d');

    echo $firts_date_month = date("Y-m-d", strtotime($deadline_date . "-$setmonths"));
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
        echo $winnerdate = date("Y-m-d", strtotime($end_vote_date. "+$setmonths-1 days"));
        $end_date_poem_submission = date("Y-m-d", strtotime($deadline_date));
    }

}
/**************************  Module 1-month and 2-days ***********************/

?>