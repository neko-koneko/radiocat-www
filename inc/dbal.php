<?
$mysqli_connection = false;
// соединяемся с базой на основном поддомене
function reconnect_db()
{
global $config;
global $mysqli_connection;

  $mysqli_connection = mysqli_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'],$config['mysql']['database']);
  mysqli_set_charset($mysqli_connection, "utf8");
  mysqli_query($mysqli_connection,"SET timezone = 'GMT'");
  return $mysqli_connection;
}

function last_insert_id()
{
  global $mysqli_connection;
  return mysqli_insert_id($mysqli_connection);
}

function get_active_playlist()
{
 global $mysqli_connection;

 $query = mysqli_query($mysqli_connection,"SELECT *
 FROM `playlist_status` ");

 if(!$query) {return false;}

 $row = mysqli_fetch_assoc($query);
 return $row;
}

function set_active_playlist($playlist_id,$track_number=1,$change_playlist='N')
{
 global $mysqli_connection;

 $result = false;
 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}
 $track_number = intval($track_number);
 if ( $track_number<0 ) {return $result;}
 $change_playlist = ($change_playlist=='Y')?"Y":"N";

 $qry = "UPDATE `playlist_status`
  SET `current_playlist_id` = '".$playlist_id."', ";

  if ($track_number!=0) $qry .= "`current_track_number` = '".$track_number."', ";

  $qry .= "`change_playlist` = '".$change_playlist."'
  WHERE `id` = '1'
  ";

 $query = mysqli_query($mysqli_connection,$qry);

 if(!$query) {return false;}
 return true;
}


function get_all_playlists()
{
 global $mysqli_connection;

 $result=array();

 $qry = "SELECT *
  FROM `playlists`
  WHERE 1=1
   ";
 $query = mysqli_query($mysqli_connection,$qry);


 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}


function get_playlist($playlist_id)
{
 global $mysqli_connection;

 $result = false;
 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "SELECT * FROM `playlists`
  WHERE `playlists`.`id`='".$playlist_id."'"
  );
 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);
 return $result;
}

function is_playlist_static($playlist_id)
{
 global $mysqli_connection;

 $result = false;
 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "SELECT `static` FROM `playlists`
  WHERE `playlists`.`id`='".$playlist_id."'"
  );
 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);
 return ($result['static']=='Y');
}


function get_playlist_tracks($playlist_id,$sort_parameter='',$sort_order='')
{
 global $mysqli_connection;

 $parameters=array('id','artist','title','year','genre','size','length','bpm','camelot_ton','rating','date','add_date');

 $result = false;
 $i = 0;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 if ($sort_parameter =='' or !in_array($sort_parameter,$parameters)) {$sort_parameter = 'track_number';}

 $sort_order=($sort_order=='up')?'ASC':'DESC';

 $qry = "SELECT *
  FROM `tracks`,`files`
  WHERE
   `tracks`.`playlist_id` = '".$playlist_id."'
  AND
   `files`.`id` = `tracks`.`file_id`

  ORDER BY ".$sort_parameter." ".$sort_order."
   ";

 $query = mysqli_query($mysqli_connection,$qry);

 $result=array();
 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function delete_playlist($playlist_id)
{
 global $mysqli_connection;

 $result = false;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "DELETE FROM `playlists`
  WHERE `playlists`.`id`=".$playlist_id."
  "
  );
 if(!$query) {return $result;}
 return true;
}

function add_playlist($playlist_name,$playlist_static,$playlist_rules)
{
 global $mysqli_connection;

 $result = false;

 $playlist_name = mysqli_real_escape_string($mysqli_connection,$playlist_name);
 $playlist_rules =  mysqli_real_escape_string($mysqli_connection,$playlist_rules);
 $playlist_static = ($playlist_static=="Y")?"Y":"N";

 $qry = "INSERT INTO `playlists`
  (`name`,`static`,`rules`)
   VALUES ('".$playlist_name."','".$playlist_static."','".$playlist_rules."');
  ";

 $query = mysqli_query($mysqli_connection,$qry);

 if(!$query) {return $result;}
 return last_insert_id();
}

