<?php
session_start();
require_once 'include/functions.php';

if (!IS_LOGIN) {
	die("Login Failed");
}

switch(@$_POST['action']) {
	case 'get_shops': {
		get_shops();
		break;
	};
	case 'ping': {
		ping();
		break;
	};
	default:{
		die('Function not found or under development');
	}
}

function get_shops() {
	global $user, $db;
	$shops = $db->query("SELECT * FROM {{table}};", "shops", "array"); 
	$data = "";
	$len = $_POST['length'] + 0;
	$i = 0;
	foreach ($shops as $shop) {
		$tpl = new Template("user_shop_item");
		$tpl->addAll($shop);
		$min = ($shop["min"] < 999) ? $shop["min"] . " гр." : ($shop["min"] / 1000) . " кг.";
		$max = ($shop["max"] < 999) ? $shop["max"] . " гр." : ($shop["max"] / 1000) . " кг.";
		$tpl->add("min", $min);
		$tpl->add("max", $max);
		$i++;
		$data .= $tpl->display();
		if ($len > $i) {
			break;
		}
	}
	$reret = array(
		'draw' => ($_POST['draw'] + 0),
		'data' => $data,
		'recordsFiltered' => 0,
		'recordsTotal' => count($shops)
	);
	die(json_encode($reret));
}

function ping() {
	global $config, $user/*, $db*/;
	//$TheCookie  = explode("/%/", $_COOKIE[$config["cookiename"]]);
	
	//$login = $db->query("SELECT * FROM {{table}} WHERE login = '".replace($TheCookie[1])."' LIMIT 1;", "users", "assoc");
	//$cookie = $login["id"] . "/%/" . $login["login"] . "/%/" . md5($login["password"]."--".$config["secretword"]) . "/%/" . md5($_SERVER['REMOTE_ADDR']) . "/%/";
	$cookie = $user->get("id") . "/%/" . $user->get("login") . "/%/" . md5($user->get("password")."--".$config["secretword"]) . "/%/" . md5($_SERVER['REMOTE_ADDR']) . "/%/";
	setcookie($config["cookiename"], $cookie, time() + 3600, "/", "", 0);
	echo "ping ok";
}