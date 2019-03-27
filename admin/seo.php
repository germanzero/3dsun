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

    //$id = $UTIL->EID_ID(urldecode($_GET['EID']));

	//Load template
	$p=new Panel("admin_frame.html");

		$p = $USER->load_user_details($p);

		$p->add("SERVER_URL",SERVER_URL);
        
        //Variables ppales
		$p->add("PAGE-TITLE",'Update SEO');
               
        //Content
        $p->add("CONTENT",$PAGE->get_seo_pages());
        
	$p->show();
