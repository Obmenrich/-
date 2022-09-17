<?php
define('INSIDE','true');
define('TEMPLATE_DIR', 'template');
ini_set('error_reporting', E_ALL);

require_once('include/ClassAutoloader.php');

$ALERT  = "";
$user   = array();
$RB_DATA   = array();

$debug  = new Debug();
$config = Config::getInstance()->getConfig();
//$db     = Postgres::getInstance();
$db   = SQLite::getInstance();
//$db   = Mysql::getInstance();
$cookie = new Cookie();

$ALERT = "";

function savetext($filename, $content) {
	$handle = @fopen ($filename, 'wb+') or false;
	if ($handle) {
		flock($handle, LOCK_EX);
	} else {
		return false;
	}
	$r = fwrite($handle, $content);
	flock($handle, LOCK_UN);
	fclose($handle);
	return $r;
}

function replace($string) {
	$pattern = array('/\;/', '/\</', '/\>/', '/\(/', '/\)/', '/\\\/', '/\|/', '/\"/', '/\'/', '/\`/', '/\,/', '/\+/');
	return preg_replace($pattern, '', stripslashes( $string ) );
}

//die(print_r($_POST));
if (isset($_POST)) {
    //print_r($_POST);
}

//------------------------------------------------------------------------------------
if (isset($_POST['sign_in'])) {
	process_sign_in($_POST);
}
if (isset($_POST['sign_up'])) {
	process_sign_up($_POST);
}

if (isset($_POST['change_password'])) {
	change_password($_POST);
}
if (isset($_POST['add_shop'])) {
	add_shop($_POST);
}
if (isset($_POST['delete_shop'])) {
	delete_shop($_POST);
}
if (isset($_POST['exit'])) {
	setcookie($cookie_name, $cookie, time() - 1000000000, "/", "", 0);
	header("Location: ./");
}

