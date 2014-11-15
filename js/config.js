var config_ajax_server = global_baseurl+"/local_services/config/config_ajax_server.php";

function config_apply_new_values_clear_errors()
{	document.getElementById('offset_hours_error').innerHTML="";
	document.getElementById('max_forward_lookup_tracks_counter_error').innerHTML="";
	document.getElementById('max_try_count_error').innerHTML="";

	document.getElementById('config_apply_new_values').innerHTML="";}

function config_apply_new_values()
{
  config_apply_new_values_clear_errors();  var offset_hours=document.getElementById('offset_hours').value;
  var max_forward_lookup_tracks_counter=document.getElementById('max_forward_lookup_tracks_counter').value;
  var max_try_count=document.getElementById('max_try_count').value;

  var request = 'request=config_apply_new_values'+
  '&offset_hours='+offset_hours+
  '&max_forward_lookup_tracks_counter='+max_forward_lookup_tracks_counter+
  '&max_try_count='+max_try_count;

  a = new AJAX(config_ajax_server,a_config_apply_new_values);
  a.doPost(request);
}


function a_config_apply_new_values(data)
{
  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  document.getElementById('config_apply_new_values').innerHTML = arr[1];
  if (arr[0]=='OK')
  { CSSClass.remove('config_apply_new_values','field_error'); CSSClass.add('config_apply_new_values','field_ok'); return; }
  else
  { CSSClass.remove('config_apply_new_values','field_ok'); CSSClass.add('config_apply_new_values','field_error');}

  for (var i=2; i<arr.length; i++)
  {
   var val = arr[i].split('#t');
   var obj = document.getElementById(val[0]+'_error');
   if (obj) {obj.innerHTML=val[1];}
  }
}

