<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

error_reporting(E_ALL);
//error_reporting(0);

$global_description = 'Редактор плейлистов';
$global_keywords = 'Редактор плейлистов';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/time_lib.php");
 include("inc/playlist.php");

echo '<script type="text/javascript" src="'.$base.'/js/playlist.js"></script>';

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');

 ?>



 <!-- content -->
<div id="content">

<?php
 if ($main_request_array[1]=='new')
 {  print_playlist_new_form(); }
 if ($main_request_array[1]=='add')
 {
  $message ='';
  if ($_POST['action']=='submit_add')
  {  // print_r ($_POST);

   $playlist_id = $_POST['playlist_id'];
   $hour        = $_POST['hour'];
   $minute      = $_POST['minute'];

   $year      = $_POST['year'];
   $month      = $_POST['month'];
   $day      = $_POST['day'];

   $repeat_weekly = ($_POST['repeat_weekly']=="Y")?"Y":"N";

   $time = mktime($hour,$minute,0,$month,$day,$year);

   $result = cron_add_job($playlist_id,$time,$repeat_weekly);

   if ($result)
   {
    $message = '<h1 class="tacentr ok">Плейлист успешно добавлен</h1>';
 	$message .= '<script>setTimeout(\'window.location.href="'.$base.'/calendar/'.$year.'/'.$month.'/'.$day.'"\',100);</script>';
   	print_message($message,'Добавление плейлиста в расписание');
   	return;
   }
   else
   {    $message ='<h1 class="error">Не удалось добавить элемент расписания!</h1>';
   	print_message($message,'Добавление плейлиста в расписание');
    return;   }

  }

  $job_time = isset($_SESSION['data']['time'])?$_SESSION['data']['time']:time();
    // $playlist_id     = $_POST['playlist_id'];

    // if (isset($_POST['hour']))   { $job_hour    = sprintf("%02d",$_POST['hour']);}
    // if (isset($_POST['minute'])) { $job_minute  = sprintf("%02d",$_POST['minute']);}

	// $repeat_weekly = $_SESSION['data']['repeat_weekly'];
	// $repeat_weekly = ($repeat_weekly == "Y")?"Y":"N";

    // $data['playlist_id']   = $result['playlist_id'];
    // $data['repeat_weekly'] = $result['repeat_weekly'];;
     $data['time'] = $job_time;

     $data['action']='submit_add';
     $data['title']='Добавление элемента расписания';
  print_playlist_form($data,$message);

 }
 if ($main_request_array[1]=='del')
 {
   echo 'del';
   $job_id = $main_request_array[2];

   echo ' '.$job_id;

   $job_time = isset($_SESSION['data']['time'])?$_SESSION['data']['time']:time();

   $year   = date("Y",$job_time);
   $month   = date("m",$job_time);
   $day   = date("d",$job_time);
   $result = cron_del_job($job_id);

   if ($result)
   {
    $message = '<h1 class="tacentr ok">Элемент расписания удален из списка воспроизведения на указанный день</h1>'.
   	'<script>setTimeout(\'window.location.href="'.$base.'/calendar/'.$year.'/'.$month.'/'.$day.'"\',100);</script>';
   }
   else
   {
     $message = '<h1 class="tacentr error">Не удалось удалить элемент расписания!</h1>';
   }   print_message($message,'Удалить плейлист из расписания');
 }
 if ($main_request_array[1]=='edit')
 {
   $job_id = $main_request_array[2];
   $result = cron_get_job($job_id);

   if ($result)
   {   	 //$job_time = isset($_SESSION['data']['time'])?$_SESSION['data']['time']:time();
    // $playlist_id     = $_POST['playlist_id'];

    // if (isset($_POST['hour']))   { $job_hour    = sprintf("%02d",$_POST['hour']);}
    // if (isset($_POST['minute'])) { $job_minute  = sprintf("%02d",$_POST['minute']);}

	// $repeat_weekly = $_SESSION['data']['repeat_weekly'];
	// $repeat_weekly = ($repeat_weekly == "Y")?"Y":"N";

     if ($_POST['action']=='submit_edit')
     {
       $job_id = $_POST['job_id'];
       $playlist_id = $_POST['playlist_id'];
	   $hour        = $_POST['hour'];
	   $minute      = $_POST['minute'];

	   $year      = $_POST['year'];
	   $month      = $_POST['month'];
	   $day      = $_POST['day'];

	   $repeat_weekly = ($_POST['repeat_weekly']=="Y")?"Y":"N";

	   $time = mktime($hour,$minute,0,$month,$day,$year);

	   $edit_result = cron_edit_job($job_id,$playlist_id,$time,$repeat_weekly);

	   if ($edit_result)
	   {
	    $message = '<h1 class="tacentr ok">Элемент расписания успешно отредактирован</h1>';
        $message .=	'<script>setTimeout(\'window.location.href="'.$base.'/calendar/'.$year.'/'.$month.'/'.$day.'"\',100);</script>';
	   	print_message($message,'Редактирование элемента расписания');
	   	return;
	   }
	   else
	   {
	     $message ='<h1 class="error">Не удалось добавить плейлист!</h1>';

			$data['job_id'] = $job_id;
			$data['playlist_id']   = $playlist_id;
			$data['repeat_weekly'] = $repeat_weekly;
			$data['time'] = $time;
			$data['action']='submit_edit';
            $data['title']='Редактирование элемента расписания';

	     print_playlist_form($data,$message);
         return;
	   }
     }

     $data['job_id'] = $job_id;
     $data['playlist_id']   = $result['playlist_id'];
     $data['repeat_weekly'] = $result['repeat_weekly'];;
     $data['time'] = datetime_to_timestamp($result['time']);

     $data['action']='submit_edit';
     $data['title']='Редактирование элемента расписания';
     print_playlist_form($data,$message);
   }
   else
   {
     $message = '<h1 class="tacentr error">Не удалось загрузить элемент расписания!</h1>';
     print_message($message,'Редактирование элемента расписания');
   }
 }


