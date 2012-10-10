<?php 

			include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
?>
<h3>添加分类：<h3>
<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].__CFG_document_place__.'/api.php?table=moduletype&action=addmoduletype' ?>" method="post" target='_black'>
	分类标题：<input type="text" name="typename"><br>
分类<select name='moduleid'>
<?php
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
	$sql=new SQL();
	$sql->table='modulelist';
	$res=$sql->select();
	if (!($res<0))
	{
		while ($line=$sql->fetch_assoc($res))
		echo "<option value='".$line['id']."'>".$line['modulename']."</option>";
	}
	?>
	</select><br><br>
	<input type='submit'>
</form>

