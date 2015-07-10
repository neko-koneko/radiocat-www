<?php

// таблица месяцев
$month_name_table[1]="Январь";
$month_name_table[2]="Февраль";
$month_name_table[3]="Март";
$month_name_table[4]="Апрель";
$month_name_table[5]="Май";
$month_name_table[6]="Июнь";
$month_name_table[7]="Июль";
$month_name_table[8]="Август";
$month_name_table[9]="Сентябрь";
$month_name_table[10]="Октябрь";
$month_name_table[11]="Ноябрь";
$month_name_table[12]="Декабрь";

$month_abbr_table[1]="Янв";
$month_abbr_table[2]="Фев";
$month_abbr_table[3]="Мар";
$month_abbr_table[4]="Апр";
$month_abbr_table[5]="Май";
$month_abbr_table[6]="Июн";
$month_abbr_table[7]="Июл";
$month_abbr_table[8]="Авг";
$month_abbr_table[9]="Сен";
$month_abbr_table[10]="Окт";
$month_abbr_table[11]="Ноя";
$month_abbr_table[12]="Дек";


$month_abbr_gen_table[1]="Янв";
$month_abbr_gen_table[2]="Фев";
$month_abbr_gen_table[3]="Мар";
$month_abbr_gen_table[4]="Апр";
$month_abbr_gen_table[5]="Мая";
$month_abbr_gen_table[6]="Июн";
$month_abbr_gen_table[7]="Июл";
$month_abbr_gen_table[8]="Авг";
$month_abbr_gen_table[9]="Сен";
$month_abbr_gen_table[10]="Окт";
$month_abbr_gen_table[11]="Ноя";
$month_abbr_gen_table[12]="Дек";


$month_name_genetive_table[1]="Января";
$month_name_genetive_table[2]="Февраля";
$month_name_genetive_table[3]="Марта";
$month_name_genetive_table[4]="Апреля";
$month_name_genetive_table[5]="Мая";
$month_name_genetive_table[6]="Июня";
$month_name_genetive_table[7]="Июля";
$month_name_genetive_table[8]="Августа";
$month_name_genetive_table[9]="Сентября";
$month_name_genetive_table[10]="Октября";
$month_name_genetive_table[11]="Ноября";
$month_name_genetive_table[12]="Декабря";

// таблица четвергов
$weekday_abbr[0]="Вс";
$weekday_abbr[1]="Пн";
$weekday_abbr[2]="Вт";
$weekday_abbr[3]="Ср";
$weekday_abbr[4]="Чт";
$weekday_abbr[5]="Пт";
$weekday_abbr[6]="Сб";
$weekday_abbr[7]="Вс";


$weekday = array(
"0" => "Воскресенье",
"1" => "Понедельник",
"2" => "Вторник",
"3" => "Среда",
"4" => "Четверг",
"5" => "Пятница",
"6" => "Суббота",
"7" => "Воскресенье"
);

//таблица документов
$doc_name_table[1]="гражданский паспорт";
$doc_name_table[2]="загранпаспорт";
$doc_name_table[3]="свидетельство о рождении";

$en_doc_name_table[1]="passport";
$en_doc_name_table[2]="international_passport";
$en_doc_name_table[3]="birth_certificate";

//статус заказа
$en_order_state[1]='pending';
$en_order_state[2]='confirmed';
$en_order_state[3]='to_pay';
$en_order_state[4]='paid';
$en_order_state[5]='to_cancell';
$en_order_state[6]='cancelled';
$en_order_state[7]='done';
$en_order_state[8]='empty';

$order_state[1]='обрабатывается';
$order_state[2]='принят к оплате';
$order_state[3]='ждёт проведения оплаты';
$order_state[4]='оплачен';
$order_state[5]='к отмене';
$order_state[6]='отменён';
$order_state[7]='выполнен';
$order_state[8]='отсутствует';

