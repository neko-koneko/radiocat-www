<?php
error_reporting(0);

mb_internal_encoding( 'UTF-8');
mb_regex_encoding( 'UTF-8');

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
require_once "../../inc/auth.php";
require_once '../../inc/admin_users_view.php';

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
 case "get_user_select":
  {
    echo "OK#n";
    print_admin_users_select('current_user_id','style="width: 805px;" class="select" onchange="admin_get_user_data();"',0);

    break;
  }

 case "get_user_data":
  {
    $id = intval($_POST['id']);
    if ($id <= 0){echo 'NF#n'.$id.'#n'; return;}

    $s = '';

    $user_data = auth_get_user_data_by_id($id);
    if (!is_array($user_data) or count($user_data)==0) {echo 'NF#n'.$id.'#n'; return;}

    $s = 'OK#n';
    $s.= $user_data['id'].'#t'.$user_data['name'].'#t'.$user_data['workplace_id'].'#t'.$user_data['role_id'].'#t'.$user_data['print_use_pos'].'#n';    echo $s;

    break;
  }

 case "edit_user":
  {    $user_id = intval($_POST['user_id']);
    $name = $_POST['name'];
    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];

    $result = array();
    $result['status'] ='OK';

    if ($user_id<=0)
    {            $result['status'] ='ER';
    	    $result['errors']['user_id']['message'] = 'Неверный id пользователя';
    }
    if ($password!='********') // special case for 'not changed password'
    {
	    if (!auth_test_password($password))
	    {
	            $result['status'] ='ER';	    	    $result['errors']['password']['message'] = 'Пароль содержит недопустимые символы или слишком короткий';
	    }
    }
    if ($password_2!='********') // special case for 'not changed password'
    {
	    if (!auth_test_password($password_2))
	    {
	            $result['status'] ='ER';
	    	    $result['errors']['password_2']['message'] = 'Пароль содержит недопустимые символы или слишком короткий';
	    }
    }
    if ($password != $password_2)
    {
            $result['status'] ='ER';
    	    $result['errors']['password']['message'] = 'Пароли не совпадают';
    	    $result['errors']['password_2']['message'] = 'Пароли не совпадают';
    }
    if ($name=='')
    {
            $result['status'] ='ER';
    	    $result['errors']['name']['message'] = 'Введите имя';
    }

    if ($result['status'] == 'ER')
    {     $s = 'ER#nОшибка — неверно заполнены поля#n';
     foreach ($result['errors'] as $field_name => $error_msg)
     {       $s .= $field_name.'#t'.$error_msg['message'].'#n';     }
     echo $s;
     return;    }

    if(get_magic_quotes_gpc()) {$name = stripslashes($name);}
    $name = mysqli_real_escape_string($mysqli_connection,$name);

    if ($password=='********') // not changed
    {
	    $query = mysqli_query($mysqli_connection,"UPDATE `admin_users`
	                            SET `name` = '$name'
	                            WHERE `id` = '$user_id';");
	    if (!$query)  {	echo 'ER#nНе удалось записать данные пользователя в базу (1)#n'; return;}
    }
    else
    {
        $hashed_password = auth_create_hash($password);

	    $query = mysqli_query($mysqli_connection,"UPDATE `admin_users`
	                            SET `name` = '$name',
	                                `hash` = '$hashed_password'
	                            WHERE `id` = '$user_id';");

	    if (!$query)  {	echo 'ER#nНе удалось записать данные пользователя в базу 3#n'; return;}

    }

    if (!$query)
    {    	$result['status'] = 'ER';
    	$s = 'ER#nНе удалось записать данные пользователя в базу#n';
    	echo $s;
        return;
    }
    else
    {    	$s = 'OK#nДанные пользователя записаны успешно#n';
    	echo $s;
        return;
    }

    break;  }

 case 'new_user':
  {
    $login = $_POST['login'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];

    $result = array();
    $result['status'] ='OK';

    if (!auth_test_login($login))
    {
            $result['status'] ='ER';
    	    $result['errors']['login']['message'] = 'Неверный логин (допустимы символы [0-9,a-z,_] минимальная длина — 4 символа )';
    }
    else
    {    	if (!auth_is_login_free($login))
    	{            $result['status'] ='ER';
    	    $result['errors']['login']['message'] = 'Логин занят';
    	}    }

    if (!auth_test_password($password))
    {
            $result['status'] ='ER';
    	    $result['errors']['password']['message'] = 'Пароль содержит недопустимые символы или слишком короткий';
    }
    if (!auth_test_password($password_2))
    {
            $result['status'] ='ER';
    	    $result['errors']['password_2']['message'] = 'Пароль содержит недопустимые символы или слишком короткий';
    }
    if ($password != $password_2)
    {
            $result['status'] ='ER';
    	    $result['errors']['password']['message'] = 'Пароли не совпадают';
    	    $result['errors']['password_2']['message'] = 'Пароли не совпадают';
    }
    if ($name=='')
    {
            $result['status'] ='ER';
    	    $result['errors']['name']['message'] = 'Введите имя';
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

    $name = mysqli_real_escape_string($mysqli_connection,$name);
    $login = mysqli_real_escape_string($mysqli_connection,$login);


    $hashed_password = auth_create_hash($password);

        $query = mysqli_query($mysqli_connection,"INSERT INTO `admin_users`
                                (`login`, `name`, `hash`)
                                VALUES
                                ('$login','$name','$hashed_password')
	                             ");      /**/


	    if (!$query)  {	echo 'ER#nНе удалось записать данные пользователя в базу#n'; return;}
        $user_id = last_insert_id();

    	$s = 'OK#nДанные пользователя записаны успешно#n';
    	echo $s;
        return;

    break;
  }

 case 'delete_user':
 {    $user_id = intval($_POST['user_id']);

    $result = array();
    $result['status'] ='OK';

    if ($user_id<=0)
    {
            $result['status'] ='ER';
    	    $result['errors']['user_id']['message'] = 'Неверный id пользователя';
    }
    if ($user_id==1)
    {
            $result['status'] ='ER';
    	    $result['errors']['user_id']['message'] = 'Этого пользователя удалять нельзя';
    }

    if ($result['status'] == 'ER')
    {
     $s = 'ER#nОшибка — не удалось удалить пользователя#n';
     foreach ($result['errors'] as $field_name => $error_msg)
     {
       $s .= $field_name.'#t'.$error_msg['message'].'#n';
     }
     echo $s;
     return;
    }


    $query = mysqli_query($mysqli_connection,"DELETE FROM `admin_users`
                            WHERE `id` = '$user_id'
                             ");
    if (!$query)  {	echo 'ER#nНе удалось удалить пользователя из БД#n'; return;}

	$s = 'OK#nПользователь удалён#n';
	echo $s;
	return;

    break; }

 case 'regenerate_password':
 {    $user_id = intval($_POST['user_id']);
    if ($user_id<=0)  {echo 'ER#nНеверный id пользователя'; return;}
    $user_data = auth_get_user_data_by_id($user_id);
    if (!is_array($user_data) or count($user_data)==0)  {echo 'ER#nНеверный id пользователя'; return;}

    $password_charset_str = CHARSET_DIGITS_STR.CHARSET_ALPHABET_LATIN_STR.CHARSET_FINE_PRINTABLE_STR;
	$charset_array = array_unique(preg_split('/(?<!^)(?!$)/u', $password_charset_str ));

	$charset_data[0]['charset']=CHARSET_DIGITS_STR;
	$charset_data[0]['count']=2;
	$charset_data[1]['charset']=CHARSET_ALPHABET_LATIN_STR;
	$charset_data[1]['count']=4;
	$charset_data[2]['charset']=CHARSET_FINE_PRINTABLE_STR;
	$charset_data[2]['count']=2;

    while (true)
    {
		$password = auth_generate_password(14,$charset_array);
	    if (auth_test_password($password,$charset_data)) {break;}
    }

    $hashed_password = auth_create_hash($password);
    $hashed_password = mysqli_real_escape_string($mysqli_connection,$hashed_password);

    $query = mysqli_query($mysqli_connection,"UPDATE `admin_users`
	                            SET `hash` = '$hashed_password'
	                            WHERE `id` = '$user_id';");
	if (!$query)  {	echo 'ER#nНе удалось записать данные пользователя в базу#n'; return;}
    else {echo "OK#n";}

    echo "<div class='password'>";
		echo $password;
	echo "</div>";

    return;

    break; }



 default:
  {
  	echo "Неверный запрос '".$request."'";
  	echo "<br />";
  	print_r ($_POST);
  }     /**/


}


?>

