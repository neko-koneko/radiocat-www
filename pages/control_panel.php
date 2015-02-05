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
require_once 'inc/auth_form.php';
require_once 'inc/auth_form.php';
require_once "inc/_utf_symbols.php";

echo '<script type="text/javascript" src="'.$base.'/js/control_panel.js"></script>';

?>



 <!-- content -->
<div id="content">
<?php


echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Панель управления
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';



  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="control_panel_restart_ices();" >';
       echo '<h1>Перезапуск демона Ices</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/backup/\'"  >';
       echo '<h1>Резервные копии</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/admin/\'" >';
       echo '<h1>Управление пользователями</h1>';
       echo '</div>';
  echo "</div>";

   echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/config/\'" >';
       echo '<h1>Настройки</h1>';
       echo '</div>';
  echo "</div>";

  echo "</div>";

  ?>
</div>
    <!-- content end -->
