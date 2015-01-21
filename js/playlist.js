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

function playlist_toggle_filter_result(id){
  var playlist_block=document.getElementById('playlist_tracklist_block_'+id);
  if(playlist_block.style.display == 'none'){
  	document.getElementById('ptfr_char_'+id).innerHTML='&#8679;';
  	document.getElementById('ptfr_text_'+id).innerHTML='Скрыть';
  	playlist_block.style.display = '';

  }else{
  	document.getElementById('ptfr_char_'+id).innerHTML='&#8681;';
  	document.getElementById('ptfr_text_'+id).innerHTML='Показать результат работы фильтра';
  	playlist_block.style.display = 'none';
  }

}

