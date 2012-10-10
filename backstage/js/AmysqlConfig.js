/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param AmysqlConfig 系统配置
 *
 */
var _HttpPath = '';
var _ContentUrl = 'AmysqlContent.html';
var _TagUrl = 'AmysqlTag.html';			// AmysqlTag
var _LeftUrl = 'AmysqlLeft.html';		// AmysqlLeft
var _AmysqlContent;						// 框架内容
var _AmysqlTag;							// 框架标签
var _AmysqlLeft;						// 框架左栏
var _AmysqlContentLoad = false;
var _AmysqlTagLoad = false;
var _AmysqlLeftLoad = false;



// 设置默认小标签列表
var _AmysqlTabMinJson = [];


// 设置左栏下拉菜单数据
var _AmysqlLNIJson = [
];

// 设置左栏列表数据
/*
var _AmysqlLeftListJson = [
{'id':'MyTable','name':'Amysql MyTable','url':'NewPage.html?MyTable','IcoClass':'ico_database','open':true,'ChildItem':[
	{'id':'MyUser','name':'MyUser','url':'NewPage.html?MyUser','IcoClass':'ico_tabel','ChildItem':null},
	{'id':'MyAddress','name':'MyAddress','url':'NewPage.html?MyAddress','IcoClass':'ico_tabel','ChildItem':null},
	{'id':'MyLog','name':'MyLog','url':'NewPage.html?MyLog','IcoClass':'ico_tabel','open':true,'ChildItem':[
		{'id':'MyLog2009','name':'MyLog MyLog2009','url':'NewPage.html?MyLog2009','IcoClass':'ico_tabel','ChildItem':null},
		{'id':'MyLog2011','name':'MyLog MyLog2011','url':'NewPage.html?MyLog2011','IcoClass':'ico_tabel','open':false,'ChildItem':[
			{'id':'2011-02','name':'2011-02','url':'NewPage.html?2011-02','IcoClass':'ico_tabel','ChildItem':null},
			{'id':'2011-03','name':'2011-03','url':'NewPage.html?2011-03','IcoClass':'ico_tabel','ChildItem':null},
			{'id':'2011-07','name':'2011-07','url':'NewPage.html?2011-07','IcoClass':'ico_tabel','ChildItem':null}
		]},
		{'id':'MyLog2012','name':'MyLog MyLog2012','url':'NewPage.html?MyLog2012','IcoClass':'ico_tabel','ChildItem':null}
		]}
	]
},
{'id':'TestTree','name':'TestTree','url':'NewPage.html?TestTree','IcoClass':'ico_database','ChildItem':null},
{'id':'information_schema','name':'information_schema','url':'NewPage.html?information_schema','IcoClass':'ico_tabel','open':true,'ChildItem':[
	{'id':'CHARACTER_SETS','name':'CHARACTER_SETS','url':'NewPage.html?CHARACTER_SETS','IcoClass':'ico_tabel','ChildItem':null},
	{'id':'TABLES','name':'TABLES','url':'NewPage.html?TABLES','IcoClass':'ico_tabel','ChildItem':null},
	{'id':'COLLATIONS','name':'COLLATIONS','url':'NewPage.html?COLLATIONS','IcoClass':'ico_tabel','ChildItem':null}
]}
];*/
var _AmysqlLeftListJson = [
{
	'id':'data',
	'name':'数据',
	'url':'',
	'IcoClass':'ico_database',
	'open':true,
	'ChildItem':[
		{
		'id':'phpmaker',
		'name':'数据管理',
		'url':'phpmaker/',
		'IcoClass':'ico_database',
		'childItem':null
		},
		{
		'id':'album',
		'name':'相册管理',
		'url':'loader.php?f=showpic',
		'IcoClass':'ico_database',
		'childItem':null
		},
		{
		'id':'video',
		'name':'视频管理',
		'url':'loader.php?f=showvideo',
		'IcoClass':'ico_database',
		'childItem':null
		}
	]
},
{
	'id':'cache',
	'name':'缓存',
	'url':'',
	'IcoClass':'ico_database',
	'open':true,
	'ChildItem':[
		{
		'id':'buildcache',
		'name':'建立缓存',
		'url':'loader.php?f=buildcache',
		'IcoClass':'ico_database',
		'childItem':null
		},
		{
		'id':'clearcache',
		'name':'清理缓存',
		'url':'loader.php?f=clearcache',
		'IcoClass':'ico_database',
		'childItem':null
		}
	]
},
{
	'id':'showlog',
	'name':'运行状态',
	'url':'loader.php?f=showlog',
	'IcoClass':'ico_database',
	'childItem':null
},
{
	'id':'showrealtimestatus',
	'name':'实时运行状态',
	'url':'loader.php?f=showrealtimestatus',
	'IcoClass':'ico_database',
	'childItem':null
}
];

// 设置默认打开的标签列表
var _AmysqlTabJson = [
{'type':'Activate','id':'showlog','name':'运行状态', 'url':'loader.php?f=showlog'}
];
