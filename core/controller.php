<?php
class controller {
	public $echo = false;
	public $default_function = 'main';
	function __toString() {
	}
	function __autoload() {
	}
	function main() {
	}
	function loadview($uri, $data = array()) {
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" )) {
			$htmldefult = file_get_contents ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" );
			$htmldefult = str_replace ( '"', '\"', $htmldefult );
			extract ( $data );
			$eval = '$html="' . $htmldefult . '";';
			eval ( $eval );
			if ($this->echo)
				echo $html;
			else
				return $html;
		} else {
			echo "view $uri not exisit";
			exit ( 0 );
		}
	}
	/*
	 * function _loadloopview_html_ng($html,$data) {
	 * $loopstarts=strpos($html,'#loop '); $loopstarte=strpos($html,'
	 * start#',$loopstarts); $loopname=substr($html,
	 * $loopstarts+6,$loopstarte-$loopstarts-6); $loopends=strpos($html,"#loop
	 * $loopname end#",$loopstarts); $loopende=$loopends+strlen("#loop $loopname
	 * end#"); $loophtml=substr($html, $loopstarte+7,$loopends-($loopstarte+7));
	 * $loopdata=$data[$loopname]; unset($data[$loopname]);
	 * $loophtml_ok=$this->_loadloopview_html($loophtml, $data); foreach
	 * ($loopdata['self'] as $row) { extract($row);
	 * $loophtml_ok=$this->_loadloopview_html($loophtml, $data);
	 * $eval='$loopedhtml="'.$loophtml.'";'; eval($eval); $output=$output.$html;
	 * } if ($this->echo) echo $output; else return $output; return 0; }
	 */
	function _loadloopview_html($html, $data) {
		$loopoutput = '';
		$loopstarts = strpos ( $html, '<!--loop ' );
		$loopstarte = strpos ( $html, ' start-->', $loopstarts );
		$loopname = substr ( $html, $loopstarts + 9, $loopstarte - $loopstarts - 9 );
		$loopends = strpos ( $html, "<!--loop $loopname end-->", $loopstarts );
		
		$loopende = $loopends + strlen ( "<!--loop $loopname end-->" );
		
		$loophtml = substr ( $html, $loopstarte + 9, $loopends - $loopstarte - 10 );
		$loophtml = str_replace ( '"', '\"', $loophtml );
		$loopdata = $data [$loopname];
		unset ( $data [$loopname] );
		foreach ( $loopdata as $row ) {
			extract ( $row );
			$eval = '$loopedhtml="' . $loophtml . '";';
			eval ( $eval );
			$loopoutput = $loopoutput . $loopedhtml;
		}
		return substr ( $html, 0, $loopstarts - 1 ) . $loopoutput . substr ( $html, $loopende );
	}
	function loadloopview($uri, $datas) {
		$output = '';
		$arguint = func_num_args () - 1;
		$evaldef = '$out=$this->_loadloopview_html($htmldefult, ';
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" )) {
			$htmldefult = file_get_contents ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" );
			if ($this->echo)
				echo $this->_loadloopview_html ( $htmldefult, $datas );
			else
				return $this->_loadloopview_html ( $htmldefult, $datas );
		} else {
			echo "view $uri not exisit";
			exit ( 0 );
		}
	}
	function loadview_ajaxtype($uri, $data) {
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" )) {
			$html = file_get_contents ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/view/$uri" );
			foreach ( $data as $key => $val ) {
				str_replace ( '*' . $key . '#', $val, $html );
			}
			echo $html;
		} else {
			echo "view $uri not exisit";
			exit ( 0 );
		}
	}
	function loadmodel($uri) {
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/model/$uri.php" )) {
			include_once $_SERVER ['DOCUMENT_ROOT'] . __CFG_document_place__ . "/includes/model/$uri.php";
			$uri='M'.$uri;
			return new $uri ();
		} else {
			echo "model $uri not exisit";
			exit ( 0 );
		}
	}
}