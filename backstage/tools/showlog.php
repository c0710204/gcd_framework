<?php

		include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
		include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
		if (isset($data['filename']))
		$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log'][$data['filename'].'log']);
	?>
	<script>
		//setTimeout("self.location.reload();",10000);
	</script>
	<img src='<?php echo __CFG_document_place__?>/backstage/tools/showlogpic.php?f=sql-QUERY&long=300&length=60'?">
	
