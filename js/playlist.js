function change_range(o)
{
 document.getElementById('current_'+o.name).innerHTML = zerofill(o.value,2);
}

function select_playlist_edit()
{
  var playlist_id=document.getElementById('playlist_id').value;
  if (!playlist_id) {return;}
  window.location.href=global_baseurl+'/playlist/edit/'+playlist_id;
}

function select_playlist_delete()
{
  var playlist_id=document.getElementById('playlist_id').value;
  if (!playlist_id) {return;}
  window.location.href=global_baseurl+'/playlist/delete/'+playlist_id;
}


