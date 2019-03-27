<?php	
	session_start();
	
	include_once( "../include/global.php" );

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	
	$BLOG = new Blog();

	//Load template
	$p=new Panel("admin_frame.html");

		$p->add("SERVER_URL",SERVER_URL);
        $p = $USER->load_user_details($p);
        
        //Variables ppales
        $p->add("PAGE-TITLE",'Blog Post List');
        
        //Content
        $pi=new Panel("admin_post_ls.html");
        $pi->add("TITLE-FORM",'New Blog Post');
        $pi->add("SERVER_URL",SERVER_URL);
        $pi->add("ROWS",$BLOG->adm_post_ls());
        $p->add("CONTENT",$pi->pagina());
        
	$p->show();
