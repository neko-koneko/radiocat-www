<?php
require_once(dirname(__FILE__).'/get_mp3info.php');
require_once(dirname(__FILE__).'/time_lib.php');

function scan($folder){
	//$main = dirname(getcwd());
	$path = $folder;
	$dir = opendir($path);
	while(false !== ($file = readdir($dir))){
		if(is_file("{$path}/{$file}"))
		{
		    if (preg_match("@^.*\.mp3$@i",$file))
		    {
			$list[] = $folder."/".$file;
			}
		}
		else if(is_dir("{$path}/{$file}") && $file != "." && $file != ".."){
			$result = scan($folder.'/'.$file);
			if(count($result) > 0){
				foreach($result as $item){
					$list[] = $item;
				}
			}
		}
	}
	closedir($dir);
	return $list;
}


function media_add_file_data($filename,$force_update=false)
{
 global $mysqli_connection;

    $result = array();
    $result['error']=false;
    $result['description']="";

    if (!is_file($filename))
    {
    $result['error']=true;
  	$result['description'] = '<span style="color: red; font-weight:bold;">Файл '.htmlspecialchars($filename).' не найден';
  	return $result;
  	}

    $file_modification_time = filemtime($filename);

    $old_file_data = media_library_get_file_data_by_filename($filename);

    if (empty($old_file_data))
    {

    }
    else
    {
	    $old_file_modification_date = datetime_to_timestamp($old_file_data['date']);

	    if (!($old_file_modification_date<$file_modification_time) and !$force_update)
	    {
	    $result['error']=false;
	  	$result['description'] = '<span style="color: orange; font-weight:bold;">Файл '.htmlspecialchars(($filename)).' не изменился — Пропущен '.
	  	date ("Y-m-d H:i:s",$old_file_modification_date).
	  	' '.date ("Y-m-d H:i:s",$file_modification_time).'</span><br />';
	  	return $result;
	    }
    }

	$tag_data=get_mp3_tags_and_info($filename);
    if (empty($tag_data))
    {
    	$result['error']=true;
	  	$result['description'] = '<span style="color: red; font-weight:bold;">Не удалось прочитать теги файла '.htmlspecialchars($filename).'</span><br />';
    	return $result;
    }
    //print_r($tag_data);
    $tag_data['filename'] = $filename;


 if (is_file_in_db($filename))
  {
    $re =media_update_track_info_from_file_tag_data($old_file_data['id'],$tag_data,$context);
    if (!$re)
    {
    	$result['error']=true;
	  	$result['description'] = '<span style="color: red; font-weight:bold;">Не удалось обновить файл '.$old_file_data['id'].' '.htmlspecialchars($filename).'</span><br />';
    	return $result;
    }
    else
    {
    $result['description'] = '<span style="color: green; font-weight:bold;">Файл '.$old_file_data['id'].' '.htmlspecialchars($filename).' уже имеется в медиатеке — обновлён</span><br />';
    $result['tag']=$tag_data;
    }
  }
 else
  {
  	$re = media_add_track_info_from_file_tag_data($tag_data,$context);
    if (!$re)
    {
    	$result['error']=true;
	  	$result['description'] = '<span style="color: red; font-weight:bold;">Не удалось добавить файл '.htmlspecialchars($filename).'</span><br />';
    	return $result;
    }
    else
    {
    $result['description'] = '<span style="color: green; font-weight:bold;">Файл '.htmlspecialchars($filename).' добавлен </span><br />';
    }
  }


  return $result;
}

