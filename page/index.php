<?php
session_start();
require_once 'include/functions.php';
require_once 'page/BorodaDigital.php';

if (@$_GET['p'] != "" && !file_exists("page/" . @$_GET['p'] . ".php")) {
	require_once 'page/404.php';
	exit;
} else {
	

	require_once 'page/header.php';
	

	if (@$_GET['p'] == "") {
		require_once 'page/home.php';
	} else {
		require_once 'page/'. @$_GET['p'] .'.php';
	}
}


require_once 'page/footer.php';
