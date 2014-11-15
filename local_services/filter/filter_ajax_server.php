<?php
//error_reporting(E_ALL);
error_reporting(0);

if (!headers_sent()) { header("Content-type: text/html; charset=UTF-8"); }

$BASE_PATH = $_SERVER["DOCUMENT_ROOT"]."/";

$hostname = $_SERVER['HTTP_HOST'];
$mail_host = (strpos($hostname,'www.')===0)?substr($hostname,4):$hostname;
$www_domain_name = 'www.'.$mail_host;

require_once "../../config/db_config.php";
require_once "../../config/auth_config.php";
require_once "../../inc/init.php";

require_once "../../inc/dbal.php";
require_once "../../inc/playlist.php";
reconnect_db();

/*******************************************************************************************************/
//session check
session_start();
require_once '../../inc/auth_check.php';

if ($_SESSION['authorized']!=='Y' or $_SESSION['userid']<=0){ echo "RELOAD"; die;}


$request = $_POST['request'];
$s = "";


switch ($request)
{
  case "get_filter_form":
  {
    $id = $_POST['id'];

    echo get_filter_form($id);
    break;
  }

  case "save_playlist":
  {
     $result['error']=false;
     $result['description']='Плейлист сохранён упешно';

     $final_playlist_data = $_POST['final_playlist'];

     $playlist_id = $_POST['playlist_id'];
     $playlist_name = $_POST['playlist_name'];
     $playlist_static = $_POST['playlist_static'];

     $rules= $_POST['rule'];

     $playlist_rules ='<ruleset>';
     if (!is_array($rules) or empty($rules))
     {
          $playlist_rules .=
	        '
	        <rule id="0"
	        artist = ""
	        title = ""
	        genre = ""
	        bpm_high = ""
	        bpm_low = ""
	        camelot_ton = ""

			rating_low = ""
			rating_high = ""
			date_time_first = ""
			date_time_last = ""
			date_time_offset = ""
			context = ""
			max_tracks_count = ""
			comment = ""
			>
	        </rule>
	        ';
     }
     else
     {

	     $i=0;

	     foreach ($rules as $id => $data)
	     {

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
      		$date_time_offset = $data['date_time_offset'];
			$year = $data['year'];
			$context = $data['context'];
			$comment = $data['comment'];
			$max_tracks_count = $data['max_tracks_count'];

            $count_priority =  (isset($data['count_priority']) and  $data['count_priority']=="Y")?"Y":"N";

            //print_r ($data);

	        $filter_empty = true;
	        foreach ($data as $value) {if ($value!=''){$filter_empty = false; break;}  }
	        if ($id!=1 and $filter_empty) {continue;}

	        $playlist_rules .=
	        '
	        <rule id="'.$i.'"
	        artist = "'.$artist.'"
	        title = "'.$title.'"
	        genre = "'.$genre.'"
	        bpm_low = "'.$bpm_low.'"
	        bpm_high = "'.$bpm_high.'"
	        camelot_ton = "'.$camelot_ton.'"

			rating_low = "'.$rating_low.'"
			rating_high = "'.$rating_high.'"
			date_time_first = "'.$date_time_first.'"
			date_time_last = "'.$date_time_last.'"
			date_time_offset = "'.$date_time_offset.'"
			context = "'.$context.'"
			max_tracks_count = "'.$max_tracks_count.'"
			comment = "'.$comment.'"

			count_priority = "'.$count_priority.'"
			>
	        </rule>
	        ';

	        $i++;
	     }
     }
     $playlist_rules .='</ruleset>';

    // echo $playlist_rules;

    // echo 'debug = '.$playlist_name.' '.$playlist_static.' '.$playlist_rules;
    // die();

     $playlist_id = intval ($playlist_id);
     if ($playlist_id<=0) // add new
     {     $playlist_id = add_playlist($playlist_name,$playlist_static,$playlist_rules);

     }
     else // edit
     {      edit_playlist($playlist_id,$playlist_name,$playlist_static,$playlist_rules);
     }

     if (is_array($final_playlist_data) and (!empty($final_playlist_data)))
     {
     $re = clear_playlist_tracks($playlist_id);
     if (!$re) {$result['error']=true; $result['description']='Не удалось сохранить плейлист';}
     }



     $track_number = 1;
     foreach ($final_playlist_data as $file_id)
     {
      $re = add_playlist_track($playlist_id,$track_number,$file_id);
      if (!$re) {$result['error']=true; $result['description']='Не удалось сохранить плейлист';}
      $track_number ++;     }

    if ($result['error']==true)
    {    	echo "ER#n".$playlist_id."#n".$result['description']."#n"; return;
    }
    else
    {    	echo "OK#n".$playlist_id."#n".$result['description']."#n"; return;
    }
    break;
  }


 case "get_genre_list":
  {
    $name = $_POST['name'];
    $time = $_POST['time'];

   // if ($name == ''){echo 'NF#n'.$time.'#n'.'#n'; return;}

    $s = '';

    $genres = get_genres_list_by_name_start($name);

    if (count($genres)==0) {echo 'NF#n'.$time.'#n#n'; return;}

    $s = 'OK#n';

    $s .= $time.'#n';

    foreach ($genres as $genre_name)
    {
          	$s.= $genre_name.'#t';
    }

    $s .= '#n';
    echo $s;

    break;
  }

 default:
  {
  	echo "invalid request";
  }


}



?>

