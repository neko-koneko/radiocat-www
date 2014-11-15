var table_edit_last_affected_row_id = null;

function table_edit_select_all_rows(event,control_checkbox_obj,id_prefix)
{
 if (!control_checkbox_obj) {return;}

 var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
		row_id = rows[i].id;
        cb_id  = 'cb_'+row_id;
        var row = document.getElementById(row_id);
        var cb = document.getElementById(cb_id);
        cb.checked= control_checkbox_obj.checked;

		 if (control_checkbox_obj.checked)
		 {
        	CSSClass.add (row,'selected_row');
		 }
		 else
		 {
        	CSSClass.remove (row,'selected_row');
		 }
	 }
 table_edit_toggle_control_element(id_prefix);
}


function table_edit_select_row(event,control_checkbox_obj,id_prefix)
{
   if (!control_checkbox_obj) {return;}
   var cb_id = control_checkbox_obj.id;
   var id = cb_id.substr(cb_id.lastIndexOf("_")+1);

   table_edit_last_affected_row_id = parseInt(table_edit_last_affected_row_id);
   id=parseInt(id);

   if (event.shiftKey)
   {
    // get all rows in between
    if (table_edit_last_affected_row_id>id)
     {
      var start = id;
      var stop = table_edit_last_affected_row_id;
     }
     else
     {
	  var start = table_edit_last_affected_row_id;
      var stop = id;
     }

     for (var i=start;i<=stop; i++)
     {
       var row_id = id_prefix+'_'+i;
       table_edit_apply_select_row(row_id,control_checkbox_obj);
       cb_id  = 'cb_'+row_id;
       var cb = document.getElementById(cb_id);
       cb.checked= control_checkbox_obj.checked;
     }
   }
   else
   {
    var row_id = id_prefix+'_'+id;
    table_edit_apply_select_row(row_id,control_checkbox_obj)
   }
table_edit_last_affected_row_id = id;
table_edit_toggle_control_element(id_prefix);
}


function table_edit_apply_select_row(row_id,control_checkbox_obj)
{
   var row = document.getElementById(row_id);
   if (control_checkbox_obj.checked)
		 {
        	CSSClass.add (row,'selected_row');
		 }
		 else
		 {
        	CSSClass.remove (row,'selected_row');
		 }
}

function table_edit_apply_select_row_playing(row_id,play_status_obj,id_prefix)
{
   var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
 	    var current_row_id = id_prefix+'_'+i;
 	    var row = document.getElementById(current_row_id);
	 	CSSClass.remove (row,'playing_row');
      }

        var row = document.getElementById(row_id);
    	CSSClass.add (row,'playing_row');
}

function table_edit_apply_clear_all_rows_playing(id_prefix)
{
   var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
 	    var current_row_id = id_prefix+'_'+i;
 	    var row = document.getElementById(current_row_id);
	 	CSSClass.remove (row,'playing_row');
      }
}

//media_row_edit
function table_edit_toggle_control_element(id_prefix)
{
  button_text = (table_edit_get_selected_rows_count(id_prefix) == 0)?'[e]':'[g]';
  var group_name =  id_prefix+'_edit_button';
  var rows=document.getElementsByName(group_name);
  for (var i=0;i<rows.length; i++)
	 {
		rows[i].innerHTML = button_text;
	 }

  button_text = (table_edit_get_selected_rows_count(id_prefix) == 0)?'[r]':'[R]';
  var group_name =  id_prefix+'_update_from_file_button';
  var rows=document.getElementsByName(group_name);
  for (var i=0;i<rows.length; i++)
	 {
		rows[i].innerHTML = button_text;
	 }

  button_text = (table_edit_get_selected_rows_count(id_prefix) == 0)?'[p]':'[P]';
  var group_name =  id_prefix+'_add_to_cart_button';
  var rows=document.getElementsByName(group_name);
  for (var i=0;i<rows.length; i++)
	 {
		rows[i].innerHTML = button_text;
	 }
}


function table_edit_get_selected_rows_count(id_prefix)
{
     var count = 0;
     var rows=document.getElementsByName(id_prefix);
	 for (var i=0;i<rows.length; i++)
	 {
 	    var row_id = id_prefix+'_'+i;
        cb_id  = 'cb_'+row_id;
        var cb = document.getElementById(cb_id);

		 if (cb.checked)
		 {
        	count++;
		 }
	 }
	 return count;
}
