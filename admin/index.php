<?php	
	include_once( "../include/global.php" );
	
	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	//Load template
	$p=new Panel("admin_frame.html");
	
		//variables from template
		$p->add("PAGE-TITLE","Sun 3D - Web Admin");
		
		//Session
		$p->add("SERVER_URL",SERVER_URL);
		$p = $USER->load_user_details($p);

        //Variables ppales
        $p->add("TITLE",'Administrador web');
        $p->add("PAGE-DESC",'Web admin for Sun 3D Corporation webpage.');
        
		//Content
		//$ID = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_GET['EID'])));

		$pi=new Panel("admin_index.html");
		$pi->add("SERVER_URL",SERVER_URL);
	
        $p->add("CONTENT",$pi->pagina());

	$p->show();
	

