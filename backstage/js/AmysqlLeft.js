/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param Object AmysqlLeftListObject
 * AmysqlLeft 左栏列表对象 
 *
 */
var AmysqlLeftListObject = function ()
{
	this.AmysqlLeftList = null;
	this.list = C('ul');
	this.CopyRight =  C('A', {'className':'ico2 ico_copyright','id':'copyright','title':'Powered by Amysql'});	
	this.ExtendObject = [];
	this.LastClickItem = {};				// 最后点击项

	this.add = function (json, parentE)
	{
		var ul = (parentE) ? parentE: this.list;
		ul.Item = [];
		for (var k in json)
		{
			var i = ul.Item.length;
			var li = C('li',{'id':json[k].id,'name':json[k].name,'url':json[k].url});
			li.dl = C('dl');
			li.dd = C('dd', {'className':(json[k].ChildItem) ? (json[k].open ? 'closelist':'openlist') : ''});
			li.dt = C('dt','', {'whiteSpace':'nowrap'});
			li.dt.i = C('i',{'className':json[k].IcoClass});
			li.dt.a = C('a',{'innerHTML':json[k].name});
			C(li,'In',C(li.dl,'In',[li.dd,C(li.dt,'In',[li.dt.i,li.dt.a])]));
			ul.Item[i] = li;
			
			if (json[k].ChildItem != null)
			{
				ul.Item[i].ChildItem = C('ul','',{'display':(json[k].open)? 'block':'none'});
				C(ul.Item[i], 'In', ul.Item[i].ChildItem);
				this.add(json[k].ChildItem, ul.Item[i].ChildItem);
			}
			C(ul,'In',ul.Item[i]);

			(function (o, t)
			{
				o.dt.onclick = function (e)
				{
					if(o.ChildItem)
					{
						o.ChildItem.style.display = 'block';
						o.dd.className = (o.ChildItem.style.display == 'none') ? 'openlist':'closelist';
					}
					if(o.url != '')parent.OpenWindow('Activate', o.id, o.name, o.url);
					
					AmysqlLeftList.CopyAutoHeight();
					t.RunExtend('ListClick');
					t.LastClickItem = o;
				}
				o.dd.onclick = function ()
				{
					o.ChildItem.style.display = o.ChildItem.style.display == 'block' ? 'none':'block';
					var status = (o.ChildItem.style.display == 'none') ? 'openlist':'closelist';
					this.className = status;
					AmysqlLeftList.CopyAutoHeight();
					t.RunExtend(status);
				}
			})(ul.Item[i], this)

		}
		if(!parentE) C(parentE,'In',ul);
		return true;
	}
	this.CopyAutoHeight = function ()
	{
		// copyright show
		C(this.CopyRight,'',{'display':getClientHeight()-70-AmysqlLNO.LeftNavigationDom.clientHeight > this.AmysqlLeftList.scrollHeight ? 'block':'none'});
	}

	this.run = function ()
	{
		if(this.AmysqlLeftList == null)
		{
			this.AmysqlLeftList = G('AmysqlLeftList');
			C(this.AmysqlLeftList,'In',this.list);
			if(this.CopyRight)
			{
				C(window.document.body, 'In', this.CopyRight);
				this.CopyRight.onclick = function ()
				{
					parent.OpenWindow('Activate', 'black', '', 'about:black');
				}
			}
			this.CopyAutoHeight();
			this.RunExtend('run');
		}
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




