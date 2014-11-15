<?php
require_once(dirname(__FILE__).'/auth_password_hash.php');
require_once(dirname(__FILE__).'/auth_password_generator.php');

define('CHARSET_DIGITS_STR', "0123456789");
define('CHARSET_ALPHABET_LATIN_STR', "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
define('CHARSET_FINE_PRINTABLE_STR', "!@#$%^&*()_+-=?");
define('CHARSET_SPECIAL_PRINTABLE_STR', "!@#$%^&*()_+|~-=\\/{}[]:\;,.<>?");


function aut_get_default_password_charset_data()
{
/*$charset_data['charset_rules'][0]['charset']=CHARSET_DIGITS_STR;
$charset_data['charset_rules'][0]['count']=2;
$charset_data['charset_rules'][1]['charset']=CHARSET_ALPHABET_LATIN_STR;
$charset_data['charset_rules'][1]['count']=4;
$charset_data['charset_rules'][2]['charset']=CHARSET_FINE_PRINTABLE_STR;
$charset_data['charset_rules'][2]['count']=2;/**/
$charset_data['minimal_length']=14;
return $charset_data;
}

//test for weak password using charset_rules and minimal_length
function auth_test_password($password,$charset_data)
{

   if (!is_array($charset_data) or empty($charset_data)) {$charset_data = aut_get_default_password_charset_data();}

   $password_chars = preg_split('/(?<!^)(?!$)/u', $password);

   if (count($password_chars)<$charset_data['minimal_length']) {return false;}

   foreach ($charset_data['charset_rules'] as $data)
   {
    $charset = $data['charset'];
    $charset_chars = preg_split('/(?<!^)(?!$)/u', $charset);
    $minimal_allowed_char_count_for_this_charset = $data['count'];

    $char_count =0;
    foreach ($password_chars as $password_char)
    {
     if (in_array($password_char,$charset_chars)) {$char_count++;}
    }
    if ($char_count<$minimal_allowed_char_count_for_this_charset){return false;}
   }
   return true;
}

function auth_test_login($login)
{
 if (!preg_match("@^[a-z0-9\_]{1,35}$@i", $login)){return false;} else {return true;}
}


function auth_login($login, $password)
{
 global $mysqli_connection;

 if (!auth_test_login($login)) {$login="";}
 //if (!auth_test_password($password)) {return false;} //ok that was stupid
 $login = mysqli_real_escape_string($mysqli_connection,$login);

 $query = mysqli_query($mysqli_connection,
                           "SELECT *
                            FROM `admin_users`
                            WHERE
                            `login` = '$login'
                             ");
 $row = mysqli_fetch_assoc($query);
 if (!$row) { $row['hash']="";}
 if (auth_validate_password($password, $row['hash'])){ return $row['id'];}
 else {return false;}
}

function auth_get_user_data_by_id($id)
{
 global $mysqli_connection;

 $id = intval($id);
 if ($id<=0){return false;}

 $query = mysqli_query($mysqli_connection,
 						   "SELECT *
                            FROM `admin_users`
                            WHERE
                            `id` = '$id'
                             ");
 $row = mysqli_fetch_assoc($query);
 return $row;
}

function auth_is_login_free($login)
{
 global $mysqli_connection;

 //if (!auth_test_login($login)) {return false;}
 $login = mysqli_real_escape_string($mysqli_connection, $login);

 $query = mysqli_query($mysqli_connection,
 						   "SELECT `admin_users`.`id` as id
                            FROM `admin_users`
                            WHERE
                            `admin_users`.`login` = '$login'
                             ");
 if (mysqli_num_rows($mysqli_connection, $query)>0)
 { return false;}
 else
 { return true; }
}

function auth_get_all_users_data()
{
 global $mysqli_connection;

 $result = array();
 $query = mysqli_query($mysqli_connection,"SELECT *
                            FROM `admin_users`
                            ORDER BY name
                             ");
 while ($row = mysqli_fetch_assoc($query))
 {
 	$result[] = $row;
 }
 return $result;
}

?>