function edit_playlist($playlist_id,$playlist_name,$playlist_static,$playlist_rules)
{
 global $mysqli_connection;

 $result = false;
 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $playlist_name = mysqli_real_escape_string($mysqli_connection,$playlist_name);
 $playlist_rules =  mysqli_real_escape_string($mysqli_connection,$playlist_rules);
 $playlist_static = ($playlist_static=="Y")?"Y":"N";

 $playlist_data = get_playlist($playlist_id);
 if (empty($playlist_data))
 {
	 $qry = "INSERT INTO `playlists`
	  (`id`,`name`,`static`,`rules`)
	   VALUES ('".$playlist_id."','".$playlist_name."','".$playlist_static."','".$playlist_rules."');
	  ";
 }
 else
 {
     $qry = "UPDATE `playlists`
	  SET `name` = '".$playlist_name."',
	      `static` = '".$playlist_static."',
	      `rules` = '".$playlist_rules."'
	  WHERE `id` = '".$playlist_id."'
	  ";
 }
 $query = mysqli_query($mysqli_connection,$qry);
 if(!$query) {return $result;}
 return true;
}


function get_playlist_track($playlist_id,$track_number)
{
 global $mysqli_connection;

 $result = false;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}
 $track_number = intval($track_number);
 if ( $track_number<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "SELECT *
  FROM `tracks`,`files`
  WHERE `tracks`.`playlist_id` = '".$playlist_id."'
  AND   `tracks`.`track_number` = '".$track_number."'
  AND   `files`.`id` = `tracks`.`file_id`
   ");
 if(!$query) {return $result;}

 $row = mysqli_fetch_assoc($query);
 return $row;
}

function clear_playlist_tracks($playlist_id)
{
 global $mysqli_connection;

 $result = false;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "DELETE FROM `tracks`
  WHERE `tracks`.`playlist_id`=".$playlist_id."
  "
  );

 if(!$query) {return $result;}
 return true;
}

function add_playlist_track($playlist_id,$track_number,$file_id)
{
 global $mysqli_connection;

 $result = false;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}
 $track_number = intval($track_number);
 if ( $track_number<=0 ) {return $result;}
 $file_id = intval($file_id);
 if ( $file_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "INSERT INTO `tracks`
  (`playlist_id`, `track_number`, `file_id`)
   VALUES
  ('".$playlist_id."','".$track_number."','".$file_id."');
  "
  );

 if(!$query) {return $result;}
 return true;
}

function get_playlist_tracks_count($playlist_id)
{
 global $mysqli_connection;

 $result = false;

 $playlist_id = intval($playlist_id);
 if ( $playlist_id<=0 ) {return $result;}

 $query = mysqli_query($mysqli_connection,
 "SELECT COUNT(*) as count
  FROM `tracks`
  WHERE `playlist_id` = '".$playlist_id."'
   ");
 if(!$query) {return $result;}

 $row = mysqli_fetch_assoc($query);
 return $row['count'];
}


function get_genres_list_by_name_start($name)
{
 global $mysqli_connection;

 $result = false;
 $name = mysqli_real_escape_string ($mysqli_connection,$name);

 $qry ="SELECT DISTINCT genre
  FROM `files`
  WHERE `genre` LIKE '$name%'
  ORDER BY genre ASC";

 $query = mysqli_query($mysqli_connection,$qry);
 if(!$query) {return $result;}

 $result = array();
 $i=0;
 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row['genre'];
       $i++;
      }
return ($result);
}

