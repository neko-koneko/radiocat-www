<?
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

error_reporting(E_NONE);

$global_description = 'Расписание - Календарь';
$global_keywords = 'Расписание - Календарь';

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


<?


$current_year = intval(($main_request_array[1]=='')?date("Y"):$main_request_array[1]);
if ($current_year==0){$current_year = intval(date("Y"));}

$current_month = intval(($main_request_array[2]=='')?date("m"):$main_request_array[2]);
if ($current_month==0){$current_month = intval(date("m"));}

$current_day = intval(($main_request_array[3]=='')?date("d"):$main_request_array[3]);
if ($current_day==0){$current_day = intval(date("d"));}




?>

 <!-- content -->
<div id="content">

<?
  if ($main_request_array[2]=='')
  {    print_year_table($current_year,$current_month);  }
  else if ($main_request_array[3]=='')
  {    print_month_table($current_year,$current_month);
  }
  else
  {    print_day_table($current_year,$current_month,$current_day);
  }




?>


</div>
    <!-- content end -->

<?

function print_year_table($current_year,$current_month)
{
 global $month_name_table,$base;
 global $utf_symbol;

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b> </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.($current_year-1).'\'"> <b><</b>  </td>
           <td>'.$current_year.'
           </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.($current_year+1).'\'"> <b>></b>  </td>
           <td>
        </tr>
      </table>
   </div>

   <div class="pad20">';


       $month=1;
       for ($i=1;$i<=4;$i++)
       {
	       for ($j=1;$j<=3;$j++)
	       {
               echo '<div class="fleft three movedownonsmall movedownonmedium">';

               print_month_plate($current_year,$month);

               $month++;
               echo "</div>";

	       }
       }



  echo "</div>";
}


function print_month_table($current_year,$current_month)
{
 global $base;
 global $month_name_table;
 global $weekday_abbr;
 global $utf_symbol;

 $next_month = $current_month+1;
 $prev_month = $current_month-1;

 $next_year = $current_year;
 $prev_year = $current_year;

 if ($next_month > 12){$next_month =1;  $next_year=$current_year+1;}
 if ($prev_month <= 0){$prev_month =12; $prev_year=$current_year-1; }

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$current_year.'\'"> <b><<</b> </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$prev_year.'/'.$prev_month.'\'"> <b><</b>  </td>
           <td>'.$month_name_table[$current_month].' '.$current_year.'
           </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$next_year.'/'.$next_month.'\'"> <b>></b>  </td>
           <td>
        </tr>
      </table>
   </div>

   <div class="pad20">';


 // $current_year,$current_month

  echo '<table class="w100 month_table">';

  echo '<tr>';
	  for ($day_of_the_week=1;$day_of_the_week<=7;$day_of_the_week++)
	  {
	   echo '<th class="w1x7p">'.$weekday_abbr[$day_of_the_week].'</th>';
	  }
  echo '</tr>';

  $now = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

  $day = 1;
  $row = 1;
  $end_of_month = false;
  $start_of_month =true;

  $current_weekday = date('w', mktime(0, 0, 0, $current_month, 1, $current_year));
  if ($current_weekday==0) {$current_weekday=7;}

  do
  {
	  echo '<tr>';
		  for ($day_of_the_week=1;$day_of_the_week<=7;$day_of_the_week++)
		  {
			   if($end_of_month)
			   {			   		   echo '<td class="dummy"></td>';
			   }
			   else
			   {
           		       if ($start_of_month)
           		       {           		       		if($day_of_the_week>=$current_weekday) {$start_of_month = false;}
           		       }

                       if ($start_of_month)
                       {			           echo '<td class="dummy"></td>';
                       }
                       else
                       {
	                       $new_time = mktime(0, 0, 0, $current_month, $day, $current_year);

	                       $day_class ='';

	                       if ($new_time==$now){$day_class = 'day_current ';}

	                       $current_day_last_time = mktime(0, 0, -1, $current_month, $day+1, $current_year);
	   					   $jobs = cron_get_job_by_time_interval($new_time,$current_day_last_time);


	   					   $jobs_count = count($jobs);
	   					   $jobs_count = ($jobs_count>6)?6:$jobs_count;
	                       $day_class.=' jobs_day_'.$jobs_count;


				           echo '<td class="day pointer '.$day_class.'"  onclick="window.location.href=\''.$base.'/calendar/'.$current_year.'/'.$current_month.'/'.$day.'\'" >'.
				                '<div class="day_num">'.$day.'</div>';

				           if ($jobs_count >0) echo  '<div class="hideonbig jobs_count">'.$jobs_count.'</div>';

                           echo '<div class="hideonmedium hideonsmall">';
	                       foreach ($jobs as $job)
	                       {
	                         $job_time_str = $job['time'];
						     $job_time = datetime_to_timestamp($job_time_str);
						     $playlist_id = $job['playlist_id'];
						     $playlist_data = get_playlist($playlist_id);	                       	echo '<div><span class="day_job_time">'.date("H:i",$job_time).'</span> '.$playlist_data['name'].'</div>';	                       }
	                       echo '</div>';

				           echo '</td>';
		                   $day++;

		                   $new_time = mktime(0, 0, 0, $current_month, $day, $current_year);
	                       $newmonth=date('m',$new_time);
		                   if($newmonth!=$current_month){$end_of_month=true;}
                       }

			   }

		  }
	  echo '</tr>';

  } while (!$end_of_month);


  /*
  for ($day_of_the_week==1;$day_of_the_week++;$day_of_the_week<=7)
  {   echo '<td>'.$day.'</td>';
   $day++;  }
   echo '<td>'.$day.'</td>';  /**/


  echo '</table>';




  echo "</div>";
}



