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
		$p->add("PAGE-TITLE",'New Blog Post');
		
        //Content
		//Home S3
		$section3 = $SEC->get_section("name","'Home S3'");
		$pi3=new Panel("admin_home_s3.html");
		$pi3->add("SERVER_URL",SERVER_URL);
		$pi3->add("EID-SECTION",$UTIL->ID_EID($section3['id']));
		$pi3->add("TITLE_HOME_S3",$section3['title']);
		$pi3->add("PHOTOS_HOME_S3",$SEC->admin_photo_list('Home',$section3['id']));
		$pi3->add("IMG_INPUT",$UTIL->load_inputfile("photo-new","Upload New Image"));
		$pi3->add("INPUT-ID","photo-new");
        $p->add("CONTENT",$pi3->pagina());
        
	$p->show();
