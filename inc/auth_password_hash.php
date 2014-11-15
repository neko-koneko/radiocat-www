<?php
/*******************************************************************************
*
*  hash checking and generating functions with scrypt key stretching algo
*  http://www.tarsnap.com/scrypt/scrypt.pdf
*
*  requirements
*  - PHP >= 5.2
*  - 'scrypt' PHP extension - wrapper to Colin Percival's scrypt implementation
*    http://pecl.php.net/package/scrypt
*
/*******************************************************************************/
const SCRYPT_KEY_LENGTH = 48;  //in octets
const SCRYPT_SALT_LENGTH = 24; //in octets
const SCRYPT_N = 32768; //Mixing Function iterations count
const SCRYPT_p = 1;     //Parallelization parameter
const SCRYPT_r = 8;     //Block size parameter

function auth_create_hash($password)
{
   // format: salt:hash
   $salt = base64_encode(mcrypt_create_iv(SCRYPT_SALT_LENGTH, MCRYPT_DEV_URANDOM));
   return $salt.":".base64_encode( do_scrypt($password,$salt,SCRYPT_KEY_LENGTH) );
}

function auth_validate_password($password, $hash)
{
    $hash_elements = explode(":", $hash);
    if(count($hash_elements) < 2) return false;
    $expected_scrypt_result = base64_decode($hash_elements[1]);
    $salt = $hash_elements[0];
    return slow_equals($expected_scrypt_result, do_scrypt($password,$salt,SCRYPT_KEY_LENGTH));
}

// Compares two strings $a and $b in length-constant time.
function slow_equals($a, $b)
{
    $diff = strlen($a) ^ strlen($b);
    for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
    {
        $diff |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $diff === 0;
}


function do_scrypt($password,$salt,$key_length)
{
    return scrypt($password, $salt, SCRYPT_N, SCRYPT_r, SCRYPT_p, $key_length);
}

?>
