<?php
	define("__CFG_document_place__",'/' );
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/core/core.php';
	//url路由
	  $uri = $_SERVER['REQUEST_URI'];  
		$link= explode('.php',$uri);
	$pathinfo=parse_url($link[1]);
	
	$GET=$_GET;
	$url=$pathinfo['path'];
	$array=explode('/', $url);
	array_shift($array);
	$class=array_shift($array);
	$function=array_shift($array);
	$queryarray=$array;
	
	//url路由结束
	if (!(isset($class))) $class=$cfg["default_class"];
	if (file_exists($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/controller/$class.php"))
	{
		include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/controller/$class.php";
		$maincontroller=new $class();
	}
	else
	{
		echo '404找不到指定页面-class';
	
		exit(0);
	}
	if (!(isset($function))) $function=$maincontroller->default_function;
	if (!(method_exists($maincontroller, $function))){
		echo '404找不到指定页面-'.$function;
		exit(0);
	}
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/core/log/logger.php';
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
	$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
	
	$l->writelog($class.'-'.$function.json_encode($GET),'api-QUERY');
	//ob_start();
	
	$maincontroller->$function();
	//ob_end_flush();


