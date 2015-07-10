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
  case "create_backup_window";
  {    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Создание разервной копии</h2>';

     $s.= '<div class="fleft w100 pad10_0">';
     $s.= '<span >Создать резервную копию?</span>';
	 $s.=  '</div> ';


        $s.= "<h2 class='error pad10_0' id='playlist_id_error'></h2>";

        $s.= "</div>";       /**/



        $s.="<table class='defaulttable'><tr>";

        $s.="<td>
              <div class='center button' onclick='backup_create_backup_execute();'>Создать резервную копию</div>
              </td>";
        $s.="<td>
               <div class='center button' onclick='hide_modal_window()'>Отмена</div>
              </td>
              </tr>
             </table>";

        $s.="</div>";

    echo $s;

   break;  }

  case "create_backup":
  {    $result = "Создание разервной копии поставлено в очередь Cron.";
    chdir("../../tmp");
    $tmp_file_path = getcwd();
    $file=fopen($tmp_file_path."/backup-controller","w");
    if (!$file) {$result="Не удалось открыть файл задачи ".$tmp_file_path."/backup-controller";}
    if (!fwrite($file,"backup")){$result.="Не удалось записать файл задачи ".$tmp_file_path."/backup-controller";} ;
    fclose($file);

    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Создание разервной копии</h2>';

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


  case "restore_backup_window":
  {
    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Восстановление разервной копии</h2>';

     $s.= '<div class="fleft w100 pad10_0">';
     $s.= '<span >Восстановить резервную копию?</span>';
	 $s.=  '</div> ';


        $s.= "<h2 class='error pad10_0' id='playlist_id_error'></h2>";

        $s.= "</div>";       /**/



        $s.="<table class='defaulttable'><tr>";

        $s.="<td>
              <div class='center button' onclick='backup_restore_backup_execute();'>Восстановить резервную копию</div>
              </td>";
        $s.="<td>
               <div class='center button' onclick='hide_modal_window()'>Отмена</div>
              </td>
              </tr>
             </table>";

        $s.="</div>";

    echo $s;

   break;
  }

  case "restore_backup":
  {
    $fpath = $_POST['filename'];

    $filegroup = substr($fpath, strrpos($fpath, "-")+1);
    $filename = substr($fpath, 0, strrpos($fpath, "-"));

    if (in_array($filegroup, array("auto","manual")))
    {
	    if (preg_match("@[0-9]{4}\-[0-9]{2}\-[0-9]{2}\_[0-9]{2}\:[0-9]{2}\:[0-9]{2}$@i",$filename))
	    {
		    $result = "Восстановление разервной копии (".$filename."-".$filegroup.") поставлено в очередь Cron.";
		    chdir("../../tmp");
		    $tmp_file_path = getcwd();
		    $file=fopen($tmp_file_path."/backup-controller","w");
		    if (!$file) {$result="Не удалось открыть файл задачи ".$tmp_file_path."/backup-controller";}
		    if (!fwrite($file,"restore".PHP_EOL.$filegroup.PHP_EOL.$filename)){$result.="Не удалось записать файл задачи ".$tmp_file_path."/backup-controller";} ;
		    fclose($file);
		}
		else
		{		    $result = "Неверное имя файла резервной копии (".$filename."-".$filegroup.")";
		}
    }
	else
	{	    $result = "Неверное имя файла резервной копии (".$filename."-".$filegroup.")";
	}

    $s = '';

     $s.="<div class='modal_window'>
             <div class='pad10_0'>";

     $s.= '<h2 class="pad10_0">Восстановление разервной копии</h2>';

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

    echo $s;
   break;
  }


 default:
  {
  	echo "invalid request";
  }


}



?>

