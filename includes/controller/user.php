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
		$userdata = $this->userwork ();
		$headhtml = $this->loadview ( 'head.html', $userdata );
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
		try {
			$email = $_POST ['email'];
			$pass = $_POST ['pass'];
		} catch ( Exception $e ) {
			echo "<Script>alert('邮箱或密码未输入，请重新登陆');window.self.location='/index.php/user/login';</Script>";
		}
		if (! (preg_match ( '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email ))) {
			echo "<Script>alert('邮箱输入错误，请重新登陆');window.self.location='/index.php/user/login';</Script>";
			return;
		}
		if (! (preg_match ( '/^\w+$/', $pass ))) {
			echo "<Script>alert('密码输入错误，请重新登陆');window.self.location='/index.php/user/login';</Script>";
			return;
		}
		
		try {
			$user = $this->loadmodel ( 'user' );
			$statusres = $user->loaddata ( 'login', array (
					'email' => $email,
					'pass' => ($pass) 
			) )->getall ();
			$status = ($statusres [0] ['success'] == '1');
		} catch ( Exception $e ) {
			$status = false;
		}
		if ($status) {
			$k = rand ( 0, 10000000 );
			$token = md5 ( $k );
			$user->loaddata ( 'settoken', array (
					'email' => $email,
					'token' => $token 
			) );
			echo "<Script>alert('登陆成功');window.self.location='/index.php/story/main?token=" . $token . "';</Script>";
			return;
		} else {
			echo "<Script>alert('用户名或密码错误，请重新登陆');window.self.location='/index.php/user/login';</Script>";
			return;
		}
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
	function signup() {
		$userdata = $this->userwork ();
		$headhtml = $this->loadview ( 'head.html', $userdata );
		$foothtml = $this->loadview ( 'foot.html' );
		$loginhtml = $this->loadview ( 'signup.html' );
		$this->echo = true;
		$this->loadview ( 'home.html', array (
				'main' => $loginhtml,
				'head' => $headhtml,
				'foot' => $foothtml 
		) );
	}
	function logout() {
		$user = $this->loadmodel ( 'user' );
		try {
		$userdata = $this->userwork ();
		if ($userdata['email']!='anyone@anywhere.any')
			$user->loaddata ( 'logout', $userdata );
		} catch ( Exception $e ) {
			echo "<Script>alert('登出失败，返回主页');window.self.location='/index.php/story/main?token=".$userdata['token']."';</Script>";
			return;
		}
		echo "<Script>alert('登出成功，返回主页');window.self.location='/index.php';</Script>";
	}
	function signuped() {
		$this->echo = false;
		try {
			$name = $_POST ['username'];
			$email = $_POST ['email'];
			$password1 = $_POST ['pass1'];
			$password2 = $_POST ['pass2'];
		} catch ( Exception $e ) {
			echo "<Script>alert('缺少信息，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		}
		if ($password1 != $password2) {
			echo "<Script>alert('两次密码输入不同，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		} elseif (strlen ( $password1 ) <= 6) {
			echo "<Script>alert('密码过短，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		} elseif (! (preg_match ( '/^\w+$/', $password1 ))) {
			echo "<Script>alert('密码与要求不符，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		} elseif (! (preg_match ( '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email ))) {
			echo "<Script>alert('邮箱输入错误，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		} elseif (! (preg_match ( '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$ */', $name ))) {
			echo "<Script>alert('用户名不合法，请重新输入');window.self.location='/index.php/user/signup';</Script>";
		} else {
			$user = $this->loadmodel ( 'user' );
			try {
				$idres = $user->loaddata ( 'signup', array (
						'name' => $name,
						'email' => $email,
						'password' => $password1 
				) )->getall ();
			} catch ( Exception $e ) {
				echo "<Script>alert('与已有用户重复，请重新输入');window.self.location='/index.php/user/signup';</Script>";
				return;
			}
			echo "<Script>alert('注册成功,请以用户名密码登陆');window.self.location='/index.php/user/login';</Script>";
		}
	}
}