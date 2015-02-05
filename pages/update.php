<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

error_reporting(E_ALL);
$global_description = 'шаблон';
$global_keywords = 'шаблон';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/media.php");
 include("inc/_utf_symbols.php");

 include("config/media_config.php");

echo '<script type="text/javascript" src="'.$base.'/js/media_library.js"></script>';

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');
?>



 <!-- content -->
<div id="content">

<?php

 echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b> </td>
           <td>Обновление медиатеки
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';

	chdir($config['media']['media_root_folder']);
	echo '<h2>Директория медиатеки: '.$config['media']['media_root_folder'].'</h2>';
	echo "<br /><br />";

	$files = scan($config['media']['media_root_folder']);

	$context = get_last_context();

   if (count ($files)==0)
   {
   	echo "<h1>Не найдены файлы медиатеки</h1>";
   	return;
   }

    foreach ($files as $file)
    {
    $enc_files[]=base64_encode($file);
    }

    echo "<script> ";
    echo " var media_update_files=[";
    echo "'".implode ("','",$enc_files)."'";
    echo "] ";
    echo "</script> ";

    echo "<div id='progress_bar' class='progress_bar'>";
    echo "<div id='progress_bar_message' class='progress_bar_message'> 0/".count($files)."</div>";
    echo "<div id='progress_bar_done' class='progress_bar_done'></div>";
    echo "</div>";

    echo "<div id = 'done_file_list'></div>";
  /* echo '<table class="media_table">';
    echo '<tr>
           <th>info</th>
           <th>Исполнитель</th>
           <th>Название трека</th>
           <th>Год выпуска</th>
           <th>Жанр</th>
           <th>Размер</th>
           <th>Длительность</th>
           <th>Битрейт</th>
           <th>bpm</th>
           <th>Комментарий</th>
           <th>Дата модификации</th>
         </tr>';      /**/

/*	foreach ($files as $filename)
	{
    //echo $filename;
    $tag_data=get_mp3_tags_and_info($filename);

    $title = ($tag_data['Title']=='')?$tag_data['NAME']:$tag_data['Title'];
    $artist =($tag_data['Artist']=='')?$tag_data['ARTISTS']:$tag_data['Artist'];
    $year =($tag_data['Year']=='')?$tag_data['YEAR']:$tag_data['Year'];
    $genre =($tag_data['Genre']=='')?get_v1_genre_name($tag_data['GENRENO']):$tag_data['Genre'];
    $bitrate =$tag_data['bitrate'];
    $bpm =$tag_data['bpm'];
    $comment =($tag_data['Comment']=='')?$tag_data['COMMENT']:$tag_data['Comment'];

    $tag_data['filename'] = $filename;
    echo '<tr>';

	    echo '<td>';
        echo '<!--'; print_r($tag_data); echo '-->';
   	    echo '</td><td>';
	    echo $artist;
   	    echo '</td><td>';
	    echo $title;
   	    echo '</td><td>';
   	    echo $year;
   	    echo '</td><td>';
   	    echo $genre;
   	    echo '</td><td>';
        echo get_cute_file_size(filesize($filename));
	    echo '</td><td>';
        echo $tag_data['lengthh'];
	    echo '</td><td>';
	    echo  $bitrate;
	    echo '</td><td>';
        echo $bpm;
	    echo '</td><td>';
	    echo $comment;
	    echo '</td><td>';
        echo date ("d-m-Y H:i:s.", filemtime($filename));
	    echo '</td><td>';



	    echo '</td><td>';
	    echo '</td>';

    echo '</tr>';


     echo 'file '.$tag_data['filename'].' '.filesize($tag_data['filename']).'<br />';

     media_add_file_data($tag_data,$context+1);
	}
    echo '</table>';

    echo "</div>";

	echo "<br /><br />";

	echo "<div style='background:#fea;'>";
	echo "<h2>Автокоррекция плейлистов</h2>";
	check_for_deleted_files();
	echo "</div>";
                       /**/

	echo "<br /><br />";
    echo "<div>";

	    echo '<div class="job_plate pad10  asc pointer" onclick="media_library_update_files_start(0)">';
		 echo '<table class="">
		            <tr class="pointer">
		              <td class="job_time"><b>[U]</b></td>
		              <td class="job_name" >Начать обновление</td>
		            </tr>
		            </table>
		            ';
		echo '</div>' ;

	    echo '<div class="job_plate pad10  asc pointer" onclick="media_library_update_playlists()">';
		 echo '<table class="">
		            <tr class="pointer">
		              <td class="job_time"><b>[?]</b></td>
		              <td class="job_name" >Проверка плейлистов/Расписания</td>
		            </tr>
		            </table>
		            ';
		echo '</div>' ;

	    echo '<div class="job_plate pad10  asc pointer" onclick="window.location.href=\''.$base.'/media_library\'">';
		 echo '<table class="">
		            <tr class="pointer">
		              <td class="job_time"><b>[>]</b></td>
		              <td class="job_name" >Перейти в медиатеку</td>
		            </tr>
		            </table>
		            ';
		echo '</div>' ;

    echo "</div>";

?>

</div>
    <!-- content end -->









