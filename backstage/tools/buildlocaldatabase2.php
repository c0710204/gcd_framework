
<div id='data_trans' style='display: none;'>

<?php
$place_depth=1;
include '../settings/setting.php';
$databases=array();
$temp2[1]='courselist';
$row=array( $temp2[1],'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1]);
array_push($databases,$row);
/*for ($i = 0; $i < 9; $i++) {
	$temp2[1]='classtime';
	$row=array( $temp2[1],'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1].'&DataLimitStart='.$i*1000 .'&DataLimitLength='. 1000);
	array_push($databases,$row);
}*/
if ($handle = opendir('../includes/api/'))
 {
	/* 这是正确地遍历目录方法 */
	 while (false !== ($file = readdir($handle))) 
	 {
	 	$temp=explode('.', $file);
	 	
	 	if ((isset($temp[1]))&&($temp[1]=='php'))
	 	{
	 		$temp2=explode('_', $temp[0]);
	 		if (!(isset($cfg['nocache'][$temp2[1]])))
	 		{
	 			$row=array( $temp2[1],'http://'.$_SERVER['HTTP_HOST'].$cfg['place_api.php'].'databaseCopy.php?table='.$temp2[1]);
		 		array_push($databases,$row);
		
		 	}
	 	}
	 }
	closedir($handle);
	$a['databases']=$databases;
	$json=json_encode($a);
	echo $json;
 }
 ?>
</div>
<table id='list'>
  <tr>
    <th style="width: 100">数据表名称</th>
    <th style="width: 250">处理状态</th>
    <th style="width: 150">结果</th>
  </tr>
</table>


 
 
 
 