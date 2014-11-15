function log_in(auth_url='/')
{
 l = document.getElementById('i_l').value;
 p = document.getElementById('i_p').value;
 a = new AJAX(auth_url,a_log_in);
 request="i_l="+l+"&i_p="+p;
 a.doPost(request);
}

function a_log_in(data)
{
document.location.reload();}

function log_in_onkeydown(event)
{
     if (event.which == 13 || event.keyCode == 13)
     {
	  log_in();
     }
  return true;
}
