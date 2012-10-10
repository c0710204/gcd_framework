<a onclick="loader('addpic')" id='BTNaddalbum' class='BTNadd'>添加照片</a>
  显示分类：

<?php

	if ((isset($_GET['typeid']))&&($_GET['typeid']==0)) unset($_GET['typeid']);
	
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/api/action_moduletype.php";
	$modulelist=new moduletype();
	$namelist=$modulelist->gettypenamelist(6);
	$typenamelist=array();
	if (!($namelist<0)){
		echo "<select name=typeid onchange=\"loader('showpic','&typeid='+this.value)\">";
		echo "<option value='0'>全部</option>";
		foreach ($namelist as $line)
		{
		if ((isset($_GET['typeid']))&&($line['id']==$_GET['typeid'])) $str='selected="selected"';
		else $str='';
		echo "<option value='".$line['id']." '$str>".$line['typename']."</option>";
		}
	}
	echo "</select>";
	if (!($namelist<0)){
		foreach ($namelist as $line)
		{
			$typenamelist[$line['id']]=$line['typename'];
		}
	}
	//__CFG_document_place__='';	
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
	$sql=new SQL();
	$sql->table="album";
	
	if (isset($_GET['typeid']))
	{
		array_push($sql->S_where,array('typeid',$_GET['typeid']));
	}
	$res=$sql->select();
	$ans=array();
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/api/drives/picloader.php";
	include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/api/coverpathloader/album.php";
	if ($res>0)
	{
		while ($result=$sql->fetch_assoc($res))
		{
			$row=array();
			$row=$result;			
			$row['picpath']=coverpathloader_album($result,'','default');
			$row['picpath_big']=coverpathloader_album($result,'','defaultbig');
			array_push($ans, $row);
	    }
	}
	foreach ($ans as $line)
	{
		echo "<div>";
		$path=$line['picpath'];
		$id=$line['id'];
		$typeid=$line['typeid'];
		echo "<img src='$path' style='float:left'>";
		echo "<div style='float:left'>";
		if (isset($_GET['typeid'])){
			$tem="'".$_GET['typeid']."'";
			echo "<a href='http://m.bistu.edu.cn/api/api.php?table=album&action=deletephoto&id=$id' onclick=\"loader('showpic','&typeid='+$tem)\" target='_self' class='BTNdel'>删除</a>";
		}
		else
		echo "<a href='http://m.bistu.edu.cn/api/api.php?table=album&action=deletephoto&id=$id' onclick=\"loader('showpic')\" target='_self' class='BTNdel'>删除</a>";
		echo "<a href='http://m.bistu.edu.cn/api/api.php?table=moduletype&action=setalbumcover&from=pic&id=$typeid&coverid=$id' onclick=\"loader('showpic')\" target='_self' class='BTNset'>设定为封面</a><br>";

		echo "title:".$line['title'].'<br>';
		echo "time:".$line['publish'].'<br>';
		echo "type:".$typenamelist[$line['typeid']].'<br>';
		echo "</div>
		<div style='clear:both'></div>
		";
		echo "</div><br>";
	}