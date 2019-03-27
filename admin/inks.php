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
		$p->add("PAGE-TITLE",'Update "Inks" page.');

        //Content
      	//$pi->add("SERVER_URL",SERVER_URL);

		//Panel
		$pi1=new Panel("admin_inks.html");
		$pi1->add("SERVER_URL",SERVER_URL);
		
		//Inks S1
		$sec1 = $SEC->get_section("name","'Inks S1'");
		$pi1->add("EID_S1",$UTIL->ID_EID($sec1['id']));
        $pi1->add("TITLE_S1",$sec1['title']);
        $pi1->add("SUBTITLE_S1",$sec1['subtitle']);
        $pi1->add("PARAGRAF_S1",$sec1['paragraf']);

        //Inks S2
        $sec2 = $SEC->get_section("name","'Inks S2'");
        $pi1->add("EID_S2",$UTIL->ID_EID($sec2['id']));
        $pi1->add("TITLE_S2",$sec2['title']);
        $pi1->add("PARAGRAF_S2",$sec2['paragraf']);
       
        //Inks S3
        $sec3 = $SEC->get_section("name","'Inks S3'");
        $pi1->add("EID_S3", $UTIL->ID_EID($sec3['id']));
        $pi1->add("SUBTITLE_S3", $sec3['subtitle']);
        $pi1->add("PARAGRAF_S3", $sec3['paragraf']);

        $p->add("CONTENT",$pi1->pagina());
        
	$p->show();
