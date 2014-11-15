<?php
//error_reporting(E_ALL);
error_reporting(0);

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

require_once "../../inc/id.php";
require_once "../../inc/id3v2.php";


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
  case "restart_ices_window";
  {    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Перезапуск демона</h2>';

     $s.= '<div class="fleft w100 pad10_0">';
     $s.= '<span >Перезапустить демон?</span>';
	 $s.=  '</div> ';


        $s.= "<h2 class='error pad10_0' id='playlist_id_error'></h2>";

        $s.= "</div>";       /**/



        $s.="<table class='defaulttable'><tr>";

        $s.="<td>
              <div class='center button' onclick='control_panel_restart_ices_execute();'>Перезапустить</div>
              </td>";
        $s.="<td>
               <div class='center button' onclick='hide_modal_window()'>Отмена</div>
              </td>
              </tr>
             </table>";

        $s.="</div>";

    echo $s;

   break;  }

  case "restart_ices":
  {    $result = "Перезапуск Ices Поставлен в очередь Cron. В течение минуты произойдёт перезапуск";
    chdir("../../tmp");
    $tmp_file_path = getcwd();
    $file=fopen($tmp_file_path."/ices-controller","w");
    if (!$file) {$result="Не удалось открыть файл задачи ".$tmp_file_path."/ices-controller";}
    if (!fwrite($file,"restart")){$result.="Не удалось записать файл задачи ".$tmp_file_path."/ices-controller";} ;
    fclose($file);

    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Перезапуск демона</h2>';

     $s.= '<div class="fleft w100 pad10_0">';
     $s.= '<span >'.$result.'</span>';
	 $s.=  '</div> ';


        $s.= "<h2 class='error pad10_0' id='playlist_id_error'></h2>";

        $s.= "</div>";       /**/



        $s.="<table class='defaulttable'><tr>";

       /* $s.="<td>
              <div class='center button' onclick='control_panel_restart_ices();'>Перезапустить ещё раз</div>
              </td>"; /**/
        $s.="<td>
               <div class='center button' onclick='hide_modal_window()'>ОК</div>
              </td>
              </tr>
             </table>";

        $s.="</div>";

    echo $s;   break;
  }


 default:
  {
  	echo "invalid request";
  }


}



?>

