<?php
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';

if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/api/'))
 {
	echo "Apis :<br/>";

	/* 这是正确地遍历目录方法 */
	 while (false !== ($file = readdir($handle))) 
	 {
	 	$temp=explode('.', $file);
	 	
	 	if ((isset($temp[1]))&&($temp[1]=='php'))
	 	{
	 		$temp2=explode('_', $temp[0]);
	 		if (isset($temp2[1])&&(!(isset($cfg['nocache'][$temp2[1]]))))
	 		{
		 		echo "$temp2[1]";
		 		//echo 'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'api.php?table='.$temp2[1].'&zipmode=noout';
		 		file_get_contents('http://'.$_SERVER['HTTP_HOST'].__CFG_document_place__.'/api.php?table='.$temp2[1].'&zipmode=noout');
		 		echo "<br/>";
		 	}
	 	}
	 }
	closedir($handle);
	echo 'build success';
 }