<?
require_once("utils.php");
require_once("time_lib.php");
require_once("dbal.php");

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


function  print_playlist($playlist,$include_file_id_inputs=false)
{
	echo '<div class="fleft w100">';

		  echo '<table class="media_table">';
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
		           <th >счётчик</th>
		         </tr>';      /**/

			foreach  ($playlist as $data)
			{
		        echo '<tr>';

			    echo '<td>';
			    echo $data['id'];
               if ($include_file_id_inputs) {echo '<input type="hidden" name="final_playlist_dnd" value="'.$data['id'].'">';}
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

		        echo date ("d-m-Y H:i:s", datetime_to_timestamp($data['date']));
			    echo '</td><td>';
			    echo date ("d-m-Y", datetime_to_timestamp($data['add_date']));
			    echo '</td><td>';
		   	    echo $data['count'];
			    echo '</td>';

		    echo '</tr>';   /**/
			}
		  echo '</table>';
	echo '</div>';
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
           {		   echo '<tr>
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
	        return $s;}


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


function playlist_get_adaptive_tracks_by_filter($data)
{

return get_tracks_by_filter($data);
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

?>