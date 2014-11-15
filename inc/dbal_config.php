<?php


function config_get_all_config()
{
 global $mysqli_connection;
 $result = false;
 $i=0;

 $query = mysqli_query($mysqli_connection,
 "SELECT * FROM `config`"
  );
 if(!$query) {return $result;}

 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function config_edit_config_value_by_name($name,$value)
{
 global $mysqli_connection;

 $result = false;

 $name = mysqli_real_escape_string($mysqli_connection,$name);
 $value = mysqli_real_escape_string($mysqli_connection,$value);

 $qry = "UPDATE `config`
  SET `value` = '".$value."'
  WHERE `name` = '".$name."'
  ";

 $query = mysqli_query($mysqli_connection,$qry);

 if(!$query) {return $result;}
 return true;
}


?>