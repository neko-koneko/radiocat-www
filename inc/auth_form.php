<?php
if ($_SESSION['authorized']!=='Y' or $_SESSION['userid']<=0)
{
	$login     = $_POST['i_l'];
	$password = $_POST['i_p'];

    $userid = auth_login($login,$password);

	if ($userid<=0)
	{
	 ?>
	  <div class="main" style="padding:20px 0px;">
	       <div class="login">


				  <table class="login_table">
				  <tr>
				      <td style="width:70px">
				      </td>
				  	  <td  style="text-align:center; height:100px">
				  		<h1>Вход в систему</h1>
				      </td>
				      <td style="width:70px">
				      </td>
				  </tr>
				  </table>

				  <table class="login_table shadow_block">
				  <tr><td colspan=3></td></tr>
				  <tr>
				      <td>
					  <span class="h2_like">Логин</span>
					  </td>
					  <td>
					  <input type="text" autocomplete="off" style="width:400px;" id="i_l">
					  </td>
				  </tr>
				  <tr>
				      <td>
					  <span class="h2_like">Пароль</span>
					  </td>
					  <td>
					  <input type="password" autocomplete="off" style="width:400px;" id="i_p"  onkeydown="log_in_onkeydown(event);">
					  </td>
				  </tr>
				  <tr>
				  	  <td colspan=2 style="text-align:center;">
				  		<input type="submit" onclick="log_in();" value="Войти">
				      </td>
				  </tr>
                  </table>

          </div>
          </body>
          </html>

	 <?php


	 die;
	}
	else
	{
	$_SESSION['userid']=$userid;
    $_SESSION['authorized']='Y';
	}
}
?>