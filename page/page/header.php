<?php

$user_reg = "";
$l_c_p="";


if (IS_LOGIN) {

// Видимость панели пользователя
	$template = new Template("panel_user");
	$template->add("user_name",  $user->get("login"));
$user_reg = $template->display();

$template->add("user_reg", $user_reg );

//$template_l_c_p = new Template("left_control_shop");
//$template_l_c_p ->add("l_c_p", $l_c_p);
//$l_c_p = $template_l_c_p->display();

//$template_l_c_p->display(true);

$template_l_c_p = new Template("left_control_shop");
$template_l_c_p ->add("l_c_p", $l_c_p);
$l_c_p = $template_l_c_p->display();


} else {
	
	
	$template = new Template("panel_reg");
	$user_reg = $template->display();


}
$template = new Template("header");


$template->add("user_reg", $user_reg );
$template->add("l_c_p", $l_c_p);
//кнопка панели управления магазином

$template->display(true);


	





