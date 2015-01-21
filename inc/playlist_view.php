<?php

function  print_playlist($playlist,$include_file_id_inputs=false){	echo get_print_playlist($playlist,$include_file_id_inputs);}

function  get_print_playlist($playlist,$include_file_id_inputs=false,$showhidebutton=false,$rule_id=0)
{
	$s='';

	if ($showhidebutton){		$s.= '<div class="">
                  <div class="fleft w100"><div class="fleft w100 job_plate_50 asc pointer movedownonsmall movedownonmedium" onclick="playlist_toggle_filter_result('.$rule_id.');"><table class="">
			            <table class="">
			            <tbody><tr class="pointer pad10">
			              <td class="job_time pad10">[<b><span id="ptfr_char_'.$rule_id.'">&#8681;</span></b>]</td>
			              <td class="job_name pad10" id="ptfr_text_'.$rule_id.'">Показать результат работы фильтра</td>
			            </tr>
			            </tbody></table>
			            </div></div>
			 </div>';	}

	$s .=  '<div id="playlist_tracklist_block_'.$rule_id.'" class="fleft w100" ';
	if ($showhidebutton){		$s.= ' style="display:none;" ';	}
	$s .='>';

		  $s .=  '<table class="media_table">';
		    $s .=  '<tr>
		           <th >id</th>
		           <th >Исполнитель</th>
		           <th >Название трека</th>
		           <th >Год выпуска</th>
		           <th >Жанр</th>
		           <th >Размер</th>
		           <th >Длительность</th>
		           <th >bpm</th>
		           <th >Тон</th>
		           <th >Рейтинг</th>
		           <th >Коментарий</th>
		           <th >Дата модификации</th>
		           <th >Дата добавления</th>
		           <th >счётчик</th>
		         </tr>';      /**/

			foreach  ($playlist as $data)
			{
		        $s .=  '<tr>';

			    $s .=  '<td>';
			    $s .=  $data['id'];
               if ($include_file_id_inputs) {$s .=  '<input type="hidden" name="final_playlist_dnd" value="'.$data['id'].'">';}
		   	    $s .=  '</td><td>';
			    $s .=  $data['artist'];
		   	    $s .=  '</td><td>';
			    $s .=  $data['title'];
		   	    $s .=  '</td><td>';
		   	    $s .=  $data['year'];
		   	    $s .=  '</td><td>';
		   	    $s .=  $data['genre'];
		   	    $s .=  '</td><td>';
		        $s .=  get_cute_file_size($data['size']);
			    $s .=  '</td><td>';
			    $s .=  sec_to_hour_min_sec($data['length']);
			    $s .=  '</td><td>';
		/*	    $s .=  $data['bitrate'];
			    $s .=  '</td><td>'; /**/
		        $s .=  $data['bpm'];
			    $s .=  '</td><td>';
			    $s .=  $data['camelot_ton'];
			    $s .=  '</td><td>';
			    $s .=  $data['rating'];
			    $s .=  '</td><td>';
			    $s .=  $data['comment'];
			    $s .=  '</td><td>';

		        $s .=  date ("d-m-Y H:i:s", datetime_to_timestamp($data['date']));
			    $s .=  '</td><td>';
			    $s .=  date ("d-m-Y", datetime_to_timestamp($data['add_date']));
			    $s .=  '</td><td>';
		   	    $s .=  $data['count'];
			    $s .=  '</td>';

		    $s .=  '</tr>';   /**/
			}
		  $s .=  '</table>';
	$s .=  '</div>';
	return $s;
}

function  print_playlist_edit($playlist_data,$playlist_id,$order_type,$use_sort_header=false)
{
    global $base;
	echo '<div class="fleft w100">';

		  echo '<table  id="media_table_dnd" class="media_table">';

		  if ($use_sort_header)
		  {
		   echo '<tr>
		           <th >id</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/artist/'.$order_type.'\'">Исполнитель</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/title/'.$order_type.'\'">Название трека</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/year/'.$order_type.'\'">Год выпуска</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/genre/'.$order_type.'\'">Жанр</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/size/'.$order_type.'\'">Размер</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/length/'.$order_type.'\'">Длительность</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/bpm/'.$order_type.'\'" >bpm</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/camelot_ton/'.$order_type.'\'">Тон</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'/rating/'.$order_type.'\'">Рейтинг</th>
		           <th >Коментарий</th>
		           <th >Дата модификации</th>
		           <th >Дата добавления</th>
		           <th >ОП</th>
		         </tr>';      /**/
           }
           else
           {
		   echo '<tr>
		           <th >id</th>
		           <th >Исполнитель</th>
		           <th >Название трека</th>
		           <th >Год выпуска</th>
		           <th >Жанр</th>
		           <th >Размер</th>
		           <th >Длительность</th>
		           <th >bpm</th>
		           <th >Тон</th>
		           <th >Рейтинг</th>
		           <th >Коментарий</th>
		           <th >Дата модификации</th>
		           <th >Дата добавления</th>
		           <th >ОП</th>
		         </tr>';      /**/
           }

			foreach  ($playlist_data as $key => $data)
			{
		        echo '<tr id="final_playlist_'.$key.'">';

			    echo '<td>';
			    echo $data['id'];
                echo '<input type="hidden" name="final_playlist_dnd" value="'.$data['id'].'">';
		   	    echo '</td><td>';
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

		        echo date ("d-m-Y H:i:s", filemtime($data['filename']));
			    echo '</td><td>';
			    echo date ("d-m-Y", datetime_to_timestamp($data['add_date']));
			    echo '</td><td>';
			    echo '<div class="red pointer " onclick="remove_dom_element(\'final_playlist_'.$key.'\');">[X]</div>';
			    echo '</td>';

		    echo '</tr>';   /**/
			}

 echo '</table>';

  echo '<script type="text/javascript">
			var table = document.getElementById(\'media_table_dnd\');
			var tableDnD = new TableDnD();
			tableDnD.init(table);
			</script>';

	echo '</div>';
}

function  print_playlist_new($playlist_data,$playlist_id,$order_type,$use_sort_header=false)
{
    global $base;

    $playlist_id = intval($playlist_id);
    if ($playlist_id<=0){$playlist_id ='0';}

	echo '<div class="fleft w100">';

		  echo '<table  id="media_table_dnd" class="media_table">';

		  if ($use_sort_header)
		  {
		   echo '<tr>
		           <th >id</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/artist/'.$order_type.'\'">Исполнитель</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/title/'.$order_type.'\'">Название трека</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/year/'.$order_type.'\'">Год выпуска</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/genre/'.$order_type.'\'">Жанр</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/size/'.$order_type.'\'">Размер</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/length/'.$order_type.'\'">Длительность</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/bpm/'.$order_type.'\'" >bpm</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/camelot_ton/'.$order_type.'\'">Тон</th>
		           <th onclick="window.location.href=\''.$base.'/playlist/add/'.$playlist_id.'/rating/'.$order_type.'\'">Рейтинг</th>
		           <th >Коментарий</th>
		           <th >Дата модификации</th>
		           <th >Дата добавления</th>
		           <th >ОП</th>
		         </tr>';      /**/
           }
           else
           {
		   echo '<tr>
		           <th >id</th>
		           <th >Исполнитель</th>
		           <th >Название трека</th>
		           <th >Год выпуска</th>
		           <th >Жанр</th>
		           <th >Размер</th>
		           <th >Длительность</th>
		           <th >bpm</th>
		           <th >Тон</th>
		           <th >Рейтинг</th>
		           <th >Коментарий</th>
		           <th >Дата модификации</th>
		           <th >Дата добавления</th>
		           <th >ОП</th>
		         </tr>';      /**/
           }

			foreach  ($playlist_data as $key => $data)
			{
		        echo '<tr id="final_playlist_'.$key.'">';

			    echo '<td>';
			    echo $data['id'];
                echo '<input type="hidden" name="final_playlist_dnd" value="'.$data['id'].'">';
		   	    echo '</td><td>';
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

		        echo date ("d-m-Y H:i:s", filemtime($data['filename']));
			    echo '</td><td>';
			    echo date ("d-m-Y", datetime_to_timestamp($data['add_date']));
			    echo '</td><td>';
			    echo '<div class="red pointer " onclick="remove_dom_element(\'final_playlist_'.$key.'\');">[X]</div>';
			    echo '</td>';

		    echo '</tr>';   /**/
			}

 echo '</table>';

  echo '<script type="text/javascript">
			var table = document.getElementById(\'media_table_dnd\');
			var tableDnD = new TableDnD();
			tableDnD.init(table);
			</script>';

	echo '</div>';
}

function get_filter_form($id,$data)
{
			$artist = $data['artist'];
			$title = $data['title'];
			$genre = $data['genre'];
			$bpm_high = $data['bpm_high'];
			$bpm_low = $data['bpm_low'];
			$camelot_ton = $data['camelot_ton'];

			$rating_low = $data['rating_low'];
			$rating_high = $data['rating_high'];
			$date_time_first = $data['date_time_first'];
			$date_time_last = $data['date_time_last'];
			$year = $data['year'];
			$context = $data['context'];
			$max_tracks_count = $data['max_tracks_count'];
            $date_time_offset = $data['date_time_offset'];
            $comment = $data['comment'];

            $count_priority = (isset($data['count_priority']) and $data['count_priority']=="Y");


	  	    $s = '';
			$s.= '<div id ="ff_'.$id.'" class="fleft filter_rule" >';

			$s.= '<div  class="fleft w100">';

	        $s.= '<div>';
	        $s.= '<h2 class="fleft">Правило № '.$id.'</h2>';

	        $s.= '<div style="width:120px;" class="fright sym_button pointer" onclick="window.location.href=window.location.href;">[☠]</div>';
	        $s.= '<div class="fright sym_button" style="width:120px;">&nbsp;</div>';
	        $s.= '<div class="fright sym_button pointer" onclick="remove_dom_element(\'ff_'.$id.'\');">[X]</div>';
	        $s.= '<div class="fright sym_button pointer" onclick = "get_filter_form();">[+]</div>';
	        $s.= '<div class="fright sym_button pointer" onclick = "reset_filter_form(\'ff_'.$id.'\');">[C]</div>';
	        $s.= '<div class="fright sym_button pointer" onclick="submit_form(\'form1\');">[➲]</div>';



	        $s.= '</div>';

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
						>
				      </div>
				 </div>';
			$s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						bpm нижняя граница
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input class="w90p"                                  		name="rule['.$id.'][bpm_low]"            id="bpm_low['.$id.']"            type="text" value="'.$bpm_low.'">
				      </div>
				  </div>';
			$s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						bpm верхняя граница
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input class="w90p"                                  		name="rule['.$id.'][bpm_high]"            id="bpm_high['.$id.']"            type="text" value="'.$bpm_high.'">
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
					Добавленые в последние
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="rule['.$id.'][date_time_offset]"   id="date_time_offset['.$id.']"   type="text" value="'.$date_time_offset.'"> дней
			      </div>
			 </div>';
			$s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						Коментарий содержит
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input class="w90p" name="rule['.$id.'][comment]"   id="comment['.$id.']"   type="text" value="'.$comment.'">
				      </div>
				 </div>';
	 		$s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						Число треков
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input class="w90p" name="rule['.$id.'][max_tracks_count]"   id="max_tracks_count['.$id.']"   type="text" value="'.$max_tracks_count.'">
				      </div>
				 </div>';
	 		$s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						Приоритет новых треков
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input type="checkbox" ';
						if($count_priority) {$s.=' checked="checked" ';}
						$s.=' name="rule['.$id.'][count_priority]"   id="count_priority['.$id.']"   value="';
						$s.=($count_priority=="Y")?"Y":"N";
						$s.= '" onclick="checkbox_set_value_on_toggle(this);">';
				        $s.='</div>
				 </div>';
	        $s.= '</div>';
	        return $s;
}

function get_print_filter_info($id,$data)
{
			$artist = $data['artist'];
			$title = $data['title'];
			$genre = $data['genre'];
			$bpm_high = $data['bpm_high'];
			$bpm_low = $data['bpm_low'];
			$camelot_ton = $data['camelot_ton'];

			$rating_low = $data['rating_low'];
			$rating_high = $data['rating_high'];
			$date_time_first = $data['date_time_first'];
			$date_time_last = $data['date_time_last'];
			$year = $data['year'];
			$context = $data['context'];
			$max_tracks_count = $data['max_tracks_count'];
            $date_time_offset = $data['date_time_offset'];
            $comment = $data['comment'];

            $count_priority = (isset($data['count_priority']) and $data['count_priority']=="Y");


	  	    $s = '';

	        $s.= '<h2 class="fleft">Правило № '.$id.'</h2>';


			$s.= 'Исполнитель <b>'.$artist.'</b><br/>';
			$s.= 'Название <b>'.$title.'</b><br/>';
			$s.= 'Год выхода трека <b>'.$year.'</b><br />';
			$s.= 'Жанр <b>'.$genre.'</b><br />';
			$s.= 'bpm нижняя граница <b>'.$bpm_low.'</b><br />';
			$s.= 'bpm верхняя граница <b>'.$bpm_high.'</b><br />';
			$s.= 'Тональность <b>'.$camelot_ton.'</b><br />';
			$s.= 'Рейтинг нижняя граница <b>'.$rating_low.'</b><br />';
			$s.= 'Рейтинг верхняя граница <b>'.$rating_high.'</b><br />';
			$s.= 'Время добавления (начало интервала, ГГГГ-ММ-ДД) <b>'.$date_time_first.'</b><br />';
			$s.= 'Время добавления (конец интервала, ГГГГ-ММ-ДД) <b>'.$date_time_last.'</b><br />';
			$s.= 'Добавленые в последние <b>'.$date_time_offset.'</b> дней <br />';
			$s.= 'Коментарий содержит <b>'.$comment.'</b><br />';
	 		$s.= 'Число треков <b>'.$max_tracks_count.'</b><br />';
	 		$s.= 'Приоритет новых треков <b>';
			$s.=($count_priority=="Y")?"ДА":"НЕТ";
			$s.='</b><br />';
	        return $s;
}


function get_playlist_select($cntrl_attrib,$static="",$selected=0)
{
 global $mysqli_connection;

 $s='';
 if (!headers_sent()) { header("Content-type: text/html; charset=UTF-8"); }

 $sel = intval($selected);

 $qry = "SELECT * FROM `playlists`";

 if ($static=="Y" or $static=="N")
 {
 $qry .=" WHERE `playlists`.`static`='".$static."' ";
 }
  $qry .= " ORDER BY `name`";

 echo $qry;

 $query = mysqli_query($mysqli_connection, $qry);

  $s .= '<select '.$cntrl_attrib.'>';
  $s .= "<option value='0' >- Выберите -</option>";

 while ($row = mysqli_fetch_assoc($query))
 {
  $id=$row['id'];
  $name=$row['name'];
  $static=$row['static'];

  if ($static=="Y"){ $static_text = " (статический)";}
  if ($static=="N"){ $static_text = " (динамический)";}


  if ($id==$sel)
   {
   	$s .= "<option selected='selected' value='".$id."'>".$name.$static_text."</option>";
   	}
  else
   {
    $s .= "<option value='".$id."'>".$name.$static_text."</option>";
    }

 }

 $s .= '</select>';
 return $s;
}

function print_message($message,$title)
{
  global $base;
	 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.history.back()"> <b><<</b> </td>
           <td>'.$title.'
           </td>
        </tr>
      </table>
   </div>';

   echo '<div class="pad10">'.$message.'</div>';

   echo "</div>";
}




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
	 $playlist_data['static'] = 'N';
	 print_playlist_view_edit($playlist_data);
	 return;
	}
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

 $playlist_id=$playlist_data['id'];
 $playlist_name=$playlist_data['name'];
 $playlist_static=($playlist_data['static']=='Y'?'Y':'N');
 $rules= $playlist_data['ruleset'];


echo '<div id="helper"></div>';
 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>';
           if ($playlist_id==0)
           {            echo '<td>Создание динамического плейлиста</td>';
           	}
           	else
           	{
            echo '<td>Редактирование динамического плейлиста «'.$playlist_name.'»</td>';
           }
  echo '</tr>
      </table>
   </div>';

 echo '<div class="pad20" >';
    echo '<H1>Правила для плейлиста</H1>';

	echo '<form method="POST" action="" id="form1">';

		echo '<div id="filter_form"  class="fleft w100" >';

		$final_playlist_data = generate_dynamic_playlist($playlist_data,false,'showhidden');
	    $final_playlist =  $final_playlist_data['data'];
	    echo $final_playlist_data['view'];

        $next_rule_id = $final_playlist_data['info']['rules_processed'];
        echo '<script>var filter_rule_form_id='.$next_rule_id.';</script>';

		echo '</div>';

		print_playlist_add_filter_block();

		print_playlist_common_filter_block($playlist_data);

	echo '</form>';

	shuffle($final_playlist);

   	echo '<div class="fleft w100 pad10"></div>';
 	echo '<div  class="fleft w100">';

	if (!empty($final_playlist))
	{
    	print_final_playlist_block($final_playlist,$playlist_data);
	}
	else
	{  		print_playlist_total_results();	}

    echo '<div class="fleft w100 pad10"></div>';

 echo '</div>';

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';


echo '</div>';

}


function print_playlist_common_filter_block($playlist_data){    echo '<div class="fleft w100 pad20 bbsz asc fleft filter_rule" >';

  	//print_r($playlist_data);

  	$common_rules = $playlist_data['ruleset']['special']['common'];

    $max_tracks = $common_rules['max_tracks'];
    $max_total_time = $common_rules['max_total_time'];
    $count_priority  = $common_rules['count_priority'];
    $s.= '<div  class="fleft w100">';

	        $s.= '<div>';
	        $s.= '<h2 class="fleft">Общие Правила </h2>';

	        $s.= '<div class="fright sym_button pointer" onclick="submit_form(\'form1\');">[➲]</div>';



	        $s.= '</div>';

			$s.= '<div class="fleft w100 pad5">
				      <div class="fleft movedownonmedium movedownonsmall w30p">
				      Количество треков
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
				        <input  class="w90p"                         		name="rule[special][common][max_tracks]"   type="text" value="'.$max_tracks.'">
				      </div>
				  </div> ';

			$s.= '<div class="fleft w100 pad5">
				      <div class="fleft movedownonmedium movedownonsmall w30p">
				      Гарантированная длительность(ЧЧ:ММ:CC)
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
				        <input  class="w90p"                         		name="rule[special][common][max_total_time]"   type="text" value="'.$max_total_time.'">
				      </div>
				  </div> ';
            $s.= '<div class="fleft w100 pad5">
					  <div class="fleft movedownonmedium movedownonsmall w30p">
						Приоритет новых треков
				      </div>
				      <div class="fleft movedownonmedium movedownonsmall w70">
						<input type="checkbox" ';
						if($count_priority) {$s.=' checked="checked" ';}
						$s.=' name="rule[special][common][count_priority]"    value="';
						$s.=($count_priority=="Y")?"Y":"N";
						$s.= '" onclick="checkbox_set_value_on_toggle(this);">';
				        $s.='</div>
				 </div>';
	        $s.= '</div>';
	        echo  $s;

    echo '</div>';}


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


function print_final_playlist_block($final_playlist,$playlist_data){	echo get_print_final_playlist_block($final_playlist,$playlist_data);	}

function get_print_final_playlist_block($final_playlist,$playlist_data){    global $base;
    $s='';

	$playlist_id=$playlist_data['id'];
	$playlist_name=$playlist_data['name'];
	$playlist_static=($playlist_data['static']=='Y'?'Y':'N');
	$rules= $playlist_data['ruleset'];
	$s .=  '<div class="fleft w100 pad10"></div>';
	$s .=  '<h1>Результаты отбора</h1>';

    $s .=  '<div class="fleft w100 pad10"></div>';

    $s.= get_print_playlist_total_results($final_playlist);

    $s .=  '<div class="fleft w100 pad10"></div>';

	$s.= get_print_playlist($final_playlist,true);

    $s .=  '<input type="hidden" id="playlist_static_flag" value="'.$playlist_static.'">';


    $s .=  '<div class="fleft w100 pad10"></div>';



      $s .=  '<div class="fleft w100">';
			$s .=  '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
			onclick="show_modal_window_playlist_save(\''.$playlist_id.'\',\''.$playlist_name.'\');">';
			 $s .=  '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time pad10"><b>[⇩]</b></td>
			              <td class="job_name pad10" >Сохранить</td>
			            </tr>
			            </table>
			            ';
			$s .=  '</div>' ;
			$s .=  '<div class="fleft w50 job_plate_50 asc pointer movedownonsmall movedownonmedium"
            onclick="window.location.href=\''.$base.'/playlist/edit/'.$playlist_id.'\'"
			>';
			 $s .=  '<table class="">
			            <tr class="pointer pad10">
			              <td class="job_time red pad10"><b>[R]</b></td>
			              <td class="job_name pad10" >Сброс</td>
			            </tr>
			            </table>
			            ';
			$s .=  '</div>' ;
		$s .=  '</div>' ;

    $s .=  '<div class="fleft w100 pad10"></div>';
    return $s;}

function print_playlist_total_results($final_playlist){         echo get_print_playlist_total_results($final_playlist);}
function get_print_playlist_total_results($final_playlist){
	$s = '';
	$s .=  '<div class="fleft w100 pad5 filter_result_info">';
	if (count($final_playlist)==0)
	{			$s .=  '<span class="message_warn">Ничего не найдено</span><br />';
	}
	else
	{
		$pl_time = 0;
		foreach ($final_playlist as $pl_element)
		{
			$pl_time += $pl_element['length'];
		}
		$s .=  'Отобрано: '.count($final_playlist).' треков ';
		$s .=  '<span class="maroon">'.sec_to_hour_min_sec($pl_time).'</span> ';

		$doubles = playlist_has_doubles($final_playlist);

		if($doubles)
		{
			$s .=  '<span class="message_warn">С повторами</span><br />';
		}
		else
		{
			$s .=  '<span class="message_ok">Без повторов</span><br />';
		}
	}
    $s .=  '</div>';
    return $s;
}

function print_playlist_add_filter_block(){	echo '<div class="fleft w100 pad10"></div>';

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

 	echo '</div>';}



?>