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
		$p->add("PAGE-TITLE",'Update Footer');
               
        //Content
		$pi=new Panel("admin_footer.html");
		$pi->add("SERVER_URL",SERVER_URL);
        $pi->add("ADDRESS",$_SESSION['ADDRESS_FOOTER']);
		$pi->add("PHONE",$_SESSION['PHONE_FOOTER']);
		$email = $UTIL->getValConfig("EMAIL_FOOTER");
		$pi->add("EMAIL",$email);
		
		$pi->add("INPUT_FILE",$UTIL->load_inputfile("input-brochure","Select brochure"));
		

       
        $p->add("CONTENT",$pi->pagina());
        
    $p->show();
    


