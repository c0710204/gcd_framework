<?php
include "../../../settings/setting.php";

function buildxml($dom,$head,$data)
{
	
	if (isset($data))
	{
		
	foreach ($data as $key=>$val)
	{
		if (!(is_array($val)))
		{
		//	echo $val.'<br>';
			$now = $dom->createElement($key);
			$head->appendChild($now);
			$now1 = $dom->createTextNode($val);
			$now->appendChild($now1);
		}
		else
		{	
			var_dump($val);
			$now = $dom->createElement($key);
			buildxml($dom,$now,$val);
			$head->appendChild($now);
			echo $dom->saveXML();
			
					
		}
	//	echo $dom->saveXML();
		echo '<br>';
	}
	 //打开要写入 XML数据的文件
	}
}
$dom1 = new DomDocument();
$settings = $dom1->createElement('settings');
$dom1->appendChild($settings);
buildxml($dom1,$settings,$cfg);

$xml=$dom1->saveXML();

$fp = fopen('newxml.xml', 'w'); //打开要写入 XML数据的文件
fwrite($fp, $xml); //写入 XML数据
fclose($fp); //关闭文件
//$xmlstr=$xml->asXML();
//echo $xmlstr;