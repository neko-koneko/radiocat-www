<?
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');

$global_description = 'Управление плейлистами';
$global_keywords = 'Управление плейлистами';

 include("inc/head.php");
 include("inc/header.php");
 //include("inc/menu.php");
 //include("inc/navi.php");
 include("inc/playlist.php");
 include("inc/_utf_symbols.php");

/*******************************************************************************************************/
//auth form
require_once ('inc/auth_form.php');

?>

<?
echo '<script type="text/javascript" src="'.$base.'/js/playlist.js"></script>';
echo '<script type="text/javascript" src="'.$base.'/js/tablednd.js"></script>';
 ?>
 <!-- content -->
<div id="content">
<?
//print_r ($_POST);

switch($main_request_array[1])
{
	case 'delete':
		playlist_model_delete_playlist();
		break;
	case 'new':
		$playlist_data['ruleset']=$_POST['rule'];
  		print_playlist_view('new',$playlist_data);
		break;
	case 'edit':
        playlist_model_edit_playlist();
        break;
	case 'manager':
		print_playlist_manager('new');
		break;
	case 'add':
		print_playlist_view('add');
		break;
}

?>