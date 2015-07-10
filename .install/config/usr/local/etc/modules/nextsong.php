<?
error_reporting(E_ALL);

$mysql_connection = false;

$config['mysql']['host'] = "localhost";
$config['mysql']['user'] = "radio";
$config['mysql']['password'] = "password";
$config['mysql']['database'] = "radio";/**/

// соединяемся с базой на основном поддомене
function reconnect_db()
{
global $config;
global $mysql_connection;

  $mysql_connection = mysqli_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'],$config['mysql']['database']);
  mysqli_set_charset($mysql_connection, "utf8");
  mysqli_query($mysql_connection,"SET timezone = 'GMT'");
  return $mysql_connection;
}

if (reconnect_db() == false)
{
	echo "ERROR: ".mysqli_error(); die;
}


function get_active_playlist()
{
 global $mysql_connection;
 $query = mysqli_query($mysql_connection,
 "SELECT *
 FROM `playlist_status` ");

 $row = mysqli_fetch_assoc($query);
 return $row;
}

function get_playlist_track($playlist_id,$track_number)
{
 global $mysql_connection;
 $result = array();

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<0 ) {return $result;}
 $track_number = intval($track_number);
 if ( $track_number<0 ) {return $result;}

 $query = mysqli_query($mysql_connection,
 "SELECT *
  FROM `tracks`,`files`
  WHERE `tracks`.`playlist_id` = '".$playlist_id."'
  AND   `tracks`.`track_number` = '".$track_number."'
  AND   `files`.`id` = `tracks`.`file_id`
   ");

 $row = mysqli_fetch_assoc($query);
 return $row;
}

function set_playlist($playlist_id,$track_number,$change='N')
{
 global $mysql_connection;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<0 ) {return false;}
 $track_number = intval($track_number);
 if ( $track_number<0 ) {return false;}
 $change = ($change=="Y")?"Y":"N";

 $query = mysqli_query($mysql_connection,
 "UPDATE `playlist_status`
   SET `current_playlist_id` = '".$playlist_id."',
       `current_track_number` = '".$track_number."',
       `change_playlist` = '".$change."'
   WHERE `id` = 1
   LIMIT 1;
   ");
 return true;
}

function get_playlist_tracks_count($playlist_id)
{
 global $mysql_connection;

 $result = 0;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<0 ) {return $result;}

 $query = mysqli_query($mysql_connection,
 "SELECT COUNT(*) as count
  FROM `tracks`
  WHERE `playlist_id` = '".$playlist_id."'

   ");

 $row = mysqli_fetch_assoc($query);
 return $row['count'];
}

function get_playlist_tracks($playlist_id)
{
 global $mysql_connection;

 $result = array();
 $i = 0;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<0 ) {return $result;}

 $query = mysqli_query($mysql_connection,
 "SELECT *
  FROM `tracks`,`files`
  WHERE
   `tracks`.`playlist_id` = '".$playlist_id."'
  AND
   `files`.`id` = `tracks`.`file_id`

  ORDER BY `track_number`
   ");


 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function log_track($filedata)
{
 global $mysql_connection;

 $result = false;

 $filename = $filedata['filename'];
 $filename= mysqli_real_escape_string($mysql_connection,$filename);

 $file_id = intval($filedata['id']);

 $query = mysqli_query($mysql_connection,
 "INSERT INTO
  `files_log`
  (`file_id`)
  VALUES
  ('".$file_id."')
   ");
 if (!$query){return $result;}
 return increment_file_counter($filedata);
}

function increment_file_counter($filedata)
{
 global $mysql_connection;

 $result = false;

 $id = intval($filedata['id']);
 $count = intval($filedata['count'])+1;

 $query = mysqli_query($mysql_connection,
 "UPDATE `files`
   SET `count` = '".$count."'
   WHERE `id` = '".$id."'"
   );
 if (!$query){return $result;}
 return true;
}


$active_playlist_data = get_active_playlist();

$playlist_id =  $active_playlist_data['current_playlist_id'];
$track_number = $active_playlist_data['current_track_number'];
$change_playlist = $active_playlist_data['change_playlist'];

//print_r ($playlist_data);

$playlist_data = get_playlist_tracks($playlist_id);

$playlist_count = count($playlist_data);



//echo "Track_number= $track_number PCOUNT=".$playlist_count."\n";

//print_r ($playlist_data);

	if (($track_number>$playlist_count) or ($change_playlist=="Y")) // ошибка плейлиста или нужно поставить новый
	{
		$track_number =0;
		$new_track_number=1;
		$change_playlist="N";

 	    set_playlist($playlist_id,$new_track_number,$change_playlist);

        $trackdata = $playlist_data[0];
        log_track($trackdata);
        echo $trackdata['filename'];
        die();
	}
	else if ($track_number==$playlist_count ) // закончился плейлист
	{
		$track_number =0;
		$new_track_number=1;
		$change_playlist="N";

 	    set_playlist($playlist_id,$new_track_number,$change_playlist);

        $trackdata = $playlist_data[0];
        log_track($trackdata);
        echo $trackdata['filename'];
        die();
	}
	else // плейлист на закончился, нужен следующий файл
	{
		$new_track_number=$track_number+1;
		$change_playlist="N";

 	    set_playlist($playlist_id,$new_track_number,$change_playlist);

        $trackdata = $playlist_data[$track_number];
        log_track($trackdata);
        echo $trackdata['filename'];
        die();

	}



//echo "/var/www/rcp/files/music/rock/Rammstein presents/01 Rammstein - Halleluja.mp3";
?>