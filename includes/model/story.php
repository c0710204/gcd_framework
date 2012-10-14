<?php
class Mstory extends model {
	protected $sql_limit50sortbytimedesc = 'select gcd.storys.*,gcd.user.`name` from gcd.storys,gcd.user where gcd.user.id=gcd.storys.upuid order by gcd.storys.time desc limit 0,50';
	protected $sql_insertstory = "inSERT INTO `gcd`.`storys` (`time`, `story`, `upuid`, `like`, `unlike`) VALUES (now(), '\$story', '\$upuid', '0', '0')";
	protected $sql_vote_1="UPDATE `gcd`.`storys` SET `like`=`like`+1 WHERE `id`='\$id';";
	protected $sql_vote_2="UPDATE `gcd`.`storys` SET `unlike`=`unlike`+1 WHERE `id`='\$id';";
	function __autoload() {
		$this->sql = new SQL ();
		$this->sql->table = 'story';
	}
}