<?php	
	session_start();
	
	include_once( "../include/global.php" );

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	$UTIL = new Util();
	$SEC = new Section();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
		$p = $USER->load_user_details($p);
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Advertising Banner');
		
        //Content
        $section = $SEC->get_section("name","'Home Banner'");
        $banner = $SEC->get_banner($section['id']);
        $pi=new Panel("admin_ad_banner.html");
        $pi->add("TITLE-FORM",'Advertising Banner - Home');
		$pi->add("SERVER_URL",SERVER_URL);
		$pi->add("EID",$UTIL->ID_EID($section['id']));
		$pi->add("EID-BAN",$UTIL->ID_EID($banner['id']));
		$pi->add("STATUS",$banner['status']);
		$pi->add("HREF",$banner['href']);

        $pi->add("IMG-INPUT-PANEL",$UTIL->load_input_image("banner-new", $banner['path'], $banner['alt_text']));
		$pi->add("INPUT-ID-BANNER","banner-new");

        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
