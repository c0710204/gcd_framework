/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param Object AmysqlContent 内容对象
 *
 */

// 创建内容子项
// id 标识
// name 名称
// command 命令
// status 状态
var AmysqlContentItem = function (id, name, command, status)
{
	this.Name = name;
	this.command = command;
	this.Status = status;			// open or close
	this.OriginalId = id;
	this.Id = id;					// 唯一ID
	this.src = null;				// frame的URL
	this.key = null;				// 在列表的位置

	this.src = (command != '') ? command : 'about:blank';
	this.I = C('frame', {'id':this.Id});
	this.I.name = this.Id;
	this.I.src = 'about:blank';
	if(this.src.indexOf('http://') == -1)	// 其它域的frames没权限写Loading操作
		this.I.src = this.src;
	this.I.scrolling = "auto";

}

var AmysqlContent = function ()
{
	this.ContentList = null;				// 内容列表元素
	this.Item = new Array();
	this.RowsSet = null;
	this.OpenId = 0;
	this.LoadRefresh = true;				// 加载是否刷新
	this.PlugInCommand = []					// 加载插件集合
	this.LastClickItem = {};				// 最后所在内容项
	this.ExtendObject = {};

	// 增加ContentItem
	this.AddAction = function (ContentItem)
	{		
		if(ContentItem.Status == 'open') this.OpenId = ContentItem.Id; // 最后打开的Id用于激活
		var Item_sum = this.Item.length;
		for (var i = 0; i < Item_sum; i++)
		{
			if(this.Item[i].Id == ContentItem.Id) 
			{
				// 存在重载、激活当前内容项 返回false不做增加
				if(ContentItem.src != 'about:blank' && this.LoadRefresh)
				{
					this.LoadAction(this.Item[i], true);
				}
				this.activationAction();
				this.LoadRefresh = true;
				return false;
			}
		}
		ContentItem.key = Item_sum;
		this.Item[Item_sum] = ContentItem;

		if(Item_sum > 0)
		{
			this.ContentList.insertBefore(ContentItem.I, this.ContentList.firstChild);	// 增加至内容DOM列表最前面
		}
		else
		{
			this.ContentList.appendChild(ContentItem.I);	// 增加至内容DOM列表
		}
		this.activationAction();	// 激活

		this.LoadAction(ContentItem);
		this.RunExtend('AddAction');
	}

	// 激活ContentItem
	this.activationAction = function (id)
	{
		if(id) this.OpenId = id;	// 有ID直接激活
		this.RowsSet = new Array();
		// 生成ContentList的rows属性值 ContentItem我们增加时是加至最前面的 
		// 最后的Item的rows属性值也就是数组RowsSet最前面的值
		var sum = this.Item.length - 1;
		for (; sum >= 0 ; sum--)
		{
			// &&  this.Item[sum].Id == id this.Item[sum].Status == 'open'
			if (this.Item[sum].Id == this.OpenId )
			{
				this.LastClickItem = this.Item[sum];
			    this.RowsSet.push('100%');
			}
			else
			{
				this.Item[sum].Status = 'close';
				this.RowsSet.push('0');
			}
		}
		this.ContentList.rows = this.RowsSet;
		this.RunExtend('activationAction');
	}

	// 加载	(Refresh是否重新加载)
	this.LoadAction = function (Item, Refresh)
	{
		// 直接加载插件
		for (var k in this.PlugInCommand )
		{
			if(this.PlugInCommand[k] == Item.command)
			{
				var content = this.blank_page('<script src="' + Item.command + '"></script>');
				if (navigator.userAgent.indexOf("Opera") > -1)	// Opera特殊处理
				{
					setTimeout(function (){
						window.frames[Item.Id].document.open();
						window.frames[Item.Id].document.write(content);
						window.frames[Item.Id].document.close();
					},0);
				}
				else
				{
				    window.frames[Item.Id].document.open();
					window.frames[Item.Id].document.write(content);
					window.frames[Item.Id].document.close();
				}
				return false;
			}
		}

		if(Refresh)
		{
			// 创建新对象
			var new_item = new AmysqlContentItem(Item.OriginalId, Item.Name, Item.command, Item.Status);
			new_item.key = Item.key;
			this.DelAction(Item.key, Item.Id, new_item);	// 替换掉原先的
			var tempNumber = this.Item.length - parseInt(Item.key) - 1;
			this.ContentList.insertBefore(new_item.I, this.ContentList.childNodes[tempNumber]);	// 在原先位置加入
		}

		// IE location跳转没有用上BASE，直接跳原先地址。其它浏览器地址就可以忽略掉根目录 因为有BASE。 (index.php/index/AmysqlHome)。有http直接跳转
		if(if_ie() && Item.src.indexOf('http://') == -1) var url = window.frames[Item.Id].window.location + '';		

		var content = this.blank_page('<div id="LoadingBlock">&nbsp; Loading...<div id="loading"></div></div>');

		if (navigator.userAgent.indexOf("Opera") > -1)	// Opera特殊处理
		{
			setTimeout(function (){
				window.frames[Item.Id].document.open();
				window.frames[Item.Id].document.write(content);
				window.frames[Item.Id].document.close();
			},0);
		}
		else
		{
		    window.frames[Item.Id].document.open();
			window.frames[Item.Id].document.write(content);
			window.frames[Item.Id].document.close();
		}
		
		// 延时100毫秒
		setTimeout(
			function () {
					window.frames[Item.Id].location = url ? url : Item.src;
			}, 200
		);
		this.RunExtend('LoadAction');
	}
	
	// 删除ContentItem
	this.DelAction = function (Listkey, id, replace_item)
	{
		var tempNumber = this.Item.length - 1 - parseInt(Listkey);
		if(replace_item)
			this.Item.splice(Listkey, 1, replace_item);
		else 
			this.Item.splice(Listkey, 1);

		this.ContentList.removeChild(this.ContentList.childNodes[tempNumber]);
		if(!document.all)  delete window.frames[id];	// firefox 需删掉这个

		// 更新位置
		var len = this.Item.length;
		for (var i = 0 ; i <= len ; ++i)
			if(this.Item[i]) this.Item[i].key = i;
		this.RunExtend('DelAction');
	}

	this.blank_page = function (content)
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' + 
		'<html xmlns="http://www.w3.org/1999/xhtml">' + 
		'<head><title></title>' + 
		'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' + 
		'<link type="text/css" rel="stylesheet" href="templet/Default/silverStyle.css" />' + 
		'<script src="js/AmysqlFun.js"></script><script>var ExtendContent = true;</script>' +
		'</head>' + 
		'<body id="body">' + content + ' </body></html>';
	}

	// 初始化
	this.run = function ()
	{
		this.ContentList = G('content');
		this.RunExtend('run');
	}

	this.RunExtend = function (id)
	{
		for (var k in this.ExtendObject[id])
			this.ExtendObject[id][k]();
	}

	this.extend = function (functions, id)
	{
		if(typeof(id) != 'object') id = [id];
		for (var k in id )
		{
			if(!this.ExtendObject[id[k]])
				this.ExtendObject[id[k]] = [];
			this.ExtendObject[id[k]].push(functions);
		}
	}
}


// ********************** 提供接口 ****************************

// 创建内容对象
var CreateAmysqlContentItemObject = function (id, name, command, status)
{
	return new AmysqlContentItem(id, name, command, status);
}