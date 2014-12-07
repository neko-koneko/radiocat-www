<?php
require_once(dirname(__FILE__).'/../config/local_config.php');

mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
date_default_timezone_set($config['local']['timezone']);
setlocale(LC_ALL,$config['local']['locale']);
?>