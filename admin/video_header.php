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
        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Update Header video - Home');
		
        //Content
        $pi=new Panel("admin_video_header.html");

		//Video header
		$pi->add("HOME_VIDEO", SERVER_URL . $UTIL->getValConfig("HOME_VIDEO"));
		$pi->add("IMG_INPUT", $UTIL->load_inputfile("input-video","Select video here"));
		$pi->add("FILE-ID", "input-video");
		$pi->add("SERVER_URL",SERVER_URL);
		$p->add("CONTENT", $pi->pagina());
        
	$p->show();
