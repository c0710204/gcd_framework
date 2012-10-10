<?php
//GLOSET
date_default_timezone_set("Asia/Shanghai");


//build-mode-set
//$cfg['build']="mobile";
$cfg['build']="local";

include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/settings/".$cfg['build'].".php";

//API-Cache
$cfg['nocache']['moduleupdate']=1;
$cfg['nocache']['test']=1;
$cfg['nocache']['courselist']=1;
$cfg['nocache']['courseStudent']=1;
$cfg['nocache']['intro']=1;
$cfg['nocache']['helper']=1;
$cfg['nocache']['member']=1;
$cfg['nocache']['activity']=1;
$cfg['nocache']['activitytime']=1;
$cfg['nocache']['album']=1;
$cfg['nocache']['moduletype']=1;
$cfg['nocache']['video']=1;
$cfg['nocache']['newschannel']=1;
$cfg['nocache']['newslist']=1;
$cfg['nocache']['errorlog']=1;
$cfg['nocache']['card']=1;
$cfg['nocache']['news']=1;
//$cfg['nocache']['classtime']=1;

$cfg["default_class"]='con1';

//NEWS-trs
//include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/settings/trs.php";



//json-loader
//$json=file_get_contents($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/settings/".$cfg['build'].".json");
//$cfg_ser=json_decode($json);

//foreach ($cfg_ser as $key=> $val)
	//{
	//	$cfg[$key]=$val;
	//}
?>

