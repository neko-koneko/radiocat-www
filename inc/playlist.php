<?
require_once("utils.php");
require_once("time_lib.php");
require_once("dbal.php");
require_once("playlist_view.php");

function generate_random_map($positions,$count)
{
   $last_val = -1;
   $result = array();

   $layers = floor($count/$positions);
   $remainder =  $count%$positions;

   for ($i=0;$i<=$layers;$i++)
   {
   	$row = fill_row($positions,$last_val);
   	if ($i==$layers)
   	{
   	for ($j=0;$j<$remainder;$j++){$result[] = $row[$j];}
   	}
   	else
   	{
   	foreach ($row as $data)	{$result[] = $data;}
   	$last_val = end($row); //echo 'last = '.$last_val;
    }
   }
  return $result;
}

function fill_row($count,$last_val=-1)
{
 $row = array();

 if ($last_val>1)
 {$row[0]=$last_val;}

 for($i=0;$i<$count;$i++)
 {
      do
      {
	  $rand = mt_rand(0,$count-1);
	  }
	  while (in_array($rand,$row));

  $row[$i] = $rand;
 }
 return $row;
}

function playlist_sort_playlist_array($final_playlist,$order_by,$order_type)
{
 $parameters=array('id','artist','title','year','genre','size','length','bpm','camelot_ton','rating','date','add_date');
 if ($order_by =='' or !in_array($order_by,$parameters)) {$order_by = 'track_number';}

 $result = array(); foreach ($final_playlist as $key=>$data)
 {    $new_key=$data[$order_by].' '.$key;
    $result[$new_key] = $data; }

 if($order_type=='up') {ksort($result);}
 else {krsort($result);}

 return $result;
}


function playlist_model_delete_playlist(){	global $main_request_array;
	$playlist_id = intval($main_request_array[2]);
	if ($playlist_id<=0)
		 {
		    $message = '<h1 class="tacentr error">Не удалось удалить плейлист!</h1>';
		    print_message($message,'Редактирование элемента расписания');
		    return;
	     }
	if ($playlist_id==1)
		 {
		    $message = '<h1 class="tacentr error">Этот плейлист удалить нельзя!</h1>';
		    print_message($message,'Редактирование элемента расписания');
		    return;
	     }
	  $result=delete_playlist($playlist_id);
	  cron_del_job_by_playlist_id($playlist_id);

	   if ($result)
	   {
	    $message = '<h1 class="tacentr ok">Плейлист удалён</h1>'.
	   	'<script>setTimeout(\'window.location.href="'.$base.'/playlist/manager"\',100);</script>';
	   	print_message($message,'Удаление плейлиста');
	   	return;
	   }
	   else
	   {
	    $message ='<h1 class="error">Не удалось удалить плейлист</h1>';
	   	print_message($message,'Удаление плейлиста');
	    return;
	   }
}

function playlist_model_edit_playlist(){	global $main_request_array;
	$load_playlist_id = intval($main_request_array[2]);
	if ($load_playlist_id<=0)
	   {
	   	echo 'Ошибка! Не указан номер плейлиста'; return;
	   }

  $playlist_data = get_playlist($load_playlist_id);

  print_r 'PDATA ='.$playlist_data.'<br/>';

  $playlist_data['id'] = $load_playlist_id;

 if ($_POST['rule']!='')
  {
   $playlist_data['ruleset']=$_POST['rule'];
  }
  else
  {
    $playlist_data['ruleset']=json_decode($playlist_data['rules'],true);
  }


   print_playlist_view('edit',$playlist_data);}

