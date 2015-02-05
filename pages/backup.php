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

echo '<script type="text/javascript" src="'.$base.'/js/backup.js"></script>';

?>



 <!-- content -->
<div id="content">
<?php


echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Резервные копии базы данных
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';



  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="backup_create_backup();" >';
       echo '<h1>Создать копию</h1>';
       echo '</div>';
  echo "</div>";

  echo '<div class="fleft three movedownonsmall movedownonmedium">';
       echo '<div class="main_plate pad10 mar10 asc pointer" style="'.$style.'" onclick="window.location.href=\''.$base.'/backup_restore\'"  >';
       echo '<h1>Восстановить с копии</h1>';
       echo '</div>';
  echo "</div>";


  echo "</div>";

  ?>
</div>
    <!-- content end -->