function  print_day_table($current_year,$current_month,$current_day)
{
 global $base;
 global $month_name_table;
 global $month_name_genetive_table;
 global $weekday_abbr;
 global $utf_symbol;

 $next_time = mktime(0, 0, 0, $current_month, $current_day+1, $current_year);
 $now_time =  mktime(0, 0, 0, $current_month, $current_day, $current_year);
 $prev_time = mktime(0, 0, 0, $current_month, $current_day-1, $current_year);

 $_SESSION['data']['time']=$now_time;

 $next_day 	 = date("d",$next_time);
 $next_month = date("m",$next_time);
 $next_year  = date("Y",$next_time);

 $prev_day 	 = date("d",$prev_time);
 $prev_month = date("m",$prev_time);
 $prev_year  = date("Y",$prev_time);

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$current_year.'/'.$current_month.'\'"> <b><<</b> </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$prev_year.'/'.$prev_month.'/'.$prev_day.'\'"> <b><</b> </td>
           <td>'.$current_day.' '.$month_name_genetive_table[$current_month].' '.$current_year.'
           </td>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$next_year.'/'.$next_month.'/'.$next_day.'\'"> <b>></b>  </td>
           <td>
        </tr>
      </table>
   </div>

   <div class="pad20">';

   $current_day_last_time = mktime(0, 0, -1, $current_month, $current_day+1, $current_year);

   $jobs = cron_get_job_by_time_interval($now_time,$current_day_last_time);

   if (!is_array($jobs) or empty($jobs))
   {   	echo '<h1>Расписание не назначено<h1>';   }
   else
   {   	foreach ($jobs as $job)
   	{
     $job_time_str = $job['time'];
     $job_time = datetime_to_timestamp($job_time_str);

     $job_day   = date("d",$job_time);
     $job_hour   = date("H",$job_time);
     $job_minute = date("i",$job_time);

     $playlist_id = $job['playlist_id'];
     $playlist_data = get_playlist($playlist_id);


     echo '<div class="w100 fleft movedownonsmall ">';

     echo '<div class="job_plate pad10  asc ">';

     echo '<table class="w100">
            <tr >
              <td class="job_time">'.$job_hour.':'.$job_minute.'</td>
              <td class="job_name">';



            /*  echo '<span class="job_indicator">';
              if ($job['repeat_weekly']=='Y') {echo '&#x27f3';}
              echo '</span>'; /**/

	           	if ($playlist_data['static']=='Y') {echo '<span class="job_indicator red">S</span> ';} else {echo '<span class="job_indicator blue">D</span> ';}
	           	//if ($job['repeat_weekly']=='Y') {echo '<span class="job_indicator green">&#x1f501;</span> ';} else  {echo '<span class="job_indicator" style="color:#f0f;">&#x1f502;</span> ';}
	           	if ($job['repeat_weekly']=='Y') {echo '<span class="job_indicator green">'.$utf_symbol['ANTICLOCKWISE_OPEN_CIRCLE_ARROW'].'</span> ';}
	           	else  {echo '<span class="job_indicator" style="color:#f0f;">'.$utf_symbol['RIGHTWARDS_ARROW_TO_BAR'].'</span> ';}


              echo $playlist_data['name'];

     echo     '<div class="fright job_del  pointer" onclick="window.location.href=\''.$base.'/schedule/del/'.$job['id'].'\'">[X]</div>'.
              '<div class="fright job_time pointer" onclick="window.location.href=\''.$base.'/schedule/edit/'.$job['id'].'\'">[E]</div>'.

              '</td>

            </tr>
            </table>
            ';
     echo '</div>';

     echo '</div>';
   	}   }

     echo '<div class="w50 fleft movedownonsmall movedownonmedium" style="min-width: 50%; padding-top:10px;">';

     echo '<div class="job_plate pad10  asc pointer" onclick="window.location.href=\''.$base.'/schedule/add\'">';

     echo '<table class="">
            <tr class="pointer">
              <td class="job_time"><b>[+]</b></td>
              <td class="job_name" >Добавить плейлист</td>
            </tr>
            </table>
            ';
     echo '</div>';

     echo '</div>';

      echo '<div class="w50 fleft movedownonsmall movedownonmedium" style="min-width: 50%; padding-top:10px;">';

     echo '<div class="job_plate pad10  asc pointer" onclick="window.location.href=\''.$base.'/playlist/new\'">';

     echo '<table class="">
            <tr class="pointer">
              <td class="job_time"><b>[!]</b></td>
              <td class="job_name" >Создать плейлист</td>
            </tr>
            </table>
            ';
     echo '</div>';

     echo '</div>';

  echo "</div>";
}



function  print_month_plate($year,$month)
{
  global $base;
  global $month_name_table;

  if ($month < date("m")  and $year<=date("Y") or  $year<date("Y")){ $style = 'background:#aaa;';}
  if ($month == date("m") and $year==date("Y")){ $style = 'background:#acc;';}

  echo '<div class="month_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/calendar/'.$year.'/'.$month.'\'" >';
  echo '<h2>'.$month_name_table[$month].'</h2>';
  echo 'text';
  echo '</div>';

}
?>