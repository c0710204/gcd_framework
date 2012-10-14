<?php
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/core/database/sql.php';
class model
{
	
	private $sql;
	private $lastdata;
	function __toString()
	{
		
	}
	function __autoload()
	{
		
		
	}	
	function loaddata($sqlselect,$datas=array()) {
		$sqllink = 'sql_' . $sqlselect;
		$sql = $this->$sqllink;
		extract($datas);
		eval('$sql="'.$sql.'";');
		$sqller = new SQL ();
		$sqller->query ( $sql );
		//var_dump($this->lastdata=$sqller->getall());
		return $sqller;
	}
	function loadval($key)
	{
		try {
		return $this->lastdata[$key] ;
		}
		catch (Exception $e){
		return false;}
	}
	
}