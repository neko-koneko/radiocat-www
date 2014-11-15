//***************************************************************************************************************
//    набор основных скриптов
//    для сайта sup-b.ru
//    автор:  Королёв Алексей
//    версия: 2.0
//    дата:   16.02.2011
//---------------------------------------------------------------------------------------------------------------


function getrandom(min_random, max_random) {
    var range = max_random - min_random + 1;
    return Math.floor(Math.random()*range) + min_random;
}


// кавайные полупрозрачные сообщения об ошибках
var kawaii_windows_timer;
var kawaii_colors = {'error':'#ff0000','ok':'#008000','warning':'#ff6600'};

function kawaii_windows_move()
{
 var container = document.getElementById('right_message_container');
 var upper_window = container.firstChild;

 if (upper_window==null)
 {
 window.clearInterval(kawaii_windows_timer);
 }
 else
 {
  upper_window.kawaii_time = upper_window.kawaii_time -1;
  if (upper_window.kawaii_time<=0)
   {
   	 container.removeChild(upper_window);
  	 upper_window = container.firstChild;
     if (upper_window==null) { window.clearInterval(kawaii_windows_timer); }
    }
 }

}

function kawaii_alert(text,type)
{
//alert ('type='+type+' kc='+kawaii_colors[type]);
var timerflag=false;
var msg = document.createElement("div");
var container = document.getElementById('right_message_container');
if (container.firstChild==null){timerflag=true;}
container.appendChild(msg);
msg.innerHTML='<div style="position:relative; float:right; font-size: 16px; filter:alpha(opacity=90); opacity:0.9; margin: 1px 1px 0px 0px;  border-radius: 5px; -moz-border-radius: 5px; padding:10px 20px; width:400px;  border: 1px solid white; color: white; background:'+kawaii_colors[type]+';">'+text+'</div>';
msg.style.visibility = "visible";
msg.kawaii_time=1;
if (timerflag) {kawaii_windows_timer = window.setInterval(kawaii_windows_move,1000);}
};



//***************************************************************************************************************
// функция заполняет объект с идентификатором text_id
// данными из массива data
//
// данные передаются в простой строке
// в кодировке CP1251
function fill_text (text_id,data){

    var txt = document.getElementById(text_id);
    txt.innerHTML = data;
}
//---------------------------------------------------------------------------------------------------------------


//***************************************************************************************************************
//  самая главная функция в мире
function submit_form(id_form) {
document.getElementById(id_form).submit();
}
//---------------------------------------------------------------------------------------------------------------




//***************************************************************************************************************
// функция заполняет объект SELECT с идентификатором select_id
// данными из массива data
//
// данные передаются в строке вида   value1 #t text1 #n value2 #t text2 #n ... valueN #t textN [#n]
// кодировка определяется header'ом со стороны аякс-сервера
function fill_select (select_id,data,clear)
{

    var select = document.getElementById(select_id);     // поле SELECT в переменную в виде объекта

    if ((select.length > 0)&&(clear !=0)){selected_text = select[0].text;}
    else {selected_text ="";}

    select.length = clear;              // очищаем SELECT
    select.length = 0;              // очищаем SELECT

    if(data.length == 0) return;    // если данных нет - не делаем больше ничего
    var arr = data.split('#n');     // в массиве arr - строки полученной таблицы
    arr.pop();

    for(var i in arr)     // для каждой строки
    {
        if (arr[i] != "")
         {
          val = arr[i].split('#t');                   // в массиве val - поля полученной таблицы

//          if  (val[1] != selected_text)
            {
             select.options[select.options.length]=
             new Option(val[1], val[0], false, false);   // добавляем новый объект OPTION к нашему SELECT
            }
       }
    }
    select.selectedIndex = 0;
}
//---------------------------------------------------------------------------------------------------------------


//***************************************************************************************************************
//
//  AJAX CORE
//
//  Denis Korolev
//
function AJAX(url, callback) {
	var request = init();
	request.onreadystatechange = processrequestuest;

	function init(){
		if(navigator.appName == "Microsoft Internet Explorer") return new ActiveXObject("Microsoft.XMLHTTP");
    	else return new XMLHttpRequest();
	}

	function processrequestuest(){
	  if (request.readyState == 4 && request.status == 200) callback( request.responseText);
	}

	this.doGet = function(){
		request.open("get", url, true);
		request.send(null);
	}

	this.doPost = function(body){
		request.open("post", url, true);
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
		request.setRequestHeader("Content-length", body.length);
    	request.setRequestHeader("Connection", "close");
		request.send(body);
	}
}

//******************************************************************************************************
//
//  CSS Class manipulation
//
//  from
//  JavaScript: The Definitive Guide, 5th Edition
//  By David Flanagan
//
var CSSClass = {};  // Create our namespace object
// Return true if element e is a member of the class c; false otherwise
CSSClass.is = function(e, c) {
    if (typeof e == "string") e = document.getElementById(e); // element id

    // Before doing a regexp search, optimize for a couple of common cases.
    var classes = e.className;
    if (!classes) return false;    // Not a member of any classes
    if (classes == c) return true; // Member of just this one class

    // Otherwise, use a regular expression to search for c as a word by itself
    // \b in a regular expression requires a match at a word boundary.
    return e.className.search("\\b" + c + "\\b") != -1;
};

