<?php
if ($user->get('admin')) {
	$count_admins = count_adm();
	$count_users = count_users();

	$menuArr = array(
		'Admin' => array(
			'url' => '?p=view_admins',
			'class' => 'fa-users',
			'count' => $count_admins,
		),
		'Users' => array(
			'url' => '?p=view_users',
			'class' => 'fa-users',
			'count' => $count_users,
		),
		/*'Tatiff' => array(
			'url' => '?p=view_tariffs',
			'class' => 'fa-address-card',
			'count' => $count_tariff,
		),*/
		//'Credits' => array(
		//	'url' => '?p=view_cretits',
		//	'class' => 'fa-dollar',
		//	'count' => 0,
		//),
	);

	$items = "";
	foreach ($menuArr as $name => $val) {
		$tmp_item = new Template("dashboard_item");
		$tmp_item->add("url", $val['url']);
		$tmp_item->add("class", $val['class']);
		$tmp_item->add("name", $name);
		$tmp_item->add("count", $val['count']);
		$items .= $tmp_item->display();
	}

	$template = new Template("dashboard_admin");
	$template->add("items", $items);
	$template->display(true);
} else {
	$tariff_name = tariff_info($user->get('tariff'));
	@$tmp = $db->query("SELECT * FROM {{table}} WHERE client_name = '{$user->get('login')}' LIMIT 1;", "tmp", "assoc");
	$traf = $db->query("SELECT * FROM {{table}} WHERE client_id = '{$user->get('id')}' LIMIT 1;", "stats", "assoc");
	$byte_in  = bcdiv((($tmp["in"] + $traf['bytes_in']) / 1000000), 1, 3);
	$byte_out = bcdiv((($tmp["out"] + $traf['bytes_out']) / 1000000), 1, 3);
	$session_list = session_list($user->get('id'));
	$limits = checkLimits($user);
	$byte_limit = bcdiv(($limits['limit'] / 1000000000), 1, 3);
	$byte_left = bcdiv((($limits['limit'] - ($tmp["in"] + $tmp["out"] + $traf['bytes_in'] + $traf['bytes_out'])) / 1000000000), 1, 3) ."";
	
	
	
	$template = new Template("dashboard_user");
	$template->add("user_login", $user->get('login'));
	$template->add("tariff_name", $tariff_name);
	$template->add("byte_in", $byte_in);
	$template->add("byte_out", $byte_out);
	$template->add("byte_limit", $byte_limit);
	$template->add("byte_left", $byte_left);
	$template->add("session_list", $session_list);
	$template->display(true);
}