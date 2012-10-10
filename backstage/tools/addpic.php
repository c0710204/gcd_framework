<?php 

			include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
?>
<h3>添加照片：<h3>
<form id='forminput' action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].__CFG_document_place__.'/api.php?table=album&action=addphoto' ?>" method="POST" enctype="multipart/form-data" target='_self' onsubmit='Fsub()'>
	图片标题：<input type="text" name="title"><br>
	图片介绍：<br><textarea name='intro'></textarea><br>
	<label for="file">图片上传:</label>
	<input type="file" name="upload_pic" id="upload_pic" /> <br>
	图片类别：
	<select name='typeid'>
<?php
		
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/api/action_moduletype.php";
	$modulelist=new moduletype();
	$namelist=$modulelist->gettypenamelist(6);
	if (!($namelist<0)){
	foreach ($namelist as $line)
		echo "<option value='".$line['id']."'>".$line['typename']."</option>";
	}
	?>
	</select><br>
	<input id='BTNsubmit'  type='submit'>
	<a  onclick="loader('showpic')" >返回</a> 
</form>
