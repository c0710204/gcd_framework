<?php

		include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
		include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
		$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['sqllog']);
	//	$l->getlog();
	?>
	<script>
		function loading()
		{
			var SQLQUERY=document.getElementById('SQLQUERY');
			SQLQUERY.src="<?php echo __CFG_document_place__?>/backstage/tools/showlogpic.php?f=sql-QUERY&long=300&length=60";
			setTimeout('loading()',2000);
		}
		setTimeout('loading()',2000);
		//change.src="validate.php?"+Math.random();
	</script>
	数据库查询数<br>
	<img id="SQLQUERY" src='<?php echo __CFG_document_place__?>/backstage/tools/showlogpic.php?f=sql-QUERY&long=300&length=60'>
