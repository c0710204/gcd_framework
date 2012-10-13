<?php
class story extends model
{
	private $sql_limit50sortbytimedesc='select * from gcd.storys order by time desc limit 0,50 ';
	function __autoload()
	{
		$this->sql=new SQL();
		$this->sql->table='story';
		
	}
	function loaddata($sqlselect)
	{
		$sqllink='sql_'.$sqlselect;
		$sql=$this->$sqllink;
		$sqller=new SQL();
		$sqller->query($sql);
		return $sqller;
	}
	
}