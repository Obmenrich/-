<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Cookie {
	private static $instance;
	
	public function __construct() {
		if ($this->checkCookie()) {
			define('IS_LOGIN', true);
			return true;
		}
		define('IS_LOGIN', false);
		return false;
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function checkCookie() {
		global $config, $user, $db;
		if (isset($_COOKIE[$config["cookiename"]])) {
			$TheCookie  = explode("/%/", $_COOKIE[$config["cookiename"]]);
			$user = new User($TheCookie[1]);;
			if ($user->get("id") == "") {
				setcookie($config["cookiename"], "", time()-100000, "/", "", 0);
				die("User is not found");
			}
			if ($user->get("id") != $TheCookie[0]) {
				setcookie($config["cookiename"], "", time()-100000, "/", "", 0);
				die("Doesn't Match Eid");
			}
			if (md5($user->get("password") . "--" . $config["secretword"]) !== $TheCookie[2]) {
				setcookie($config["cookiename"], "", time()-100000, "/", "", 0);
				header("Location: ./");
				//die("Password does not match");
			}
			return true;
		}
		return false;
	}
}