<?php
if (!headers_sent()) { header("Content-type: text/html; charset=UTF-8"); }
error_reporting(E_ALL);
//error_reporting(0);
include_once("config/db_config.php");
include_once("config/auth_config.php");
include_once("inc/init.php");

include_once("inc/dbal.php");
include_once("inc/dbal_config.php");
include_once("inc/tagreader.php");
include_once("inc/playlist.php");
include_once("inc/time_lib.php");

$tstart=microtime(true);

if (reconnect_db() == false)
{
	echo "ERROR: ".mysqli_error($mysqli_connection); die;
}

if ($_GET['debug']=='1'){$debug=true;}else{$debug=false;}

echo '<h1>Загрузка работ CRON</h1> <br />';
$cron_jobs = cron_get_jobs();
if (empty($cron_jobs)) {echo 'Активных задач не найдено<br />выполнено без ошибок'; die();}

if ($debug){ print_r ($cron_jobs); }
echo '<h2>Итого задач: '.count($cron_jobs).'</h2>';

echo '<h1>Загрузка конфигурации</h1> <br />';

$db_config = array();
$raw_db_config=config_get_all_config();

foreach ($raw_db_config as $config_item)
{ $db_config[$config_item['name']] = $config_item['value'];}

if (!$db_config){echo 'Конфигурация отсутствует, продолжаю с параметрами по умолчанию</br>';}
if ($debug){print_r ($db_config);echo '<br/>';}

$last_job = array();

echo '<span style="color:#48f">TIME='.(microtime(true)-$tstart).'</span></br>';

foreach ($cron_jobs as $job)
{
	//$time = $job['time'];	 $time_str = $job['time'];
	 $time = datetime_to_timestamp($time_str);

	 $now = time();

	 echo '<h1>ЗАДАЧА '.$job['id'].'</h1>';
	 echo '<br />time for task is '.$time.' '.date("Y-m-d h:i:s",$time).' now is '.$now.' '.date("Y-m-d h:i:s",$now).'<br />';
	 echo 'playlist_id='.$job['playlist_id']."<br /><br />";
     //debug
     if (($debug == true) && ($job['id']=='1')){$job['done']='N';}

	 if ( $time < $now )
	 {
		if ( $job['done']=='N' )
		{
		 echo '<span style="color:green">задача требует выполнения</span><br /><br />';

		   $repeat_weekly=$job['repeat_weekly'];

		   $playlist_id=intval($job['playlist_id']);
		   if ($playlist_id<=0){echo '<span style="color:red">ОШИБКА: неверный номер плейлиста ('.$playlist_id.')</span><br /><br />';continue;}

		   $playlist_data=get_playlist($playlist_id);
		   if (!is_array($playlist_data) or empty($playlist_data)){echo '<span style="color:red">ОШИБКА: плейлист ('.$playlist_id.') не найден</span><br /><br />';continue;}
		   $playlist_static=$playlist_data['static'];

		   echo 'Начинаю обрабатывать задачу - плейлист № '.$playlist_id.' '.$playlist_data['name'].'<br /><br />';

		   if ($playlist_static=="Y")
		   {
		     echo 'Тип: <b>Статический плейлист</b><br />';
		    // echo 'Устанавливаю как активный';		     //set_active_playlist($playlist_id,1,"Y");
		     if ($repeat_weekly=="Y")
		     {		        echo "<b>АВТОПОВТОР ЧЕРЕЗ НЕДЕЛЮ</b><br /><br />";
		        echo "Добавляю работу в расписание<br /><br />";
		     	$new_timestamp =strtotime("+1 week",$time);
		     	echo "Время следующего исполнения задачи =".$new_timestamp." ".timestamp_to_date($new_timestamp)."<br />";
		     	$ca_result = cron_add_job($playlist_id,$new_timestamp,$repeat_weekly);
		     	if (!$ca_result) {echo 'Не удалось добавить задачу<br />';}		     }		   }
		   else
		   {		     echo 'Тип: <b>Динамический плейлист</b><br />';
		     echo 'Перегенерирую плейлист';
		     $tstart2 = microtime(true);
		     $result = regenerate_playlist($playlist_id);
		     $tstop2 = microtime(true);
		     $tdiff2 = $tstop2 - $tstart2;
		     echo 'ПОТРАЧЕНО ВРЕМЕНИ НА ГЕНЕРАЦИЮ: '.$tdiff2.' секунд<br/>';		    // echo 'Устанавливаю как активный';
		     //set_active_playlist($playlist_id,1,"Y");
		     if ($repeat_weekly=="Y")
		     {
		        echo "<b>АВТОПОВТОР ЧЕРЕЗ НЕДЕЛЮ</b><br /><br />";
		        echo "Добавляю работу в расписание<br /><br />";
		     	$new_timestamp =strtotime("+1 week",$time);
		     	echo "Время следующего исполнения задачи =".$new_timestamp." ".timestamp_to_date($new_timestamp)."<br />";
		     	$ca_result = cron_add_job($playlist_id,$new_timestamp,$repeat_weekly);
		     	if (!$ca_result) {echo 'Не удалось добавить задачу<br />';}
		     }
		   }


	       cron_update_job($job['id'],$job_result,"Y");
	       if(($debug == false) || ($job['id']!='1')){
	       $last_job = $job;
	       }
	  	}
	  	else
	  	{		 echo '<span style="color:orange">задача НЕ требует выполнения (уже выполнена)</span><br /><br />';
	  	}


	 }
	 else
	 {	 echo '<span style="color:red">задача НЕ требует выполнения (по времени)</span><br /><br />';
	 }
 echo '<span style="color:#48f">TIME='.(microtime(true)-$tstart).'</span></br>';
}
 echo '<span style="color:#48f">job processing ends. TIME='.(microtime(true)-$tstart).'</span></br>';
 echo '<br /><br />';
 $playlist_id=intval($last_job['playlist_id']);

 $np_data = get_active_playlist();
 $np_playlist_id = $np_data['current_playlist_id'];

 echo "Сейчас играет плейлист № ".$np_playlist_id.", требуется установить: ".$playlist_id." <br />";

 echo '<span style="color:#48f">TIME='.(microtime(true)-$tstart).'</span></br>';

 if (!empty($last_job))
   {

	 if ($np_playlist_id == $playlist_id)
	 {	  echo "уже играет — не требуется смена плейлиста";
	 // set_active_playlist($last_job_id,0,"N");
	 }
	 else
	 {
 	  echo 'Устанавливаю активный плейлист '.$playlist_id."<br />";
	  set_active_playlist($playlist_id,1,"Y");
	  }
   }
   else
   {   	echo "Переключение плейлиста не требуется";   }

