<?php

function get_mp3_tags_and_info($filename)
{
	if (!is_file($filename) || !file_exists($filename)){
		return false;
		}

	$shellcmd = dirname(__FILE__)."/mp3tag.pl ".escapeshellarg ($filename);
	ob_start();
	passthru($shellcmd);
	$perlreturn = ob_get_contents();
	ob_end_clean();

	$data = json_decode($perlreturn,true);

	//print_r($data);

    $tag0 = $data['tag0'];
	$tag1 = $data['tag1'];
	$tag2 = $data['tag2'];

	$data['tag'] = combine_mp3_tags($tag0,$tag1,$tag2);
	$data['filename'] = $filename;
	unset($data['tag0']);
	unset($data['tag1']);
	unset($data['tag2']);

	return $data;
}

function combine_mp3_tags($tag0,$tag1,$tag2){

	foreach ($tag0 as $key0=>$data1)
	{
		if ($tag2[$key0]==''){
			$tag2[$key0]=$tag0[$key0];
		}
	}

	foreach ($tag1 as $key1=>$data1)
	{
		if ($tag2[$key1]==''){
			$tag2[$key1]=$tag1[$key1];
		}
	}
	return $tag2;
}

?>
