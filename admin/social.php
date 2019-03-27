<?php	
	session_start();
	
	include_once( "../include/global.php" );

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	
	$UTIL = new Util();
    $PAGE = new Page();

    $id = $UTIL->EID_ID(urldecode($_GET['EID']));
    $UTIL->load_fingers();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
        
        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Update Social Networks');
               
        //Content
		$pi=new Panel("admin_social.html");
		$pi->add("SERVER_URL",SERVER_URL);
        $pi->add("FACEBOOK_URL",$_SESSION['FACEBOOK_URL']);
        $pi->add("TWITTER_URL",$_SESSION['TWITTER_URL']);
        $pi->add("INSTAGRAM_URL",$_SESSION['INSTAGRAM_URL']);
        $pi->add("YOUTUBE_URL",$_SESSION['YOUTUBE_URL']);

        $p->add("CONTENT",$pi->pagina());
        
    $p->show();
    


