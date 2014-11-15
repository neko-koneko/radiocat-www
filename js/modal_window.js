
function show_modal_window(data)
{
 var e = document.getElementById('modal_container');
 if (!e)
 {
 	document.body.innerHTML+='<div id="modal_container" style="z-index:10; position:fixed; right:0px; left:0px; top:0px; bottom: 0px; background-color:black; display:none;"></div>';
 	var e = document.getElementById('modal_container');
 }

 e.style.display="";
 e.innerHTML = data;
}
function hide_modal_window()
{
 var e = document.getElementById('modal_container');
 if (!e) {return;}
 e.style.display="none";
}