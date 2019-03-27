<?php	
	session_start();
	include_once( "../include/global.php" );
    
	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
    $UTIL = new Util();
    $ID = $UTIL->EID_ID(urldecode($_GET['EID']));
    $BLOG = new Blog();
    $post = $BLOG->get_post($ID);
    
	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
        $p = $USER->load_user_details($p);

        //Variables ppales
		$p->add("PAGE-TITLE",'Update Post');
		
        //Content
        $pi=new Panel("admin_post_up.html");
        $pi->add("TITLE-FORM",'Update Blog Post');
		$pi->add("SERVER_URL",SERVER_URL);
        $pi->add("IMG-ACTUAL", $post['pic_header']);
        $pi->add("IMG-INPUT-PANEL",$UTIL->load_input_image("input-img", $post['pic_header']));
		$pi->add("IMG-INPUT-ID","input-img");
		$pi->add("HDR-CLASS","d-none");
        
        
		
		$pi->add("EID",$_GET['EID']);
		$pi->add("TITLE",$post['title']);
		$pi->add("SUBTITLE",$post['subtitle']);
		$pi->add("CONTENT",$post['content']);
		$pi->add("PAGE-TITLE",$post['page_title']);
		$pi->add("PAGE-DESC",$post['page_desc']);
		$pi->add("META-TAGS",$post['meta_tags']);
		$pi->add("STATUS",$post['status']);
        
        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
