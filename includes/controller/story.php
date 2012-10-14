<?php
class story extends controller {
	// public $default_function='main';
	function main() {
		if (isset ( $_GET ['token'] )) {
			$userdata = $this->loadmodel ( 'user' )->loaddata ( 'getuserdata', array (
					'token' => $_GET ['token'] 
			) )->getall ();
			$userdata = $userdata [0];
			$userdata['hidden']='';
		} else
			$userdata = array (
					'name' => '游客',
					'id' => '-1',
					'hidden' => 'hidden',
					'token'=>'-1');
		
		$storys = $this->loadmodel ( 'story' );
		$talk = $this->loadmodel ( 'talk' );
		$this->echo = false;
		
		$storyshtml = $this->loadloopview ( "row/rowhome.html", array (
				'storys' => $storys->loaddata ( "limit50sortbytimedesc" )->getall () 
		) );
		$headhtml = $this->loadview ( 'head.html', $userdata );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyshtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
		
		// $this->loadview("head.html");
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
	function vote() {
		try {
			$id = $_GET ['id'];
			$mode = $_GET ['mode'];
		} catch ( Exception $e ) {
			echo '0';
			return;
		}
		$storys = $this->loadmodel ( 'story' );
		$storys->loaddata ( 'vote_' . $mode, array (
				'id' => $id 
		) );
		echo 3 - $mode * 2;
	}
}