// Add class c to the className of element e if it is not already there.
CSSClass.add = function(e, c) {
    if (typeof e == "string") e = document.getElementById(e); // element id
    if (CSSClass.is(e, c)) return; // If already a member, do nothing
    if (e.className) c = " " + c;  // Whitespace separator, if needed
    e.className += c;              // Append the new class to the end
};

// Remove all occurrences (if any) of class c from the className of element e
CSSClass.remove = function(e, c) {
    if (typeof e == "string") e = document.getElementById(e); // element id
    // Search the className for all occurrences of c and replace with "".
    // \s* matches any number of whitespace characters.
    // "g" makes the regular expression match any number of occurrences
    e.className = e.className.replace(new RegExp("\\b"+ c+"\\b\\s*", "g"), "");
};


//******************************************************************************************************
//
// misc. functions
//
function getBound(object){

  b = getOffset(object);
  var x = b['left'], y = b['top'];

  var width = object.offsetWidth;
  var height = object.offsetHeight;
//  alert ('x=' + x + 'y=' + y + 'w=' + width + 'h=' + height);
  return [x, y, width, height];

}


function getOffset(elem) {
    if (elem.getBoundingClientRect) {
        // "правильный" вариант
        return getOffsetRect(elem)
    } else {
        // пусть работает хоть как-то
        return getOffsetSum(elem)
    }
}

function getOffsetSum(elem) {
    var top=0, left=0
    while(elem) {
        top = top + parseInt(elem.offsetTop)
        left = left + parseInt(elem.offsetLeft)
        elem = elem.offsetParent
    }

    return {top: top, left: left}
}

function getOffsetRect(elem) {
    // (1)
    var box = elem.getBoundingClientRect()

    // (2)
    var body = document.body
    var docElem = document.documentElement

    // (3)
    var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop
    var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft

    // (4)
    var clientTop = docElem.clientTop || body.clientTop || 0
    var clientLeft = docElem.clientLeft || body.clientLeft || 0

    // (5)
    var top  = box.top +  scrollTop - clientTop
    var left = box.left + scrollLeft - clientLeft

    return { top: Math.round(top), left: Math.round(left) }
}



function addEvent(elem, evType, fn) {
	if (elem.addEventListener) {
		elem.addEventListener(evType, fn, false)
                return fn
	}

        iefn = function() { fn.call(elem) }
        elem.attachEvent('on' + evType, iefn)
	return iefn
}

function removeEvent(elem, evType, fn) {
	if (elem.addEventListener) {
		elem.removeEventListener(evType, fn, false)
                return
	}

        elem.detachEvent('on' + evType, fn)
}


function trim( str, charlist ) {
    charlist = !charlist ? ' \\s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}


Number.prototype.getFStr=function(fillNum){var fillNum=fillNum?fillNum:2;var
temp=""+this;while(temp.length<fillNum)temp="0"+temp;return temp;}


function fixEvent(e) {
	// получить объект событие для IE
	e = e || window.event

	// добавить pageX/pageY для IE
	if ( e.pageX == null && e.clientX != null ) {
		var html = document.documentElement
		var body = document.body
		e.pageX = e.clientX + (html && html.scrollLeft || body && body.scrollLeft || 0) - (html.clientLeft || 0)
		e.pageY = e.clientY + (html && html.scrollTop || body && body.scrollTop || 0) - (html.clientTop || 0)
	}

	// добавить which для IE
	if (!e.which && e.button) {
		e.which = e.button & 1 ? 1 : ( e.button & 2 ? 3 : ( e.button & 4 ? 2 : 0 ) )
	}

	return e
}


function setCookie(name, value, expireminutes, path, domain, secure) {
   if (expireminutes) {
      var exdate=new Date();
      exdate.setMinutes(exdate.getMinutes()+expireminutes);
      var expires = exdate.toGMTString();
   }
   document.cookie = name + "=" + escape(value) +
   ((expireminutes) ? "; expires=" + expires : "") +
   ((path) ? "; path=" + path : "") +
   ((domain) ? "; domain=" + domain : "") +
   ((secure) ? "; secure" : "");
}


function setEternalCookie(name, value)
{
// setCookie(name, value, 1, "/");
 setCookie(name, value, 3600*24*365*100, "/");
 }
function unsetCookie(name)
{
 setCookie(name, "", -3600*24*365*100, "/");
 }

function getCookie(name) {
   var cookie = " " + document.cookie;
   var search = " " + name + "=";
   var setStr = null;
   var offset = 0;
   var end = 0;
   if (cookie.length > 0) {
      offset = cookie.indexOf(search);
      if (offset != -1) {
         offset += search.length;
         end = cookie.indexOf(";", offset)
         if (end == -1) {
            end = cookie.length;
         }
         setStr = unescape(cookie.substring(offset, end));
      }
   }
   return setStr;
}


function zerofill(number, length) {
    // Setup
    var result = number.toString();
    var pad = length - result.length;

    while(pad > 0) {
    	result = '0' + result;
    	pad--;
    }

    return result;
}




function findMagicElementsByName(name)
{
        var elArray = [];
        var tmp = document.getElementsByTagName("*");

        var regex = new RegExp("^" + name + ".*");
        for ( var i = 0; i < tmp.length; i++ ) {

            if ( regex.test(tmp[i].name) ) {
                elArray.push(tmp[i]);
            }
        }
        return elArray;
}

