<?
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'Управление плейлистами';
$global_keywords = 'Управление плейлистами';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/playlist.php");
 include("inc/_utf_symbols.php");

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');

?>

<?
echo '<script type="text/javascript" src="'.$base.'/js/playlist.js"></script>';
echo '<script type="text/javascript" src="'.$base.'/js/tablednd.js"></script>';
 ?>
 <!-- content -->
<div id="content">
<?
//print_r ($_POST);

if ($main_request_array[1]=='delete')
 {
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

if ($main_request_array[1]=='manager')
 {
  print_playlist_manager('new');
 }

if ($main_request_array[1]=='new')
 {
  $playlist_data['ruleset']=$_POST['rule'];
  print_playlist_view('new',$playlist_data);
 }
if ($main_request_array[1]=='edit')
{
  $load_playlist_id = $main_request_array[2];
	   $load_playlist_id = intval($load_playlist_id);
	   if ($load_playlist_id<=0)
	   {
	   	echo 'ERROR!'; return;
	   }

  $playlist_data = get_playlist($load_playlist_id);
  $playlist_id=$playlist_data['id'];
  $playlist_name=$playlist_data['name'];

 if ($_POST['rule']=='')
  {
	   $playlist_rules=$playlist_data['rules'];

       if ($playlist_rules!='')
       {
		 $xml = simplexml_load_string($playlist_rules);
			 if (!$xml)
			 {
			 	echo 'error - cannot load xml';
			 	return ;
			 }
		 $final_playlist = array();
		 $ruleset = array();
		 $track_stats = array(); $i=0;
		 foreach($xml->rule as $rule)
		 {
		        $attr = $rule->attributes();
		       /* echo '<br /><br />--';
		        print_r ($attr); echo '--<br />';  /**/

		        $data = array();
		        foreach ($attr as $a => $b)
		        {
		           $data[$a] = $b;
		        }
		        $id = $data['id'];

		       /* $max_tracks_count = $data['max_tracks_count'];

		        $search_result = get_tracks_by_filter($data);
				$tracks_count = count($search_result);
				$max_tracks_count = intval($max_tracks_count);

		        $track_stats[$i] = $data;
		        $track_stats[$i]['tracks_count'] = $tracks_count;
		       // $track_stats[$i]['max_tracks_count'] = $max_tracks_count;

		        $i++; /**/
		        $ruleset[]=$data;
		 }
       $playlist_data['ruleset']=$ruleset;
	   }
       else
       {
        $playlist_data['id']= $load_playlist_id;
    	$playlist_data['name']=$playlist_name;
       }

  }
  else
  {
    $playlist_data['id']= $load_playlist_id;
    $playlist_data['name']=$playlist_name;
    $playlist_data['ruleset']=$_POST['rule'];
  }


   print_playlist_view('edit',$playlist_data);
}

if ($main_request_array[1]=='add')
{
 print_playlist_view('add');
}

?>

<?
function print_playlist_manager()
{
 global $base;
 global $utf_symbol;

echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>Выберите плейлист для редактирования
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';

  echo get_playlist_select("id='playlist_id' class='w100'");

 echo '</div>';
 echo '<div class="pad20">';

	 echo '<div class="fleft w100">';
				echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
				onclick="select_playlist_edit()">';
				 echo '<table class="">
				            <tr class="pointer pad10">
				              <td class="job_time pad10"><b>[E]</b></td>
				              <td class="job_name pad10" >Редактировать</td>
				            </tr>
				            </table>
				            ';
				echo '</div>' ;
				echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
				 onclick = "select_playlist_delete()">';
				 echo '<table class="">
				            <tr class="pointer pad10">
				              <td class="job_time pad10"><b>[X]</b></td>
				              <td class="job_name pad10" >Удалить плейлист</td>
				            </tr>
				            </table>
				            ';
				echo '</div>' ;
			echo '</div>' ;
	  echo '</div>';

echo '</div>';

}

function print_playlist_view($mode='new',$playlist_data='')
{
	global $base; global $main_request_array;

	$playlist_id=$playlist_data['id'];
	$playlist_name=$playlist_data['name'];
	$playlist_static=($playlist_data['static']=='Y'?'Y':'N');
	$rules= $playlist_data['ruleset'];

	if ($mode=='add')
	{
	    print_playlist_view_add($playlist_data);
		return;
	}

	if ($mode=='edit')
	{
	 print_playlist_view_edit($playlist_data);
	 return;
	}

	if ($mode=='new')
	{
	 print_playlist_view_new($playlist_data);
	 return;
	}
}

function print_playlist_view_new($playlist_data)
{
 global $base; global $main_request_array;
 global $utf_symbol;

 $playlist_id=$main_request_array[2];
 $playlist_id = intval($playlist_id);
 $playlist_static='Y';

 $order_by   = $main_request_array[3];

 $order_type = $main_request_array[4];
 $order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
 $new_order_type   = ($order_type=='up')?'down':'up';

 $playlist_id=$playlist_data['id'];
 $playlist_name=$playlist_data['name'];
 $playlist_static=($playlist_data['static']=='Y'?'Y':'N');
 $rules= $playlist_data['ruleset'];

 echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>Создание динамического плейлиста
           </td>
        </tr>
      </table>
   </div>';

 echo '<div class="pad20" >';
    echo '<H1>Правила для плейлиста</H1>';

	echo '<form method="POST" action="" id="form1">';

	echo '<div id="filter_form"  class="fleft w100" >';


	$id = 1;
	$final_playlist = array();
	$final_playlist_repeated_track = false;
	 foreach ($rules as $rule_id => $trackdata)
	   {
	        $filter_empty = true;
	        foreach ($trackdata as $value) {if ($value!=''){$filter_empty = false; break;}  }
	        if ($id!=1 and $filter_empty) {continue;}

            echo get_filter_form($id,$trackdata);

	        $search_result = get_tracks_by_filter($trackdata);
            $max_tracks_count =  $trackdata['max_tracks_count'];


			$tracks_count = count($search_result);
			$max_tracks_count = intval($max_tracks_count);

			$pl_time_all = 0;
	                 foreach ($search_result as $pl_element)
	                   {
	                     $pl_time_all += $pl_element['length'];
	                   }

            $count_priority = (isset($trackdata['count_priority']) and $trackdata['count_priority']=="Y");

	        echo '<div class="fleft w100 pad5 filter_result_info">';

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


			 	 /*$playlist = array();
 			     $map = generate_random_map($tracks_count,$max_tracks_count);

			     foreach ($map as $map_data)
			       {
			        $playlist[] = $search_result[$map_data];
			       }/**/
				}
				else
				{
				echo 'правило '.$rule_id.': режим приоритета новых треков <b>выключен автоматически</b> — не указан предел отбора числа треков<br />';
				$playlist = $search_result;
				}
			/*
			if ($tracks_count>0)
				{
					if ($max_tracks_count>0)
					{
					 	 $playlist = array();

						 if	($count_priority and $max_tracks_count<$tracks_count)
							 {
							    echo "Режим приоритета числа воспроизведений <br />";
							    $tmp = array(); $i=0;
	                            foreach ($search_result as $trackdata)
	                            {
	                              $tmp[$trackdata['count']][$i] = $trackdata;
	                              $i++;
	                            }
	                            ksort($tmp,SORT_NUMERIC);

	                            $tmp2 = array();$i=0;
	                            foreach($tmp as $count_group)
	                            {
	                             foreach ($count_group as $trackdata)
	                             {
	                                $tmp2[$i] = $trackdata;
	                                $i++;
	                             }
	                             if ($i>=$max_tracks_count) {break;}
	                            }

	                            shuffle($tmp2);

	                            for ($i=0; $i<$max_tracks_count; $i++)
	                            {
	                               $playlist[$i] = $tmp2[$i];
	                            }
						     }
					 	 	else
						 	 {
	                         	$map = generate_random_map($tracks_count,$max_tracks_count);
						       	//print_r ($map);
						     	foreach ($map as $map_data)
						       	{
						        	$playlist[] = $search_result[$map_data];
						       	}
						 	 }

	                 $pl_time = 0;
	                 foreach ($playlist as $pl_element)
	                   {
	                     $pl_time += $pl_element['length'];
	                   }

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
				echo 'Нет результатов';
				}
				echo '</div>';
                                  /*
				if ($tracks_count>0)
				{
					if ($max_tracks_count>0)
					{
				 	 $playlist = array();
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
				echo 'Нет результатов';
				}         /**/
				echo '</div>';

		  	    if ($tracks_count>0)
		  	     {  print_playlist($playlist);
		            $final_playlist= array_merge($final_playlist,$playlist);
	             }

			echo '</div>';
	        $id++;

	    }
	echo '</div>';


 echo '<div class="fleft w100 pad10"></div>';

    echo '<div  class="fleft w100">';

	    echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick = "get_filter_form();">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[+]</b></td>
			              <td class="job_name pad10" >Добавить правило</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick="submit_form(\'form1\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇒]</b></td>
			              <td class="job_name pad10" >Отобрать</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

	   echo '<div class="fleft w100 pad10"></div>';

 echo '</div>';
 echo '</form>';

 echo '<script type="text/javascript">   var filter_rule_form_id = '.$id.';  </script>';


 shuffle($final_playlist);


 echo '<div class="fleft w100 pad10"></div>';
 echo '<div  class="fleft w100">';

	if (!empty($final_playlist))
	{
    echo '<div class="fleft w100 pad10"></div>';
	    echo '<h1>Результаты отбора</h1>';

    echo '<div class="fleft w100 pad10"></div>';

     echo '<div class="fleft w100 pad5 filter_result_info">';
             $pl_time = 0;
             foreach ($final_playlist as $pl_element)
               {
                 $pl_time += $pl_element['length'];
               }
		     echo 'Отобрано: '.count($final_playlist).' треков ';
		     echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';
		     if($final_playlist_repeated_track)
		     {
		      echo '<span class="message_warn">С повторами</span><br />';
		     }
		     else
		     {
		      echo '<span class="message_ok">Без повторов</span><br />';
		     }
     echo '</div>';

     echo '<div class="fleft w100 pad10"></div>';

		print_playlist($final_playlist);


      /*  $i=0;
		foreach ($final_playlist as $pl_element)
		{
          echo '<input type="hidden" name="final_playlist['.$i.']" value="'.$pl_element['id'].'">';
          $i++;
		}               /**/
          echo '<input type="hidden" id="playlist_static_flag" value="'.$playlist_static.'">';


    echo '<div class="fleft w100 pad10"></div>';



      echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
			onclick="show_modal_window_playlist_save(\''.$playlist_id.'\',\''.$playlist_name.'\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇩]</b></td>
			              <td class="job_name pad10" >Сохранить</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
            onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'\'"
			>';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time red pad10"><b>[R]</b></td>
			              <td class="job_name pad10" >Сброс</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

    echo '<div class="fleft w100 pad10"></div>';
	}
 echo '</div>';

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';


echo '</div>';

}


