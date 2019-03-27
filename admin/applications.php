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
		$p->add("PAGE-TITLE",'Update "Applications" page.');

        //Content
      	//$pi->add("SERVER_URL",SERVER_URL);

        //Panel
        $pi1=new Panel("admin_applications.html");
        $pi1->add("SERVER_URL",SERVER_URL);
        
        //Apps S1
		$sec1 = $SEC->get_section("name","'Apps S1'");
        $pi1->add("EID_S1",$UTIL->ID_EID($sec1['id']));
        $pi1->add("TITLE_S1",$sec1['title']);
        $pi1->add("SUBTITLE_S1",$sec1['subtitle']);
        $pi1->add("PARAGRAF_S1",$sec1['paragraf']);

        $pi1->add("INPUT-ID","photo-new");
        
        //Apps S2
        $sec2 = $SEC->get_section("name","'Apps S2'");
        $pi1->add("EID_S2",$UTIL->ID_EID($sec2['id']));
        $pi1->add("TITLE_S2",$sec2['title']);
        $pi1->add("PARAGRAF_S2",$sec2['paragraf']);
        $pi1->add("PHOTOS_S2",$SEC->admin_photo_list('Applications',$sec2['id']));
        $pi1->add("IMG_INPUT_S2",$UTIL->load_inputfile("photo-new-s2","Upload New Image"));
        
       
        //Apps S3
        $sec3 = $SEC->get_section("name","'Apps S3'");
        $pi1->add("EID_S3",$UTIL->ID_EID($sec3['id']));
        $pi1->add("TITLE_S3",$sec3['title']);
        $pi1->add("PARAGRAF_S3",$sec3['paragraf']);
        $pi1->add("PHOTOS_S3",$SEC->admin_photo_list('Applications',$sec3['id']));
        $pi1->add("IMG_INPUT_S3",$UTIL->load_inputfile("photo-new-s3","Upload New Image"));
        
        //Apps S4
        $sec4 = $SEC->get_section("name","'Apps S4'");
        $pi1->add("EID_S4",$UTIL->ID_EID($sec4['id']));
        $pi1->add("TITLE_S4",$sec4['title']);
        $pi1->add("PARAGRAF_S4",$sec4['paragraf']);
        $pi1->add("PHOTOS_S4",$SEC->admin_photo_list('Applications',$sec4['id']));
        $pi1->add("IMG_INPUT_S4",$UTIL->load_inputfile("photo-new-s4","Upload New Image"));
        
        $p->add("CONTENT",$pi1->pagina());
        
	$p->show();
