<?php
  $uri = $_SERVER['REQUEST_URI'];  
	$link= explode('.php',$uri);
$pathinfo=parse_url($link[1]);
echo $pathinfo['path'];

var_dump( $_GET);
