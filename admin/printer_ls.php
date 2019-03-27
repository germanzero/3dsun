<?php	
	session_start();
	
	include_once( "../include/global.php" );
    

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	
	$PRINTER = new Printer();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
		
		//Session
        $p->add("USERNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("DESC","");
        
        //Variables ppales
        $p->add("PAGE-TITLE",'Printers List');
        
        //Content
        $pi=new Panel("admin_printer_ls.html");
        $pi->add("TITLE-FORM",'ADD Printer');
        $pi->add("SERVER_URL",SERVER_URL);
		$pi->add("ROWS",$PRINTER->adm_printer_ls());
		
        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
