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
        
        //Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Mail Settings');
               
        //Content
		$pi=new Panel("admin_mail_settings.html");
		$pi->add("SERVER_URL",SERVER_URL);
        $pi->add("SUBSCRIPTION",$UTIL->getValconfig('SUBSCRIPTION_MAIL'));
        $pi->add("CONTACT",$UTIL->getValconfig('CONTACT_MAIL'));
        $pi->add("BROCHURE",$UTIL->getValconfig('BROCHURE_MAIL'));
        
        $p->add("CONTENT",$pi->pagina());
        
    $p->show();
    


