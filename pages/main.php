<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'Главная страница';
$global_keywords = 'Панель управления';

 include("inc/head.php");
 include("inc/header.php");

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');





echo ' <!-- content -->';
echo '<div id="content">';



echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td>Панель управления
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';



  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/calendar/\'" >';
       echo '<h1>Календарь</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/weekly/\'" >';
       echo '<h1>Расписание на неделю</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/playlist/new\'" >';
       echo '<h1>Создать плейлист</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/playlist/manager\'" >';
       echo '<h1>Менеджер плейлистов</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/update/\'" >';
       echo '<h1>Обновление медиатеки</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/media_library/\'" >';
       echo '<h1>Медиатека</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/control_panel/\'" >';
       echo '<h1>Управление</h1>';
       echo '</div>';
  echo "</div>";
  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/info/\'" >';
       echo '<h1>Информация</h1>';
       echo '</div>';
  echo "</div>";
 echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/logout/\'" >';
       echo '<h1>Выход</h1>';
       echo '</div>';
  echo "</div>";


  echo "</div>";

  ?>
</div>
    <!-- content end -->