$order_state_bg_color[1]= "#e8efff";
$order_state_bg_color[2]= "#ffffc1";
$order_state_bg_color[3]= "#ffffd9";
$order_state_bg_color[4]= "#d9ffd9";
$order_state_bg_color[5]= "#ffd5aa";
$order_state_bg_color[6]= "#ffbdaf";
$order_state_bg_color[7]= "white";
$order_state_bg_color[8]= "grey";

// управление заказами
$order_action_name["cancell"]='отменить';
$order_action_name["change"]='изменить';
$order_action_name["undo_cancellation"]='вернуть';
$order_action_name["pay"]='оплатить';

$order_type_name["flight"]='бронирование авиабилета';
$order_type_name["hotel"]='бронирование гостиницы';
$order_type_name["car"]='аренду автомобиля';
$order_type_name["taxi"]='заказ такси';


// красивые переносы
$break_50 = "<span style=\"font-size: 50%\" >&nbsp;<br /></span>";
$break_25 = "<span style=\"font-size: 25%\" >&nbsp;<br /></span>";

$mb_encodings = array(
"UCS-4",
"UCS-4BE",
"UCS-4LE",
"UCS-2",
"UCS-2BE",
"UCS-2LE",
"UTF-32",
"UTF-32BE",
"UTF-32LE",
"UTF-16",
"UTF-16BE",
"UTF-16LE",
"UTF-7",
"UTF7-IMAP",
"UTF-8",
"ASCII",
"EUC-JP",
"SJIS",
"eucJP-win",
"SJIS-win",
"ISO-2022-JP",
"JIS",
"ISO-8859-1",
"ISO-8859-2",
"ISO-8859-3",
"ISO-8859-4",
"ISO-8859-5",
"ISO-8859-6",
"ISO-8859-7",
"ISO-8859-8",
"ISO-8859-9",
"ISO-8859-10",
"ISO-8859-13",
"ISO-8859-14",
"ISO-8859-15",
"byte2be",
"byte2le",
"byte4be",
"byte4le",
"BASE64",
"HTML-ENTITIES",
"7bit",
"8bit",
"EUC-CN",
"CP936",
"HZ",
"EUC-TW",
"CP950",
"BIG-5",
"EUC-KR",
"UHC (CP949)",
"ISO-2022-KR",
"Windows-1251 (CP1251)",
"Windows-1252 (CP1252)",
"CP866 (IBM866)",
"KOI8-R");




 //********************************************************************************************
 // преобразует строку даты в формате yyyy-mm-dd
 // в строку в формате dd-месяц_на_русском_в родительном падеже-yyyy
 //
 function DateToStrWithRussianMonthInGenetive($input_date)
 {
 global  $month_name_genetive_table;

 $date_cut = explode("-", $input_date);
 settype($date_cut[1], "integer");

 $name=$month_name_genetive_table[$date_cut[1]];
 $name=mb_convert_case($name, MB_CASE_LOWER, "UTF-8");

 $new_date = $date_cut[2]." ".$name." ".$date_cut[0];
 return $new_date;
  }

//********************************************************************************************
 // преобразует дату
 // в строку в формате dd-месяц_на_русском_в родительном падеже-yyyy
 //
 function DateTimeStampToStrWithRussianMonthInGenetive($input_date)
 {
 global  $month_name_genetive_table;

 $month = date("m", $input_date);
 settype($month, "integer");

 $name=$month_name_genetive_table[$month];
 $name=mb_convert_case($name, MB_CASE_LOWER, "UTF-8");

 $new_date = date("d", $input_date)." ".$name." ".date("Y", $input_date);
 return $new_date;
  }

function get_week_day_abbr_by_date_time($date)
{
global $weekday_abbr;
 $weekday = date("w", $date);
 $name=$weekday_abbr[$weekday];
return $name;
}

// возвращает первое предложение
// может сглючить если в тексте точка используется как разделитель разряда в числах
function FirstSentence($text)
{
$content = strip_tags($text);
$point = strpos($content, ".") === false ? strlen($content) - 1 : strpos($content, ".");
$exclamation = strpos($content, "!") === false ? strlen($content) - 1 : strpos($content, "!");
$question = strpos($content, "?") === false ? strlen($content) - 1 : strpos($content, "?");
$position = $point < $exclamation ? $point : $exclamation;
$position = $position < $question ? $position : $question;

$content = substr($content, 0, $position + 1);
return $content;
 }

