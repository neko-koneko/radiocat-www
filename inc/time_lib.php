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
{  $h = floor($sec/3600);
  $m = floor(($sec%3600)/60);
  $s = $sec%60;
  return sprintf( '%02d:%02d:%02d'  , $h,$m,$s);
}

// input: time in [h[h..]]hh:mm:ss string
function hour_min_sec_to_sec($str)
{
  $sec = 0;  $parts = explode(':',$str,3);
  $h = intval($parts[0]);  $m = intval($parts[1]);
  $s = intval($parts[2]);

  if ($h<0){return $sec;}
  $sec = $h*3600;
  if ($m<0){return $sec;}
  $sec += $m*60;
  if ($s<0){return $sec;}
  $sec += $s;
  return $sec;
}



?>