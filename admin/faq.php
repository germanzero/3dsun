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

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
        $p = $USER->load_user_details($p);

        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Update FAQs');
               
        //Content
        $pi=new Panel("admin_faq.html");
        $pi->add("SERVER_URL",SERVER_URL);
		$pi->add("FAQS",$PAGE->load_admin_faqs());
		
		$p->add("CONTENT",$pi->pagina());
        
	$p->show();
