<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Postgres {
	private static $instance;
	private $connect;
	private $count_sql;
	
	private function __construct() {
		global $debug, $config;
		$this->connect = pg_connect("host={$config['db_server']} port={$config['db_port']} dbname={$config['db_name']} user={$config['db_user']} password={$config['db_pass']}");
		if ($this->connect === NULL) {
			$debug->error('Critical Stop Error: Database Error<br />' . mysql_error(),"SQL Error");
			//die('Critical Stop Error: Database Error<br />' . mysql_error());
		}
	}
	
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function query($query, $table, $fetch = "") {
		global $user, $debug, $config;
	
		$this->count_sql++;
		$start_time_sql = microtime(true);
		$arr = debug_backtrace();
		if (isset($_SERVER["OS"])) {
			$file = @end(explode('\\',$arr[0]['file']));
		} else {
			$file = @end(explode('/',$arr[0]['file']));
		}
		$line = $arr[0]['line'];
	
		$sql = str_replace("{{table}}", "".$config["prefix"].$table."", $query);
		$query = pg_query($this->connect, $sql);
		$count_row = pg_affected_rows($query);
		$time_sql = microtime(true) - $start_time_sql;
	
		$debug->add(array($this->count_sql, $sql, $file."(".$line.")", $table, $fetch, round($time_sql, 6), $count_row), $file, round($time_sql, 6), $table);
		
		switch ($fetch) {
			case "assoc":
				$query = $this->fetch($query);
				$query = @$query[0];
				return $query;
				break;
			case "array":
				return $this->fetch($query);
				break;
			case "num":
				return pg_num_rows($query);
				break;
			case "id":
				//if (pg_last_oid($query)) {
					return pg_last_oid($query);
				//} else {
				//	return q_singleval('SELECT lastval()');
				//}
				break;
			default:
				return $query;
				break;
		}
	}
	private function fetch($result) {
		$ret = array();
		while($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
			$this->fixBooleans($result, $arr);
			$ret[] = $arr;
		}
		return $ret;
	}
	
	private function fixBooleans($result, &$row) {
		for ($fld_i = 0; $fld_i < pg_num_fields($result); $fld_i++){
			$fld_name = pg_field_name($result, $fld_i);
			if( pg_field_type($result, $fld_i) == 'bool'){
				if( $row[$fld_name] == 't' ) {
					$row[$fld_name] = true;
				} elseif($row[ $fld_name] == 'f') {
					$row[$fld_name] = false;
				}
			}
		}
	}

	private function __clone() {
	}

	private function __wakeup() {
	}
}
