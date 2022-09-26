<?php
session_start();
require_once 'include/functions.php';
history_log($user->get('id'), "Logout from System");

setcookie($config["cookiename"], "", time() - 1000000000, "/", "", 0);
header("Location: ./");