function get_tracks_by_filter($filter_data, $order_by='',$order_type='')
{
	global $mysqli_connection;

    $order_by = mysqli_real_escape_string($mysqli_connection,$order_by);

	$rating_low = $filter_data['rating_low'];
	$rating_high = $filter_data['rating_high'];

	$date_time_first = $filter_data['date_time_first'];
	$date_time_last = $filter_data['date_time_last'];
	$context = $filter_data['context'];
    $size =$filter_data['size'];

    $artist =$filter_data['artist'];
    $title  = $filter_data['title'];

    $genre =$filter_data['genre'];
    $year =$filter_data['year'];

    $bpm_low  =$filter_data['bpm_low'];
    $bpm_high =$filter_data['bpm_high'];

    $camelot_ton =$filter_data['camelot_ton'];
    $bitrate =$filter_data['bitrate'];

    $limit = $filter_data['max_tracks_count'];
    $date_time_offset = $filter_data['date_time_offset'];

    $comment =$filter_data['comment'];

	$rating_low  = intval($rating_low);   $rating_low = ($rating_low>=0)?$rating_low:0;
	$rating_high = intval($rating_high); $rating_high = ($rating_high>=0)?$rating_high:0;

	$date_first = explode('-',$date_time_first,3);
	$date_first_str = sprintf("%04d-%02d-%02d 00:00:00", $date_first[0], $date_first[1], $date_first[2]);
	$date_last = explode('-',$date_time_last,3);
	$date_last_str = sprintf("%04d-%02d-%02d 00:00:00", $date_last[0], $date_last[1], $date_last[2]);
    $context = intval($context); $context = ($context>0)?$context:0;
    $size = intval($size); $size = ($size >0)?$size:0;

	$artist = mysqli_real_escape_string($mysqli_connection,$artist);
	$title = mysqli_real_escape_string($mysqli_connection,$title);

	$genre = mysqli_real_escape_string($mysqli_connection,$genre);
	$year = intval($year); $year=($year >0)?$year:0;

	$bpm_low = intval($bpm_low);
	$bpm_high = intval($bpm_high);
	$camelot_ton = mysqli_real_escape_string($mysqli_connection,$camelot_ton);
	$bitrate = intval($bitrate); $bitrate=($bitrate >0)?$bitrate:0;

    $limit =  intval($limit);
    $date_time_offset = intval($date_time_offset); $date_time_offset=($date_time_offset<=0)?0:$date_time_offset;

	$comment = mysqli_real_escape_string($mysqli_connection,$comment);

  $end_time = mktime(0, 0, -1, date("m"), date("d")+1, date("Y"));



 $result = array();
 $i = 0;

 $qry = "SELECT *
		FROM `files`
		WHERE
         ";


 $flag = false;
 if ($genre !='')
 {
 $genre = mysqli_real_escape_string($mysqli_connection,$genre);
 $qry .= " `genre`='$genre' ";
 $flag = true;
 }


 if ($rating_low==0 and $rating_high==0)
 {

 }
 else
 {

	 if ($rating_low==0){$rating_low = 1;}
	 if ($rating_high==0){$rating_high = 5;}

	 if ($rating_high>=$rating_low and $rating_low>0)
	 {
	    if ($flag)     {$qry .= " AND ";}

	 	$qry .= " (`rating`>='$rating_low' AND `rating`<='$rating_high') ";
	    $flag = true;

	 }
	 else
	 {
	    $rating = $rating_high;
	 	if ($rating>0)
	 	{
	    if ($flag)     {$qry .= " AND ";}

	 	$qry .= " `rating`='$rating' ";
	    $flag = true;
	 	}
	 }
 }

//file add date
	if ($date_time_offset>0)
	{
	$low_date = $end_time - $date_time_offset*3600*24;
	$low_date_str = sprintf("%04d-%02d-%02d 00:00:00",date("Y",$low_date),date("m",$low_date),date("d",$low_date));
	 	{
	    if ($flag)     {$qry .= " AND ";}

	 	$qry .= "  `add_date` > STR_TO_DATE('$low_date_str', '%Y-%m-%d %H:%i:%s') ";
	    $flag = true;
	 	}

	}
	else
	{
	if ($date_first_str!='0000-00-00 00:00:00')
	 	{
		 	if ($date_last_str!='0000-00-00 00:00:00')
		 	{
			    if ($flag)     {$qry .= " AND ";}

			 	$qry .= "  `add_date` BETWEEN STR_TO_DATE('$date_first_str', '%Y-%m-%d %H:%i:%s')
			             AND STR_TO_DATE('$date_last_str', '%Y-%m-%d %H:%i:%s')";
			    $flag = true;
			}
			else
			{
			    $now_time_str = date("Y-m-d H:i:s") ;

			    if ($flag)     {$qry .= " AND ";}

			 	$qry .= "  `add_date` BETWEEN STR_TO_DATE('$date_first_str', '%Y-%m-%d %H:%i:%s')
			             AND STR_TO_DATE('$now_time_str', '%Y-%m-%d %H:%i:%s')";
			    $flag = true;
			}
	 	}
	    else
	    {
			if ($date_last_str!='0000-00-00 00:00:00')
		 	{
			    if ($flag)     {$qry .= " AND ";}

			 	$qry .= "  `add_date` BETWEEN STR_TO_DATE('0000-00-00', '%Y-%m-%d %H:%i:%s')
			             AND STR_TO_DATE('$date_last_str', '%Y-%m-%d %H:%i:%s')";
			    $flag = true;
			}
	    }

	}


//context
if ($context>0)
 	{
    if ($flag)     {$qry .= " AND ";}

 	$qry .= " `context`='$context' ";
    $flag = true;
 	}


//$size
if ($size!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `size`='$size' ";
    $flag = true;
 	}


if ($title!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `title`  LIKE '%$title%' ";
    $flag = true;
 	}

if ($artist!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `artist` LIKE '%$artist%' ";
    $flag = true;
 	}

if ($year!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `year`='$year' ";
    $flag = true;
 	}

//$genre
if ($genre!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `genre`='$genre' ";
    $flag = true;
 	}

//$bpm
if ($bpm_low>0)
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `bpm`>='$bpm_low' ";
    $flag = true;
 	}
