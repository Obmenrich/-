<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class User {
	private static $instance;
	public $id;
	private $user = array();
	private $tokens = array();
	public $profile = array();
	
	public function __construct($key, $type="email") {
		global $db;
		if ($type == "email")
			$this->user = $db->query("SELECT * FROM {{table}} WHERE login = '{$key}' LIMIT 1;", "users", "assoc");
		else if ($type == "id") 
			$this->user = $db->query("SELECT * FROM {{table}} WHERE id = '{$key}' LIMIT 1;", "users", "assoc");
		if (count($this->user) == 0)
			return;
		$this->id = $this->get("id");
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function get($key = "") {
		if(isset($key)) {
			return @$this->user[$key];
		} else {
			return $this->user;
		}
	}
	
	public function set($key, $value) {
		$false = array("id", "vk_id");
		if (in_array(trim($key), $false)) {
			return $this->user;
		}
		$this->user[$key] = $value;
		return $this->user;
	}

	public function update() {
		global $db;
		$query = "";
		foreach ($this->user as $key => $val) {
			if ($key == "id") continue;
			print gettype($val). "<br>";
			if (gettype($val) == "NULL")
				$query .= "$key = '', ";
			if (gettype($val) == "string")
				$query .= "$key = '$val', ";
			if (gettype($val) == "boolean")
				$query .= "$key = $val, ";
		}
		$query = substr($query, 0, -2);
		print $query;
		$db->query("UPDATE {{table}} SET {$query} WHERE id = '{$this->id}';", 'users');
	}

	public function getRefId() {
		return "7d264f906a".$this->id;
	}

	public function delete() {
		global $db;
		$this->tokens->delete();
		$this->profile->delete();
		$db->query("DELETE FROM {{table}} WHERE `id` = '{$this->id}';", 'users');
	}
}