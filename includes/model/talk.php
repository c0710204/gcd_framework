<?php
class Mtalk extends model
{
	private $sql_sortbytimedesc='select * from gcd.talk order by time desc ';
	function __autoload()
	{
		$this->sql=new SQL();
		$this->sql->table='story';
		
	}

	
}