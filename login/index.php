<?php	
	if(session_start()) 
			session_destroy(); 

	include_once( "../include/global.php" );
	//Load template
	$p=new Panel("admin_login.html");
	$p->add("PAGE-TITLE","Admin Login");
    $p->add("SERVER_URL",SERVER_URL);
	$p->show();

    