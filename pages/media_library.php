<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'шаблон';
$global_keywords = 'шаблон';

 include "inc/head.php";
 include "inc/header.php";
 include "inc/time_lib.php";
 //include("inc/menu.php");
 //include("inc/navi.php");
 include "inc/_utf_symbols.php";

/*******************************************************************************************************/
//auth form
require_once 'inc/auth_form.php';

//config
require_once "config/media_config.php";

$order_by   = $main_request_array[1];
$order_by   = ($order_by=='')?'id':$order_by;

$order_type = $main_request_array[2];
$order_type       = ($order_type=='' or $order_type=='up')?'up':'down';
$new_order_type   = ($order_type=='up')?'down':'up';

?>

<?php
echo '<script type="text/javascript" src="'.$base.'/js/table_edit.js"></script>';
echo '<script type="text/javascript" src="'.$base.'/js/media_library.js"></script>';
echo '<script type="text/javascript" src="'.$base.'/player/niftyplayer.js"></script>';
?>

 <!-- content -->
<div id="content">
<?php
echo '<div id="helper"></div>';

echo '<div id="cart">
         <div class="fleft" id="cart_tracks_count">0</div>
         <div class="fleft" onclick="window.location.href=\''.$base.'/playlist/add/\'">[Сохранить]</div>
         <div class="fleft" onclick="media_library_show_add_to_playlist_window();">[Добавить к плейлисту]</div>
         <div class="fleft" onclick="clear_cart();">[X]</div>
     </div>';
echo '<script type="text/javascript">media_library_load_new_playlist_files_id_list();</script>';


echo '<div id="flying_player" >';

echo '<div class="tcell">';
	 echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="165" height="38" id="niftyPlayer1" align="">
	<param name=movie value="'.$base.'/player/niftyplayer.swf">
	<param name=quality value=high>
	<param name=bgcolor value=#FFFFFF>
	<embed src="'.$base.'/player/niftyplayer.swf" quality=high bgcolor=#FFFFFF width="165" height="38" name="niftyPlayer1" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
	</embed>
	</object>';
echo "</div>";

