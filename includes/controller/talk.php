<?php
class story extends controller {
	// public $default_function='main';
	function main() {
		$storys = $this->loadmodel ( 'story' );
		$talk = $this->loadmodel ( 'talk' );
		$this->echo = false;
		
		$storyshtml = $this->loadloopview ( "row/rowhome.html", array (
				'storys' => $storys->loaddata ( "limit50sortbytimedesc" )->getall () 
		) );
		$headhtml = $this->loadview ( 'head.html' );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyshtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
		
		// $this->loadview("head.html");
	}

	function added() {
		$token = $_GET ['token'];
		$talktext = $_GET ['talktext'];
		$storyid=$_GET['storyid'];
		$user = $this->loadmodel ( 'user' );
		$idres = $user->loaddata ( 'getuserid', array (
				'token' => $token 
		) )->getall ();
		$id = $idres [0] ['id'];
		$story = $this->loadmodel ( 'talk' );
		$out = $story->loaddata ( 'inserttalk', array (
				'upuid' => $id,
				'talk' => $talktext 
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
		$storys->loaddata('vote_'.$mode,array('id'=>$id));
		echo 3-$mode*2; 
	}
}