if ($bpm_high>0)
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `bpm`<='$bpm_high' ";
    $flag = true;
 	}

//$camelot_ton
if ($camelot_ton!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `camelot_ton`='$camelot_ton' ";
    $flag = true;
 	}

if ($comment!='')
 	{
    if ($flag)     {$qry .= " AND ";}
 	$qry .= " `comment` LIKE '%$comment%' ";
    $flag = true;
 	}



  /**/

  if (!$flag)  $qry .= "  1 ";


  if ($order_by!='')
  {
	    if  ($order_type=='up') { $qry .= ' ORDER BY '.$order_by.' ASC';}
	    else {$qry .= ' ORDER BY '.$order_by.' DESC';}
  }

 /*if ($limit>0)
 {
 	$qry .=" LIMIT ".$limit." ";
 }/**/

  echo '<br />'.$qry.'<br />';

 $query = mysqli_query($mysqli_connection, $qry);

 $i=0;
 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function get_last_played_tracks_files($time_offset_hours)
{
global $mysqli_connection;

 $result = false;
 $time_str = date("Y-m-d H:i:s",  mktime(date('H')-$time_offset_hours,date('i'),date('s'),date('m'),date('d'),date('Y')) );

 $qry ="SELECT *
  FROM `files_log`
  WHERE `time` >= STR_TO_DATE('$time_str', '%Y-%m-%d %H:%i:%s')
  ";

 // echo $qry;

 $query = mysqli_query($mysqli_connection,$qry);
 if(!$query) {return $result;}

 $i=0;
 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
 return ($result);
}



function cron_get_job($job_id)
{
 global $mysqli_connection;

 $result = array();
 $job_id = intval($job_id);
 if ($job_id<=0){return false;}

 $query = mysqli_query($mysqli_connection,
 "SELECT *
  FROM `cron_jobs`
  WHERE
   `id` = '".$job_id."'
   ");

 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);

 return $result;
}


function cron_update_job($job_id,$job_result,$job_done="Y")
{
 global $mysqli_connection;

 $result = false;
 $job_id = intval($job_id);
 if ( $job_id<0 ) {return $result;}

 $job_result = mysqli_real_escape_string($mysqli_connection, $job_result);
 $job_done = $job_done=="N"?"N":"Y";

 $qry = "UPDATE `cron_jobs`
  SET `done` = '".$job_done."',
      `result` = '".$job_result."'
  WHERE `id` = '".$job_id."'
  ";

 $query = mysqli_query($mysqli_connection,$qry);

 if(!$query) {return false;}
 return true;
}


