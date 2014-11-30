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

if (reconnect_db() == false)
{
	echo "ERROR: ".mysqli_error($mysqli_connection); die;
}

echo '<h1>Загрузка работ CRON</h1> <br />';
$cron_jobs = cron_get_jobs();
if (empty($cron_jobs)) {echo 'Активных задач не найдено<br />выполнено без ошибок'; die();}

print_r ($cron_jobs);

echo '<h1>Загрузка конфигурации</h1> <br />';

$db_config = array();
$raw_db_config=config_get_all_config();

foreach ($raw_db_config as $config_item)
{ $db_config[$config_item['name']] = $config_item['value'];}

print_r ($db_config);

$last_job = array();
foreach ($cron_jobs as $job)
{
//$time = $job['time']; $time_str = $job['time'];
 $time = datetime_to_timestamp($time_str);

 $now = time();

 echo '<h1>ЗАДАЧА '.$job['id'].'</h1>';
 echo '<br />time for task is '.$time.' '.date("Y-m-d h:i:s",$time).' now is '.$now.' '.date("Y-m-d h:i:s",$now).'<br />';
 echo 'playlist_id='.$job['playlist_id']."<br /><br />";

 if ($time<$now)
 {
	if ($job['done']=='N')
	{
	 echo '<span style="color:green">задача требует выполнения</span><br /><br />';	 //regenerate_playlist($playlist_id);

		/* $result = regenerate_playlist($job['playlist_id']);

		 switch ($result['status'])
		 {
			 case 'OK':
			 {
			   $job_result = 'УСПЕХ задача выполнена '.date("Y-m-d h:i:s",$now);
			   echo $job_result.'<br />****************************************************************************************************<br />';
			   set_active_playlist($job['playlist_id'],1,"Y");
			   break;
			 }
			 case 'FAIL':
			 {
			   $job_result = 'ОШИБКА задача НЕ выполнена '.date("Y-m-d h:i:s",$now).'<br />'.$result['description'] ;
			   echo $job_result.'<br />****************************************************************************************************<br />';
			   set_active_playlist(1,1,"Y");
			   break;
			 }
	     }       /**/

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
	    // echo 'Устанавливаю как активный';	     //set_active_playlist($playlist_id,1,"Y");
	     if ($repeat_weekly=="Y")
	     {	        echo "<b>АВТОПОВТОР ЧЕРЕЗ НЕДЕЛЮ</b><br /><br />";
	        echo "Добавляю работу в расписание<br /><br />";
	     	$new_timestamp =strtotime("+1 week",$time);
	     	echo "Время следующего исполнения задачи =".$new_timestamp." ".timestamp_to_date($new_timestamp)."<br />";
	     	$ca_result = cron_add_job($playlist_id,$new_timestamp,$repeat_weekly);
	     	if (!$ca_result) {echo 'Не удалось сохранить задачу<br />';}	     }	   }
	   else
	   {	     echo 'Тип: <b>Динамический плейлист</b><br />';
	     echo 'Перегенерирую плейлист';
	     $result = regenerate_playlist($playlist_id);	    // echo 'Устанавливаю как активный';
	     //set_active_playlist($playlist_id,1,"Y");
	     if ($repeat_weekly=="Y")
	     {
	        echo "<b>АВТОПОВТОР ЧЕРЕЗ НЕДЕЛЮ</b><br /><br />";
	        echo "Добавляю работу в расписание<br /><br />";
	     	$new_timestamp =strtotime("+1 week",$time);
	     	echo "Время следующего исполнения задачи =".$new_timestamp." ".timestamp_to_date($new_timestamp)."<br />";
	     	$ca_result = cron_add_job($playlist_id,$new_timestamp,$repeat_weekly);
	     	if (!$ca_result) {echo 'Не удалось сохранить задачу<br />';}
	     }
	   }


       cron_update_job($job['id'],$job_result,"Y");
  	}
  	else
  	{	 echo '<span style="color:orange">задача НЕ требует выполнения (уже выполнена)</span><br /><br />';
  	}

  	$last_job = $job;
 }
 else
 { echo '<span style="color:red">задача НЕ требует выполнения (по времени)</span><br /><br />';
 }

}

 echo '<br /><br />';
 $playlist_id=intval($last_job['playlist_id']);

 if (!empty($last_job))
   {
	 $np_data = get_active_playlist();
	 $np_playlist_id = $np_data['current_playlist_id'];

	 echo "Сейчас играет плейлист № ".$np_playlist_id."<br />";

	 echo 'Устанавливаю активный плейлист '.$playlist_id."<br />";

	 if ($np_playlist_id == $playlist_id)
	 {	  echo " этот плейлист уже играет — не требуется смена плейлиста";
	 // set_active_playlist($last_job_id,0,"N");
	 }
	 else
	 {
	  echo " готово";
	  set_active_playlist($playlist_id,1,"Y");
	  }
   }
   else
   {   	echo "Переключение плейлиста не требуется";   }

 die();




