<?php
/*******************************************************************************************************/
//session check
require_once 'inc/auth_check.php';

$global_description = 'Настройки';
$global_keywords = 'Панель управления';

 require_once("inc/head.php");
 require_once("inc/header.php");

/*******************************************************************************************************/
//auth form
require_once 'inc/auth_form.php';

echo '<script type="text/javascript" src="'.$base.'/js/config.js"></script>';

require_once 'inc/admin_users_view.php';
require_once "inc/_utf_symbols.php";

require_once "inc/dbal_config.php";

$db_config = array();
$raw_db_config=config_get_all_config();

foreach ($raw_db_config as $config_item)
{
 $db_config[$config_item['name']] = $config_item['value'];
}


?>



 <!-- content -->
<div id="content">
<?php


echo '<div class="calendar_nav">
      <table class="w100 debug ctable calendar_nav">
        <tr>
           <td class="w10p pointer" onclick="window.location.href=\''.$base.'/\'"> <b>'.$utf_symbol['HOUSE_BUILDING'].'</b></td>
           <td>Настройки
           </td>
        </tr>
      </table>
   </div>

   <div class="pad20">';


?>



    <div id="admin_area">

            <table class="">
		      <tr>
		        <td><h2>Настройки</h2></td>
		      </tr>
		    </table>

            <table class="form_table w100 asc bbsz" >
		      <tr>
		        <td ><h2>Настройки задач CRON (автогенерации плейлистов)</h2></td>
		      </tr>
              <tr>
                 <td >
					 <table id="cron_place" class="w100">
					      <tr>
					        <td style="width:30%">
                              При построени плейлиста<br />
                              Просматривать воспроизведённые треки
                              назад на заданное число <b>часов</b>
					        </td>
					        <td>
							 <input type="text" autocomplete="off" id="offset_hours" style="width:40px"
							  value="<?php echo $db_config['offset_hours']; ?>">
						    </td>
						    <td id="offset_hours_error" class="field_error"></td>
						  </tr>
					      <tr>
					        <td >
                              При построени плейлиста<br />
                              Просматривать отобранные треки
                              вперёд на заданное число треков
					        </td>
					        <td>
							 <input type="text" autocomplete="off" id="max_forward_lookup_tracks_counter" style="width:40px"
							 value="<?php echo $db_config['max_forward_lookup_tracks_counter']; ?>">
						    </td>
						    <td id="max_forward_lookup_tracks_counter_error" class="field_error"></td>
						  </tr>
					      <tr>
					        <td >
                              При построени плейлиста<br />
                              Максимальное число попыток
					        </td>
					        <td>
							 <input type="text" autocomplete="off" id="max_try_count" style="width:40px"
							  value="<?php echo $db_config['max_try_count']; ?>">
						    </td>
						    <td id="max_try_count_error" class="field_error"></td>
						  </tr>
						  <tr>
						  	<td colspan=5>
  						  			<table>
										  <tr>
									        <td>
				                                 <div class="big_button" onclick="config_apply_new_values();"  title="сохранить">Сохранить</div>
										    </td>
										    <td id="config_apply_new_values" class="field_error"></td>
										  </tr>
				                    </table>
				            </td>
				          <tr>

					</table>
                 </td>
               </tr>
		    </table>




    </div>




    <!-- content end -->
