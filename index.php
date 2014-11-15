<?php
session_start();
error_reporting(E_NONE);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
header("Expires: Mon, 23 May 1995 02:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must_revalidate");
header("Pragma: no-cache;");
header("Strict-Transport-Security: max-age=15768000");
//header("Content-Security-Policy: default-src 'self'; script-src 'unsafe-inline'; style-src 'unsafe-inline';"); // FF 23+ Chrome 25+ Safari 7+ Opera 19+
//header("X-Content-Security-Policy: default-src 'self'; script-src 'unsafe-inline'; style-src 'unsafe-inline' "); // IE 10+
header("X-Frame-Options: SAMEORIGIN");

require_once("config/db_config.php");
require_once("config/auth_config.php");
require_once("inc/init.php");

require_once("inc/utils.php");   // полезные утилиты
require_once("inc/dbal.php");    // Database Abstraction Layer
require_once("inc/auth.php");


if (reconnect_db() == false)
{
	include("503.php"); die;
}

$query = "SELECT `key`, `value` FROM `config`;";
$result = mysqli_query($mysqli_connection,$query);
while($fetch = mysqli_fetch_array($result)){
	$key = $fetch['key'];
	$value= $fetch['value'];
	eval("$".$key." = \"".$value."\";");
}


if (!empty( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
{$server_protocol='https://';}
else
{$server_protocol='http://';}
$server_name = rtrim($_SERVER['SERVER_NAME'], '/');
$base = $server_protocol.$server_name;

$server_port = $_SERVER['SERVER_PORT'];
if ($server_port!='80' and $server_port!='443'){$base .= ":".$server_port;}

$base .= (dirname($_SERVER['PHP_SELF']) != "\\") ? dirname($_SERVER['PHP_SELF']) : "";



$main_request  = substr( $_SERVER['REQUEST_URI'],1);
$base_url_components=parse_url($base);
$base_url_path = substr( $base_url_components['path'],1);

//echo 'mr='.$main_request.' base_url='.$base_url_path;

if (strpos($main_request,$base_url_path)===0)
{
  $main_request = substr($main_request,strlen($base_url_path) );
}



$main_request_array = explode('/',$main_request,10); // лимит на обработку вложенности
if (end($main_request_array) == ''){array_pop($main_request_array);} // убрать пустое из конца (для запроса оканчивающегося на '/')
if ($main_request_array[0] == ''){array_shift($main_request_array);} // убрать пустое из конца (для запроса оканчивающегося на '/')
//print_r($main_request_array);

$page_include_path = $main_request_array[0];

if ($page_include_path == '')
 {
  $page_include_path = 'pages/main.php';
 }
else
 {
  if (file_exists('pages/'.$page_include_path.'.php'))
   {
	$page_include_path = 'pages/'.$page_include_path.'.php';
   }
   else
   {
    $page_include_path = 'pages/main.php';
   }
 }

 include($page_include_path);
 include("inc/footer.php");

?>