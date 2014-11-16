<?
error_reporting(E_ALL);
//error_reporting(0);

if (!headers_sent()) { header("Content-type: text/html; charset=UTF-8"); }

$BASE_PATH = $_SERVER["DOCUMENT_ROOT"]."/";

$hostname = $_SERVER['HTTP_HOST'];
$mail_host = (strpos($hostname,'www.')===0)?substr($hostname,4):$hostname;
$www_domain_name = 'www.'.$mail_host;



require_once "../../config/db_config.php";
require_once "../../config/auth_config.php";
require_once "../../config/media_config.php";
require_once "../../inc/init.php";

require_once "../../inc/dbal.php";
require_once "../../inc/media.php";
require_once "../../inc/playlist.php";
/*
require_once "../../inc/id.php";
require_once "../../inc/id3v2.php";
/**/

reconnect_db();

/*******************************************************************************************************/
//session check
session_start();
require_once('../../inc/auth_check.php');

if ($_SESSION['authorized']!=='Y' or $_SESSION['userid']<=0){ echo "RELOAD"; die;}


$request = $_POST['request'];
$s = "";


switch ($request)
{
  case "get_media_add_to_playlist_form":
  {
    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Выберите плейлист</h2>';

     $s.= '<div class="fleft w100 pad5">';

     $s.=  get_playlist_select("id='playlist_id' class='w100'",'Y');

	 $s.=  '</div> ';


        $s.= "<h2 class='error pad10_0' id='playlist_id_error'></h2>";

        $s.= "</div>";       /**/



        $s.="<table class='defaulttable'><tr><td>
             <div class='center button' onclick='media_library_add_to_playlist();'>Сохранить в плейлист</div>
             </td><td>
             <div class='center button' onclick='hide_modal_window()'>Отмена</div>
             </td></tr></table>
            </div>";

    echo $s;
  }
  break;

  case "get_media_edit_form":
  {
     $id=0;
     $file_id = intval($_POST['id']);
     if ($file_id<=0) {return;}

     $file_list = $_POST['file_list'];



     if (!is_array($file_list) or empty($file_list))
     {
        $window_title = 'Редактирование одного элемента медиатеки';
        $data=media_library_get_file_data_by_id($file_id);

        $artist = $data['artist'];
		$title = $data['title'];
		$genre = $data['genre'];
		$bpm = $data['bpm'];
		$camelot_ton = $data['camelot_ton'];

		$rating = $data['rating'];
		$add_date = date("Y-m-d", strtotime($data['add_date']));;
		$year = $data['year'];
		$context = $data['context'];
		$comment = $data['comment'];
     }
     else
     {
        $window_title = 'Редактирование нескольких ('.count($file_list).') элементов медиатеки';
        $first_entry = true;
        foreach ($file_list as $file_list_id)
        {
            $data=media_library_get_file_data_by_id($file_list_id);

	        $artist_change_flag		  =  (($artist_change_flag) 		or ( !$first_entry 	and ($artist != $data['artist']) ) ) ;
	        $title_change_flag  	  =  (($title_change_flag)  		or ( !$first_entry 	and ($title  != $data['title'])	 ) ) ;
	        $genre_change_flag 		  =  (($genre_change_flag)  		or ( !$first_entry  and ($genre  != $data['genre'])	 ) ) ;
	        $bpm_change_flag          =  (($bpm_change_flag)    		or ( !$first_entry 	and ($bpm    != $data['bpm'])	 ) ) ;

	        $camelot_ton_change_flag  =  (($camelot_ton_change_flag )   or ( !$first_entry  and ($camelot_ton  != $data['camelot_ton'])	) ) ;

	        $rating_change_flag 	  =  (($rating_change_flag   )  	or ( !$first_entry  and ($rating   != $data['rating'])	 )) ;
	        $add_date_change_flag     =  (($add_date_change_flag )  	or ( !$first_entry 	and ($add_date != date("Y-m-d", strtotime($data['add_date'])) ) )) ;
	        $year_change_flag  		  =  (($year_change_flag     )  	or ( !$first_entry  and ($year     != $data['year'])	 )	) ;
	        $context_change_flag   	  =  (($context_change_flag  )  	or ( !$first_entry  and ($context  != $data['context'])	 )	) ;

	        $comment_change_flag   	  =  (($comment_change_flag  )  	or ( !$first_entry  and ($comment  != $data['comment'])  )	) ;

	        $artist = $data['artist'];
			$title = $data['title'];
			$genre = $data['genre'];
			$bpm = $data['bpm'];
			$camelot_ton = $data['camelot_ton'];

			$rating = $data['rating'];
			$add_date = date("Y-m-d", strtotime($data['add_date']));;
			$year = $data['year'];
			$context = $data['context'];
			$comment = $data['comment'];
			$first_entry = false;
        }

        $artist = $artist_change_flag?'':$artist;
        $title = $title_change_flag?'':$title;
        $genre = $genre_change_flag?'':$genre;
        $bpm = $bpm_change_flag?'':$bpm;
        $camelot_ton = $camelot_ton_change_flag?'':$camelot_ton;

        $rating = $rating_change_flag?'':$rating;
        $add_date = $add_date_change_flag?'':$add_date;
        $year = $year_change_flag?'':$year;
        $context = $context_change_flag?'':$context;
        $comment = $comment_change_flag?'':$comment;
     }


  	 $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<input type="hidden" name="file_data['.$id.'][file_id]"           id="file_id['.$id.']"            type="text" value="'.$file_id.'">';

     //$s .= var_export ($data,true);
    // $s.= 'EX='.var_export ($_POST,true);
     $s.= '<h2 class="pad10_0">'.$window_title.'</h2>';

     $s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
			      Исполнитель
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
			        <input  class="w90p"                         		name="file_data['.$id.'][artist]"           id="artist['.$id.']"            type="text" value="'.$artist.'">
			      </div>
			  </div> ';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
			      Название
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
			     	<input class="w90p"                         	  		name="file_data['.$id.'][title]"            id="title['.$id.']"            type="text" value="'.$title.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Год выхода трека (ГГГГ)
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" 			  		name="file_data['.$id.'][year]"   			 id="date_time_last['.$id.']"   type="text" value="'.$year.'">
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Жанр
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p"                                 		name="file_data['.$id.'][genre]"            id="genre['.$id.']"            type="text" value="'.$genre.'"
					onkeydown="helper(this.id)" onfocus="this.value=\'\'; helper(this.id);"
					>
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					bpm
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p"                                  		name="file_data['.$id.'][bpm]"            id="bpm['.$id.']"            type="text" value="'.$bpm.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
				  <div class="fleft movedownonmedium movedownonsmall w30p">
					Тональность
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input  class="w90p"                         		name="file_data['.$id.'][camelot_ton]"            id="camelot_ton['.$id.']"            type="text" value="'.$camelot_ton.'">
			      </div>
			 </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Рейтинг
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input  class="w90p"              		name="file_data['.$id.'][rating]"       id="rating['.$id.']"       type="text" value="'.$rating.'">
			      </div>
			  </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Дата добавления (ГГГГ-ММ-ДД)
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="file_data['.$id.'][add_date]"  id="add_date['.$id.']"  type="text" value="'.$add_date.'">
			      </div>
 			 </div>';
		$s.= '<div class="fleft w100 pad5">
			      <div class="fleft movedownonmedium movedownonsmall w30p">
					Коментарий
			      </div>
			      <div class="fleft movedownonmedium movedownonsmall w70">
					<input class="w90p" name="file_data['.$id.'][comment]"  id="add_date['.$id.']"  type="text" value="'.$comment.'">
			      </div>
 			 </div>';


        $s.= "<h2 class='error pad10_0' id='file_data_error'></h2>";

        $s.= "</div>";       /**/

        $s.="<table class='defaulttable'><tr><td>
             <div class='center button' onclick='media_library_save()'>Cохранить</div>
             </td><td>
             <div class='center button' onclick='hide_modal_window()'>Отмена</div>
             </td></tr></table>
            </div>";

    echo $s;
    break;
  }

  case "save_file_data":
  {
     $file_data = $_POST['file_data']['0'];
     $file_list = $_POST['file_list'];

     //print_r($file_data);

     $error_flag = false;
     $error_string = '';
     if (!is_array($file_list) or empty($file_list))
     {
        if (!is_array($file_data) or empty($file_data))
        {
        $s = 'ER#nНе указан файл#n';
        echo $s;
        return;
        }
        if ($file_data['file_id']!='')
        {
         $file_id = $file_data['file_id'] ;
         $result =  media_library_save_file_data_from_edit_form($file_id,$file_data);
         if (!$result) {$error_flag=true;}
        }

     }
     else
     {
        foreach ($file_list as $file_id)
        {
           $file_data['file_id'] =  $file_id;
           $result =  media_library_save_file_data_from_edit_form($file_id,$file_data);
           if (!$result) {$error_flag=true;}
        }
     }

     if ($error_flag)
     {
       $s = 'ER#nНе удалось записать данные в базу';
     }
     else
     {
       $s = 'OK#n';
     }

    // $s.=var_export($data,true);
    echo $s;
    break;
  }

  case "media_get_filnames_list":
  {
     $fileslist = $_POST['fileslist'];
     $file_id_array = explode(',',$fileslist);
     $file_id_array = array_map ('intval', $file_id_array);
     $file_id_array = array_filter($file_id_array);

     $error_string ='';
     if (!is_array($file_id_array) or empty($file_id_array))
     {
        $s = 'ER#nНе указан файл#n';
        echo $s;
        return;
     }

     $enc_files = array();
     $context = get_last_context();

     foreach ($file_id_array as $file_id)
     {
       // echo $file_id;
          $file_data = media_library_get_file_data_by_id($file_id);
          $filename = $file_data['filename'];
       //echo $filename;

          if (!is_file($filename))
          {
           	$error_flag=true;
           	$error_string.= $file_id.'#tФайл не найден#n';
          		continue;
          }
	   $enc_files[]=base64_encode($filename);

     }
     if ($error_string!=''){
     $s = 'ER#n';
     $s.= $error_string;
     echo $s;
     return;
     }
     $s = 'OK#n';
     $s .= count($enc_files).$error_string.'#n';
     $s.=implode ("'#t'",$enc_files);
     echo $s;
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

  case "media_get_files_list":
  {
    $files = scan($config['media']['media_root_folder']);

	$context = get_last_context();

	   if (count ($files)==0)
	   {
	   	 echo 'ER#nНе найдены файлы медиатеки';
	   	 return;
	   }

    foreach ($files as $file)
    {
    $enc_files[]=base64_encode($file);
    }

    $s = 'OK#n';
    $s .= count($enc_files).'#n';
    $s .= implode ("#t",$enc_files);
    echo $s;
  	break;
  }

  case "media_update_file":
  {
    $filename = base64_decode($_POST['filename']);
    $force_update = ($_POST['force_update']=='Y')?true:false;

    if (strpos($filename, $config['media']['media_root_folder'])!==0){echo "ER#nПлохое имя файла ".htmlspecialchars($filename); return;}
    $filename_parts = explode('/',$filename);
    if (in_array('..',$filename_parts)){echo "ER#nПлохое имя файла ".htmlspecialchars($filename); return;}
    $filename_parts = explode("\\",$filename);
    if (in_array('..',$filename_parts)){echo "ER#nПлохое имя файла ".htmlspecialchars($filename); return;}
    if (!is_file($filename)){echo "ER#nПлохое имя файла ".htmlspecialchars($filename); return;}
    $context = get_last_context();

   // echo "OK#n";
    $result = media_add_file_data($filename,$force_update);
    if ($result['error'])
    {
    	echo "ER#nНе удалось обновить файл ".htmlspecialchars($filename).' '.$result['description'];
    	return;
    }

 	echo "OK#n".$result['description'];
 	if (!empty($result['tag']))
 	{
 		echo "\r\n".var_export($result['tag'],true);
 	}



    break;
  }

  case "media_update_playlists":
  {
    echo "OK#n";
    check_for_deleted_files();
    break;
  }
 default:
  {
  	echo "invalid request";
  }


}



?>

