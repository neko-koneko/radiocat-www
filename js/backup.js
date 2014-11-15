var backup_ajax_server = global_baseurl+"/local_services/backup/backup_ajax_server.php";

function backup_create_backup()
{
 var request = 'request=create_backup_window';
 var a = new AJAX(backup_ajax_server,a_backup_create_backup);
  	a.doPost(request);
}
function a_backup_create_backup(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}


function backup_create_backup_execute()
{
 var request = 'request=create_backup';
 var a = new AJAX(backup_ajax_server,a_backup_create_backup_execute);
  	a.doPost(request);
}
function a_backup_create_backup_execute(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}


function backup_show_actions_form()
{
  if (document.getElementById('current_backup_id').value == 0 )
  {
  document.getElementById('backup_place').style.display = "none";
  }
  else
  {
  document.getElementById('backup_place').style.display = "";
  }
}


function backup_restore_backup()
{
 var filename = document.getElementById('current_backup_id').value;
 if (filename==''){return;}
 var request = 'request=restore_backup_window';

 var a = new AJAX(backup_ajax_server,a_backup_restore_backup);
  	a.doPost(request);
}
function a_backup_restore_backup(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}


function backup_restore_backup_execute()
{
 var e = document.getElementById('current_backup_id');
 var filename = e.options[e.selectedIndex].value;

 if (filename==''){return;}
 var request = 'request=restore_backup'+
               '&filename='+filename;

 var a = new AJAX(backup_ajax_server,a_backup_restore_backup_execute);
  	a.doPost(request);
}
function a_backup_restore_backup_execute(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}
