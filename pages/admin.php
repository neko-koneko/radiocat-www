<?php
/*******************************************************************************************************/
//session check
require_once 'inc/auth_check.php';

$global_description = 'Управление пользователями';
$global_keywords = 'Панель управления';

 require_once("inc/head.php");
 require_once("inc/header.php");

/*******************************************************************************************************/
//auth form
require_once 'inc/auth_form.php';

echo '<script type="text/javascript" src="'.$base.'/js/control_panel.js"></script>';
echo '<script type="text/javascript" src="'.$base.'/js/admin.js"></script>';

require_once 'inc/admin_users_view.php';
require_once "inc/_utf_symbols.php";
?>



 <!-- content -->
<div id="content">
<?php


echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Управление пользователями
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';


?>



    <div id="admin_area">

            <table class="">
		      <tr>
		        <td><h2>Администрирование</h2></td>
		      </tr>
		    </table>

            <table class="form_table w100 asc bbsz" >
		      <tr>
		        <td ><h2>Редактировать пользователя</h2></td>
		      </tr>
              <tr>
                 <td >
                     <table>
					     <tr>
					        <td style="width: 150px;">
					         логин
					        </td>
					        <td id="current_user_id_select">
							   <?php print_admin_users_select('current_user_id','style="width: 805px;" class="select" onchange="admin_get_user_data();"',0); ?>
						    </td>
						    <td id="current_user_id_error" class="field_error"></td>
						 </tr>
					 </table>
					 <table id="user_place" style="display: none;">
					      <tr>
					        <td style="width: 150px;">
					         ФИО
					        </td>
					        <td>
							 <input type="text" autocomplete="off" id="current_name" style="width:800px">
						    </td>
						    <td id="current_name_error" class="field_error"></td>
						  </tr>
					      <tr>
					        <td>
					         Пароль
					        </td>
					        <td>
							 <input type="password" autocomplete="off" id="current_password" style="width:800px">
						    </td>
						    <td id="current_password_error" class="field_error"></td>
						  </tr>
			 		      <tr>
					        <td>
					         Пароль (ещё раз)
					        </td>
					        <td>
							 <input type="password" autocomplete="off" id="current_password_2" style="width:800px">
						    </td>
						    <td id="current_password_2_error" class="field_error"></td>
						  </tr>

						  <tr>
						     <td colspan =5>
										<table>
											  <tr>
										        <td>
					                             <div class="big_button" onclick="admin_apply_edit_user();"  title="применить новые настройки">Сохранить</div>
											    </td>
											    <td>
				                                 <div class="big_button red_button" onclick="admin_delete_user();"  title="удалить этого пользователя">Удалить</div>
										    	</td>
											    <td>

										    	</td>
											    <td id="edit_user_status" class="field_error"></td>
											  </tr>
					                    </table>
						     </td>
						  </tr>
					</table>
                 </td>
               </tr>
		    </table>

		     <table class="form_table w100" >
		      <tr>
		        <td><h2>Новый пользователь</h2></td>
		      </tr>
		      <td style="padding: 0px">
		           <table>
				      <tr>
				        <td style="width: 150px;">
				         логин
				        </td>
				        <td>
						 <input type="text" autocomplete="off" id="new_login" style="width:800px">
					    </td>
					    <td id="new_login_error" class="field_error"></td>
					  </tr>
				      <tr>
				        <td>
				         пароль
				        </td>
				        <td>
						 <input type="password" autocomplete="off" id="new_password" style="width:800px">
					    </td>
					    <td id="new_password_error" class="field_error"></td>
					  </tr>
				      <tr>
				        <td>
				         пароль (ещё раз)
				        </td>
				        <td>
						 <input type="password" autocomplete="off" id="new_password_2" style="width:800px">
					    </td>
					    <td id="new_password_2_error" class="field_error"></td>
					  </tr>
				      <tr>
				        <td>
				         ФИО
				        </td>
				        <td>
						 <input type="text" autocomplete="off" id="new_name" style="width:800px">
					    </td>
					    <td id="new_name_error" class="field_error"></td>
					  </tr>
					  <tr>
					     <td colspan =5>
									<table>
										  <tr>
									        <td>
				                                 <div class="big_button" onclick="admin_apply_new_user();"  title="создать нового пользователя">Создать</div>
										    </td>
										    <td id="new_user_status" class="field_error"></td>
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
