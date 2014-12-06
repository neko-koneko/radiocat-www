<?php
if (!headers_sent()) { header("Content-type: text/html; charset=UTF-8"); }
error_reporting(E_ALL);
//error_reporting(0);
include_once("config/db_config.php");
include_once("config/auth_config.php");
include_once("inc/init.php");

include_once("inc/dbal.php");
include_once("inc/dbal_config.php");
include_once("inc/tagreader.php");
include_once("inc/playlist.php");
include_once("inc/time_lib.php");

if (reconnect_db() == false)
{
	echo "ERROR: ".mysqli_error($mysqli_connection); die;
}

global $db_config;
$result = array();
$result['status'] = 'FAIL';

$playlists = get_all_playlists();

echo '<h1>Playlist migration tool v1.0 -> v1.1</h1>';

foreach ($playlists as $playlist_data)
{
    $playlist_id = $playlist_data['id'];
	$playlist_name = $playlist_data['name'];
	$playlist_static = $playlist_data['static'];
	$playlist_rules = $playlist_data['rules'];
	echo '<h2>playlist id='.$playlist_id.' name='.$playlist_name.'</h2>';
    $xml = simplexml_load_string($playlist_rules);
	if (!$xml)
	{ echo 'error - cannot load xml<br/> rules='.$playlist_rules;
	}

	foreach($xml->rule as $rule)
	{
		$attr = $rule->attributes();
		/* echo '<br /><br />--';
		print_r ($attr); echo '--<br />'; /**/
		$data = array();
		foreach ($attr as $a => $b)
		{ $data[$a] = (string)$b; }
	}
	$playlist_rules = json_encode($data);
    echo $playlist_rules.'<br/>';
    $re = edit_playlist($playlist_id,$playlist_name,$playlist_static,$playlist_rules);

    if (!$re){echo 'cannot save playlist!<br/>';}else{echo 'Converted successfully!';}
}

echo '<h1>Finished!</h2>';
die();



?>