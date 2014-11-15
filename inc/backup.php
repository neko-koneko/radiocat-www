<?php
require_once(dirname(__FILE__).'/../config/backup_config.php');

function print_backup_file_select($id,$attrib,$selected)
{
  global $config;
  $backups = backup_get_all_backups_filenames($config['backup']['backup_root_folder']);

  echo '<select id ="'.$id.'" '.$attrib.'>';
  echo '<option selected="Selected" value=0>--Выберите файл--</option>';
  foreach ($backups as $filename)
  {
  	echo '<option value="'.$filename.'">';
  	echo $filename;
  	echo '</option>';
  }

  echo '</select>';
}

function backup_get_all_backups_filenames($backup_root_folder)
{
	$list = array();
	if (empty($backup_root_folder)) {return $list;}
	if (!is_dir($backup_root_folder)) {return $list;}

    $path = $backup_root_folder.'/manual';

	$dir = opendir($path);
	while(false !== ($file = readdir($dir))){
		if(is_file("{$path}/{$file}"))
		{
		    if (preg_match("@[0-9]{4}\-[0-9]{2}\-[0-9]{2}\_[0-9]{2}\:[0-9]{2}\:[0-9]{2}.sql.gz$@i",$file))
		    {
			$list[] = substr($file,0,19)."-manual";
			}
		}
	}
    $path = $backup_root_folder.'/auto';

	$dir = opendir($path);
	while(false !== ($file = readdir($dir))){
		if(is_file("{$path}/{$file}"))
		{
		    if (preg_match("@[0-9]{4}\-[0-9]{2}\-[0-9]{2}\_[0-9]{2}\:[0-9]{2}\:[0-9]{2}.sql.gz$@i",$file))
		    {
			$list[] = substr($file,0,19)."-auto";
			}
		}
	}
	closedir($dir);
	arsort($list);
	return $list;
}

?>