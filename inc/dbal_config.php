<?php


function config_get_all_config()
{
 global $mysqli_connection;
 $result = false;
 $i=0;


 $query = mysqli_query($mysqli_connection,
 "SELECT * FROM `config`"
  );
 if(!$query){ 	 return $result;
 	 }

 while ( $row = mysqli_fetch_assoc($query))
      {
       $result[$i] = $row;
       $i++;
      }
return ($result);
}

function config_get_data_by_name($name){ global $mysqli_connection;

 $result = false;
 $name = mysqli_real_escape_string($mysqli_connection,$name);
 if ( $name=='') {return $result;}

 $query = mysqli_query($mysqli_connection,
 "SELECT * FROM `config`
  WHERE `config`.`name`='".$name."'"
  );
 if(!$query) {return $result;}
 $result = mysqli_fetch_assoc($query);
 return $result;}

function config_edit_config_value_by_name($name,$value)
{
 global $mysqli_connection;

 $result = false;

 $name = mysqli_real_escape_string($mysqli_connection,$name);
 $value = mysqli_real_escape_string($mysqli_connection,$value);

 $old_config = config_get_data_by_name($name);

 if (empty($old_config))
 { 	 $qry = "INSERT INTO `config`
	  (`name`,`value`)
	   VALUES ('".$name."','".$value."');
	  "; }
 else
 {
 $qry = "UPDATE `config`
  SET `value` = '".$value."'
  WHERE `name` = '".$name."'
  ";
 }
 $query = mysqli_query($mysqli_connection,$qry);

 if(!$query) {return $result;}
 return true;
}


?>