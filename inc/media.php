<?

 include_once ("inc/id.php");
 include_once ("inc/id3v2.php");
 include_once ("inc/time_lib.php");

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


function media_add_file_data($filename)
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

	    if (!($old_file_modification_date<$file_modification_time))
	    {
	    $result['error']=false;
	  	$result['description'] = '<span style="color: orange; font-weight:bold;">Файл '.htmlspecialchars(($filename)).' не изменился — Пропущен '.
	  	date ("Y-m-d H:i:s",$old_file_modification_date).
	  	' '.date ("Y-m-d H:i:s",$file_modification_time).'</span><br />';
	  	return $result;
	    }
    }

	$tag_result=get_mp3_tags_and_info($filename);

    if ($tag_result['error'])
    {
    	$result['error']=true;
	  	$result['description'] = '<span style="color: red; font-weight:bold;">Не удалось прочитать теги файла '.htmlspecialchars($filename).'</span><br />';
    	return $result;
    }
    $tag_data = $tag_result['data'];
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
    }
  }
 else
  {
  	$re = media_add_track_info_from_file_tag_data($tag_data,$context);
    if (!$re)
    {
    	$result['error']=true;
	  	$result['description'] = '<span style="color: red; font-weight:bold;">Не удалось добавить файл '.htmlspecialchars($filename).' '.$result['description'].'</span><br />';
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

    if (empty ($tag_data) ) {return false;}

    $filename = $tag_data['filename'];
    if ($filename=='') {return false;}

    $size = filesize($filename);


    $title = ($tag_data['Title']=='')?$tag_data['NAME']:$tag_data['Title'];
    $artist =($tag_data['Artist']=='')?$tag_data['ARTISTS']:$tag_data['Artist'];
    $year =($tag_data['Year']=='')?$tag_data['YEAR']:$tag_data['Year'];
    $genre =($tag_data['Genre']=='')?get_v1_genre_name($tag_data['GENRENO']):$tag_data['Genre'];
    $bitrate =$tag_data['bitrate'];
    $bpm =$tag_data['bpm'];
    $comment =($tag_data['Comment']=='')?$tag_data['COMMENT']:$tag_data['Comment'];


    $length = ($tag_data['lengths']>0)?$tag_data['lengths']:'';

  $file_date_str = timestamp_to_date(filemtime($filename));
  $now_date_str = date ("Y-m-d H:i:s");
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


    $title = ($tag_data['Title']=='')?$tag_data['NAME']:$tag_data['Title'];
    $artist =($tag_data['Artist']=='')?$tag_data['ARTISTS']:$tag_data['Artist'];
    $year =($tag_data['Year']=='')?$tag_data['YEAR']:$tag_data['Year'];
    $genre =($tag_data['Genre']=='')?get_v1_genre_name($tag_data['GENRENO']):$tag_data['Genre'];
    $bitrate =$tag_data['bitrate'];
    $bpm =$tag_data['bpm'];
    $comment =($tag_data['Comment']=='')?$tag_data['COMMENT']:$tag_data['Comment'];


    $length = ($tag_data['lengths']>0)?$tag_data['lengths']:'';

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
     `add_date`='$now_date_str',
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



function get_mp3_tags_and_info($filename)
{
  // echo $filename.'<br />';

  $result['error'] = false;
  $result['description'] = "";

  $file=fopen($filename,'r');
  if (!$file) { $result['error']=true; $result['description']="Не могу открыть файл"; return $result;}
  fclose($file);

  $v1_tags = read_v1_tags($filename);
 // print_r ($v1_tags);

  $i = new Id3v2;
  $v2_tags = $i->read($filename);
//print_r($v2_tags);

  $info = read_mp3_frame($filename);
//print_r($info) ;

  /*$id3 = new MP3_Id();
  echo '1';
  $id3->_readframe($filename);
  print_r ($id3);/**/
 $tag_result = array();
 foreach ($v1_tags as $key => $data){ $tag_result[$key] = $data; }
 foreach ($v2_tags as $key => $data){ $tag_result[$key] = $data; }
 foreach ($info as $key => $data){ $tag_result[$key] = $data; }

  $result['data']=$tag_result;
  $result['error'] = false;
  $result['description'] = "";

 return $result;
}

function read_v1_tags($file)
{
	if (! ($f = fopen($file, 'rb')) ) return array();
	rewind($f);
	fseek($f, -128, SEEK_END);
	$tmp = fread($f,128);
	if ($tmp[125] == Chr(0) and $tmp[126] != Chr(0)) {
	    // ID3 v1.1
	    $format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a28COMMENT/x1/C1TRACK/C1GENRENO';
	} else {
	    // ID3 v1
	    $format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a30COMMENT/C1GENRENO';
	}

	$id3tag = unpack($format, $tmp);
	return  $id3tag;
}

function read_mp3_frame($file)
{
	if (! ($f = fopen($file, 'rb')) ) return array();
    $res['filesize'] = filesize($file);
    do {
        while (fread($f,1) != Chr(255)) { // Find the first frame
        	if (feof($f))  return false; ;
        }
        fseek($f, ftell($f) - 1); // back up one byte

        $frameoffset = ftell($f);

        $r = fread($f, 4);

        $bits = sprintf("%'08b%'08b%'08b%'08b", ord($r{0}), ord($r{1}), ord($r{2}), ord($r{3}));
    }
	while (!$bits[8] and !$bits[9] and !$bits[10]); // 1st 8 bits true from the while

    // Detect VBR header
    if ($bits[11] == 0) {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 9; // MPEG 2.5 Mono
        } else {
            $vbroffset = 17; // MPEG 2.5 Stereo
        }
    } else if ($bits[12] == 0) {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 9; // MPEG 2 Mono
        } else {
            $vbroffset = 17; // MPEG 2 Stereo
        }
    } else {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 17; // MPEG 1 Mono
        } else {
            $vbroffset = 32; // MPEG 1 Stereo
        }
    }

    fseek($f, ftell($f) + $vbroffset);
    $r = fread($f, 4);

    switch ($r) {
        case 'Xing':
            $res['encoding_type'] = 'VBR';
        case 'Info':
        {
            $r = fread($f, 4);
            $vbrbits = sprintf("%'08b", ord($r{3}));

            if ($vbrbits[7] == 1) {
                // Next 4 bytes contain number of frames
                $r = fread($f, 4);
                $frames = unpack('N', $r);
                $res['frames'] = $frames[1];
            }

            if ($vbrbits[6] == 1) {
                // Next 4 bytes contain number of bytes
                $r = fread($f, 4);
                $musicsize = unpack('N', $r);
                $res['musicsize'] = $musicsize[1];
            }

            if ($vbrbits[5] == 1) {
                // Next 100 bytes contain TOC entries, skip
                fseek($f, ftell($f) + 100);
            }

            if ($vbrbits[4] == 1) {
                // Next 4 bytes contain Quality Indicator
                $r = fread($f, 4);
                $quality = unpack('N', $r);
                $res['quality'] = $quality[1];
            }

        }
        break;

        case 'VBRI':
        default:
            if ($vbroffset != 32) {
                // VBRI Header is fixed after 32 bytes, so maybe we are looking at the wrong place.
                fseek($f, ftell($f) + 32 - $vbroffset);
                $r = fread($f, 4);

                if ($r != 'VBRI') {
                    $res['encoding_type'] = 'CBR';
                }
                else
                {
                    $res['encoding_type'] = 'VBR';
                }

            }
            else
            {
                $res['encoding_type'] = 'CBR';
            }




            // Next 2 bytes contain Version ID, skip
            fseek($f, ftell($f) + 2);

            // Next 2 bytes contain Delay, skip
            fseek($f, ftell($f) + 2);

            // Next 2 bytes contain Quality Indicator
            $r = fread($f, 2);
            $quality = unpack('N', $r);
            $res['quality'] = $quality[1];

            // Next 4 bytes contain number of bytes
            $r = fread($f, 4);
            $musicsize = unpack('N', $r);
            $res['musicsize'] = $musicsize[1];

            // Next 4 bytes contain number of frames
            $r = fread($f, 4);
            $frames = unpack('N', $r);
            $res['frames'] = $frames[1];    /**/

            break;
    }

    fclose($f);

    if ($bits[11] == 0) {
        $res['mpeg_ver'] = "2.5";
        $bitrates = array(
            '1' => array(0, 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256, 0),
            '2' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
            '3' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
                 );
    } else if ($bits[12] == 0) {
        $res['mpeg_ver'] = "2";
        $bitrates = array(
            '1' => array(0, 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256, 0),
            '2' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
            '3' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
                 );
    } else {
        $res['mpeg_ver'] = "1";
        $bitrates = array(
            '1' => array(0, 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448, 0),
            '2' => array(0, 32, 48, 56,  64,  80,  96, 112, 128, 160, 192, 224, 256, 320, 384, 0),
            '3' => array(0, 32, 40, 48,  56,  64,  80,  96, 112, 128, 160, 192, 224, 256, 320, 0),
                 );
    }

    $layer = array(
        array(0,3),
        array(2,1),
              );
    $res['layer'] = $layer[$bits[13]][$bits[14]];

    if ($bits[15] == 0) {
        // It's backwards, if the bit is not set then it is protected.
        $res['crc'] = true;
    }

    $bitrate = 0;
    if ($bits[16] == 1) $bitrate += 8;
    if ($bits[17] == 1) $bitrate += 4;
    if ($bits[18] == 1) $bitrate += 2;
    if ($bits[19] == 1) $bitrate += 1;
    $res['bitrate'] = $bitrates[$res['layer']][$bitrate];

    $frequency = array(
        '1' => array(
            '0' => array(44100, 48000),
            '1' => array(32000, 0),
                ),
        '2' => array(
            '0' => array(22050, 24000),
            '1' => array(16000, 0),
                ),
        '2.5' => array(
            '0' => array(11025, 12000),
            '1' => array(8000, 0),
                  ),
          );
    $res['frequency'] = $frequency[$res['mpeg_ver']][$bits[20]][$bits[21]];

    $mode = array(
        array('Stereo', 'Joint Stereo'),
        array('Dual Channel', 'Mono'),
             );
    $res['mode'] = $mode[$bits[24]][$bits[25]];

    $samplesperframe = array(
        '1' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 1152
        ),
        '2' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 576
        ),
        '2.5' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 576
        ),
    );
    $res['samples_per_frame'] = $samplesperframe[$res['mpeg_ver']][$res['layer']];

    if ($res['encoding_type'] != 'VBR')
    {
        if ($res['bitrate'] == 0)
        {
            $s = -1;
        }
        else
        {
            $s = ((8*filesize($file))/1000) / $res['bitrate'];
        }
        $res['length'] = sprintf('%02d:%02d',floor($s/60),floor($s-(floor($s/60)*60)));
        $res['lengthh'] = sprintf('%02d:%02d:%02d',floor($s/3600),floor($s/60),floor($s-(floor($s/60)*60)));
        $res['lengths'] = (int)$s;

        $res['samples'] = ceil($res['lengths'] * $res['frequency']);
        if(0 != $res['samples_per_frame']) {
            $res['frames'] = ceil($res['samples'] / $res['samples_per_frame']);
        }
        else
        {
            $res['frames'] = 0;
        }
        $res['musicsize'] = ceil($res['lengths'] * $res['bitrate'] * 1000 / 8);
    }
    else
    {
        $res['samples'] = $res['samples_per_frame'] * $res['frames'];
        $s = $res['samples'] / $res['frequency'];

        $res['length'] = sprintf('%02d:%02d',floor($s/60),floor($s-(floor($s/60)*60)));
        $res['lengthh'] = sprintf('%02d:%02d:%02d',floor($s/3600),floor($s/60),floor($s-(floor($s/60)*60)));
        $res['lengths'] = (int)$s;

        $res['bitrate'] = (int)(($res['musicsize'] / $s) * 8 / 1000);
    }

    return $res;
}

 function get_v1_genre_name($id) {
    $genres= array(
        0   => 'Blues',
        1   => 'Classic Rock',
        2   => 'Country',
        3   => 'Dance',
        4   => 'Disco',
        5   => 'Funk',
        6   => 'Grunge',
        7   => 'Hip-Hop',
        8   => 'Jazz',
        9   => 'Metal',
        10  => 'New Age',
        11  => 'Oldies',
        12  => 'Other',
        13  => 'Pop',
        14  => 'R&B',
        15  => 'Rap',
        16  => 'Reggae',
        17  => 'Rock',
        18  => 'Techno',
        19  => 'Industrial',
        20  => 'Alternative',
        21  => 'Ska',
        22  => 'Death Metal',
        23  => 'Pranks',
        24  => 'Soundtrack',
        25  => 'Euro-Techno',
        26  => 'Ambient',
        27  => 'Trip-Hop',
        28  => 'Vocal',
        29  => 'Jazz+Funk',
        30  => 'Fusion',
        31  => 'Trance',
        32  => 'Classical',
        33  => 'Instrumental',
        34  => 'Acid',
        35  => 'House',
        36  => 'Game',
        37  => 'Sound Clip',
        38  => 'Gospel',
        39  => 'Noise',
        40  => 'Alternative Rock',
        41  => 'Bass',
        42  => 'Soul',
        43  => 'Punk',
        44  => 'Space',
        45  => 'Meditative',
        46  => 'Instrumental Pop',
        47  => 'Instrumental Rock',
        48  => 'Ethnic',
        49  => 'Gothic',
        50  => 'Darkwave',
        51  => 'Techno-Industrial',
        52  => 'Electronic',
        53  => 'Pop-Folk',
        54  => 'Eurodance',
        55  => 'Dream',
        56  => 'Southern Rock',
        57  => 'Comedy',
        58  => 'Cult',
        59  => 'Gangsta',
        60  => 'Top 40',
        61  => 'Christian Rap',
        62  => 'Pop/Funk',
        63  => 'Jungle',
        64  => 'Native US',
        65  => 'Cabaret',
        66  => 'New Wave',
        67  => 'Psychadelic',
        68  => 'Rave',
        69  => 'Showtunes',
        70  => 'Trailer',
        71  => 'Lo-Fi',
        72  => 'Tribal',
        73  => 'Acid Punk',
        74  => 'Acid Jazz',
        75  => 'Polka',
        76  => 'Retro',
        77  => 'Musical',
        78  => 'Rock & Roll',
        79  => 'Hard Rock',
        80  => 'Folk',
        81  => 'Folk-Rock',
        82  => 'National Folk',
        83  => 'Swing',
        84  => 'Fast Fusion',
        85  => 'Bebob',
        86  => 'Latin',
        87  => 'Revival',
        88  => 'Celtic',
        89  => 'Bluegrass',
        90  => 'Avantgarde',
        91  => 'Gothic Rock',
        92  => 'Progressive Rock',
        93  => 'Psychedelic Rock',
        94  => 'Symphonic Rock',
        95  => 'Slow Rock',
        96  => 'Big Band',
        97  => 'Chorus',
        98  => 'Easy Listening',
        99  => 'Acoustic',
        100 => 'Humour',
        101 => 'Speech',
        102 => 'Chanson',
        103 => 'Opera',
        104 => 'Chamber Music',
        105 => 'Sonata',
        106 => 'Symphony',
        107 => 'Booty Bass',
        108 => 'Primus',
        109 => 'Porn Groove',
        110 => 'Satire',
        111 => 'Slow Jam',
        112 => 'Club',
        113 => 'Tango',
        114 => 'Samba',
        115 => 'Folklore',
        116 => 'Ballad',
        117 => 'Power Ballad',
        118 => 'Rhytmic Soul',
        119 => 'Freestyle',
        120 => 'Duet',
        121 => 'Punk Rock',
        122 => 'Drum Solo',
        123 => 'Acapella',
        124 => 'Euro-House',
        125 => 'Dance Hall',
        126 => 'Goa',
        127 => 'Drum & Bass',
        128 => 'Club-House',
        129 => 'Hardcore',
        130 => 'Terror',
        131 => 'Indie',
        132 => 'BritPop',
        133 => 'Negerpunk',
        134 => 'Polsk Punk',
        135 => 'Beat',
        136 => 'Christian Gangsta Rap',
        137 => 'Heavy Metal',
        138 => 'Black Metal',
        139 => 'Crossover',
        140 => 'Contemporary Christian',
        141 => 'Christian Rock',
        142 => 'Merengue',
        143 => 'Salsa',
        144 => 'Trash Metal',
        145 => 'Anime',
        146 => 'Jpop',
        147 => 'Synthpop'
            );
     return $genres[$id];
    }

?>