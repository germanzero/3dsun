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

        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'New Printer');
		
		//input image
		
        
        //Content
        $pi=new Panel("admin_printer_crx.html");
        $pi->add("TITLE-FORM",'Add new printer to system.');
		$pi->add("SERVER_URL",SERVER_URL);
		$pi->add("IMG_INPUT",$UTIL->load_inputfile("hdr-post"));
        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