//*********************************************************************************************
// заменяет пробелы на &nbsp;
function NBSP($text)
{
 $text=mb_ereg_replace(" ", "&nbsp;", $text);
 return $text;
}

function bold($text)
{
 $text=str_replace(".", "</b>.<b>", $text);
 $text=str_replace(",", "</b>,<b>", $text);
 $text=str_replace(";", "</b>;<b>", $text);
 $text=str_replace(":", "</b>:<b>", $text);
 $text=str_replace("!", "</b>!<b>", $text);
 $text=str_replace("?", "</b>?<b>", $text);

 $text=str_replace("-", "</b>-<b>", $text);
 $text=str_replace("+", "</b>+<b>", $text);
 $text=str_replace("%", "</b>%<b>", $text);
 $text=str_replace("№", "</b>№<b>", $text);

 $text=str_replace("*", "</b>*<b>", $text);
 $text=str_replace("(", "</b>(<b>", $text);
 $text=str_replace(")", "</b>)<b>", $text);
 $text=str_replace("@", "</b>@<b>", $text);

 $text=str_replace("'", "</b>'<b>", $text);
 $text=str_replace("^", "</b>^<b>", $text);
 $text=str_replace("$", "</b>$<b>", $text);

 $text=str_replace("\'", "</b>\'<b>", $text);
 $text=str_replace("\"", "</b>\"<b>", $text);

 $text=str_replace("«", "</b>«<b>", $text);
 $text=str_replace("»", "</b>»<b>", $text);

 $text = "<b>".$text."</b>";

 return $text;
}

function html_sc($text)
{
return htmlspecialchars(stripslashes($text), ENT_QUOTES,"UTF-8");
 }



function RussianGenetiveDaySubst1($val)
{
  settype($val, "string");

  $rest2 = substr($val, -2);
  if (($rest2)=="11"){return "дней";}

  $last = $val[strlen($val)-1];
  switch($last)
  {
   case 1: {return "день";}
   case 2:
   case 3:
   case 4: {return "дня";}

   default:{return "дней";}
   }
}

function free_time_to_str($time)
{
 $offset = date_offset_get(date_create());
 $time = $time - $offset;

 $y = (date("Y",$time)-1970);
 $m = (date("m",$time)-1);
 $d = (date("d",$time)-1);
 $h = date("H",$time);
 $i = date("i",$time);

 $s = "";
 $s .= ($y>0)?($y." лет "):("");
 $s .= ($m>0)?($m." мес. "):("");
 $s .= ($d>0)?($d." дней "):("");
 $s .= ($h>0)?($h." ч. "):("");
 $s .= ($i>0)?($i." м. "):("");
return $s;
}

function time_diff_to_str($time_diff)
{


 $d = floor ($time_diff/(3600*24));
 $time_diff = $time_diff - $d*3600*24 ;

 $h = floor ($time_diff/3600);
 $time_diff = $time_diff - $h*3600 ;

 $i = floor ($time_diff/60);
 $sec = $time_diff - $i*60 ;



 $s = "";
// $s .= ($d>0)?($d." дней "):("");
// $s .= ($h>0)?($h." ч. "):("");
// $s .= ($i>0)?($i." м. "):("");
 $s .= $d." дней.";
 $s .= $h." ч.";
 $s .= $i." мин.";
 $s .= $sec." с.";
return $s;
}

function print_dummy_select($cntrl_attrib='')
{
 echo '<select '.$cntrl_attrib.'">';
 echo '</select>';
}


/***************************************************************************************************/


function i_in_array ($needle,$haystack)
{
 if (!is_array( $haystack )) {return false;}

 $needle = mb_strtoupper($needle);

 foreach ($haystack as $val)
 {
 	if (mb_strtoupper ($val) == $needle) {return true;}
 }

 return false;
}


