<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Mysql {
    private static $instance;
    private $connect;
    private $count_sql;

    private function __construct() {
        global $debug, $config;
        $this->connect = new mysqli($config["db_server"], $config["db_user"], $config["db_pass"], $config["db_name"]);
        if (!$this->connect) {
            $debug->error('Critical Stop Error: Database Error<br />' . mysql_error(),"SQL Error");
            //die('Critical Stop Error: Database Error<br />' . mysql_error());
        }
		
        //@mysql_select_db($dbconfig["dbname"], $this->connect) or $debug->error(mysql_error()."<br />$query","SQL Error");
        $this->query('SET character set cp1251', "");
        $this->query('SET names cp1251', "");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($query, $table, $fetch = "") {
        global $user, $debug, $dbconfig;

        $this->count_sql++;
        $start_time_sql = microtime(true);
        $arr = debug_backtrace();
        if (isset($_SERVER["OS"])) {
            $file = @end(explode('\\',$arr[0]['file']));
        } else {
            $file = @end(explode('/',$arr[0]['file']));
        }
        $line = $arr[0]['line'];

        $sql = str_replace("{{table}}", "`".$dbconfig["prefix"].$table."`", $query);
        $query = $this->connect->query($sql) or die("<br/><span style='color:red'>Ошибка в SQL запросе:</span> ".mysqli_error($this->connect));
        $count_row = mysqli_affected_rows($this->connect);
        $time_sql = microtime(true) - $start_time_sql;

        $debug->add(array($this->count_sql, $sql, $file."(".$line.")", $table, $fetch, round($time_sql, 6), $count_row), $file, round($time_sql, 6), $table);

        switch ($fetch) {
            case "assoc":
                return $this->fetch_array($query)[0];
                break;
            case "array":
                return $this->fetch_array($query);
                break;
            case "row":
                $query = $query->fetch_row();
                $query = $query[0];
                return $query;
                break;
            case "num":
                return count($this->fetch_array($query));
                break;
            case "id":
                return $this->connect->insert_id;
                break;
            default:
                return $query;
                break;
        }
    }
	private function fetch_array($result) {
		$rows = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$rows[] = $row;
		}
		return $rows;
	}
    private function __clone() {
    }

    private function __wakeup() {
    }
}
