<?php 

			include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
?>
<h3>添加视频：<h3>
<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].__CFG_document_place__.'/api.php?table=video&action=addvideo' ?>" method="POST" enctype="multipart/form-data" target='_self' onsubmit='Fsub()'>
	视频标题：<input type="text" name="title"><br>
	视频地址：<input type="text" name="source"  style='width:400px'><br>	
	视频介绍：<br><textarea name='intro'></textarea><br>
	<label for="file">封面图片上传:</label>
	<input type="file" name="upload_pic" id="upload_pic" /> <br>
	视频长度：
	<input type="text" name="timeh" style='width:50px'>小时
	<input type="text" name="timem"style='width:50px'>分
	<input type="text" name="times"style='width:50px'>秒<br>
	视频大小：<input type="text" name="size"><br>
	视频评分：<input type="text" name="rate"><br>
	视频类别：	<select name='typeid'>
<?php
		
	include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__."/includes/api/action_moduletype.php";
	$modulelist=new moduletype();
	$namelist=$modulelist->gettypenamelist(5);
	if (!($namelist<0)){
	foreach ($namelist as $line)
		echo "<option value='".$line['id']."'>".$line['typename']."</option>";
	}
	?>
	</select><br><br>
	<input id='submit' onclick='submitjs()' type='submit'>
<!--		<button onclick="loader('showvideo')" >返回</button> -->
</form>