function media_add_track_info_from_file_tag_data($tag_data,$context)
{
 global $mysqli_connection;
 global $config;

    if (empty ($tag_data) ) {return false;}

    $filename = $tag_data['filename'];
    if ($filename=='') {return false;}

    $size = filesize($filename);


    $title = $tag_data['tag']['TITLE'];
    $artist = $tag_data['tag']['ARTIST'];
    $year = $tag_data['tag']['YEAR'];
    $genre = $tag_data['tag']['GENRE'];
    $bpm =$tag_data['tag']['TBPM'];
    $comment = $tag_data['tag']['COMMENT'];

    $bitrate = $tag_data['info']['BITRATE'];
    $length = round($tag_data['info']['SECS']);

  $file_date_str = timestamp_to_date(filemtime($filename));
  $now_date_str = date ("Y-m-d H:i:s");
  $default_rating = $config['media']['default_rating'];
  $filename = mysqli_real_escape_string($mysqli_connection,$filename);
  $title = mysqli_real_escape_string($mysqli_connection,$title);
  $artist = mysqli_real_escape_string($mysqli_connection,$artist);
  $year = intval($year);
  $year = ($year >0)?$year:'';
  $genre = mysqli_real_escape_string($mysqli_connection,$genre);
  $bitrate = intval($bitrate);
  $bitrate = ($bitrate >0)?$bitrate:'';
  $bpm = intval($bpm);
  $bpm = ($bpm >0)?$bpm:'';
  $comment = mysqli_real_escape_string($mysqli_connection,$comment);
  $camelot_ton = substr(  $comment,0,4 );
  $context = intval ($context);
 $qry =" INSERT INTO
   `files`
   ( `filename`,
     `date`,
     `add_date`,
     `size`,
     `rating`,
     `artist`,
     `title`,
     `genre`,
     `year`,
     `length`,
     `bpm`,
     `camelot_ton`,
     `context`)
      VALUES
      (
      '$filename',
      '$file_date_str',
      '$now_date_str',
      '$size',
      '$default_rating',
      '$artist',
      '$title',
      '$genre',
      '$year',
      '$length',
      '$bpm',
      '$comment',
      '$context')
      ;"  ;


  $query = mysqli_query($mysqli_connection,$qry);
  //echo $qry;

  if (!$query) { return false;}
  else { return true;}
}

function media_update_track_info_from_file_tag_data($file_id,$tag_data,$context)
{
 global $mysqli_connection;
    $file_id = intval($file_id);
    if ($file_id<=0) {return false;}

    $filename = $tag_data['filename'];
    $size = filesize($filename);


    $title = ($tag_data['tag']['TIT2']=='')?$tag_data['tag']['TITLE']:$tag_data['tag']['TIT2'];
    $artist = $tag_data['tag']['ARTIST'];
    $year = $tag_data['tag']['YEAR'];
    $genre = $tag_data['tag']['GENRE'];
    $bpm =$tag_data['tag']['TBPM'];
    $comment = $tag_data['tag']['COMMENT'];

    $bitrate = $tag_data['info']['BITRATE'];
    $length = round($tag_data['info']['SECS']);

  $file_date_str = timestamp_to_date( filemtime($filename));
  $now_date_str = date ("Y-m-d");
  $default_rating=3;

  $filename = mysqli_real_escape_string($mysqli_connection,$filename);
  $title = mysqli_real_escape_string($mysqli_connection,$title);
  $artist = mysqli_real_escape_string($mysqli_connection,$artist);
  $year = intval($year);
  $year = ($year >0)?$year:'';
  $genre = mysqli_real_escape_string($mysqli_connection,$genre);
  $bitrate = intval($bitrate);
  $bitrate = ($bitrate >0)?$bitrate:'';
  $bpm = intval($bpm);
  $bpm = ($bpm >0)?$bpm:'';
  $comment = mysqli_real_escape_string($mysqli_connection,$comment);
  $camelot_ton = substr(  $comment,0,4 );
  $context = intval ($context);

  $qry =" UPDATE
   			`files`
          SET
           `filename`='$filename',
     `date`='$file_date_str',
     `size`='$size',
     `artist`='$artist',
     `title`='$title',
     `genre`='$genre',
     `year`='$year',
     `length`='$length',
     `bpm`='$bpm',
     `camelot_ton`='$camelot_ton',
     `context`='$context'
         WHERE
        `id`='$file_id'
      ;"  ;

  $query = mysqli_query($mysqli_connection,$qry);
  //echo $qry;

  if (!$query) {return false;}
  else { return true;}
}