function regenerate_playlist($playlist_id)
{
 global $db_config;
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
 /*$ruleset = array();
 $track_stats = array(); $i=0;
 foreach($xml->rule as $rule)
 {
        $attr = $rule->attributes();

        $data = array();
        foreach ($attr as $a => $b)
        {           $data[$a] = $b;        }
        $id = $data['id'];

        $max_tracks_count = $data['max_tracks_count'];

        $search_result = get_tracks_by_filter($data);
		$tracks_count = count($search_result);
		$max_tracks_count = intval($max_tracks_count);

        $track_stats[$i] = $data;
        $track_stats[$i]['tracks_count'] = $tracks_count;

        $i++;
 } /**/

 $final_playlist_data = generate_dynamic_playlist($playlist_data);
	    $final_playlist =  $final_playlist_data['data'];
	    echo $final_playlist_data['view'];


 /*echo 'Шаг 2 - Анализ результатов<br /><br />';
 $playlist_total_required_track_count =0;
 $playlist_total_found_track_count =0;
 $deficite_flag = false;
 foreach ($track_stats as $rule_id => $track_data)
 {
  if ($track_data['tracks_count']<$track_data['max_tracks_count']){$deficite_flag=true;}
  $playlist_total_required_track_count += ($track_data['max_tracks_count']==0)?$track_data['tracks_count']:$track_data['max_tracks_count'];
  $playlist_total_found_track_count += $track_data['tracks_count'];
 }

 echo 'Итого требуется треков: '.$playlist_total_required_track_count.'<br /> Итого найдено треков: '.$playlist_total_found_track_count.'<br />';

 if ($playlist_total_required_track_count > $playlist_total_found_track_count)
 { 	// все фильтры находят меньше чем нужно - аварийный плейлист
            $default_playlist = get_playlist(1);
			echo 'Все Фильтры: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            $result['status'] = 'FAIL';
            $result['description'] = 'Все Фильтры: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            return $result;
 }
 else
 {
  	// все фильтры находят достаточное число треков, но при этом не ясно, какой из фильтров хуёвничает

      if ($deficite_flag) // есть плохой фильтр
      {       echo 'Как минимум один из фильтров не получил нужное число треков - повторяю поиск без жёсткого задания числа треков - ожидается найти '.$playlist_total_found_track_count.' треков<br />';
          $final_playlist = array();
	      foreach ($track_stats as $rule_id => $track_data)
	      {
//            $track_data['max_tracks_count']=0;
            $search_result = get_tracks_by_filter($track_data);
            $final_playlist= array_merge($final_playlist,$search_result);
	      }

      }
      else    // все фильтры находят сколько нужно
      {       echo 'Все фильтры нашли нужное число треков<br />';

         foreach ($track_stats as $rule_id => $track_data)
	      {
            $search_result = get_tracks_by_filter($track_data);
            $tracks_count = count($search_result);
   		    $max_tracks_count = intval($max_tracks_count);
            $count_priority = ($track_data['count_priority']=="Y");

            if ($max_tracks_count>0)  // в правилах задано ограничение на число треков
				{
				  echo 'правило '.$rule_id.': в правилах задано ограничение на число треков <br />';
			 	  $playlist = array();

						 if	($max_tracks_count<$tracks_count) // найдено треков больше чем нужно
						 {
						      echo 'правило '.$rule_id.': найдено треков больше чем нужно<br />';

						     if ($count_priority) // в правилах задано сортровать по приоритету счётчика числа проигрываний
							 {
							    echo 'правило '.$rule_id.': режим приоритета новых треков <b>ВКЛ</b><br />';
							    $tmp = array(); $i=0;
	                            foreach ($search_result as $trackdata)   // разобъём на блоки по числу проигрываний
	                            {
	                              $tmp[$trackdata['count']][$i] = $trackdata;
	                              $i++;
	                            }
	                            ksort($tmp,SORT_NUMERIC);   // сортировка блоков

	                            $tmp2 = array();$i=0;         // забиваем блоками стек, пока не будет достигнут нужный размер или более
	                            foreach($tmp as $count_group)
	                            {
	                             foreach ($count_group as $trackdata)
	                             {
	                                $tmp2[$i] = $trackdata;
	                                $i++;
	                             }
	                             if ($i>=$max_tracks_count) {break;}
	                            }

	                            shuffle($tmp2); // перемешать

	                            for ($i=0; $i<$max_tracks_count; $i++) // обрезать под нужное число треков и записать в выходной массив
	                            {
	                               $playlist[$i] = $tmp2[$i];
	                            }

						     }
					 	 	else
						 	 {
						 	    echo 'правило '.$rule_id.': режим приоритета новых треков <b>ВЫКЛ</b><br />';

							    shuffle($search_result); // перемешать

                                $i=0;
	                            foreach ($search_result as $trackdata)
	                            {
	                               $playlist[$i] = $trackdata;
	                               $i++;
	                               if ($i==$max_tracks_count){break;}
	                            }
						 	 }
         				 }
                         else
                         {
                                echo 'правило '.$rule_id.': найдено треков Меньше или сколько требуется<br />';
							    echo 'правило '.$rule_id.': режим приоритета новых треков <b>выключен</b><br />';

	                         	$map = generate_random_map($tracks_count,$max_tracks_count); // забить массив (если треков меньше чем нужно, то можно забить с дублями, и, если это возможно, без повторов)
						       	//print_r ($map);
						     	foreach ($map as $map_data)
						       	{
						        	$playlist[] = $search_result[$map_data];
						       	}
                         }


				}
				else
				{
				echo 'правило '.$rule_id.': режим приоритета новых треков <b>выключен автоматически</b> — не указан предел отбора числа треков<br />';
				$playlist = $search_result;
				}
          $final_playlist= array_merge($final_playlist,$playlist);
	      }
      }
 } /**/

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


 echo '<br />Шаг 4 - Проверка на совпадение недавно сыгранных треков <br /><br />';


$offset_hours = intval($db_config['offset_hours']);
if($offset_hours<=0){$offset_hours=1;}
$max_forward_lookup_tracks_counter = intval($db_config['max_forward_lookup_tracks_counter']);
if($max_forward_lookup_tracks_counter<=0){$max_forward_lookup_tracks_counter=20;}
$max_try_count = intval($db_config['max_try_count']);
if($max_try_count<=0 or $max_try_count>10){$max_try_count=5;}

echo '<b>настройки</b>: просматривать назад на '.$offset_hours.' часов, просматривать вперёд не более '.$max_forward_lookup_tracks_counter.' треков, макс. число попыток ',$max_try_count.'<br />';

echo 'сыграны за последние '.$offset_hours.' часов <br />';

$files_data = get_last_played_tracks_files($offset_hours);
foreach ($files_data as $file_data)
{ echo $file_data['id']." ";
 echo $file_data['file_id']." ";
 echo date ("d-m-Y H:i:s", datetime_to_timestamp($file_data['time']))." ";
 echo $file_data['filename']."<br /> ";}


//print_r($final_playlist);

$try_count=0;
while ($try_count<$max_try_count)
	{
	 echo  'перетасовываю, попытка '.$try_count.'<br />';

     shuffle ($final_playlist);

        $repeat_error_flag = false;
		foreach ($files_data as $file_data)
		{	     $played_file_id = $file_data['file_id'];

	     $tracks_counter=0;
		     foreach ($final_playlist as $playlist_data)
		     {			     $candidate_file_id = $playlist_data['id'];

			     if ($candidate_file_id == $played_file_id)
			     {			     	$repeat_error_flag =true;
			     	echo 'неудача, '.$playlist_data['filename'].' [id='.$playlist_data['id'].'] , пробую снова<br />';
			     	break 2;
			     }

			     $tracks_counter++;
			     if ($tracks_counter == $max_forward_lookup_tracks_counter){break;}		     }
		}
        if(!$repeat_error_flag)
        {         echo 'Успешно<br />'; break;        }

    $try_count++;
    if ($try_count==$max_try_count){ echo 'максимальное число попыток достигнуто<br />'; break;}
    }

    $temp_playlist_first_block = Array();
    $temp_playlist_last_block = Array();

    if($repeat_error_flag)
    {
        echo 'перемещаю сыгранные за  последние '.$offset_hours.' часов треки в конец плейлиста';

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


	 print_playlist($final_playlist);

// return;
     /*echo 'final =<br />';
     print_r($final_playlist); /**/

     // save playlist
     $playlist_id = intval ($playlist_id);
    // edit_playlist($playlist_id,$playlist_name,$playlist_static,$playlist_rules);

     clear_playlist_tracks($playlist_id);

     $track_number = 1;
     foreach ($final_playlist as $playlist_item)
     {
      $file_id = $playlist_item['id'];
      add_playlist_track($playlist_id,$track_number,$file_id);

      $track_number ++;
     }

 $result['status'] = 'OK';
return $result;
}



/*

if ($tracks_count>0)
			{
				if ($max_tracks_count>0)
				{

					if ($max_tracks_count<=$tracks_count)
					{
					   echo 'Отобрано: '.$max_tracks_count.' треков (из '.$tracks_count.') ';
					   echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';
					   echo '<span class="maroon"> / '.sec_to_hour_min_sec($pl_time_all).'</span> ';
                       echo '<span class="message_ok">Без повторов</span><br />';
					}
					else

					{
					   echo 'Отобрано: '.$max_tracks_count.' треков (из '.$tracks_count.') ';
					   echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';
					   echo '<span class="maroon"> / '.sec_to_hour_min_sec($pl_time_all).'</span> ';
					   echo '<span class="message_warn">С повторами</span><br />';
					   $final_playlist_repeated_track = true;

                     /*  $required_tracks =  $max_tracks_count - $tracks_count;

					   echo 'Включаю адаптивный фильтр — пытаюсь добрать '.$required_tracks.' трэков с менее жёсткими условиями';

					   $adaptive_result = playlist_get_adaptive_tracks_by_filter($data);

                       $adaptive_$tracks_count = count($adaptive_result);

                       if ($adaptive_$tracks_count>0)
                       {

                       }
                       else // total FAIL - адаптивный фильтр не справился с задачей
                       {
					    $default_playlist = get_playlist(1);
						echo 'Фильтр (включён адаптивный режим): не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
			            $result['status'] = 'FAIL';
			            $result['description'] = 'Фильтр (включён адаптивный режим): не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
			            return $result;
			            }
					}

                 $playlist = $search_result;
			 	/* $playlist = array();
 			     $map = generate_random_map($tracks_count,$max_tracks_count);
			       //print_r ($map);

			     foreach ($map as $map_data)
			       {
			        $playlist[] = $search_result[$map_data];
			       }
                 $pl_time = 0;


                 foreach ($playlist as $pl_element)
                   {
                     $pl_time += $pl_element['length'];
                   }


				}
				else
				{
				echo 'Отобрано: '.$tracks_count.' треков ';
 			    echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time_all).'</span> ';
				$playlist = $search_result;
				}
			}
			else
			{
			/*$default_playlist = get_playlist(1);
			echo 'Фильтр: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            $result['status'] = 'FAIL';
            $result['description'] = 'Фильтр: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            return $result;
			}

*/


		/*$pl_time_all = 0;
                 foreach ($search_result as $pl_element)
                   {
                     $pl_time_all += $pl_element['length'];
                   }            /**/

/*

   if ($tracks_count>0)
	  	     {  print_playlist($playlist);
	            $final_playlist= array_merge($final_playlist,$playlist);
             }

        echo '</div>';
*/

/*$default_playlist = get_playlist(1);
			echo 'Фильтр: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            $result['status'] = 'FAIL';
            $result['description'] = 'Фильтр: не могу подобрать ни одного трека — переключаюсь на плейлист по умолчанию ('.$default_playlist['name'].')';
            return $result;  /**/

?>