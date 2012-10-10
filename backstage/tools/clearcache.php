<?php

if ($handle = opendir('../cache'))
 {
	echo "cache Files:<br/>";

	/* 这是正确地遍历目录方法 */
	 while (false !== ($file = readdir($handle))) 
	 {
	 	$temp=explode('.', $file);
	 
	 	if ((isset($temp[1]))&&($temp[1]=='json'))
	 	{
	 		echo "$file<br/>";
	 		unlink('../cache'.'/'.$file);
	 	}
	 }
	closedir($handle);
	echo "cache clear!";
 }
 