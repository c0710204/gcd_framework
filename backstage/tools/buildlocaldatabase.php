<?php	define("__CFG_document_place__",'/api' );
$start=time();
ini_set('default_socket_timeout','300');
$place_depth=1;
include '../settings/setting.php';

if ($handle = opendir('../includes/api/'))
 {
	echo "Databases :<br/>";
	$temp2[1]='courselist';
	echo 'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1];
	$len=file_get_contents('http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1]);
	echo "\t处理$len<br/>";
	/* 这是正确地遍历目录方法 */
	 while (false !== ($file = readdir($handle))) 
	 {
	 	$temp=explode('.', $file);
	 	
	 	if ((isset($temp[1]))&&($temp[1]=='php'))
	 	{
	 		$temp2=explode('_', $temp[0]);
	 		if (!(isset($cfg['nocache'][$temp2[1]])))
	 		{
		 		echo "$temp2[1]";
		 //		echo 'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1];
		 		$len=file_get_contents('http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1]);
		 		echo "	$len<br/>";
		 	}
	 	}
	 }
	closedir($handle);
	echo 'build success';
 }
 ini_set('default_socket_timeout','60');
 echo time()-$start;