function is_file_in_db($filename)
{
 global $mysqli_connection;

  $filename = mysqli_real_escape_string($mysqli_connection,$filename);

  $query = mysqli_query($mysqli_connection,
  "SELECT COUNT(*) as count
  FROM `files`
  WHERE `filename` = '".$filename."'
   ");

 $row = mysqli_fetch_assoc($query);
 return ($row['count']>=1);
}


function get_last_context()
{
 global $mysqli_connection;

  $query = mysqli_query($mysqli_connection,
  "SELECT MAX(context) as context
  FROM `files`
  WHERE 1
   ");

 $row = mysqli_fetch_assoc($query);
 return $row['context'];
}


function check_for_deleted_files()
{
 global $mysqli_connection;

  $query = mysqli_query($mysqli_connection,
  "SELECT filename,id
  FROM `files`
  WHERE 1
   ");


 while ($row = mysqli_fetch_assoc($query))
 {
 $filename = $row['filename'];
 $id = $row['id'];
 	if (is_file ($filename))
 	{
 		echo "<div style='color: green;'>Файл ".htmlspecialchars($filename)." уже существует</div>";
 	}
 	else
 	{
 		echo "<div style='color: red;'>Файл ".htmlspecialchars($filename)." отсутствует на диске! ";

 		$playlists = get_all_playlists_where_file_is($filename);

 		if (!is_array($playlists) or empty ($playlists))
 		{
 			echo "<div style='color: green;'>Файл ".htmlspecialchars($filename)." не входит в плейлисты — коррекция не требуется<br />";
 		}
 		else
 		{
            foreach ($playlists as $playlist_id)
            {
             echo "<div style='color: red;'> Файл (id=".$playlist_id.") удалён из всех плейлистов <br /> ";
             remove_playlist_entry_by_id($playlist_id);
            }
 		}
        remove_file_entry_by_id ($id);

        echo "</div>";
 	}
 }
 echo '<h2>Коррекция Расписания</h2>';

 $playlists = get_all_playlists();

 foreach ($playlists as $playlist)
 {
    if ($playlist['static']!=='Y')
    {
        echo '<div style="color: green;">Плейлист (id='.$playlist['id'].') - '.htmlspecialchars($playlist['name']).' - ДИНАМИЧЕСКИЙ, пропущен<br /></div>';
    	continue;
    }

    $playlist_id = $playlist['id'];
	$tracks_count = get_playlist_tracks_count();
	if ($tracks_count==0)
	{
	  if (delete_playlist($playlist_id))
	  {
      	echo '<div style="color: red;">Плейлист (id='.$playlist['id'].') - '.htmlspecialchars($playlist['name']).' - СТАТИЧЕСКИЙ, НЕТ ТРЕКОВ - УДАЛЁН<br /></div>';

      	if (cron_del_job_by_playlist_id($playlist_id))
      	{
      	echo '<div style="color: red;">Плейлист (id='.$playlist['id'].') - '.htmlspecialchars($playlist['name']).' - УДАЛЁН ИЗ РАСПИСАНИЯ<br /></div>';
      	}
      	else
      	{
      	echo '<div style="color: red;">Плейлист (id='.$playlist['id'].') - '.htmlspecialchars($playlist['name']).' - НЕ УДАЛОСЬ УДАЛИТЬ ИЗ РАСПИСАНИЯ<br /></div>';
      	}

	  }
	  else
	  {
      echo '<div style="color: red;">Плейлист (id='.$playlist['id'].') - '.htmlspecialchars($playlist['name']).' - СТАТИЧЕСКИЙ, НЕТ ТРЕКОВ - НЕ УДАЛОСЬ УДАЛИТЬ<br /></div>';
      }
	}
 }



}


function get_all_playlists_where_file_is($filename)
{
 global $mysqli_connection;

  $filename = mysqli_real_escape_string($filename);

  $query = mysqli_query($mysqli_connection,
  "SELECT `tracks`.`id` as id
  FROM `tracks`,`files`
  WHERE `files`.`filename` = '".$filename."'
  AND `files`.`id` = `tracks`.`file_id`
   ");

 $result = array();

  while ($row = mysqli_fetch_assoc($query))
  {
   $result[] = $row['id'];
  }
  return $result;
}


function remove_playlist_entry_by_id($id)
{
 global $mysqli_connection;

 $id = intval ($id);
 if ($id <=0) {return;}
 $query = mysqli_query($mysqli_connection,"DELETE FROM `tracks` WHERE `id` = $id");
}

function remove_file_entry_by_id($id)
{
 global $mysqli_connection;

 $id = intval ($id);
 if ($id <=0) {return;}
 $query = mysqli_query($mysqli_connection,"DELETE FROM `files` WHERE `id` = $id");
}




?>