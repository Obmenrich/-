<?php
$template = new Template("faq");
$rb = new Template("rightblock");
$rb->addAll($RB_DATA);
$template->add("rightblock", $rb->display());
$template->display(true);