function base64_decode_plus_fix( $data )
{
    $data = str_replace(" ","+",$data);

    return( base64_decode( $data ) );
}





 if(!function_exists("stripos")){
    function stripos(  $str, $needle, $offset = 0  ){
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  );
    }/* endfunction stripos */
}/* endfunction exists stripos */

if(!function_exists("strripos")){
    function strripos(  $haystack, $needle, $offset = 0  ) {
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  );
        if(  $offset < 0  ){
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  );
        }
        else{
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    );
        }
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE;
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   );
        return $pos;
    }/* endfunction strripos */
}/* endfunction exists strripos */

function cp1251_to_utf8($string){
  $table = array(
    0x80 => "\xD0\x82",
    0x81 => "\xD0\x83",
    0x82 => "\xE2\x80\x9A",
    0x83 => "\xD1\x93",
    0x84 => "\xE2\x80\x9E",
    0x85 => "\xE2\x80\xA6",
    0x86 => "\xE2\x80\xA0",
    0x87 => "\xE2\x80\xA1",
    0x88 => "\xE2\x82\xAC",
    0x89 => "\xE2\x80\xB0",
    0x8A => "\xD0\x89",
    0x8B => "\xE2\x80\xB9",
    0x8C => "\xD0\x8A",
    0x8D => "\xD0\x8C",
    0x8E => "\xD0\x8B",
    0x8F => "\xD0\x8F",
    0x90 => "\xD1\x92",
    0x91 => "\xE2\x80\x98",
    0x92 => "\xE2\x80\x99",
    0x93 => "\xE2\x80\x9C",
    0x94 => "\xE2\x80\x9D",
    0x95 => "\xE2\x80\xA2",
    0x96 => "\xE2\x80\x93",
    0x97 => "\xE2\x80\x94",
    0x99 => "\xE2\x84\xA2",
    0x9A => "\xD1\x99",
    0x9B => "\xE2\x80\xBA",
    0x9C => "\xD1\x9A",
    0x9D => "\xD1\x9C",
    0x9E => "\xD1\x9B",
    0x9F => "\xD1\x9F",
    0xA0 => "\xC2\xA0",
    0xA1 => "\xD0\x8E",
    0xA2 => "\xD1\x9E",
    0xA3 => "\xD0\x88",
    0xA4 => "\xC2\xA4",
    0xA5 => "\xD2\x90",
    0xA6 => "\xC2\xA6",
    0xA7 => "\xC2\xA7",
    0xA8 => "\xD0\x81",
    0xA9 => "\xC2\xA9",
    0xAA => "\xD0\x84",
    0xAB => "\xC2\xAB",
    0xAC => "\xC2\xAC",
    0xAD => "\xC2\xAD",
    0xAE => "\xC2\xAE",
    0xAF => "\xD0\x87",
    0xB0 => "\xC2\xB0",
    0xB1 => "\xC2\xB1",
    0xB2 => "\xD0\x86",
    0xB3 => "\xD1\x96",
    0xB4 => "\xD2\x91",
    0xB5 => "\xC2\xB5",
    0xB6 => "\xC2\xB6",
    0xB7 => "\xC2\xB7",
    0xB8 => "\xD1\x91",
    0xB9 => "\xE2\x84\x96",
    0xBA => "\xD1\x94",
    0xBB => "\xC2\xBB",
    0xBC => "\xD1\x98",
    0xBD => "\xD0\x85",
    0xBE => "\xD1\x95",
    0xBF => "\xD1\x97",
    0xC0 => "\xD0\x90",
    0xC1 => "\xD0\x91",
    0xC2 => "\xD0\x92",
    0xC3 => "\xD0\x93",
    0xC4 => "\xD0\x94",
    0xC5 => "\xD0\x95",
    0xC6 => "\xD0\x96",
    0xC7 => "\xD0\x97",
    0xC8 => "\xD0\x98",
    0xC9 => "\xD0\x99",
    0xCA => "\xD0\x9A",
    0xCB => "\xD0\x9B",
    0xCC => "\xD0\x9C",
    0xCD => "\xD0\x9D",
    0xCE => "\xD0\x9E",
    0xCF => "\xD0\x9F",
    0xD0 => "\xD0\xA0",
    0xD1 => "\xD0\xA1",
    0xD2 => "\xD0\xA2",
    0xD3 => "\xD0\xA3",
    0xD4 => "\xD0\xA4",
    0xD5 => "\xD0\xA5",
    0xD6 => "\xD0\xA6",
    0xD7 => "\xD0\xA7",
    0xD8 => "\xD0\xA8",
    0xD9 => "\xD0\xA9",
    0xDA => "\xD0\xAA",
    0xDB => "\xD0\xAB",
    0xDC => "\xD0\xAC",
    0xDD => "\xD0\xAD",
    0xDE => "\xD0\xAE",
    0xDF => "\xD0\xAF",
    0xE0 => "\xD0\xB0",
    0xE1 => "\xD0\xB1",
    0xE2 => "\xD0\xB2",
    0xE3 => "\xD0\xB3",
    0xE4 => "\xD0\xB4",
    0xE5 => "\xD0\xB5",
    0xE6 => "\xD0\xB6",
    0xE7 => "\xD0\xB7",
    0xE8 => "\xD0\xB8",
    0xE9 => "\xD0\xB9",
    0xEA => "\xD0\xBA",
    0xEB => "\xD0\xBB",
    0xEC => "\xD0\xBC",
    0xED => "\xD0\xBD",
    0xEE => "\xD0\xBE",
    0xEF => "\xD0\xBF",
    0xF0 => "\xD1\x80",
    0xF1 => "\xD1\x81",
    0xF2 => "\xD1\x82",
    0xF3 => "\xD1\x83",
    0xF4 => "\xD1\x84",
    0xF5 => "\xD1\x85",
    0xF6 => "\xD1\x86",
    0xF7 => "\xD1\x87",
    0xF8 => "\xD1\x88",
    0xF9 => "\xD1\x89",
    0xFA => "\xD1\x8A",
    0xFB => "\xD1\x8B",
    0xFC => "\xD1\x8C",
    0xFD => "\xD1\x8D",
    0xFE => "\xD1\x8E",
    0xFF => "\xD1\x8F",
  );
  $result = "";
  for($index = 0, $line = strlen($string); $index < $line; $index++) {
    $char = $string{$index};
    $byte = ord($char);
    if($byte < 128){
      $result .= $char;
    }else{
      $result .= @$table[$byte];
    }
  }
  return $result;
}