function print_playlist_view_edit($playlist_data)
{
 $playlist_static=($playlist_data['static']=='Y'?'Y':'N');

 if ($playlist_static=="Y")
 {
    print_playlist_view_edit_static($playlist_data);
 }
 else
 {
    print_playlist_view_edit_dynamic($playlist_data);
 }
}

function print_playlist_view_edit_static($playlist_data)
{
 global $base; global $main_request_array;
 global $utf_symbol;

 $playlist_id=$playlist_data['id'];
 $playlist_name=$playlist_data['name'];
 $playlist_static=($playlist_data['static']=='Y'?'Y':'N');
 $rules= $playlist_data['ruleset'];

 $order_by   = $main_request_array[3];
 $order_type = $main_request_array[4];
 $order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
 $new_order_type   = ($order_type=='up')?'down':'up';

 echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>Редактирование статического плейлиста «'.$playlist_name.'»
           </td>
        </tr>
      </table>
   </div>';

  echo '<div class="pad20" >';


 $final_playlist=get_playlist_tracks($playlist_id,$order_by,$order_type);
// print_r($final_playlist);

   echo '<div class="fleft w100 pad10"></div>';
 echo '<div  class="fleft w100">';

	if (!empty($final_playlist))
	{
    echo '<div class="fleft w100 pad10"></div>';

     echo '<div class="fleft w100 pad5 filter_result_info">';
             $pl_time = 0;
             foreach ($final_playlist as $pl_element)
               {
                 $pl_time += $pl_element['length'];
               }
		     echo 'Отобрано: '.count($final_playlist).' треков ';
		     echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';
		     if($final_playlist_repeated_track)
		     {
		      echo '<span class="message_warn">С повторами</span><br />';
		     }
		     else
		     {
		      echo '<span class="message_ok">Без повторов</span><br />';
		     }
     echo '</div>';

     	  echo '<div class="fleft w100 pad10"></div>';

		  print_playlist_edit($final_playlist,$playlist_id,$new_order_type,true);

          echo '<input type="hidden" id="playlist_static_flag" value="'.$playlist_static.'">';


    echo '<div class="fleft w100 pad10"></div>';



      echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
			onclick="show_modal_window_playlist_save(\''.$playlist_id.'\',\''.$playlist_name.'\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇩]</b></td>
			              <td class="job_name pad10" >Сохранить</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
            onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'\'"
			>';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time red pad10"><b>[R]</b></td>
			              <td class="job_name pad10" >Сброс</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

	}
  echo '</div>';

  echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';
  echo '</div>';

 echo "</div>";
}

