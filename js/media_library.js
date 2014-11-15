var media_library_ajax_server = global_baseurl+"/local_services/media_library/media_library_ajax_server.php";

var media_update_file_index=0;

function media_library_edit(id,id_prefix)
{

 var req_file = '';
 var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
		var row_id = rows[i].id;
        var cb_id  = 'cb_'+row_id;
        var file_id_id  = 'file_id_'+row_id;

        //var row = document.getElementById(row_id);
        var cb = document.getElementById(cb_id);

    	 if (cb.checked)
		 {
 		    var file_id = document.getElementById(file_id_id).value;
        	req_file += '&file_list['+i+']='+file_id;
		 }
	 } /**/

 var request = 'request=get_media_edit_form'+
 '&id='+id+req_file;

 var a = new AJAX(media_library_ajax_server,a_media_library_edit);
  	a.doPost(request);
}

function a_media_library_edit(data)
{
 if (data==''){return;}
 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}
 show_modal_window(data);
}

function media_library_save()
{
 var request = 'request=save_file_data';

  var rule_elements = findMagicElementsByName('file_data');

  for ( var i = 0; i < rule_elements.length; i++ )
    {
    	request += '&' + rule_elements[i].name + '=' + rule_elements[i].value;
    }

 id_prefix = 'media_row';

 var req_file = '';
 var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
		var row_id = rows[i].id;
        var cb_id  = 'cb_'+row_id;
        var file_id_id  = 'file_id_'+row_id;

        //var row = document.getElementById(row_id);
        var cb = document.getElementById(cb_id);

    	 if (cb.checked)
		 {
 		    var file_id = document.getElementById(file_id_id).value;
        	req_file += '&file_list['+i+']='+file_id;
		 }
	 } /**/
 /*if (req_file == '')
 {
   req_file = '&file_list[0]='+id;
 }           /**/
 request = request+req_file;
// alert (request);
var a = new AJAX(media_library_ajax_server,a_media_library_save);
  	a.doPost(request);
}
function a_media_library_save(data)
{
// alert(data);
  if (data==''){return;}
  arr = data.split('#n');

  if (arr.length <= 1) { helper.innerHTML = ''; helper.style.display="none"; return;}

  if (arr[0]=='RELOAD') {window.location.reload(true);}
  if (arr[0]=='OK') {hide_modal_window(); submit_form('form1'); return;}
  if (arr[0]=='ER') {document.getElementById('file_data_error').innerHTML=arr[1]; return;}

  return;
}

function media_library_update_from_file(id,id_prefix)
{
 var req_file = '';
 var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
		var row_id = rows[i].id;
        var cb_id  = 'cb_'+row_id;
        var file_id_id  = 'file_id_'+row_id;

        //var row = document.getElementById(row_id);
        var cb = document.getElementById(cb_id);

    	 if (cb.checked)
		 {
 		    var file_id = document.getElementById(file_id_id).value;
        	req_file += '&file_list['+i+']='+file_id;
		 }
	 } /**/
 if (req_file == '')
 {
   req_file = '&file_list[0]='+id;
 }
 var request = 'request=update_from_file'+req_file;
 //alert (request);

 var a = new AJAX(media_library_ajax_server,a_media_library_update_from_file);
  	a.doPost(request);
}
function a_media_library_update_from_file(data)
{
 //alert(data);
  if (data==''){return;}
  arr = data.split('#n');

  if (arr[0]=='RELOAD') {window.location.reload(true);}
  if (arr[0]=='OK') {submit_form('form1'); return;}
  if (arr[0]=='ER') {document.getElementById('file_data_error').innerHTML=arr[1]; return;}

  return;
}

function media_library_add_to_cart(id,id_prefix)
{
 var new_playlist_files_id_list = media_library_load_new_playlist_files_id_list();
 var new_playlist_files_id =  new_playlist_files_id_list.split(',');

 var req_file = '';
 var use_cb_flag=false;
 var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
		var row_id = rows[i].id;
        var cb_id  = 'cb_'+row_id;
        var file_id_id  = 'file_id_'+row_id;
        var row = document.getElementById(row_id);
        var cb = document.getElementById(cb_id);

    	 if (cb.checked)
		 {

 		    var file_id = document.getElementById(file_id_id).value;

 		    if( new_playlist_files_id.indexOf(file_id)> -1 )
 		    {}
            else
            {
            if (new_playlist_files_id[0]==''){new_playlist_files_id[0]=file_id;}
            else{ new_playlist_files_id.push(file_id);}
            new_playlist_files_id_list=new_playlist_files_id.join(",");
        	setEternalCookie("file_list",new_playlist_files_id_list);
        	}
        	CSSClass.remove (row,'selected_row');
        	cb.checked=false;
        	use_cb_flag=true;
		 }

	 } /**/

     if(!use_cb_flag)
     {
         id=id+"";
         new_playlist_files_id =  new_playlist_files_id_list.split(',');
	     if (new_playlist_files_id.indexOf(id)> -1){}
	     else
	     {
		  if (new_playlist_files_id[0]==''){new_playlist_files_id[0]=id;}
	            else{ new_playlist_files_id.push(id);}
	            new_playlist_files_id_list=new_playlist_files_id.join(",");
	        	setEternalCookie("file_list",new_playlist_files_id_list);
	     }
     }/**/

	 media_library_load_new_playlist_files_id_list();
     table_edit_toggle_control_element(id_prefix);
}

