<?php
class story extends controller {
	// public $default_function='main';
	
	function main() {
		$userdata = $this->userwork ();
		$storys = $this->loadmodel ( 'story' );
		$talk = $this->loadmodel ( 'talk' );
		$this->echo = false;
		$storyary = $storys->loaddata ( "limit50sortbytimedesc" )->getall ();
		
		foreach ( $storyary as $key => $row )
			$storyary [$key] ['token'] = $userdata ['token'];
		$storyshtml = $this->loadloopview ( "row/rowhome.html", array (
				'storys' => $storyary 
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
		$userdata = $this->userwork ();
		$storyaddhtml = $this->loadview ( 'storyadd.html', $userdata );
		$headhtml = $this->loadview ( 'head.html', $userdata );
		$foothtml = $this->loadview ( 'foot.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $storyaddhtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
	}
	function added() {
		$userdata = $this->userwork ();
		try{
		$storytext = $_GET ['storytext'];
		$user = $this->loadmodel ( 'user' );
		$idres = $user->loaddata ( 'getuserid',$userdata)->getall ();
		$id = $idres [0] ['id'];}catch(Exception $e){echo "<Script>alert('用户名错误，请重新登陆');window.self.location='/index.php/user/login';</Script>";
			return;};
		$story = $this->loadmodel ( 'story' );
		$out = $story->loaddata ( 'insertstory', array (
				'upuid' => $userdata['id'],
				'story' => $storytext 
		) );
		$this->echo = false;
		// **************
		$storyaddsuccesshtml = $this->loadview ( 'storyaddsuccess.html',$userdata );
		$headhtml = $this->loadview ( 'head.html' ,$userdata);
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