echo "<br/>готово</br>";
echo "*******************************************************************************************************<br>";
echo '<span style="color:#48f">TOTAL EXECUTION TIME='.(microtime(true)-$tstart).'</span></br>';
echo "*******************************************************************************************************";
die();


function regenerate_playlist($playlist_id)
{
 global $db_config;

 $tstart = microtime(true);
 $result = array();
 $result['status'] = 'FAIL';

 $playlist_data = get_playlist($playlist_id);

 if (!is_array($playlist_data) or empty($playlist_data))
 {   $result['description'] = "error - invalid playlist id";
   return $result;
 }

 $playlist_id     = $playlist_data['id'];
 $playlist_name   = $playlist_data['name'];
 $playlist_static = $playlist_data['static'];
 $playlist_rules  = $playlist_data['rules'];

 $playlist_data['ruleset']=json_decode($playlist_data['rules'],true);

 /*$xml = simplexml_load_string($playlist_rules);

 if (!$xml)
 { 	$result['description'] = 'error - cannot load xml';
 	return $result;
 }  /**/

 echo '<br />Начинаю обработку правил <br /><br />';

 echo 'Шаг 1 - предварительная выборка<br /><br />';


 $final_playlist = array();

 $final_playlist_data = generate_dynamic_playlist($playlist_data,'nofilterform',false);
	    $final_playlist =  $final_playlist_data['data'];
	    echo $final_playlist_data['view'];

 echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span></br>';


 echo '<br />Шаг 3 - Сборка плейлиста<br /><br />';

 echo 'Проверка итогового плейлиста на дубли<br />';


     $tmp = array();

     foreach ($final_playlist as $track_data)
     {       $tmp[$track_data['id']] = $track_data;     }


     $before_count = count ($final_playlist);
     $after_count = count ($tmp);

 echo 'Изначальное количество треков в итоговом плейлисте: '.$before_count.'<br />';
 echo 'Конечное    количество треков в итоговом плейлисте: '.$after_count.'<br />';

 if ($before_count!=$after_count)
 {
    $diff_count = $before_count - $after_count; 	echo 'Из плейлиста удалены дубли: '.$diff_count.' шт<br />';

 	$final_playlist = $tmp; }
 else
 { 	echo 'В итоговом плейлисте дубли не найдены<br />'; }


	 echo '<h2>сгенерирован новый плейлист — '.$playlist_data['name'].' ('.$playlist_id.')</h2>';

 echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span></br>';

 echo '<br />Шаг 4 - Проверка на совпадение недавно сыгранных треков <br /><br />';


$offset_hours = intval($db_config['offset_hours']);
if ($offset_hours<0 or $offset_hours>6){$offset_hours=1;}
$max_forward_lookup_tracks_counter = intval($db_config['max_forward_lookup_tracks_counter']);
if ($max_forward_lookup_tracks_counter<0 or $max_forward_lookup_tracks_counter>60){$max_forward_lookup_tracks_counter=20;}
$max_try_count = intval($db_config['max_try_count']);
if ($max_try_count<=0 or $max_try_count>10){$max_try_count=5;}

echo '<b>настройки</b>: просматривать назад на '.$offset_hours.' часов, просматривать вперёд не более '.$max_forward_lookup_tracks_counter.' треков, макс. число попыток ',$max_try_count.'<br />';

echo 'сыграны за последние '.$offset_hours.' часов <br />';

$last_played_files = get_last_played_tracks_files($offset_hours);
if (empty($last_played_files)){ echo '<b>Нет данных</b><br />';}

foreach ($last_played_files as $last_played_file)
{ //echo $file_data['id']." ";
 echo '<b>'.$last_played_file['file_id'].'</b> ';
 echo date ("d-m-Y H:i:s", datetime_to_timestamp($last_played_file['time']))." ";
 //echo '<b>'.$last_played_file['filename'].'</b>'
 echo '<br />';}

 echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span></br>';


$try_count=0;
while ($try_count<$max_try_count)
	{
	 echo  'перетасовываю, попытка '.$try_count.'<br />';
     $repeat_error_flag = false;
      echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span></br>';

     shuffle ($final_playlist);
	 if ($_GET['debug']=='1') { echo 'final playlisy:';print_r($final_playlist);echo'<br>';}

     foreach ($last_played_files as $last_played_file)
     {     	$last_played_files_ids[] = $last_played_file['file_id'];     }

     $tracks_counter = 0;
	 foreach ($final_playlist as $playlist_data)
		     {			     $candidate_file_id = $playlist_data['id'];

			     if (in_array($candidate_file_id,$last_played_files_ids))
			     {			     	$repeat_error_flag =true;
			     	echo '<br/>неудача, '.$playlist_data['filename'].' [id=<b>'.$playlist_data['id'].'</b>] , пробую снова<br />';
			     	break;
			     }

			     $tracks_counter++;
			     if ($tracks_counter == $max_forward_lookup_tracks_counter){break;}		     }

        if(!$repeat_error_flag)
        {         echo 'Успешно<br />'; break;        }

    $try_count++;
    if ($try_count==$max_try_count){ echo 'максимальное число попыток достигнуто<br />'; break;}
    }
     echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span><br>';

    $temp_playlist_first_block = Array();
    $temp_playlist_last_block = Array();

    if($repeat_error_flag)
    {
        echo 'перемещаю сыгранные за  последние '.$offset_hours.' часов треки в конец плейлиста<br><br>';

        $played_files_id = array();		foreach ($files_data as $file_data)
		{
	     $played_files_id[] = $file_data['id'];
        }

		     foreach ($final_playlist as $playlist_data)
		     {
			     $candidate_file_id = $playlist_data['id'];

			     if (in_array($candidate_file_id,$played_files_id))
			     {                   $temp_playlist_last_block[] = $playlist_data;			     }
			     else
			     {                   $temp_playlist_first_block[] = $playlist_data;
			     }
		     }


        $final_playlist = array_merge($temp_playlist_first_block,$temp_playlist_last_block);
    }

     echo '<span style="color:red">main processing ends. TIME='.(microtime(true)-$tstart).'</span></br>';

	 echo '<br><b>ИТОГ:</b><br>';
     if ($_GET['debug']=='1') { echo 'final playlisy:';print_r($final_playlist);echo'<br>';}
	// print_playlist($final_playlist);

// return;
     /*echo 'final =<br />';
     print_r($final_playlist); /**/

     // save playlist
     $playlist_id = intval ($playlist_id);
    // edit_playlist($playlist_id,$playlist_name,$playlist_static,$playlist_rules);

     clear_playlist_tracks($playlist_id);
     echo '<span style="color:red">tracks deleted TIME='.(microtime(true)-$tstart).'</span></br>';

     $track_number = 1;
     foreach ($final_playlist as $playlist_item)
     {
      $file_id = $playlist_item['id'];
      add_playlist_track($playlist_id,$track_number,$file_id);

      $track_number ++;
     }
     echo '<span style="color:red">'.$track_number.' tracks added TIME='.(microtime(true)-$tstart).'</span></br>';


  echo '<span style="color:red">TIME='.(microtime(true)-$tstart).'</span></br>';

 $result['status'] = 'OK';
return $result;
}

?>