function cron_edit_job($job_id,$playlist_id,$timestamp,$repeat_weekly="N",$job_done="N")
{
 global $mysqli_connection;

 $result = false;
 $job_id = intval($job_id); if ( $job_id<0 ) {return $result;}
 $playlist_id = intval($playlist_id); if ( $playlist_id<0 ) {return $result;}
 $time_str = date("Y-m-d H:i:s",$timestamp);
 $time_plus_week = strtotime("+1 week",$timestamp);
 $time_plus_week_str = date("Y-m-d H:i:s",$time_plus_week);

 $repeat_weekly = ($repeat_weekly=="Y")?"Y":"N";
 $job_done = $job_done=="Y"?"Y":"N";

 $old_job = cron_get_job($job_id);

 $old_time_str = $old_job['time'];
 $old_time = datetime_to_timestamp($old_time_str);
 $old_time_plus_week = strtotime("+1 week",$old_time);
 $old_time_plus_week_str = date("Y-m-d H:i:s",$old_time_plus_week);


 $query = mysqli_query($mysqli_connection,
  "UPDATE `cron_jobs`
  SET `playlist_id` = '".$playlist_id."',
      `time` = '".$time_str."',
      `repeat_weekly` = '".$repeat_weekly."',
      `done`  = '".$job_done."'
  WHERE `time` = '".$old_time_str."'
  "
  );

 if(!$query) {return false;}

 $query = mysqli_query($mysqli_connection,
  "UPDATE `cron_jobs`
  SET `playlist_id` = '".$playlist_id."',
      `time` = '".$time_plus_week_str."',
      `repeat_weekly` = '".$repeat_weekly."',
      `done`  = '".$job_done."'
  WHERE `time` = '".$old_time_plus_week_str."'
  "
  );

 if(!$query) {return false;}

 //return true;
 return true;
}

function cron_edit_job_fix_future_jobs_by_playlist_id($playlist_id,$timestamp)
{
 global $mysqli_connection;

 $playlist_id = intval($playlist_id);
 if ($playlist_id<=0) {return false;}
 $time = date("Y-m-d H:i:s",$timestamp);
 $now = date("Y-m-d H:i:s",time());


 $query = mysqli_query($mysqli_connection,
 "UPDATE `cron_jobs`
  SET `time` = '".$time."'
  WHERE `playlist_id` = '".$playlist_id."'
  AND
   `time`>='".$now."'
  "
  );

 if(!$query) {return false;}
 return true;
}

