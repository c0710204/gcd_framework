<?php
class talk extends model
{
	private $sql_sortbytimedesc='select * from gcd.talk order by time desc ';
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