<?php
/*
 * based on
 *
 * Unbiased random password generator.
 * This code is placed into the public domain by Defuse Security.
 * WWW: https://defuse.ca/
 */

// mb_internal_encoding( 'UTF-8');
// mb_regex_encoding( 'UTF-8');

function auth_generate_password($length,$charset)
{
    $length = intval ($length);
    if ($length <=0 ) {return false;}

    if (!is_array($charset)) { $charset_array = preg_split('/(?<!^)(?!$)/u', $charset);} else {$charset_array=$charset;}

    $charset_array = array_unique($charset_array);
    $charset_item_count = count($charset_array);
    if ($charset_item_count<=1 or $charset_item_count>255){return false;}
    $bitmask = get_minimal_bitmask($charset_item_count);

    $password="";
    $current_password_length=0;

    while (true)
	 {
	  $PRNG_byte = ord(openssl_random_pseudo_bytes(1, $strong)) & $bitmask ;
      if ($PRNG_byte>=$charset_item_count){continue;}
      $c=$charset_array[$PRNG_byte];
      $password.=$c;

      $current_password_length++;
      if ($current_password_length==$length){break;}
	 }

  return $password;
}

function get_minimal_bitmask($byte)
{
    if($byte < 1) {return false;}
    $mask = 0x01;
    while($mask < $byte)
    {
     $mask = ($mask << 1) | 1;
    }
    return $mask;
}


?>