function cron_get_jobs()
{
 global $mysqli_connection;

 $result = array();
 $i = 0;

 $query = mysqli_query($mysqli_connection,
 "SELECT *
  FROM `cron_jobs`
  ORDER BY `time`
   ");


 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function cron_get_active_jobs()
{
 global $mysqli_connection;

 $result = array();
 $i = 0;

 $query = mysqli_query($mysqli_connection,
 "SELECT *
  FROM `cron_jobs`
  WHERE
   `done` = 'N'
  ORDER BY `time`
   ");


 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}



function cron_get_job_by_time_interval($min,$max)
{
 global $mysqli_connection;

 $result = array();
 $i = 0;

 $min = intval($min);
 $max = intval($max);

 if ($min>$max){return $result;}
 if ($min==0 or $max==0) {return $result;}


 $min=date("Y-m-d H:i:s",$min);
 $max=date("Y-m-d H:i:s",$max);

 $qry="SELECT *
  FROM `cron_jobs`
  WHERE
    `time`<='$max'
    AND
    `time`>='$min'

  ORDER BY `time`
   ";

$query = mysqli_query($mysqli_connection, $qry);

 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function cron_add_job($playlist_id,$timestamp,$repeat_weekly="N")
{
 global $mysqli_connection;

 $playlist_id = intval($playlist_id);
 if ($playlist_id<=0) {return false;}

 $time = date("Y-m-d H:i:s",$timestamp);

 $repeat_weekly = ($repeat_weekly=="Y")?"Y":"N";

 $query = mysqli_query($mysqli_connection,
 "INSERT INTO
  `cron_jobs`
   ( `playlist_id`, `time`, `repeat_weekly`)
   VALUES
   ('".$playlist_id."', '".$time."', '".$repeat_weekly."');
   ");       /**/

 if(!$query) {return false;}
 return true;
}

function cron_del_job($job_id)
{
 global $mysqli_connection;

 $job_id = intval($job_id);
 if ($job_id<=0) {return false;}

 $query = mysqli_query($mysqli_connection,
 "DELETE FROM
  `cron_jobs`
  WHERE
   `id` = '".$job_id."'
   ");       /**/

 if(!$query) {return false;}
 return true;
}

function cron_del_job_by_playlist_id($playlist_id)
{
 global $mysqli_connection;

 $playlist_id = intval($playlist_id);
 if ($playlist_id<=0) {return false;}

 $query = mysqli_query($mysqli_connection,
 "DELETE FROM
  `cron_jobs`
  WHERE
   `playlist_id` = '".$playlist_id."'
   ");       /**/

 if(!$query) {return false;}
 return true;
}

function media_library_get_file_data_by_id($id)
{
 global $mysqli_connection;

 $id = intval($id);
 if ($id<=0) {return false;}

 $result = array();

 $query = mysqli_query($mysqli_connection,
 "SELECT  *
  FROM `files`
  WHERE
   `id` = '".$id."'
   ");

 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);

 return $result;
}

function media_library_get_file_data_by_filename($filename)
{
 global $mysqli_connection;

 $filename = mysqli_real_escape_string($mysqli_connection,$filename);

 $result = array();

 $query = mysqli_query($mysqli_connection,
 "SELECT  *
  FROM `files`
  WHERE
   `filename` = '".$filename."'
   ");

 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);

 return $result;
}

function media_library_save_file_data_from_edit_form($file_id,$data)
{
 global $mysqli_connection;

//print_r($data);
 $media_update_date = date("Y-m-d");

 $file_id = intval($file_id);
 if ($file_id<0){return false;}

 $artist = $data['artist'];
 $title = $data['title'];
 $year = $data['year'];
 $genre = $data['genre'];

 $bpm = $data['bpm'];
 $camelot_ton = $data['camelot_ton'];
 $rating = $data['rating'];
 $add_date = $data['add_date'];
 $comment = $data['comment'];

  $artist = mysqli_real_escape_string($mysqli_connection, $artist);
  $title = mysqli_real_escape_string($mysqli_connection, $title);
  $year = intval($year);
  $year = ($year >0)?$year:'';
  $genre = mysqli_real_escape_string($mysqli_connection, $genre);

  $bpm = intval($bpm);
  $bpm = ($bpm >0)?$bpm:'';
  $camelot_ton = mysqli_real_escape_string($mysqli_connection, $camelot_ton);
  $rating = intval($rating);
  $rating = ($rating<0 or $rating>5)?"":$rating;
  $add_date_parts = explode('-',$add_date);
  $add_date_str = sprintf("%04d-%02d-%02d", $add_date_parts[0], $add_date_parts[1], $add_date_parts[2]);

  $comment = mysqli_real_escape_string($mysqli_connection, $comment);

  $qry=" UPDATE `files`
     SET 	".
     (($artist!='')?"`artist` = '".$artist."', ":"").
     (($title!='')?"`title` = '".$title."', ":"").
     (($year!='')?"`year` = '".$year."', ":"").
     (($genre!='')?"`genre` = '".$genre."', ":"").
     (($bpm!='')?"`bpm` = '".$bpm."', ":"").
     (($camelot_ton!='')?"`camelot_ton` = '".$camelot_ton."', ":"").
     (($add_date!='')?"`add_date` = '".$add_date_str."', ":"").
     (($rating!='')?"`rating` = '".$rating."', ":"").
     (($comment!='')?"`comment` = '".$comment."', ":"").
     ("`date` = '".$media_update_date."' ").

    " WHERE `files`.`id` = '".$file_id."'
    ";

  $query = mysqli_query($mysqli_connection, $qry);

  //echo $qry;

  if(!$query) {return false;}
  else {return true;}
}


?>