function translitIt($str)
{
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
    );
    return strtr($str,$tr);
}

function translitIt2($str)
{
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"C",
        "Ш"=>"S","Щ"=>"S","Ъ"=>"","Ы"=>"I","Ь"=>"",
        "Э"=>"E","Ю"=>"U","Я"=>"Y","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"c","ч"=>"c","ш"=>"s","щ"=>"s","ъ"=>"",
        "ы"=>"i","ь"=>"","э"=>"e","ю"=>"u","я"=>"y"
    );
    return strtr($str,$tr);
}

function get_cute_file_size($size)
{if(floor($size / 1073741824) > 1) return floor($size / 1073741824)." ГБ";
else if(floor($size / 1048576) > 1) return floor($size / 1048576)." МБ";
else if(floor($size / 1024) > 1) return floor($size / 1024)." КБ";
else return floor($size)." Б";
}

function get_cute_file_size_ex($size,$round=2){	$blocks = array('Б','кБ','МБ','ГБ','ТБ');
	$result = array();
	do{
		$quot = $size / 1024;
		$rem = bcmod ($size , 1024);
		$result[]=$rem;
		$size = $quot;
	}while ($quot>1);
//    $result[] = $rem;
   // print_r($result);

	$string_elements=array();
	$i = 0;
	foreach ($result as $current_size){		$string_elements[] = $current_size.' '.$blocks[$i];
		$i++;	}

	$arr = array_slice($string_elements,-$round,$round);
	//$arr = $string_elements;
	return implode(' ', array_reverse($arr));
}
?>