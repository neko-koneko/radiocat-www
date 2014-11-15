<?php
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
require_once "../../inc/dbal_config.php";


if (!reconnect_db()){ die ('ER#nНет соединения с MySQL');}

/*******************************************************************************************************/
//session check
session_start();
require_once '../../inc/auth_check.php';

if ($_SESSION['authorized']!=='Y' or $_SESSION['userid']<=0){ echo "RELOAD"; die;}

$request = $_POST['request'];
$s = "";

switch ($request)
{
 case "config_apply_new_values":
  {
    $offset_hours = intval($_POST['offset_hours']);
    $max_forward_lookup_tracks_counter = intval($_POST['max_forward_lookup_tracks_counter']);
    $max_try_count = intval($_POST['max_try_count']);

    $result = array();
    $result['status'] ='OK';

    if ($offset_hours<0 or $offset_hours>6)
    {
            $result['status'] ='ER';
    	    $result['errors']['offset_hours']['message'] = 'Допустимы значения 0-6';
    }
    else
    {
    	$mod_result = config_edit_config_value_by_name('offset_hours',$offset_hours);
    	if (!$mod_result)
    	{    		$result['status'] ='ER';
    	    $result['errors']['offset_hours']['message'] = 'Не удалось записать в базу';    	}
    }

    if ($max_forward_lookup_tracks_counter<0 or $max_forward_lookup_tracks_counter>60)
    {
            $result['status'] ='ER';
    	    $result['errors']['max_forward_lookup_tracks_counter']['message'] = 'Допустимы значения 0-60';
    }
    else
    {
    	$mod_result = config_edit_config_value_by_name('max_forward_lookup_tracks_counter',$max_forward_lookup_tracks_counter);
    	if (!$mod_result)
    	{
    		$result['status'] ='ER';
    	    $result['errors']['max_forward_lookup_tracks_counter']['message'] = 'Не удалось записать в базу';
    	}
    }

	if ($max_try_count<=0 or $max_try_count>10)
    {
            $result['status'] ='ER';
    	    $result['errors']['max_try_count']['message'] = 'Допустимы значения 1-10';
    }
    else
    {
    	$mod_result = config_edit_config_value_by_name('max_try_count',$max_try_count);
    	if (!$mod_result)
    	{
    		$result['status'] ='ER';
    	    $result['errors']['max_try_count']['message'] = 'Не удалось записать в базу';
    	}
    }

    if ($result['status'] == 'ER')
    {
     $s = 'ER#nОшибка — неверно заполнены поля#n';
     foreach ($result['errors'] as $field_name => $error_msg)
     {
       $s .= $field_name.'#t'.$error_msg['message'].'#n';
     }
     echo $s;
     return;
    }
    else
    {    	$s = 'OK#nКонфигурация записана успешно#n';
    	echo $s;
        return;    }

    break;
  }


 default:
  {
  	echo "Неверный запрос '".$request."'";
  	echo "<br />";
  	print_r ($_POST);
  }     /**/


}


?>

