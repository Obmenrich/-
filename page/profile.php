<?php
if (IS_LOGIN) {
	$template = new Template("profile");
	$rb = new Template("rightblock");
	$rb->addAll($RB_DATA);
	$template->add("rightblock", $rb->display());
	$template->display(true);
} else {
	$template = new Template("404");
	$template->display(true);
}