function print_playlist_view_edit_dynamic($playlist_data)
{
 global $base; global $main_request_array;
 global $utf_symbol;

 $playlist_id=$main_request_array[2];
 $playlist_id = intval($playlist_id);
 $playlist_static='Y';

 $order_by   = $main_request_array[3];

 $order_type = $main_request_array[4];
 $order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
 $new_order_type   = ($order_type=='up')?'down':'up';

 $playlist_id=$playlist_data['id'];
 $playlist_name=$playlist_data['name'];
 $playlist_static=($playlist_data['static']=='Y'?'Y':'N');
 $rules= $playlist_data['ruleset'];



echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>Редактирование динамического плейлиста «'.$playlist_name.'»
           </td>
        </tr>
      </table>
   </div>';

 echo '<div class="pad20" >';
    echo '<H1>Правила для плейлиста</H1>';

	echo '<form method="POST" action="" id="form1">';

	echo '<div id="filter_form"  class="fleft w100" >';


	$id = 1;
	$final_playlist = array();
	$final_playlist_repeated_track = false;
	 foreach ($rules as $rule_id => $trackdata)
	   {
	        $filter_empty = true;
	        foreach ($trackdata as $value) {if ($value!=''){$filter_empty = false; break;}  }
	        if ($id!=1 and $filter_empty) {continue;}

            echo get_filter_form($id,$trackdata);

	        $search_result = get_tracks_by_filter($trackdata);
			$tracks_count = count($search_result);

			$max_tracks_count =  $trackdata['max_tracks_count'];
			$max_tracks_count = intval($max_tracks_count);

			$pl_time_all = 0;
	                 foreach ($search_result as $pl_element)
	                   {
	                     $pl_time_all += $pl_element['length'];
	                   }

             $count_priority = ($trackdata['count_priority']=="Y");

			//print_r ($trackdata); echo "cp=".$count_priority;

	        echo '<div class="fleft w100 pad5 filter_result_info">';
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


			 	 /*$playlist = array();
 			     $map = generate_random_map($tracks_count,$max_tracks_count);

			     foreach ($map as $map_data)
			       {
			        $playlist[] = $search_result[$map_data];
			       }/**/
				}
				else
				{
				echo 'правило '.$rule_id.': режим приоритета новых треков <b>выключен автоматически</b> — не указан предел отбора числа треков<br />';
				$playlist = $search_result;
				}
			/*	if ($tracks_count>0)
				{
					if ($max_tracks_count>0)
					{
					 	 $playlist = array();

						 if	($count_priority and $max_tracks_count<$tracks_count)
							 {
							    $tmp = array(); $i=0;
	                            foreach ($search_result as $trackdata)
	                            {
	                              $tmp[$trackdata['count']][$i] = $trackdata;
	                              $i++;
	                            }
	                            ksort($tmp,SORT_NUMERIC);

	                            $tmp2 = array();$i=0;
	                            foreach($tmp as $count_group)
	                            {
	                             foreach ($count_group as $trackdata)
	                             {
	                                $tmp2[$i] = $trackdata;
	                                $i++;
	                             }
	                             if ($i>=$max_tracks_count) {break;}
	                            }

	                            shuffle($tmp2);

	                            for ($i=0; $i<$max_tracks_count; $i++)
	                            {
	                               $playlist[$i] = $tmp2[$i];
	                            }

						     }
					 	 	else
						 	 {
	                         	$map = generate_random_map($tracks_count,$max_tracks_count);
						       	//print_r ($map);
						     	foreach ($map as $map_data)
						       	{
						        	$playlist[] = $search_result[$map_data];
						       	}
						 	 }

	                 $pl_time = 0;
	                 foreach ($playlist as $pl_element)
	                   {
	                     $pl_time += $pl_element['length'];
	                   }

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
				echo 'Нет результатов';
				}   /**/
				echo '</div>';

		  	    if ($tracks_count>0)
		  	     {  print_playlist($playlist);
		            $final_playlist= array_merge($final_playlist,$playlist);
	             }

			echo '</div>';
	        $id++;

	    }
	echo '</div>';


 echo '<div class="fleft w100 pad10"></div>';

    echo '<div  class="fleft w100">';

	    echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick = "get_filter_form();">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[+]</b></td>
			              <td class="job_name pad10" >Добавить правило</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick="submit_form(\'form1\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇒]</b></td>
			              <td class="job_name pad10" >Отобрать</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

	   echo '<div class="fleft w100 pad10"></div>';

