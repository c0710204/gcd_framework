<?php
/**
 * Class SQL
 * 
 * @auther c0710204
 */
class SQL implements Iterator {
	static public $debug = false;
	static public $debug_only_in_error = false;
	public $table = "";
	public $S_where = array();
	public $S_row = array();
	public $I_data = array();
	public $I_mutidata = array();
	public $I_mutilen = 0;
	public $I_temp = "";
	public $I_len = 0;
	public $U_set = array();
	public $U_where = array();
	public $D_where = array();
	public $S_limit = array();
	public $S_orderby = array();
	public $S_group = "";
	public $db_info = array();
	public $dblink = 0;
	public $row;
	private $rowcount=0;
	private $result=-1;
	static public $version = '1.2';
	/**
	 * 数据提取
	 * 函数组
	 */

	function fetch_assoc() {
		
		if (!(is_resource($this -> result))) return false;
	 
		else return mysql_fetch_assoc($this -> result);
	}
	function fetch_object() {
		if (!(is_resource($this -> result))) return false;
		return mysql_fetch_object($this -> result);
	} 
	function fetch_lengths() {
		if (!(is_resource($this -> result))) return false;
		return mysql_fetch_lengths($this -> result);
	} 
	function fetch_row() {
		if (!(is_resource($this -> result))) return false;
		return mysql_fetch_row($this -> result);
	} 
	function fetch_field() {
		if (!(is_resource($this -> result))) return false;
		return mysql_fetch_field($this -> result);
	} 
	/**
	 * 取得全部数据
	 *@return
	 * 	array(dictionary()) 存有数据的数组，内部构成为字典。
	 */ 
	function getall() {
		$temp = array();
		while ($row = $this -> fetch_assoc())
		array_push($temp, $row);
		return $temp;
	} 
	/**
	 *  Iterator
	 */
	public function rewind() {
		mysql_data_seek($this->result,0);
		$this->rowcount=0;
	} 
	public function current() {
		return $this->row;
	} 
	public function key() {
		return $this->rowcount;
	} 
	public function next() {
		$row=$this->fetch_assoc();
		$this->rowcount++;
	} 
	public function valid() {
		return ($this -> current() !== false);
	} 
	// sqlmaker
	function limitmaker($limit) {
		if (!($limit)) return "";
		else return "limit " . $limit[0] . "," . $limit[1];
	} 
	function __destruct() {
		if ($this -> dblink != 0)
			mysql_close($this -> dblink);
	} 
	function wheremaker($data) {
		$table = $this -> table;
		if (!($data)) return ""; {
			$str = "where";
			$f = false; //"add����"
			foreach ($data as $row) {
				if ($f) {
					$f = true;
					$str = $str . " and ";
				} else {
					$f = true;
				} 
				if (!(isset($row[1])))
					$str = $str . " $row[0] ";
				else if (isset($row[2]))
					$str = $str . "`$table`.`" . mysql_real_escape_string($row[0]) . "`" . mysql_real_escape_string($row[2]) . "'" . mysql_real_escape_string($row[1]) . "'";
				else
					$str = $str . "`$table`.`$row[0]`='$row[1]'";
			} 
		} 
		return $str;
	} 
	function orderbymaker($orderby) {
		$result = "";
		if (!($orderby)) return $result;
		else $result = 'order by ';
		$flag = 1;
		foreach ($orderby as $order) {
			if ($flag != 1) {
				$flag = 0;
				$result = $result . ' , ';
			} 
			if ((!(isset($order['row']))) && (!($order['row']))) {
				if ($result == 'order by ') $result = '';
				return $result;
			} else $result = $result . ' ' . $order['row'];
			if ((!(isset($order['mode']))) && (!($order['mode']))) continue;
			else $result = $result . ' ' . $order['mode'];
		} 
		return $result;
	} 
	/**
	 * 执行sql查询
	 * 
	 * @param
	 *  str string
	 * 	 查询语句
	 * @return 
	 * res mysql_result
	 * 返回值或查询结果
	 */
	function query($str) {
		include_once $_SERVER['DOCUMENT_ROOT'] . __CFG_document_place__ . '/core/log/logger.php';
		include $_SERVER['DOCUMENT_ROOT'] . __CFG_document_place__ . '/settings/files.php';
		$l = new logger($_SERVER['DOCUMENT_ROOT'] . __CFG_document_place__ . $cfg['file']['log']['sqllog']);
		if ($this -> dblink == 0) {
			if (isset($this -> db_info['host'])) $database_dbname = $this -> db_info['host'];
			if (isset($this -> db_info['user'])) $database_dbname = $this -> db_info['user'];
			if (isset($this -> db_info['pass'])) $database_dbname = $this -> db_info['pass'];
			if (isset($this -> db_info['dbname'])) $database_dbname = $this -> db_info['dbname'];
			if (isset($this -> db_info['port'])) $database_dbname = $this -> db_info['port'];

			include $_SERVER['DOCUMENT_ROOT'] . __CFG_document_place__ . "/core/database/link.php";
			$this -> dblink = $dblink;
		} 
		// 记录日志


		$l -> writelog($str, 'sql-QUERY'); 
		// 执行查询
		$q = mysql_query($str, $this -> dblink); 
		// 检查正误
		$err = $this -> dblink . mysql_errno();
		$errstr = $this -> dblink . mysql_error(); 
		// echo "<br/>|$str|<br/>";
		if (self :: $debug) {
			echo "<br/>|$str|<br/>";
		} 
		if (!$q) {
			if (self :: $debug_only_in_error) {
				echo "<br/>$q<br/> $str <br/>";
				echo "<br/>MYSQL Select Fail ,Code=" . $err . ",string=$errstr<br/>";
			} 
			$l -> writelog("YSQL Select Fail ,Code=" . $err . ",string=$errstr", "SQL-ERROR");
			$this -> result -10003;
		} 
		$this -> result = $q;
		$this->rowcount=0;
	} 
	function select() {
		$table = $this -> table;

		$str = "select ";

		if ($this -> S_row) {
			$f = false; //",����"
			foreach ($this -> S_row as $line) {
				if ($f) {
					$str = $str . ",";
				} else {
					$f = true;
				} 
				$str = $str . "`" . $table . "`.`" . $line . "`";
			} 
		} else {
			$str = $str . " * ";
		} 

		$str = $str . "from `" . $table . "`";
		$str = $str . $this -> wheremaker($this -> S_where);
		$str = $str . $this -> limitmaker($this -> S_limit);
		$str = $str . $this -> orderbymaker($this -> S_orderby);
		$q = $this -> query($str);
		return $q;
	} 
	function update() {
		$table = $this -> table;

		$str = "update `" . $table . "` set";

		if ($this -> U_set) {
			$f = false; //",����"
			foreach ($this -> U_set as $key => $val) {
				if ($f) {
					$str = $str . ",";
				} else {
					$f = true;
				} 
				$str = $str . "`" . $table . "`.`" . $key . "`='" . $val . "'";
			} 
		} 
		$str = $str . $this -> wheremaker($this -> U_where);
		$q = $this -> query($str);
		return $q;
	} 
	function insert() {
		$table = $this -> table;
		$str = "INSERT INTO";
		$str = $str . "`" . $table . "`(";
		$row1 = "";
		$num1 = "";
		if ($this -> I_data) {
			$f = false; //",����"
			foreach ($this -> I_data as $key => $val) {{
					if ($f) {
						$row1 = $row1 . ",";
						$num1 = $num1 . ",";
					} else {
						$f = true;
					} 
					$row1 = $row1 . "`" . $table . "`.`" . $key . "`";
					$val = str_replace('"', '\"', $val);
					$num1 = $num1 . '"' . $val . '"';
				} 
			} 
		} 
		$str = $str . $row1 . ")VALUES(" . $num1 . ")";
		$q = $this -> query($str);
		return $q;
	} 
	function mutiinsert() {
		$table = $this -> table;
		$str = "INSERT INTO";
		$str = $str . "`" . $table . "`(";
		$row1 = "";
		$num1 = array();
		if ($this -> I_data) {
			$f = false; //",����"
			foreach ($this -> I_mutidata[0] as $key => $val) {{
					if ($f) {
						$row1 = $row1 . ",";
						$num1 = $num1 . ",";
					} else {
						$f = true;
					} 
					$row1 = $row1 . "`" . $table . "`.`" . $key . "`";

					for ($i = 0; $i < $this -> I_mutilen; $i++) {
						$val1 = str_replace('"', '\"', $this -> I_mutidata[$i][$key]);
						$num1[$i] = $num1[$i] . '"' . $val1 . '"';
					} 
				} 
			} 
		} 
		var_dump($num1);
		$str = $str . $row1 . ")VALUES";
		for ($i = 0; $i < $this -> I_mutilen-1; $i++) {
			$str = $str . "(" . $num1[$i] . '),';
		} 
		$str = $str . "(" . $num1[$this -> I_mutilen-1] . ')';

		$q = $this -> query($str);
		return $q;
	} 
	function mutiinsert_end() {
		$q = $this -> query($this -> I_temp);
		return $q;
	} 
	function delete() {
		$table = $this -> table;

		$str = "delete from `" . $table . "` ";
		$str = $str . $this -> wheremaker($this -> D_where);
		$q = $this -> query($str);
		return $q;
	} 
} 
