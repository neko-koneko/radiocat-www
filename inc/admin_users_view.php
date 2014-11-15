<?php

function print_admin_users_select($id,$attrib,$selected)
{
  $users = auth_get_all_users_data();

  echo '<select id ="'.$id.'" '.$attrib.'>';
  echo '<option selected="Selected" value=0>--Выберите пользователя--</option>';
  foreach ($users as $user)
  {
  	echo '<option value="'.$user['id'].'">';
  	echo $user['login'];
  	echo '</option>';
  }

  echo '</select>';
}

?>