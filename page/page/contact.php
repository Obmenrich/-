<?php
$template = new Template("contact");
$rb = new Template("rightblock");
$rb->addAll($RB_DATA);
$template->add("rightblock", $rb->display());
$template->display(true);


