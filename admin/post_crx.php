<?php	
	session_start();
	
	include_once( "../include/global.php" );

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	$UTIL = new Util();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
		$p = $USER->load_user_details($p);
        
        //Variables ppales
		$p->add("PAGE-TITLE",'New Blog Post');
		
        //Content
        $pi=new Panel("admin_post_crx.html");
        $pi->add("TITLE-FORM",'New Blog Post');
		$pi->add("SERVER_URL",SERVER_URL);
		$pi->add("ID-INPUT","input-img-crx");
        $pi->add("IMG_INPUT",$UTIL->load_inputfile("input-img-crx"));
        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
