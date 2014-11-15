var control_panel_ajax_server = global_baseurl+"/local_services/control_panel/control_panel_ajax_server.php";

function control_panel_restart_ices()
{
 var request = 'request=restart_ices_window';
 var a = new AJAX(control_panel_ajax_server,a_control_panel_restart_ices);
  	a.doPost(request);
}
function a_control_panel_restart_ices(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}


function control_panel_restart_ices_execute()
{
 var request = 'request=restart_ices';
 var a = new AJAX(control_panel_ajax_server,a_control_panel_restart_ices_execute);
  	a.doPost(request);
}
function a_control_panel_restart_ices_execute(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}
