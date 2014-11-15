<?php
//force https
if (!empty( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
	{ }
	else
	{
		header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	    exit;
	}
include (dirname(__FILE__).'/../inc/auth_password_hash.php');
include (dirname(__FILE__).'/../inc/auth.php');

$password=$_GET['password'];

echo 'password='.$password.'<br />';

if (!auth_test_password($password)) {echo ' <br />your password is too weak!'; die;}

$hash = auth_create_hash($password);

echo ' hash='.$hash.'<br />';

if (auth_validate_password($password, $hash))
{echo ' hash check OK';}
else
{echo ' hash check FAIL';}


?>