<?php
/*******************************************************************************************************/
//session check
require_once 'inc/auth_check.php';

$global_description = 'Управление резервными копиями';
$global_keywords = 'Панель управления';

 require_once("inc/head.php");
 require_once("inc/header.php");

/*******************************************************************************************************/
//auth form
require_once 'inc/auth_form.php';

echo '<script type="text/javascript" src="'.$base.'/js/backup.js"></script>';

require_once 'inc/backup.php';
require_once "inc/_utf_symbols.php";
?>



 <!-- content -->
<div id="content">
<?php

echo '<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';

echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Управление резервными копиями
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';


?>



    <div id="admin_area">

            <table class="">
		      <tr>
		        <td><h2>Восстановить резервную копию</h2></td>
		      </tr>
		    </table>

            <table class="form_table w100 asc bbsz" >
		      <tr>
		        <td ><h2>Выбор резервной копии</h2></td>
		      </tr>
              <tr>
                 <td >
                     <table>
					     <tr>
					        <td style="width: 150px;">
					         файл
					        </td>
					        <td id="current_user_id_select">
							   <?php print_backup_file_select('current_backup_id','style="width: 805px;" class="select" onchange="backup_show_actions_form();"',0); ?>
						    </td>
						    <td id="current_user_id_error" class="field_error"></td>
						 </tr>
					 </table>
					 <table id="backup_place" style="display: none;">

						  <tr>
						     <td colspan =5>
										<table>
											  <tr>
										        <td>
					                             <div class="big_button" onclick="backup_restore_backup();"  title="восстановить резервную копию">Восстановить</div>
											    </td>
											    <td>
				                                 <div class="big_button red_button" onclick="backup_delete_backup();"  title="удалить эту копию">Удалить</div>
										    	</td>
											    <td>

										    	</td>
											    <td id="backup_restore_status" class="field_error"></td>
											  </tr>
					                    </table>
						     </td>
						  </tr>
					</table>
                 </td>
               </tr>
		    </table>
    </div>
    <!-- content end -->
