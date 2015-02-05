<?php
session_start();
header("Content-type: text/html; charset=UTF-8");
header("Expires: Mon, 23 May 1995 02:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must_revalidate");
header("Pragma: no-cache;");
$base = "http://".$_SERVER['HTTP_HOST'].(dirname($_SERVER['PHP_SELF']) != "\\" ? dirname($_SERVER['PHP_SELF']) : "");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?=$global_description?>">
<meta name="keywords" content="<?=$global_keywords?>">
<meta name="Autor" content="Королёв Алексей a.k.a _emploi-kun_ (программирование) +79022511929">
<meta name="robots" content="index,follow">

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />


<title>soft 503 — Сервис временно недоступен</title>

</head>

<body >

<div  style='text-align: center; font-family: Verdana; font-size: 12px; color: #363640; width: 1024px; margin: 0px auto; outline-style: none;'>
 <H1>
  Радио Барсик
 </H1>

 <img src='<?=$base?>files/images/503/503.jpg'>

 <H2>
  Сайт временно недоступен по причине отказа Базы Данных
 </H2>
 <H2>
  Попробуйте зайти через час
 </H2>


</div>

</body>
</html>
