<?php	
	define("__CFG_document_place__",'/api');
	
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/phplot.php';
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
	$file=explode('-',$_GET['f']);
		$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log'][$file[0].'log']);
//	$l->getlog();
	$data=$l->getlogdata($_GET['long'],$_GET['f'],$_GET['length']);
//	var_dump($data);
	$plot = new PHPlot(400,200);
	$plot->SetDataValues($data);
	$plot->SetDataType('text-data');
	$plot->SetPlotType('lines');
	$plot->DrawGraph();
	