?>

</div>
    <!-- content end -->

<?php



function print_playlist_new_form()
{
 global $base; echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar\'"> <b><<</b> </td>
           <td>Создать новый плейлист
           </td>
        </tr>
      </table>
   </div>';

   echo "</div>";}

function print_playlist_form($data,$message)
{
     global $base;
     $job_id =  $data['job_id'];

     $job_time = $data['time'];
     $repeat_weekly = $data['repeat_weekly'];
     $playlist_id   = $data['playlist_id'];
     $action = $data['action'];



     $job_year   = date("Y",$job_time);
     $job_month   = date("m",$job_time);
     $job_day   = date("d",$job_time);
     $job_hour   = date("H",$job_time);
     $job_minute = date("i",$job_time);

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/calendar/'.$job_year.'/'.$job_month.'/'.$job_day.'\'"> <b><<</b> </td>
           <td>'.$data['title'].'
           </td>
        </tr>
      </table>
   </div>';

   echo "</div>";




   echo '<div class="pad20">';

   echo '<form method="POST" action="">';
   echo '<input type="hidden" name="job_id" value="'.$job_id.'">';
   echo '<input type="hidden" name="action" value="'.$action.'">';

   echo '<input type="hidden" name="year"  value="'.$job_year.'">';
   echo '<input type="hidden" name="month" value="'.$job_month.'">';
   echo '<input type="hidden" name="day"   value="'.$job_day.'">';

   echo '<h2>На '.DateTimeStampToStrWithRussianMonthInGenetive($job_time).''.'</h2>';
   echo '<br /><br />';

   echo get_playlist_select('class="w100" id="playlist_id" name="playlist_id"','',$playlist_id);

   echo '<br /><br />';

   echo '<table class="valignmiddle w100">';
   echo '<tr><td style="width:10px;">';
	   echo 'Час:';
	   echo '</td><td>';
	   echo '<div id="current_hour">'.$job_hour.'</div>';
	   echo '</td><td>';
	   echo '<input class="w90 pad10" type="range" name="hour"  value="'.$job_hour.'" min="0" max="23" oninput="change_range(this);">';
   echo '</td></tr>';
   echo '<tr><td>';
	   echo	'Мин:';
	   echo '</td><td>';
	   echo '<div id="current_minute">'.$job_minute.'</div>';
	   echo '</td><td>';
	   echo '<input class="w90 pad10" type="range" name="minute" value="'.$job_minute.'" min="0" max="59" oninput="change_range(this);">';
   echo '</td></tr>';
   echo '</table>';

   echo '<br /><br />';

   echo '<input name="repeat_weekly" type="checkbox" value="Y"';
   if ($repeat_weekly=="Y") {echo ' checked="checked" ';}
   echo '> Автоповтор через неделю';

   echo '<br /><br />';

   echo '<button type="submit" value="Submit">ОK</button>';
   echo '<br /><br />';

   echo $message;
   echo '</form>';

   echo "</div>";

}





?>