echo '<div class="tcell pad10 maroon pointer tacentr valignmiddle"
                   onclick="
                      table_edit_apply_clear_all_rows_playing(\'media_row\');
                      hide_player();
                      niftyplayer(\'niftyPlayer1\').stop();
                   ">';
echo '[X]</div>';
echo '</div>';

echo '<div id="flying_progress_bar" class="pad20">';

	echo "<div id='progress_bar' class='progress_bar'>";
    	echo "<div id='progress_bar_message' class='progress_bar_message'> 0/".count($files)."</div>";
    	echo "<div id='progress_bar_done' class='progress_bar_done'></div>";
    echo "</div>";

    echo '<div class="tcell pad10 maroon pointer tacentr valignmiddle"
                   onclick="
                      hide_progress_bar();
                   ">';
	echo '[X]</div>';

echo '</div>';

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';

echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'﻿</b> </td>
           <td>Медиатека
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';

   echo '<form method="POST" action="" id="form1">';

        $id =1;
        $data= $_POST['rule'][$id];

		$artist = $data['artist'];
		$title = $data['title'];
		$genre = $data['genre'];
		$bpm_low = $data['bpm_low'];
		$bpm_high = $data['bpm_high'];
		$camelot_ton = $data['camelot_ton'];

		$rating_low = $data['rating_low'];
		$rating_high = $data['rating_high'];
		$date_time_first = $data['date_time_first'];
		$date_time_last = $data['date_time_last'];
		$year = $data['year'];
		$context = $data['context'];
		$max_tracks_count = $data['max_tracks_count'];
		$comment = $data['comment'];


        $filter_empty = true;
        foreach ($data as $value) {
        	if ($value!=''){$filter_empty = false; break;}
        }



  	    $s = '';
		$s.= '<div  class="fleft w100">';
        $s.= '<h2>Фильтр</h2>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
			      Исполнитель
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
			        <input  class="w90p"                         		name="rule['.$id.'][artist]"           id="artist['.$id.']"            type="text" value="'.$artist.'">
			      </div>
			  </div> ';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
			      Название
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
			     	<input class="w90p"                         	  		name="rule['.$id.'][title]"            id="title['.$id.']"            type="text" value="'.$title.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Год выхода трека (ГГГГ)
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" 			  		name="rule['.$id.'][year]"   			 id="date_time_last['.$id.']"   type="text" value="'.$year.'">
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Жанр
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p"                                 		name="rule['.$id.'][genre]"            id="genre['.$id.']"            type="text" value="'.$genre.'"
					onkeydown="helper(this.id)" onfocus="this.value=\'\'; helper(this.id);"
					autocomplete="off"
					>
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					bpm нижняя граница
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p"                                  		name="rule['.$id.'][bpm_low]"            id="bpm['.$id.']"            type="text" value="'.$bpm_low.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					bpm верхняя граница
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p"                                  		name="rule['.$id.'][bpm_high]"            id="bpm['.$id.']"            type="text" value="'.$bpm_high.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Тональность
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input  class="w90p"                         		name="rule['.$id.'][camelot_ton]"            id="camelot_ton['.$id.']"            type="text" value="'.$camelot_ton.'">
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Рейтинг нижняя граница
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input  class="w90p"              		name="rule['.$id.'][rating_low]"       id="rating_low['.$id.']"       type="text" value="'.$rating_low.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Рейтинг верхняя граница
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input   class="w90p"            		name="rule['.$id.'][rating_high]"      id="rating_high['.$id.']"      type="text" value="'.$rating_high.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Время добавления (начало интервала, ГГГГ-ММ-ДД)
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="rule['.$id.'][date_time_first]"  id="date_time_first['.$id.']"  type="text" value="'.$date_time_first.'">
			      </div>
 			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Время добавления (конец интервала, ГГГГ-ММ-ДД)
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="rule['.$id.'][date_time_last]"   id="date_time_last['.$id.']"   type="text" value="'.$date_time_last.'">
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Коментарий
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="rule['.$id.'][comment]"   id="date_time_last['.$id.']"   type="text" value="'.$comment.'">
			      </div>
			 </div>';
        $s.= '</div>';

        echo $s;

   echo '<div class="fleft w100 pad10"></div>';

   echo '<div class="fleft w100">';
   echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick="submit_form(\'form1\');">';
		 echo '<table class="">
		            <tr class="pointer pad10">
		              <td class="job_time pad10"><b>[⇒]</b></td>
		              <td class="job_name pad10" >Отобрать</td>
		            </tr>
		            </table>
		            ';
	echo '</div>';
    echo '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick="window.location.href=window.location.href;">';
		 echo '<table class="">
		            <tr class="pointer pad10">
		              <td class="job_time pad10"><b>[C]</b></td>
		              <td class="job_name pad10" >Сбросить фильтр</td>
		            </tr>
		            </table>
		            ';
	echo '</div>';
	echo '</div>';

    echo '<div class="fleft w100 pad10"></div>';
    echo '<input type="hidden" name="show_all" value="Y">';

    if ($_POST['show_all']!='Y'){

    		echo '<div class="fleft w100">';
   			echo '<h1 class="w100 tacentr">Для отображения всей медиатеки нажмите Отобрать при пустом фильтре</h1>';
    		echo '</div>';

    }else{

			echo '<div class="fleft w100">';

				  echo '<table  class="media_table">';
				    echo '<tr>
				           <th>';
		            echo '<input type="checkbox" id="cb_media_row_select_all" onclick="table_edit_select_all_rows(event,this,\'media_row\');">';
		            echo '</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/id/'.$new_order_type.'\' ; submit_form(\'form1\');">id</th>
				           <th></th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/count/'.$new_order_type.'\'; submit_form(\'form1\');">Cч</th>';

				   			if ($config['media']['show_file_path']==true)
				   			{
				           	echo '<th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/filename/'.$new_order_type.'\'; submit_form(\'form1\');">Путь к файлу</th>';
				    		}

				    echo  '<th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/artist/'.$new_order_type.'\'; submit_form(\'form1\');">Исполнитель</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/title/'.$new_order_type.'\'; submit_form(\'form1\');">Название трека</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/year/'.$new_order_type.'\'; submit_form(\'form1\');">Год выпуска</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/genre/'.$new_order_type.'\'; submit_form(\'form1\');">Жанр</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/size/'.$new_order_type.'\'; submit_form(\'form1\');">Размер</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/length/'.$new_order_type.'\'; submit_form(\'form1\');">Длительность</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/bpm/'.$new_order_type.'\'; submit_form(\'form1\');">bpm</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/camelot_ton/'.$new_order_type.'\'; submit_form(\'form1\');">Тон</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/rating/'.$new_order_type.'\'; submit_form(\'form1\');">Рейтинг</th>
				           <th >Комментарий</th>
				           <th >Дата модификации</th>
				           <th onclick=" document.getElementById(\'form1\').action=\''.$base.'/media_library/add_date/'.$new_order_type.'\'; submit_form(\'form1\');">Дата добавления</th>
				           <th colspan=3>ОП</th>
				         </tr>';      /**/

					$all_tracks = get_tracks_by_filter($data,$order_by,$order_type);

		            echo '<div class="fleft w100 pad5 filter_result_info">';
		             $pl_time = 0;
		             foreach ($all_tracks as $data)
		               {
		                 $pl_time += $data['length'];
		               }
				     echo 'Отобрано: '.count($all_tracks).' треков ';
				     echo '<span class="maroon">'.sec_to_hour_min_sec($pl_time).' ('.$pl_time.') сек </span> ';

		     		echo '</div>';

		            $i=0;
					foreach  ($all_tracks as $data)
					{
				        echo '<tr id="media_row_'.$i.'" name="media_row">';

					    echo '<td>';
		   			    echo '<input type="hidden" value="'.$data['id'].'" id="file_id_media_row_'.$i.'">';

		                echo '<input type="checkbox" id="cb_media_row_'.$i.'" name="cb_media_row" onclick="table_edit_select_row(event,this,\'media_row\');" >';
				   	    echo '</td><td>';
					    echo $data['id'];
				   	    echo '</td><td>';

		                echo '<div class="green_button pointer" onclick="
		                      show_player();
		                      table_edit_apply_select_row_playing(\'media_row_'.$i.'\', this,\'media_row\');
		                      niftyplayer(\'niftyPlayer1\').loadAndPlay(\''.$base.'/media_proxy/'.$data['id'].'\')
		                      ">

		                   &#9658;

		                   </div>';

				   	    echo '</td><td>';
				   	    echo $data['count'];
				   	    echo '</td>';

					   	if ($config['media']['show_file_path']==true)
					   	{
					   	    echo '<td >';
			                //echo $config['media']['media_root_folder'];
						    $relative_file_path = str_replace($config['media']['media_root_folder'],'',$data['filename']); // cut root media folder

						    $path_components = explode('/',$relative_file_path);
						    $path_components = array_filter($path_components,'strlen'); // get rid off empty components (i.e. leading '/')
			                array_pop($path_components);   // remove filename
			                array_shift($path_components); // remove first component of path as it is suppossed to be a folder inside root_media_folder
			                $current_folder = implode('/',$path_components);

						    echo $current_folder;

					   	    echo '</td>';
		                }
				   	    echo '<td>';
					    echo $data['artist'];
				   	    echo '</td><td>';
					    echo $data['title'];
				   	    echo '</td><td>';
				   	    echo $data['year'];
				   	    echo '</td><td>';
				   	    echo $data['genre'];
				   	    echo '</td><td>';
				        echo get_cute_file_size($data['size']);
					    echo '</td><td>';
					    echo sec_to_hour_min_sec($data['length']);
					    echo '</td><td>';
				/*	    echo $data['bitrate'];
					    echo '</td><td>'; /**/
				        echo $data['bpm'];
					    echo '</td><td>';
					    echo $data['camelot_ton'];
					    echo '</td><td>';
					    echo $data['rating'];
					    echo '</td><td>';
					    echo $data['comment'];
					    echo '</td><td>';

		                echo $data['date'];
				       // echo date ("d-m-Y", filemtime($data['filename']));

					    echo '</td><td>';
					    echo date ("d-m-Y", datetime_to_timestamp($data['add_date']));
					    echo '</td><td>';
					    echo '<div onclick="media_library_add_to_cart('.$data['id'].',\'media_row\');" class="maroon pointer" name="media_row_add_to_cart_button">[p]</div>';
					    echo '</td><td>';
					    echo '<div onclick="media_library_edit('.$data['id'].',\'media_row\');" class="maroon pointer" name="media_row_edit_button">[e]</div>';
					    echo '</td><td>';
					    echo '<div onclick="media_library_update_from_file('.$data['id'].',\'media_row\');" class="maroon pointer" name="media_row_update_from_file_button">[r]</div>';
					    echo '</td>';


				    echo '</tr>';   /**/
				    $i++;
					}
				  echo '</table>';
			echo '</div>';
    }
echo '</div>';

echo '</form>';
?>
</div>
    <!-- content end -->
