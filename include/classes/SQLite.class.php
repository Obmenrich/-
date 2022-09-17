<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class SQLite {
	private $conn;
	private static $instance;
	private $count_sql;

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function __construct() {
		global $config;
		if ($this->conn === NULL)
			if(!file_exists("include/" . $config["sqlite_db"])) {
				$this->conn = new SQLite3("include/" . $config["sqlite_db"]);
			} else {
				$this->conn = new SQLite3("include/" . $config["sqlite_db"]);
			}
		return $this->conn;
	}
	
	private function dbquery($query) {
		$ret = array();
		if($query != "") {
			$result = $this->conn->query($query);
			while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$ret[] = $row;
			}
			if (!@count(@$ret) > 0) return array();
			return $ret;
		}
	}

	public function sqlUpdateQuery($query) {
		$this->conn->query($query);
	}

	public function query($query, $table, $fetch = "") {
		global $debug, $config;
		$this->count_sql++;
		$start_time_sql = microtime(true);
		$arr = debug_backtrace();
		if (isset($_SERVER["OS"])) {
			$file = @end(explode('\\',$arr[0]['file']));
		} else {
			$file = @end(explode('/',$arr[0]['file']));
		}
		$line = $arr[0]['line'];

		$sql = str_replace("{{table}}", "`".$config["prefix"].$table."`", $query);
		$result = $this->conn->query($sql);

		$count_row = 0;
		$time_sql = microtime(true) - $start_time_sql;

		$debug->add(array($this->count_sql, $sql, $file."(".$line.")", $table, $fetch, round($time_sql, 6), $count_row), $file, round($time_sql, 6), $table);
		$ret = array();
		switch ($fetch) {
			case "array":
				return $this->feach($result, true);
				break;
			case "assoc":
				return $this->feach($result, false);
				break;
			case "num":
				return count($this->feach($result, true));
				break;
			case "id":
				return $this->conn->lastInsertRowID();
				break;
			default:
				
				break;
		}
	}
	
	private function feach($result, $one = false) {
		$ret = array();
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$ret[] = $row;
		}
		if (@count(@$ret) == 0)
			return array();
		if (@count(@$ret) == 1 && !$one)
			return $ret[0];
		else if (count($ret) > 0)
			return $ret;
	}

	private function __clone() {
	}

	private function __wakeup() {
	}
}