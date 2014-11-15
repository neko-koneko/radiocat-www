<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'Расписание - Неделя';
$global_keywords = 'Расписание - Неделя';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/time_lib.php");
 include("inc/_utf_symbols.php");

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');
?>


<?php


$current_day_of_week = intval(($main_request_array[1]=='')?date("w"):$main_request_array[1]);


$_SESSION['data']['repeat_weekly'] = "Y";

?>

 <!-- content -->
<div id="content">

<?php
	 print_week_table($current_day_of_week);
?>


</div>
    <!-- content end -->

<?php

function print_week_table($current_day_of_week)
{
 global $base;
 global $weekday_abbr,$weekday;
 global $utf_symbol;

 $next_day_of_week = $current_day_of_week+1;
 $prev_day_of_week = $current_day_of_week-1;

 if ($next_day_of_week > 6){$next_day_of_week =0;  }
 if ($prev_day_of_week < 0){$prev_day_of_week =6;  }

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Расписание на неделю
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';   /**/


  echo '<table class="w100 month_table">';


  $now = time();

  $current_day = date("d",$now);
  $day_of_week = date("w",$now);

//  $start_day = $current_day-$day_of_week+1;

  $start_day = $current_day;
//  echo 'start day ='.$start_day.'  ';
  $start_time = mktime(0, 0, 0, date('m'), $start_day, date("Y"));
 // echo 'Start date='.date("d-m-Y H:i:s",$start_time);

  echo '<tr>';
	  for ($day=0;$day<=6;$day++)
	  {
 	   $new_time = mktime(0, 0, 0, date('m'), $start_day+$day, date("Y"));
 	   $day_of_the_week = date("w",$new_time);
	   echo '<th class="w1x7p">'.$weekday_abbr[$day_of_the_week].'</th>';
	  }
  echo '</tr>';


  echo '<tr>';

  for ($day=0;$day<=6;$day++)
  {   $new_time = mktime(0, 0, 0, date('m'), $start_day+$day, date("Y"));

   $current_year = date("Y",$new_time);
   $current_month = date("m",$new_time);
   $current_day   = date("d",$new_time);
   $day_of_the_week = date("w",$new_time);

	                       $day_class ='';

	                       if ($new_time==$now){$day_class = 'day_current ';}

	                       $current_day_last_time = mktime(0, 0, -1, $current_month, $current_day+1, $current_year);
	   					   $jobs = cron_get_job_by_time_interval($new_time,$current_day_last_time);

	   					   $jobs_count = count($jobs); $jobs_count=0;
	   					   $jobs_count = ($jobs_count>6)?6:$jobs_count;
	                       $day_class.=' jobs_day_'.$jobs_count;


				           echo '<td class="day pointer '.$day_class.'"  onclick="window.location.href=\''.$base.'/calendar/'.$current_year.'/'.$current_month.'/'.$current_day.'\'" >'.
				                '<div class="day_num">'.$weekday_abbr[$day_of_the_week].'</div>';

				           if ($jobs_count >0) echo  '<div class="hideonbig jobs_count">'.$jobs_count.'</div>';

                           echo '<div class="hideonmedium hideonsmall">';
	                       foreach ($jobs as $job)
	                       {
	                         $job_time_str = $job['time'];
						     $job_time = datetime_to_timestamp($job_time_str);
						     $playlist_id = $job['playlist_id'];
						     $playlist_data = get_playlist($playlist_id);
	                       	 echo '<div><span class="day_job_time">'.date("H:i",$job_time).'</span> ';

	                       	if ($playlist_data['static']=='Y') {echo '<span class="day_job_indicator red">S</span> ';} else {echo '<span class="day_job_indicator blue">D</span> ';}
	                       	if ($job['repeat_weekly']=='Y') {echo '<span class="day_job_indicator green">&#x21ba;</span> ';} else  {echo '<span class="day_job_indicator" style="color:#f0f;">&#x21e5;</span> ';}


                            //$job_class = ($job['repeat_weekly']=='Y')?"green":"blue";
	                       	echo '<span class="'.$job_class.'">'.$playlist_data['name'].'</span>';
	                       	echo '</div>';
	                       }
	                       echo '</div>';

				           echo '</td>';
  }


	  echo '</tr>';
  echo '</table>';
  echo "</div>";
}




?>