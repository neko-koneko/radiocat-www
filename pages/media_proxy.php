<?php
/*******************************************************************************************************/
//session check
require_once('inc/auth_check.php');
if ($_SESSION['authorized']!=='Y' or $_SESSION['userid']<=0){ die;}


$file_id = intval($main_request_array[1]);
if ($file_id<=0) {die;}


$file_data = media_library_get_file_data_by_id($file_id);
           $filename = $file_data['filename'];
        //echo $filename;

           if (!is_file($filename))
           {
            echo "error - no file id=[".$file_id."] name=[".$filename."]";
            die;
           	}


// get the filename extension
$ext = substr($filename, -3);
// set the MIME type
switch ($ext)
{	 case 'mp3':
	 $mime = 'audio/mpeg';
	 break;

	 default: $mime = false;
}

if ($mime)
{
 header('Content-type: '.$mime);
 header('Content-length: '.filesize($filename));
 $file =  fopen($filename, 'rb');
 if ($file)
 { 	 fpassthru($file); exit;
 }
}

die;

?>