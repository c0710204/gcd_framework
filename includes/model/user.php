<?php
class Muser extends model {
	protected  $sql_getuserdata = "select * from gcd.user where token='\$token'";
	protected $sql_getuserid ="select id from gcd.user where token='\$token' limit 1";
	protected $sql_login="select count(*) as success from gcd.user where email='\$email' and password='\$pass'";
	protected $sql_settoken="UPDATE `gcd`.`user` SET `token`='\$token' WHERE `email`='\$email'";
	protected $sql_logout="UPDATE `gcd`.`user` SET `token`='0' WHERE `token`='\$token' and `email`='\$email'";
	protected $sql_signup=" INSERT INTO `gcd`.`user` (`email`, `name`, `password`) VALUES ('\$email', '\$name', '\$password');";
	function __autoload() {
		$this->sql = new SQL ();
		$this->sql->table = 'story';
	}


}