function media_library_load_new_playlist_files_id_list()
{
 var file_list = getCookie("file_list");
 if(!file_list){file_list=''; hide_cart();}
 else { files=file_list.split(',');
 document.getElementById("cart_tracks_count").innerHTML=files.length; show_cart();}
 return file_list;
}

function media_library_show_add_to_playlist_window()
{
 var request = 'request=get_media_add_to_playlist_form';
 var a = new AJAX(media_library_ajax_server,a_media_library_show_add_to_playlist_window);
  	a.doPost(request);
}
function a_media_library_show_add_to_playlist_window(data)
{
 if (data==''){return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}

 show_modal_window(data);
}

function media_library_add_to_playlist()
{
 var id=document.getElementById("playlist_id").value;
 if (!id || id<=0) { document.getElementById("playlist_id_error").innerHTML = 'Не выбран плейлист!'; return;}
 window.location.href = global_baseurl+'/playlist/add/'+id;
}

function media_library_update_files_start()
{
 var pb_bar_done = document.getElementById("progress_bar_done");
 var pb_message = document.getElementById("progress_bar_message");
 var done_file_list = document.getElementById("done_file_list");

 done_file_list.innerHTML = "";

 if (total_files<=0) { pb_message.innerHTML="Задание пусто";}
 media_update_file_index=0;

 var total_files = media_update_files.length
 var pb_percent = Math.ceil( (media_update_file_index/total_files)*100 );

 pb_message.innerHTML=media_update_file_index+"/"+total_files;
 pb_bar_done.style.width= pb_bar_done+"%";
 media_library_get_files_list();
}

function media_library_get_files_list()
{
  	var request = 'request=media_get_files_list';
    var a = new AJAX(media_library_ajax_server,a_media_library_get_files_list);
  	a.doPost(request);
}

function a_media_library_get_files_list(data)
{
 var pb_message = document.getElementById("progress_bar_message");
 if (data==''){pb_message.innerHTML="Ошибка - невозможно загрузить список файлов для обработки: нет ответа от сервера"; return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}
 if (arr[0]!='OK') {pb_message.innerHTML="Ошибка - "+arr[1]; return;}

 media_update_files = arr[1].split('#t');
 media_library_update_files();
}

function media_library_update_files()
{
  if (media_update_files[media_update_file_index])
  {
  	var request = 'request=media_update_file&filename='+media_update_files[media_update_file_index];
  /*	 var done_file_list = document.getElementById("done_file_list");
     done_file_list.innerHTML = media_update_files[media_update_file_index]+"<br />"+done_file_list.innerHTML;
     media_update_file_index++;
     var total_files = media_update_files.length
     if (media_update_file_index>=total_files) {done_file_list.innerHTML = "Готово"+done_file_list.innerHTML; return;} /**/

    var a = new AJAX(media_library_ajax_server,a_media_library_update_files);
  	a.doPost(request);
  }
}

function a_media_library_update_files(data)
{
 var pb_message = document.getElementById("progress_bar_message");
 var done_file_list = document.getElementById("done_file_list");

 if (data==''){pb_message.innerHTML="Ошибка - нет ответа от сервера"; return;}

 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}
 if (arr[0]!='OK') {done_file_list.innerHTML="Ошибка - "+data+done_file_list.innerHTML; return;}
 media_update_file_index++;

 var total_files = media_update_files.length
 if (media_update_file_index>=total_files) {pb_message.innerHTML="Готово"; media_library_update_playlists();return;}
 else
 {
 	pb_message.innerHTML=media_update_file_index+"/"+total_files;
 }
 var pb_percent = Math.ceil( (media_update_file_index/total_files)*100 );

 var pb_bar_done = document.getElementById("progress_bar_done");
 pb_bar_done.style.width= pb_percent+"%";


 done_file_list.innerHTML = arr[1]+done_file_list.innerHTML;

 media_library_update_files();
}


function media_library_update_playlists()
{
 var done_file_list = document.getElementById("done_file_list");
 done_file_list.innerHTML ='<h2>Автокоррекция плейлистов</h2><br />'+done_file_list.innerHTML;

    var request = 'request=media_update_playlists';
    var a = new AJAX(media_library_ajax_server,a_media_library_update_playlists);
  	a.doPost(request);
}

function a_media_library_update_playlists(data)
{
 var pb_message = document.getElementById("progress_bar_message");
 if (data==''){pb_message.innerHTML="Ошибка - нет ответа от сервера"; return;}
 arr = data.split('#n');
 if (arr[0]=='RELOAD') {window.location.reload(true);}
 if (arr[0]!='OK') {pb_message.innerHTML="Ошибка - "+data; return;}
 var done_file_list = document.getElementById("done_file_list");
 done_file_list.innerHTML = "<h2>Автокоррекция плейлистов завершена</h2>"+arr[1]+'<br />'+done_file_list.innerHTML;
}


function show_cart()
{document.getElementById("cart").style.display="";}
function hide_cart()
{document.getElementById("cart").style.display="none";}
function clear_cart()
{
 unsetCookie("file_list");
 media_library_load_new_playlist_files_id_list();
}

function show_player()
{document.getElementById("flying_player").style.visibility="visible";}
function hide_player()
{document.getElementById("flying_player").style.visibility="hidden";}
