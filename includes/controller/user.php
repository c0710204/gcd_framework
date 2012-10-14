<?php
class user extends controller {
	function main() {
		$storys = $this->loadmodel ( 'story' );
		$this->loadview ( "head.html" );
		$this->loadview ( "menu.html" );
		$this->loadloopview ( "row/homerow.html", $storys->loaddata ( "limit50sortbytimedesc" )->getall () );
		$this->loadview ( 'aboutinfo.html' );
	}
	function login() {
		$headhtml = $this->loadview ( 'head.html' );
		$foothtml = $this->loadview ( 'foot.html' );
		$loginhtml = $this->loadview ( 'login.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $loginhtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
	}
	function logined() {
		$username = $_POST ['user'];
		$pass = $_POST ['pass'];
		$user = $this->loadmodel ( 'user' );
		$statusres = $user->loaddata ( 'login', array (
				'name' => $username,'pass'=>($pass)
		) )->getall ();
		$status =( $statusres [0] ['success']=='1');
		if ($status)
		{
			$k=rand(0,10000000);
			$token=md5($k);
			$user->loaddata ( 'settoken', array (
					'name' => $username,'token'=>$token));
			echo "<Script>alert('登陆成功');window.self.location='/index.php/story/main?token=".$token."';</Script>";
			return;
		}
		else
		$story = $this->loadmodel ( 'story' );
		$out = $story->loaddata ( 'insertstory', array (
				'upuid' => $id,
				'story' => $storytext
		) );
		$this->echo = false;
		// **************
		$storyaddsuccesshtml = $this->loadview ( 'storyaddsuccess.html' );
		$headhtml = $this->loadview ( 'head.html' );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyaddsuccesshtml,
				'head' => $headhtml,
				'foot' => $foothtml
		) );
	}
	function add() {
		$this->echo = false;
		if (isset ( $_GET ['token'] ))
			$token = $_GET ['token'];
		else
			$token = 'anonymous';
		$storyaddhtml = $this->loadview ( 'storyadd.html', array (
				'token' => $token 
		) );
		$headhtml = $this->loadview ( 'head.html' );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyaddhtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
	}
	function added() {
		$token = $_GET ['token'];
		$storytext = $_GET ['storytext'];
		$user = $this->loadmodel ( 'user' );
		$idres = $user->loaddata ( 'getuserid', array (
				'token' => $token 
		) )->getall ();
		$id = $idres [0] ['id'];
		$story = $this->loadmodel ( 'story' );
		$out = $story->loaddata ( 'insertstory', array (
				'upuid' => $id,
				'story' => $storytext 
		) );
		$this->echo = false;
		// **************
		$storyaddsuccesshtml = $this->loadview ( 'storyaddsuccess.html' );
		$headhtml = $this->loadview ( 'head.html' );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyaddsuccesshtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
	}
}