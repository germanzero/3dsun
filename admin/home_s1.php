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
		$p->add("PAGE-TITLE",'Update Summary');
		

        //Content
      	//$pi->add("SERVER_URL",SERVER_URL);

		//Home S1
		$sec1 = $SEC->get_section("name","'Home S1'");
		$pi1=new Panel("admin_home_s1.html");
		$pi1->add("SERVER_URL",SERVER_URL);
        $pi1->add("TITLE",$sec1['title']);
        $pi1->add("SUBTITLE",$sec1['subtitle']);
        $pi1->add("PARAGRAF",$sec1['paragraf']);
		$pi1->add("PHOTOS_HOME_S1",$SEC->admin_photo_list('Home',$sec1['id']));
		$pi1->add("IMG_INPUT",$UTIL->load_inputfile("photo-new","Upload New Image"));
		$pi1->add("INPUT-ID","photo-new");
		$pi1->add("EID-SECTION",$UTIL->ID_EID($sec1['id']));
        $p->add("CONTENT",$pi1->pagina());
        
	$p->show();
