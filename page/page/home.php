<?php
$template = new Template("home");
$rb = new Template("rightblock");
$rb->addAll($RB_DATA);
$template->add("rightblock", $rb->display());





$shop_list = "";
$shops = $db->query("SELECT * FROM {{table}};", "shops", "array"); 
foreach ($shops as $shop) {
	$tpl = new Template("shop_item");
	$tpl->addAll($shop);
	$min = ($shop["min"] < 999) ? $shop["min"] . " гр." : ($shop["min"] / 1000) . " кг.";
	$max = ($shop["max"] < 999) ? $shop["max"] . " гр." : ($shop["max"] / 1000) . " кг.";
	$tpl->add("min", $min);
	$tpl->add("max", $max);
	
	$shop_list .= $tpl->display();
}
$template->add("shop_list", $shop_list);
$template->display(true);