echo '</div>';
echo '</form>';

echo '<script type="text/javascript">   var filter_rule_form_id = '.$id.';  </script>';


shuffle($final_playlist);


   echo '<div class="fleft w100 pad10"></div>';
 echo '<div  class="fleft w100">';

	if (!empty($final_playlist))
	{
    echo '<div class="fleft w100 pad10"></div>';
	    echo '<h1>Результаты отбора</h1>';

    echo '<div class="fleft w100 pad10"></div>';

     echo '<div class="fleft w100 pad5 filter_result_info">';
             $pl_time = 0;
             foreach ($final_playlist as $pl_element)
               {
                 $pl_time += $pl_element['length'];
               }
		     echo 'Отобрано: '.count($final_playlist).' треков ';
		     echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';
		     if($final_playlist_repeated_track)
		     {
		      echo '<span class="message_warn">С повторами</span><br />';
		     }
		     else
		     {
		      echo '<span class="message_ok">Без повторов</span><br />';
		     }
     echo '</div>';

     echo '<div class="fleft w100 pad10"></div>';

		print_playlist($final_playlist,true);


      /*  $i=0;
		foreach ($final_playlist as $pl_element)
		{
          echo '<input type="hidden" name="final_playlist['.$i.']" value="'.$pl_element['id'].'">';
          $i++;
		}               /**/
          echo '<input type="hidden" id="playlist_static_flag" value="'.$playlist_static.'">';


    echo '<div class="fleft w100 pad10"></div>';



      echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
			onclick="show_modal_window_playlist_save(\''.$playlist_id.'\',\''.$playlist_name.'\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇩]</b></td>
			              <td class="job_name pad10" >Сохранить</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
            onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'\'"
			>';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time red pad10"><b>[R]</b></td>
			              <td class="job_name pad10" >Сброс</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

    echo '<div class="fleft w100 pad10"></div>';
	}
 echo '</div>';

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';


