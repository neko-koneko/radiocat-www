var admin_ajax_server = global_baseurl+"/local_services/admin/admin_ajax_server.php";

function admin_get_user_data()
{
  admin_edit_user_clear_errors();  var user_selector=document.getElementById('current_user_id');
  var user_id = user_selector.value;
  if (user_id<=0)
  {     document.getElementById('user_place').style.display = "none";
     return;
  }

  var request = 'request=get_user_data'+
  '&id='+user_id;
  a = new AJAX(admin_ajax_server,a_admin_get_user_data);
  a.doPost(request);
}


function a_admin_get_user_data(data)
 {
 // alert (data);
  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  if (arr[0]!='OK') {     document.getElementById('user_place').style.display = "none";    return;}
  var val = arr[1].split('#t');

  var name = val[1];
  document.getElementById('current_name').value = name;
  document.getElementById('current_password').value = '********';
  document.getElementById('current_password_2').value = '********';

  document.getElementById('user_place').style.display = "";
 }


function admin_edit_user_clear_errors()
{document.getElementById('current_user_id_error').innerHTML="";
document.getElementById('current_name_error').innerHTML="";
document.getElementById('current_password_error').innerHTML="";
document.getElementById('current_password_2_error').innerHTML="";
document.getElementById('edit_user_status').innerHTML="";
}

function admin_edit_user_clear_inputs()
{
document.getElementById('current_user_id').value="";
document.getElementById('current_name').value="";
document.getElementById('current_password').value="";
document.getElementById('current_password_2').value="";
document.getElementById('edit_user_status').innerHTML="";
}

function admin_apply_edit_user()
{
 admin_edit_user_clear_errors(); var current_user_id    =  document.getElementById('current_user_id').value;
 var current_name       =  document.getElementById('current_name').value;
 var current_password   =  document.getElementById('current_password').value; var current_password_2 =  document.getElementById('current_password_2').value;

  var request = 'request=edit_user'+
  '&user_id='+current_user_id+
  '&name='+current_name+
  '&password='+current_password+
  '&password_2='+current_password_2;

  a = new AJAX(admin_ajax_server,a_admin_apply_edit_user);
  a.doPost(request);
}

function a_admin_apply_edit_user(data)
{  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  document.getElementById('edit_user_status').innerHTML = arr[1];
  if (arr[0]=='OK')
  { CSSClass.remove('edit_user_status','field_error'); CSSClass.add('edit_user_status','field_ok'); return; }
  else
  { CSSClass.remove('edit_user_status','field_ok'); CSSClass.add('edit_user_status','field_error');}

  for (var i=2; i<arr.length; i++)
  {   var val = arr[i].split('#t');
   var obj = document.getElementById('current_'+val[0]+'_error');
   if (obj) {obj.innerHTML=val[1];}  }

}


function admin_new_user_clear_errors()
{
document.getElementById('new_login_error').innerHTML="";
document.getElementById('new_name_error').innerHTML="";
document.getElementById('new_password_error').innerHTML="";
document.getElementById('new_password_2_error').innerHTML="";
document.getElementById('edit_user_status').innerHTML="";
}

function admin_apply_new_user()
{ admin_new_user_clear_errors();
 var new_login    =  document.getElementById('new_login').value;
 var new_name       =  document.getElementById('new_name').value;
 var new_password   =  document.getElementById('new_password').value;
 var new_password_2 =  document.getElementById('new_password_2').value;

  var request = 'request=new_user'+
  '&login='+new_login+
  '&name='+new_name+
  '&password='+new_password+
  '&password_2='+new_password_2;

  a = new AJAX(admin_ajax_server,a_admin_apply_new_user);
  a.doPost(request);}

function a_admin_apply_new_user(data)
{
  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  document.getElementById('new_user_status').innerHTML = arr[1];
  if (arr[0]=='OK')
  { CSSClass.remove('new_user_status','field_error'); CSSClass.add('new_user_status','field_ok'); get_user_select(); return; }
  else
  { CSSClass.remove('new_user_status','field_ok'); CSSClass.add('new_user_status','field_error');}

  for (var i=2; i<arr.length; i++)
  {
   var val = arr[i].split('#t');
   var obj = document.getElementById('new_'+val[0]+'_error');
   if (obj) {obj.innerHTML=val[1];}
  }

}


function admin_delete_user()
{
 admin_edit_user_clear_errors();
 var current_user_id    =  document.getElementById('current_user_id').value;

  var request = 'request=delete_user'+
  '&user_id='+current_user_id;

  a = new AJAX(admin_ajax_server,a_admin_delete_user);
  a.doPost(request);
}


function a_admin_delete_user(data)
{
  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  document.getElementById('edit_user_status').innerHTML = arr[1];
  if (arr[0]=='OK')
  { CSSClass.remove('edit_user_status','field_error'); CSSClass.add('edit_user_status','field_ok'); get_user_select(); return; }
  else
  { CSSClass.remove('edit_user_status','field_ok'); CSSClass.add('edit_user_status','field_error');}

  for (var i=2; i<arr.length; i++)
  {
   var val = arr[i].split('#t');
   var obj = document.getElementById('current_'+val[0]+'_error');
   if (obj) {obj.innerHTML=val[1];}
  }

}


function admin_regenerate_password()
{
 admin_edit_user_clear_errors();
 var current_user_id    =  document.getElementById('current_user_id').value;

  var request = 'request=regenerate_password'+
  '&user_id='+current_user_id;

  a = new AJAX(admin_ajax_server,a_admin_regenerate_password);
  a.doPost(request);
}


function get_user_select()
{  var request = 'request=get_user_select';

  a = new AJAX(admin_ajax_server,a_get_user_select);
  a.doPost(request);}

function a_get_user_select(data)
{
  var arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);return;}

  if (arr[0]!='OK')
  {
    return;
  }

  document.getElementById('current_user_id_select').innerHTML = arr[1];
  admin_edit_user_clear_inputs();
}