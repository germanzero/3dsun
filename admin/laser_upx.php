<?php	
	session_start();
	
	include_once( "../include/global.php" );
    

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	
	$UTIL = new Util();
    $LASER = new Laser();

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
		$p->add("PAGE-TITLE",'Update laser Machine');
               
        //Content
        $printer = $LASER->get_laser($id);
        $pi=new Panel("admin_laser_upx.html");
        $pi->add("TITLE-FORM",'Update laser data.');
		$pi->add("SERVER_URL",SERVER_URL);
		$pi->add("EID",$UTIL->ID_EID($printer['id']));
		$pi->add("NAME", $printer['name']);
		$pi->add("STATUS", $printer['status']);
		$pi->add("SHORT_DESC", $printer['description_short']);
		$pi->add("DESC", str_replace('<br>',"\r\n", $printer['description']));
		$pi->add("TECHNICAL",$printer['technical_parameters']);
		$pi->add("IMG-INPUT-PANEL",$UTIL->load_input_image("banner-new", $printer['banner'], $printer['alt_text_banner']));
		$pi->add("INPUT-ID-BANNER","banner-new");
		$pi->add("HDR-CLASS","d-none");
		
		$p->add("CONTENT",$pi->pagina());
        
	$p->show();
