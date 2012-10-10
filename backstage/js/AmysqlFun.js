/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param Object 常用函数
 *
 */

// 创建元素 
// attr 元素的属性
// CssOrHtml CSS或节点内容
var C = function (tag, attr, CssOrHtml)
{
	var o = (typeof(tag) != 'object') ? document.createElement(tag) : tag;
	if (attr == 'In')
	{
		if(CssOrHtml  && typeof(CssOrHtml) == 'object') 
		{
			if(CssOrHtml.length > 1 && CssOrHtml.constructor == Array )
			{
				for (x in CssOrHtml)
					if(CssOrHtml[x]) o.appendChild(CssOrHtml[x]);
			}
			else
			    o.appendChild(CssOrHtml);
		}
		else
			o.innerHTML = CssOrHtml;
		return o;
	}

	if (typeof(attr) == 'object')
	{
		for (k in attr )
			if(attr[k] != '') o[k] = attr[k];
	}

	if (typeof(CssOrHtml) == 'object')
	{
	    for (k in CssOrHtml )
			if(CssOrHtml[k] != '') o.style[k] = CssOrHtml[k];
	}
	return o;
}
// 取得元素
var G = function (id) {return document.getElementById(id); }

// 获取class元素
var getElementByClassName = function (cls,elm) 
{  
	var arrCls = new Array();  
	var seeElm = elm;  
	var rexCls = new RegExp('(^|\\\\s)' + cls + '(\\\\s|$)','i');  
	var lisElm = document.getElementsByTagName(seeElm);  
	for (var i=0; i<lisElm.length; i++ ) 
	{  
		var evaCls = lisElm[i].className;  
		if(evaCls.length > 0 && (evaCls == cls || rexCls.test(evaCls))) 
			arrCls.push(lisElm[i]);  
	}  
	return arrCls;  
}

// 取得样式属性值 
function getStyle(o,s)
{
	// firefox,opera,safari
	if(document.defaultView)
		return document.defaultView.getComputedStyle(o,null).getPropertyValue(s);
	else 
	{
		s=s.replace(/\-([a-z])([a-z]?)/ig,function(s,a,b){return a.toUpperCase()+b.toLowerCase();});
		return o["currentStyle"][s];	// ie 转化为驼峰写法
	}
}

