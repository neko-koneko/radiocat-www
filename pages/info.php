<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'Статистика';
$global_keywords = 'Статистика';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/playlist.php");
 include("inc/_utf_symbols.php");

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');

?>

 <!-- content -->
<div id="content">
<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
        	<td class="w10p pointer" onclick="window.location.href='<?php echo $base; ?>'"> <b> <?php echo $utf_symbol['HOUSE_BUILDING']; ?></b></td>
           <td>Информация
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">

<?php
print_r ($_POST);

	$unify_genres = array_keys ($_POST['unify']);
	echo "<br /> cmd=";print_r($unify_genres);

	$unified = array_values ($_POST['unified']);
	echo "<br /> unified=";print_r($unified);

     $combined_key = implode('+', $unify_genres);

     $all_genres_info = stat_get_genres();
     $active_genres_info = stat_get_active_genres();

     $active_genres_info_norm = array();
     $active_genres_count = 0;;
	 foreach ($active_genres_info as $genre_info){

        if (!empty($unify_genres) && in_array( $genre_info['genre'], $unify_genres )){
	 		  $active_genres_info_norm[$combined_key] += $genre_info['count'];
	 	}elseif(!empty($unified)){
	 		  $flag = false;
	 		  foreach ($unified as $key){
	 		  		$ugenres = explode('+',$key);
                    if (in_array($genre_info['genre'], $ugenres)){
     					$active_genres_info_norm[$key] += $genre_info['count'];
     					$flag = true;
     				}
	 		  }
	 		  if (!$flag){
				 	$active_genres_info_norm[$genre_info['genre']] = $genre_info['count'];
	 		  }

	 	}else{
		 	$active_genres_info_norm[$genre_info['genre']] = $genre_info['count'];
	 	}
     	$active_genres_count += $genre_info['count'];
	 }
    echo "<br />active genres=";print_r(    $active_genres_info_norm);

     $all_genres_info_norm = array();
     $all_genres_count = 0;;
     foreach($all_genres_info as $genre_info){

     	if (!empty($unify_genres) && in_array( $genre_info['genre'], $unify_genres )){
	 		  $all_genres_info_norm[$combined_key] += $genre_info['count'];
	 	}elseif(!empty($unified)){
              $flag = false;
              foreach ($unified as $key){
              	    $ugenres = explode('+',$key);
                    if (in_array($genre_info['genre'], $ugenres)){
     					$all_genres_info_norm[$key] += $genre_info['count'];
     					$flag = true;
     				}
	 		  }
	 		  if (!$flag){
				 	$all_genres_info_norm[$genre_info['genre']] = $genre_info['count'];
	 		  }
	 	}else{
		 	$all_genres_info_norm[$genre_info['genre']] = $genre_info['count'];
	 	}

     	$all_genres_count += $genre_info['count'];
     }
    echo "<br />active genres=";print_r(    $all_genres_info_norm);


   echo '<form method="POST" action="">';
 	if (!empty($combined_key)){
 		 $unified[] = $combined_key;
	}
    $i=0;
     foreach ($unified as $ugenre){
     	echo '<input type="hidden" name="unified['.$i.']" value = "'.$ugenre.'">';
     	$i++;
     }

     echo '<table class="form_table stat_table">';
     echo '<thead>';
     echo  '<tr>';
     echo '<th rowspan=2>Жанр</th>';
     echo '<th colspan=2 class="all">Число файлов</th>';
     echo '<th colspan=2 class="active">Поставленные в плейлист</th>';
     echo  '</tr>';

     echo  '<tr>';
     echo '<th class="all data">шт</th>';
     echo '<th class="all">%</th>';
     echo '<th class="active data">шт</th>';
     echo '<th class="active">%</th>';
     echo  '</tr>';

     echo '</thead>';
     echo '<tbody>';
     foreach ($all_genres_info_norm as $genre => $count){

        	echo  '<tr>';

        		echo '<td>'.$genre.'</td>';
        		echo '<td  class="all data">'.$count.'</td>';
        		echo '<td  class="all">'.round($count/$all_genres_count*100, 2 ).'</td>';

        		echo '<td class="active data">'.$active_genres_info_norm[$genre].'</td>';
        		echo '<td class="active">'.round($active_genres_info_norm[$genre]/$active_genres_count*100, 2).'</td>';

        		echo '<td class="diff">'.round($active_genres_info_norm[$genre]/$count*100 ,2).'</td>';

				$c = round(-$count/$all_genres_count*100 + $active_genres_info_norm[$genre]/$active_genres_count*100 ,2);
                $color = ($c>0)?'green ':'red ';
                $c = ($c>0)?'+'.$c:$c;

        		echo '<td class="diff bold '.$color.'">'.$c.'</td>';

				echo '<td >';

				echo '<input type="checkbox" name="unify['.$genre.']" >';

				echo '</td>';


	        echo '</tr>';

     }
     echo '</tbody>';
	echo '</table>';

   echo '<br /><br />';
   echo '<button class="button" type="submit" value="Submit">Объедининть выбранные</button>';
   echo '<br /><br />';
   echo '</form>';
   echo '<br /><br />';
   echo '<div class="button" onclick=" window.location.href = window.location.href;">Сброс</div>';
   echo '<br /><br />';



?>