function generate_dynamic_playlist($playlist_data,$view_mode=null){	global $main_request_array;

	//echo "WM=".$view_mode;

	$result = array(); $s='';

	/*$playlist_id = $main_request_array[2];
	$playlist_id = intval($playlist_id);

	$order_by   = $main_request_array[3];

	$order_type = $main_request_array[4];
	$order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
	$new_order_type   = ($order_type=='up')?'down':'up';

	$playlist_id=$playlist_data['id'];
	$playlist_name=$playlist_data['name'];
	$playlist_static=($playlist_data['static']=='Y'?'Y':'N');/**/
	$rules= $playlist_data['ruleset'];


	$id = 1;
	$final_playlist = array();


    if($rules['special']['common']['count_priority']=="Y")
    {     	$global_count_priority = true;
    }
    if( $rules['special']['common']['max_tracks']>0 || $rules['special']['common']['max_total_time'] !='')
    {        $global_maxtracks_priority = true;    }

    //print_r ($playlist_data);

	foreach ($rules as $rule_id => $rule)
	   {
	        //echo $rule_id;
	        if($rule_id == 'special'){            	continue;	        }
	        $filter_empty = true;
	        foreach ($rule as $value) {if ($value!=''){$filter_empty = false; break;}  }
	        if ($id!=1 and $filter_empty) {continue;}

            if ($view_mode != 'nofilterform') {            	$s.= get_filter_form($id,$rule);
            }
            else {                $s.= get_print_filter_info($id,$rule);            }

            $max_tracks_count =  intval($rule['max_tracks_count']);


	        $new_tracks = get_tracks_by_filter($rule);
			$tracks_count = count($new_tracks);

			$pl_time_all = 0;
	        foreach ($new_tracks as $track_data)
	                   {
	                     $pl_time_all += $track_data['length'];
	                   }

            $count_priority = ($rule['count_priority']=="Y");


	        $s.= '<div class="fleft w100 pad5 filter_result_info">';

            if ($max_tracks_count>0 && !$global_count_priority && !$global_maxtracks_priority)  // в правилах задано ограничение на число треков
				{
				  $s.= 'правило '.$rule_id.': в правилах задано ограничение на число треков <br />';

			 	  $playlist = array();

						 if	($max_tracks_count<$tracks_count) // найдено треков больше чем нужно
						 {
						     $s .= 'правило '.$rule_id.': найдено треков больше чем нужно<br />';

						     if ($count_priority) // в правилах задано сортровать по приоритету счётчика числа проигрываний
							 {
							    $s .= 'правило '.$rule_id.': режим приоритета новых треков <b>ВКЛ</b><br />';

							    $tmp = array(); $i=0;
	                            foreach ($new_tracks as $track)   // разобъём на блоки по числу проигрываний
	                            {
	                              $tmp[$track['count']][$i] = $track;
	                              $i++;
	                            }
	                            ksort($tmp,SORT_NUMERIC);   // сортировка блоков

	                            $tmp2 = array();$i=0;         // забиваем блоками стек, пока не будет достигнут нужный размер или более
	                            foreach($tmp as $count_group)
	                            {
	                             shuffle($count_group);
	                             foreach ($count_group as $track)
	                             {
	                                $tmp2[$i] = $track;
	                                $i++;
	                             }
	                             if ($i>=$max_tracks_count) {break;}
	                            }

	                            $playlist = array_slice($tmp2,0,$max_tracks_count); // обрезать под нужное число треков

                                shuffle($playlist); // перемешать
						     }
					 	 	else
						 	 {
						 	    $s.= 'правило '.$rule_id.': режим приоритета новых треков <b>ВЫКЛ</b><br />';

							    shuffle($new_tracks); // перемешать

                                $i=0;
	                            foreach ($new_tracks as $rule)
	                            {
	                               $playlist[$i] = $rule;
	                               $i++;
	                               if ($i==$max_tracks_count){break;}
	                            }
						 	 }
         				 }
                         else
                         {
                                $s.= 'правило '.$rule_id.': найдено треков <b>меньше или сколько требуется</b><br />';
							    $s.= 'правило '.$rule_id.': режим приоритета новых треков <b>выключен</b><br />';

	                         	$map = generate_random_map($tracks_count,$max_tracks_count); // забить массив (если треков меньше чем нужно, то можно забить с дублями, и, если это возможно, без повторов)
						       	//print_r ($map);
						     	foreach ($map as $map_data)
						       	{
						        	$playlist[] = $new_tracks[$map_data];
						       	}

                         }
				}
				else
				{					if($global_count_priority)
					{
 					    $s.= 'правило '.$rule_id.': ограничение на число треков <b>выключено</b> — установлен режим приоритета новых в общем фильтре<br />';
						$s.= 'правило '.$rule_id.': режим приоритета новых треков <b>выключен</b> — установлен режим приоритета новых в общем фильтре<br />';					}
					elseif($global_maxtracks_priority)
					{ 					    $s.= 'правило '.$rule_id.': ограничение на число треков <b>выключено</b> — установлено время или число треков в общем фильтре<br />';

						$s.= 'правило '.$rule_id.': режим приоритета новых треков <b>выключен</b> — установлено время или число треков в общем фильтре<br />';
					}
					else
					{
						$s.= 'правило '.$rule_id.': режим приоритета новых треков <b>выключен автоматически</b> — не указан предел отбора числа треков<br />';
					}
				$playlist = $new_tracks;
				}

				$s.= '</div>';
                $s .= get_print_playlist_total_results($playlist);

		  	    if ($tracks_count>0)
		  	     {
		  	        $s .= get_print_playlist($playlist);
		            $final_playlist= array_merge($final_playlist,$playlist);
	             }

			$s.= '</div>';
	        $id++;

	   }

	if($global_count_priority){	 $tmp = array(); $i=0;
     foreach ($final_playlist as $data)   // разобъём на блоки по числу проигрываний
     {
       $tmp[$data['count']][$i] = $data;
       $i++;
     }
     ksort($tmp,SORT_NUMERIC);   // сортировка блоков
     $tmp2 = array();
     foreach ($tmp as $block)
     {
        shuffle($block);
     	foreach ($block as $data)
     	{     	 $tmp2[] = $data;     	}     }
     $final_playlist = $tmp2;
	}


	$max_total_time_str=$rules['special']['common']['max_total_time'];
	if($max_total_time_str!='')
	{		$max_total_time = hour_min_sec_to_sec($max_total_time_str);      	if (!$global_count_priority){shuffle ($final_playlist);}
      	$tmp = array(); $tmp2 = array();
      	$total_time = 0;
        foreach ($final_playlist as $data){            $track_length = $data['length'];
            if ($track_length + $total_time > $max_total_time)
            {            	$tmp2[] = $data;
            	continue;
            }
            $total_time += $track_length;
            $tmp[] = $data;
        }

       shuffle($tmp2);
       $tmp[] = array_pop($tmp2);;

       $final_playlist = $tmp;
	}

    $max_tracks=$rules['special']['common']['max_tracks'];
	if ($max_tracks>0)
	{
      	//$s.= var_export($final_playlist,true);
      	if (!$global_count_priority){shuffle ($final_playlist);}
        $final_playlist = array_slice($final_playlist,0,$max_tracks);
	}



	$result['data'] = $final_playlist;
	$result['info']['rules_processed'] = $id;
	$result['view'] = $s;
 	return $result;}

function playlist_has_doubles($playlist){
 	 $tmp = array();

     foreach ($playlist as $track_data)
     {
       $tmp[$track_data['id']] = $track_data;
     }

     $before_count = count ($playlist);
     $after_count = count ($tmp);
     if ($before_count!=$after_count)
     {      	return true;     }
     else{     	return false;     }}

?>