var is_null = function (str)
{
	if (!str && typeof(str)!="undefined" && str!=0) return true;
	return false;
}
var HTMLEnCode = function (str)
{
	if (is_null(str)) return '<i>NULL</i>';
	var s="";
	if(str.length==0) return '';
	s=str.replace(/&/g,"&amp;");
	s=s.replace(/</g,"&lt;");
	s=s.replace(/>/g,"&gt;");
	s=s.replace(/\'/g,"'");
	s=s.replace(/\"/g,"&quot;");
	s=s.replace(/\n/g,"<br>");
	return s;
}
// 过滤空格
var trim = function (val)
{
	if(typeof(val) == 'object')
	{
		var new_val = new Array();
		for(var i = 0; i < val.length; i++) 
			if(trim(val[i]).length > 0) new_val.push(val[i]); 
		return new_val;
	}
	else
		return val.replace(/(^\s*)|(\s*$)/g, "");
}

// 取窗口滚动条高度 
var getScrollTop = function ()
{
	var scrollTop=0;
	if(document.documentElement&&document.documentElement.scrollTop)
		scrollTop=document.documentElement.scrollTop;
	else if(document.body)
		scrollTop=document.body.scrollTop;
	return scrollTop; 
}
// 取窗口滚动条宽度 
var getScrollLeft = function ()
{
	var scrollLeft=0;
	if(document.documentElement&&document.documentElement.scrollLeft)
		scrollLeft=document.documentElement.scrollLeft;
	else if(document.body)
		scrollLeft=document.body.scrollLeft;
	return scrollLeft; 
}

// 取窗口可视范围的高度
var getClientHeight = function ()
{
	return Math.max(document.body.clientHeight,document.documentElement.clientHeight);
}

// 取文档内容实际高度
var getScrollHeight = function ()
{
	return Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);
}

// 滚动条到某位置(ID)
var GoLocation = function (id)
{
	// G(id).innerHTML = 's';
	document.documentElement.scrollTop = G(id).offsetTop-35; 
	document.body.scrollTop = G(id).offsetTop;
}
// 滚动条到某位置
var set_location_top = function (val)
{
	document.documentElement.scrollTop = val;
	document.body.scrollTop = val;
}

// Cookie操作对象
var Cookies = {};
Cookies.set = function(name, value){
     var argv = arguments;
     var argc = arguments.length;
     var expires = (argc > 2) ? argv[2] : null;
     var path = (argc > 3) ? argv[3] : '/';
     var domain = (argc > 4) ? argv[4] : null;
     var secure = (argc > 5) ? argv[5] : false;
     document.cookie = name + "=" + escape (value) +
       ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
       ((path == null) ? "" : ("; path=" + path)) +
       ((domain == null) ? "" : ("; domain=" + domain)) +
       ((secure == true) ? "; secure" : "");
};
Cookies.get = function(name){
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    var j = 0;
    while(i < clen){
        j = i + alen;
        if (document.cookie.substring(i, j) == arg)
            return Cookies.getCookieVal(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if(i == 0)
            break;
    }
    return null;
};
Cookies.del = function(name) {
  if(Cookies.get(name)){
    var expdate = new Date(); 
    expdate.setTime(expdate.getTime() - (86400 * 1000 * 1)); 
    Cookies.set(name, "", expdate); 
  }
};
Cookies.getCookieVal = function(offset){
   var endstr = document.cookie.indexOf(";", offset);
   if(endstr == -1){
       endstr = document.cookie.length;
   }
   return unescape(document.cookie.substring(offset, endstr));
};

// 鼠标X坐标
function positionX(evt) {
	if (evt.pageX) return evt.pageX;
	else if (evt.clientX)
	   return evt.clientX + getScrollLeft();
	else return null;
}
// 鼠标Y坐标
function positionY(evt) {
	if (evt.pageY) return evt.pageY;
	else if (evt.clientY)
	   return evt.clientY + getScrollTop();
	else return null;
}

// 判断字段是否时间类型
function IsTimeType(val)
{
	val = val.toUpperCase();
	if (val == 'DATE')
		return 'yyyy-MM-dd';
	else if (val == 'DATETIME' || val == 'TIMESTAMP')
		return 'yyyy-MM-dd HH:mm:ss';
	else if (val == 'YEAR')
		return 'yyyy';
	else if (val == 'TIME')
		return 'HH:mm:ss';
	else 
		return false;
}

// 生成下拉框
function CreatesSelect (arr, name)
{
	if(typeof(name) != 'string') name = '';
	name = name.toUpperCase();
	var selected = false;
	var S = C('select');
	for (x in arr )
	{
		if (typeof(arr[x]) != 'object')
		{
			var O = C('option');
			S.options.add(O);
			var arr_split = arr[x].split('|');
			if(arr_split.length > 1)
			{
				O.text = arr_split[0];
				O.value = arr_split[1];
			}
			else
				O.text = O.value = arr[x];

			if(O.text.toUpperCase() == name) 
				selected = O.selected = true;
		}
		else
		{
		    var O = C('optgroup');
			O.label = arr[x][0];
			for (xx in arr[x][1] )
			{
				var SO = C('option');
				O.appendChild(SO);
				var arr_split = arr[x][1][xx].split('|');
				if(arr_split.length > 1)
				{
					SO.text = arr_split[0];
					SO.value = arr_split[1];
				}
				else
					SO.value = SO.text = arr[x][1][xx];
				if(SO.text.toUpperCase() == name && !selected) SO.selected = true;
			}
			S.appendChild(O);
		}
	}
	return S;
}

// 判断sql的字段是否都在当前数据表的字段当中
var sqlField_IN_TableField = function (arr)
{
	var table_field_arr = new Array();
	for (key in table_object.Item )
		table_field_arr.push(table_object.Item[key].Field);
	for (key in arr )
	{
		if(('`' + table_field_arr.join('`,`') + '`').indexOf('`' + arr[key].val + '`') == -1 || G('SearchFormTable').value != arr[key].table) 
			return false;
	}
	return true;
}


var if_firefox = function ()
{
	if(isFirefox=navigator.userAgent.indexOf("Firefox")>0) return true;
	return false;
}

var if_ie = function ()
{
	if(navigator.userAgent.indexOf("MSIE")>0)  return true;
	return false;
}

// 阻止冒泡
var cancelBubble = function (event)
{
	if (event && event.stopPropagation) 
		event.stopPropagation();
	else 
		window.event.cancelBubble = true;
}

// 打开新窗口
var OpenWindow = function (type,id,text,action)
{
	var TagObject = window.frames.AmysqlTag;
	var TagItem = TagObject.CreateItemObject(type,id,text,action);
	TagObject.AmysqlTabObject.add(TagItem);

}