
<?php
/*
define("__CFG_document_place__",'/framework' );
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$start=time();
$index=$_GET['long'];

$sql=new SQL();
$sql->table='booklist';
for ($i = 0; $i < $index; $i++) 
{
	$bookid=$_GET['bookids']+$i;

$sql->I_data['id']=$bookid;

$url='http://www.qidian.com/Book/'.$bookid.'.aspx';
$html=file_get_contents($url);
$title=strpos($html,'<div class="title">',0);
$titles=strpos($html,'<h1>',$title)+4;
$titlee=strpos($html,'</h1>',$titles)-$titles;
$sql->I_data['name']=substr($html,$titles,$titlee)."\n";
$s=strpos($html,'<div class="info_box">',0);
//echo $s.'<br>';
$zt=strpos($html,'<b>写作进程：</b>',$s)+22;
//echo $zt.'<br>';
$ztend=strpos($html,'</td>',$zt)-$zt;
$zs=strpos($html,'<b>完成字数：</b>',$s)+22;
$zsend=strpos($html,'</td>',$zs)-$zs;
$lx=strpos($html,'<b>小说类别：</b>',$s)+22;
$lx1=strpos($html,'blank">',$lx)+7;
$lxend=strpos($html,'</a>',$lx1)-$lx1;
$sql->I_data['status']=substr($html,$zt,$ztend)."\n";
$sql->I_data['length']= substr($html,$zs,$zsend)."\n";
$sql->I_data['type']= substr($html,$lx1,$lxend)."\n";


@$sql->insert();
}
echo time()-$start;





*/
define("__CFG_document_place__",'/framework' );
set_time_limit(18000) ;
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$sql=new SQL();
//sql::$debug=true;
$sql->table='booklist';
for ($index=1;$index<170;$index++)
{

	

$url='http://all.qidian.com/book/bookStore.aspx?ChannelId=12&SubCategoryId=10&Tag=all&Size=-1&Action=-1&OrderId=6&P=all&PageIndex='.$index.'&update=-1&Vip=-1&Boutique=-1&SignStatus=-1';
$l->writelog($index,'api-QUERY');
$html=file_get_contents($url);
$s=strpos($html,'<!--[if !IE]> 结果列表 开始 <![endif]-->',0)+46;
$e=strpos($html,' <!--[if !IE]> 结果列表 结束 <![endif]-->',$s)-$s;
$list=substr($html,$s,$e);
$e=0;
$arr=array();
for ($i = 0; $i < 100; $i++) {
	$s=strpos($list,'<div class="swa">',$e)+26;
	$e=strpos($list,'<div class="swe">',$s);
	$info=substr($list,$s,$e-$s);
	//id
	$ids=strpos($info,'/Book/',0)+6;
	$ide=strpos($info,'.asp',$ids)-$ids;
	$sql->I_data['id']=substr($info,$ids,$ide);
	//name
	$ns=strpos($info,'"_blank">',$ide)+9;
	$ne=strpos($info,'</a>',$ns)-$ns;
	$sql->I_data['name']=substr($info,$ns,$ne);
	//zs
	$zss=strpos($info,'<div class="swc">',$ns)+17;
	$zse=strpos($info,'</div>',$zss)-$zss;
	$sql->I_data['length']=substr($info,$zss,$zse);
	/*
	$ids=strpos($list,'/Book/',0)+6;
	$ide=strpos($list,'.asp',$ids)-$ids;
	$sql->I_data['id']=substr($list,$ids,$ide);*/
	array_push($arr, $sql->I_data);
	$sql->insert();
}
}
//echo json_encode($arr);
/*
 * 
 * http://all.qidian.com/book/bookstore.aspx?
 * ChannelId=12&
 * SubCategoryId=-1&
 * Tag=all&Size=-1
 * &Action=-1&
 * OrderId=6&
 * P=all&
 * PageIndex=2
 * &update=-1&
 * Vip=-1&
 * Boutique=-1&
 * SignStatus=-1
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * /
 */