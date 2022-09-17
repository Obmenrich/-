<?php
$user_reg = "";

if (IS_LOGIN) {
	$template = new Template("panel_user");
	$template->add("user_name", $user->get("login"));
	$user_reg = $template->display();
} else {
	$template = new Template("panel_reg");
	$user_reg = $template->display();
}


$template = new Template("footer");
$template->add("user_reg", $user_reg);
$template->add("alert", $ALERT);
if (@$config["debug"])
	$template->add("debug", $debug->printDebug());

$template->display(true);
