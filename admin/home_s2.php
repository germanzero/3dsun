<?php	
	session_start();
	
	include_once( "../include/global.php" );
    
/*
	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	*/
	
	$UTIL = new Util();
	$SEC = new Section();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'New Blog Post');
		
        //Content
        //Home S2
        $sec2 = $SEC->get_section("name","'Home S2'");
		$pi2=new Panel("admin_home_s2.html");
		$pi2->add("SERVER_URL",SERVER_URL);
		$pi2->add("EID-SECTION",$UTIL->ID_EID($sec2['id']));
        $pi2->add("SUBTITLE",$sec2['subtitle']);
        $pi2->add("PARAGRAF",$sec2['paragraf']);
        $p->add("CONTENT",$pi2->pagina());
        
	$p->show();