echo '</div>';

}


function print_playlist_view_add()
{
 global $base; global $main_request_array;
 global $utf_symbol;

 $playlist_id=$main_request_array[2];
 $playlist_id = intval($playlist_id);
 $playlist_static='Y';

 $order_by   = $main_request_array[3];

 $order_type = $main_request_array[4];
 $order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
 $new_order_type   = ($order_type=='up')?'down':'up';

    if ($playlist_id<=0)
    {
     $playlist_id = 0;
    }
    else
    {
     $old_playlist_data=get_playlist($playlist_id);
     $playlist_name = $old_playlist_data['name'];

     $old_playlist = get_playlist_tracks($playlist_id,$order_by,$order_type);
    }


echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>';
 if ($playlist_id<=0) { echo '<h2>Предварительный просмотр</h2>';}
 else {echo '<h2>Добавление треков в существующий плейлист</h2>';}

 echo     '</td>
        </tr>
      </table>
   </div>';

	$file_id_list=$_COOKIE["file_list"];

	print_r($_COOKIE);
    $file_id_array=explode(",",$file_id_list);

    $new_playlist = array();
    $i=0;
	foreach ($file_id_array as $file_id)
	{
 	 $new_playlist[$i]=media_library_get_file_data_by_id($file_id);
 	 $new_playlist[$i]['track_number']=$i;
 	 $i++;
	}



    $final_playlist = array();

    foreach($old_playlist as $data)  { $final_playlist[$data['id']]=$data; }
    foreach($new_playlist as $data)  { $final_playlist[$data['id']]=$data; }

    echo 'by='.$order_by.' type '.$order_type;


    $parameters=array('id','artist','title','year','genre','size','length','bpm','camelot_ton','rating','date','add_date');
    if ($order_by =='' or !in_array($order_by,$parameters))
    {}
    else
    {
  //  $final_playlist = playlist_sort_playlist_array($final_playlist,$order_by,$order_type);
    }

    echo '<div class="fleft w100 pad20" >';
    if ($playlist_id<=0) { echo '<h2>Содержимое корзины</h2>';}
    else {echo '<h2>Добавление треков в статический плейлист '.$old_playlist_data['name'].'</h2>';}
    echo '</div>';

    echo '<div class="fleft w100 pad20 bbsz asc" >';

    print_playlist_new($final_playlist,$playlist_id,$new_order_type,true);

    echo '</div>';

    echo '<input type="hidden" id="playlist_static_flag" value="'.$playlist_static.'">';
    echo '<input type="hidden" id="playlist_force_static" value="Y">';


    echo '<div class="fleft w100 pad20 bbsz asc" >';

        echo '<div class="fleft w100">';
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
			onclick="show_modal_window_playlist_save(\''.$playlist_id.'\',\''.$playlist_name.'\');">';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇩]</b></td>
			              <td class="job_name pad10" >Сохранить</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
			echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
            onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'\'"
			>';
			 echo '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time red pad10"><b>[R]</b></td>
			              <td class="job_name pad10" >Сброс</td>
			            </tr>
			            </table>
			            ';
			echo '</div>' ;
		echo '</div>' ;

    echo '</div>';

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';

}
?>

