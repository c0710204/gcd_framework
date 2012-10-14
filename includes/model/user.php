<?php
class Muser extends model {
	protected  $sql_getuserdata = "'select * form gcd.user where token='\$token'";
	protected $sql_getuserid ="select id from gcd.user where token='\$token' limit 1";
	protected $sql_login="select count(*) as success from gcd.user where name='\$name' and password='\$pass'";
	protected $sql_settoken="UPDATE `gcd`.`user` SET `token`='\$token' WHERE `name`='\$name'";
	function __autoload() {
		$this->sql = new SQL ();
		$this->sql->table = 'story';
	}


}