//Array(    [password] => 1234    [password1] => 5678    [password2] => 5678    [change_password] => 1)
function change_password($param) {
	global $user, $config, $db, $debug;
	//$TheCookie  = explode("/%/", $_COOKIE[$cookie_name]);
	//$user = $db->query("SELECT * FROM {{table}} WHERE login = '".replace($TheCookie[1])."' LIMIT 1;", "users", "assoc");
	if ($user->get("password") != md5($param["password"])) {
		die("FAIL");
		header("Location: ./?p=change_password");
		return;
	}
	if ($param["password1"] != $param["password2"]) {
		die("FAIL");
		header("Location: ./?p=change_password");
		return;
	}
	$user->set("password", md5($param["password1"]));
	history_log($user->get('id'), "Change Password");
	$db->query("UPDATE {{table}} SET password='{$user->get('password')}' WHERE id='{$user->get('id')}';", "users");
	$cookie = $user->get("id") . "/%/" . $user->get("login") . "/%/" . md5($user->get("password")."--".$config["secretword"]) . "/%/" . md5($_SERVER['REMOTE_ADDR']) . "/%/";
	setcookie($config["cookiename"], "", time() - 100000, "/", "", 0);
	setcookie($config["cookiename"], $cookie, time() + 3600, "/", "", 0);
	header("Location: ./");
}
//Array ( [shop_name] => Мед [sity] => Растов [product] => фывффф [reserve] => 12000 [min] => 100 [max] => 1000 [add_shop] => 1 )
function add_shop($param) {
	global $db, $user;
	//print_r($_POST);
	$shop_name = $param["shop_name"];
	$sity = $param["sity"];
	$product = $param["product"];
	$reserve = $param["reserve"];
	$min = $param["min"];
	$max = $param["max"];
	if (isset($shop_name) && isset($sity) && isset($product) && isset($reserve) && isset($min) &&  isset($max) && $min > 1 && $max > 1 ) {
		$eee = $db->query("INSERT INTO {{table}} 
			(user_id, shop_name, sity, product, reserve, min, max) VALUES 
			({$user->get('id')}, '{$shop_name}', '{$sity}', '{$product}', '{$reserve}', {$min}, {$max});", "shops", "id");
		
	}
	header("Location: ./?p=base_edit");
}
//Array ( [shop_name] => Мед [sity] => Растов [product] => фывффф [reserve] => 12000 [min] => 100 [max] => 1000 [add_shop] => 1 )
function delete_shop($param) {
	global $db, $user;
	print_r($_POST);
	$delete_shop = $param["delete_shop"];
	$shop = $db->query("SELECT * FROM {{table}} WHERE id = '".replace($delete_shop)."' LIMIT 1;", "shops", "assoc");
	
	if ($shop["user_id"] == $user->get('id')) {
		$db->query("DELETE FROM {{table}} WHERE id = '".replace($delete_shop)."';", "shops", "");
	}
	header("Location: ./?p=base_edit");
}
//------------------------------------------------------------------------------------
//Array ( [log] => admin [pwd] => admin [captaha_in] => 2004 [sign_in] => 1 )
function process_sign_in($post) {
	global $db, $config, $ALERT;
	if ($_SESSION['captaha_in'] == @$post['captaha_in']) {
		if (isset($post['log']) && $post['log'] != "" && isset($post['pwd']) && $post['pwd'] != "") {
			$login = $db->query("SELECT * FROM {{table}} WHERE login = '".replace($post['log'])./*"' and `password` = '".md5($post["password"]).*/"' LIMIT 1;", "users", "assoc");
			if ($login["password"] != md5($post["pwd"])) {
				$ALERT = "new _uWnd.alert('<b>No matches found.</b>','Error',{w: 270,h: 70,t: 8000});";
			}
			if ($login != "" && $login["password"] == md5($post["pwd"])) {
				$cookie = $login["id"] . "/%/" . $login["login"] . "/%/" . md5($login["password"]."--".$config["secretword"]) . "/%/" . md5($_SERVER['REMOTE_ADDR']) . "/%/";
				history_log($login["id"], "Logined to System");
				setcookie($config["cookiename"], $cookie, time() + 3600, "/", "", 0);
				header("Location: ./");
				exit;
			} else {
				$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>No matches found.</b>','Error',{w: 270,h: 70,t: 8000});";
			}
		} else {
			$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>No matches found.</b>','Error',{w: 270,h: 70,t: 8000});";
		}
	} else {
		$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>Invalid code from the image.</b>','Error',{w: 270,h: 70,t: 8000});";
	}
}
//Array ( [log] => admin [pwd] => admin1 [pwd2] => admin2 [captaha_up] => 1287 [check] => 1 [sign_up] => 1 )
function process_sign_up($post) {
	global $db, $config, $ALERT;
	if ($_SESSION['captaha_up'] == @$post['captaha_up']) {
		if (isset($post['check']) && $post['check'] == 1) {
			if (isset($post['log']) && $post['log'] != "" && isset($post['pwd']) && $post['pwd'] != "") {
				$login = $db->query("SELECT * FROM {{table}} WHERE login = '".replace($post['log'])."' LIMIT 1;", "users", "num");
				if ($login > 0) {
					$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>The login is already in use.</b>','Error',{w: 270,h: 70,t: 8000});";
				} else {
					if ($post['pwd'] == $post['pwd2']) {
						$client_key = md5(mt_rand(10000,99999) . $post['pwd'] . $post['log']);
						$eee = $db->query("INSERT INTO {{table}} 
							(login, password) VALUES 
							('{$post['log']}', '" . md5($post['pwd']) . "');", "users", "id");
						header("Location: ./");
					} else {
						$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>Password mismatch.</b>','Error',{w: 270,h: 70,t: 8000});";
					}
				}
			} else {
				$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>No matches found.</b>','Error',{w: 270,h: 70,t: 8000});";
			}
		} else {
			$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>You did not agree to the terms.</b>','Error',{w: 270,h: 70,t: 8000});";
		}
	} else {
		$ALERT = "new _uWnd.alert('<b style=\'color:red;\'>Invalid code from the image.</b>','Error',{w: 270,h: 70,t: 8000});";
	}
}

function getArrCount($arr) {
	$res = count($arr);
	if (is_array($arr)) {
		foreach ($arr as $in_ar)
			if (is_array($in_ar)) {
				$res+=getArrCount($in_ar);
			} else {
				$res--;
			}
	} else {
		$res--;
	}
	return $res;
}

function is_access($array) {
	global $user;
	if (!$user->get('admin')) {
		if (in_array('user', $array, true)) {
			return true;
		}
	} else {
		return true;
	}
	return false;
}

function array_value_count($match, $array) {
	$count = 0;
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$count += array_value_count($match, $value);
		}
		if ($value == $match) {
			$count++;
		}
		
	}
	return $count;
}

function history_log($user_id, $action) {
	global $db;
	$time = time();
	$db->query("INSERT INTO {{table}} (user_id, \"time\", action) VALUES ({$user_id}, {$time}, '{$action}');", "history", "id");
}