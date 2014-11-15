<?php

function datetime_to_timestamp($time_str)
{
 $tmp_arr = explode(' ',$time_str);

 $date_str_part = $tmp_arr[0];
 $time_str_part = $tmp_arr[1];

 $date_arr = explode('-',$date_str_part);

 $year =$date_arr[0];
 $month=$date_arr[1];
 $day =$date_arr[2];

 $time_arr = explode(':',$time_str_part);

 $hour   = intval($time_arr[0]);
 $minute = intval($time_arr[1]);
 $second = intval($time_arr[2]);

 return mktime($hour,$minute,$second,$month,$day,$year);
}

function timestamp_to_date($time)
{	return date("Y-m-d H:i:s",$time);}


function sec_to_hour_min_sec($sec)
{  $stop = mktime(0,0,$sec,0,0,0);
  return date('H:i:s',$stop);
}



?>