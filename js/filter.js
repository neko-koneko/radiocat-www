var filter_ajax_server = global_baseurl+"/local_services/filter/filter_ajax_server.php";
var filter_rule_form_id = 1;

var request_time = 1;

var helper_id = '';

function get_filter_form()
{


 var request = 'request=get_filter_form'+
                  '&id='+filter_rule_form_id;

 filter_rule_form_id += 1;


	var a = new AJAX(filter_ajax_server,a_get_filter_form);
  	a.doPost(request);
}
function a_get_filter_form(data)
{
  // alert (data);
  arr = data.split('#n');
  if (arr[0]=='RELOAD') {window.location.reload(true);}
  var inserted_element = add_dom_element_html('filter_form',data);
  var b = getBound(inserted_element);
  window.scrollTo(0,b[1]);
}


function add_dom_element_html(id,html)
{
 var tmp = document.createElement("div");
 tmp.innerHTML = html;
 var element = document.getElementById(id);
 return element.appendChild(tmp.children[0]);
}

function remove_dom_element(id)
{
 var e = document.getElementById(id);
 if(e){e.parentNode.removeChild(e);}
}

function reset_filter_form(id)
{
 var element = document.getElementById(id);
 var fields = element.getElementsByTagName('input');
 for (var i=0;i<fields.length;i++)
 {
 	fields[i].value='';
 }
}


function save_playlist()
{
    var request = 'request=save_playlist';

    e = document.getElementById('playlist_id');
    if (e) {request += '&playlist_id='+e.value; }

    e = document.getElementById('playlist_name');
    if (e) {request += '&playlist_name='+e.value; }

    e = document.getElementById('playlist_force_static');
    if (e)
    {
       request += '&playlist_static=Y';
    }
    else
    {
	    e = document.getElementById('playlist_static');
	    if (e) {request += '&playlist_static=' + (e.checked?"Y":"N") }
    }


    var rule_elements = findMagicElementsByName('rule');

    for ( var i = 0; i < rule_elements.length; i++ )
    {
    	request += '&' + rule_elements[i].name + '=' + rule_elements[i].value;
    }


    var files=document.getElementsByName('final_playlist_dnd');

    for ( var i = 0; i < files.length; i++ )
    {
    request+= '&final_playlist['+i+']='+files[i].value;
    }

    //alert(request);

	var a = new AJAX(filter_ajax_server,a_save_playlist);
  	a.doPost(request);
}

function a_save_playlist(data)
{
 //alert(data);
  var message_container = document.getElementById('playlist_data_error');

  if(data.length == 0) { message_container.innerHTML = 'Ошибка: не удалось сохранить плейлист'; return;}

  arr = data.split('#n');
  if (arr[0]=='RELOAD') {window.location.reload(true);}

  if (arr.length <= 1) {  message_container.innerHTML = 'Ошибка: не удалось сохранить плейлист'; return;}
  if (arr[0]=='OK') { hide_modal_window(); window.location.href=global_baseurl+"/playlist/edit/"+arr[1]+'?norules=Y'; return;}

  message_container.innerHTML = arr[2];
  return;
}

function show_modal_window_playlist_save(playlist_id=0,playlist_name="")
{
 var data ="<div class='modal_window'>   \
             <div class='pad10_0'>\
             \
             <h1>";

 if (playlist_id!=0)
 {
 	data = data+"Редактирование плейлиста «"+playlist_name+"»";
 }
 else
 {
 	data = data+"Сохранение плейлиста";
 }

 var playlist_static_flag=document.getElementById('playlist_static_flag').value;

 data = data + "</h1>  \
             <input type='hidden' id='playlist_id' value='"+playlist_id+"'><br /><br /> \
             Название плейлиста:  \
             <input type='text' id='playlist_name' class='w100' value='"+playlist_name+"'><br /><br /> \
             Статический плейлист:";

 e = document.getElementById('playlist_force_static');
 if (e)
 {
 data = data + "ДА<br />";
 }
 else
 {
  data = data + " <input type='checkbox' id='playlist_static' ";
  if (playlist_static_flag=="Y") {data = data + " checked='checked' ";}
  data = data + "> ";
 }

 data = data +"<br /><br /> \             \
             Нажмите отмену чтобы закрыть окно \
             </div>\
             <h2 class='error pad10_0' id='playlist_data_error'></h2> \
             <table class='defaulttable'><tr><td>\
             <div class='center button' onclick='save_playlist()'>Cохранить</div> \
             </td><td>\
             <div class='center button' onclick='hide_modal_window()'>Отмена</div> \
             </td></tr></table>\
            </div>";
 show_modal_window(data);
}







function helper(id)
 {
  e=document.getElementById(id);

  //if (e.value=='') {a_helper('');return;}

  var now = new Date( );
  request_time = now.getTime();

  helper_id = id;

  var request = 'request=get_genre_list'+
  '&name='+e.value+
  '&time='+request_time;

  a = new AJAX(filter_ajax_server,a_helper);
  a.doPost(request);
 }

function a_helper(data)
{
  var helper = document.getElementById('helper');

  if(data.length == 0) { helper.innerHTML = ''; helper.style.display="none"; return;}

  arr = data.split('#n');
  if (arr[0]=='RELOAD') {window.location.reload(true);}

  if (arr.length <= 1) { helper.innerHTML = ''; helper.style.display="none"; return;}
  if (arr[0]!='OK') { helper.innerHTML = ''; helper.style.display="none"; return;}

  //alert(arr[1]);

  var resp_time = parseInt(arr[1]);
  if (isNaN(resp_time)) {return;}
  if (resp_time<request_time) {return;}

  items = arr[2].split('#t');

  var helper_input = document.getElementById(helper_id);
  var b = getBound(helper_input);

//alert ('x='+b[0]+'px y='+b[1]+'px');

  helper.style.top=b[1]+b[3]+'px';
  helper.style.left=b[0]+'px';
  helper.style.width=b[2]+'px';

  helper.innerHTML = '';
  for(var i in items)
  {

  /*hdiv=document.createElement("div");
  value = items[i];
  hdiv.innerHTML = value;
  helper.appendChild(hdiv);
  hdiv.cnt = value;

  Event.add(hdiv, 'click', function(){ geo_add('start_name');}) /**/

  helper.innerHTML = helper.innerHTML + '<div onclick=\'javascript:helper_add("'+helper_id+'"'+',"'+(items[i])+'"); \'>'+items[i]+'</div>';
//  alert(items[i]);
  }

  helper.style.display="block";
}

function helper_add(id,value)
{
  document.getElementById(id).value = value;
  var helper = document.getElementById('helper');
  helper.style.display="none";
}

function checkbox_set_value_on_toggle(self)
{
 if(self.checked)
 {self.value="Y";}
 else
 {self.value="N";}
}