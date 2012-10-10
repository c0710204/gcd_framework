<?php
define("__CFG_document_place__",'/api' );?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<script type="text/javascript" src="js/jquery.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
<link href="css/main.css" type="text/css" rel="stylesheet">
<style type="text/css">
.success:
{
	background-color: green;
	
}

</style>

<script type="text/javascript" src='js/main.js'></script>
</head>
<body>
<div class='ui-widget-content' id='page_out'>
<div id='page' >
<?php 

if (isset($_GET['f'])) include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/backstage/tools/'.$_GET['f'].'.php';


?>
<?php if (isset($_GET['f']))echo '<script type="text/javascript" src="js/'.$_GET['f'].'.js"></script>'?>
<script type="text/javascript" src="js/jquery.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
<script type="text/javascript" src='js/main.js'></script>
</div>
</div>
</body>
</html>