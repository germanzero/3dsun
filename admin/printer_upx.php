<?php	
	session_start();
	
	include_once( "../include/global.php" );
    

	$USER = new User();
	$isIn = $USER->is_logged_in("admin");
	if(!$isIn){
		redirto("../login/logout.php");
	}
	
	
	$UTIL = new Util();
    $PRINT = new Printer();

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
		$p->add("PAGE-TITLE",'Update Printer');
               
        //Content
        $printer = $PRINT->get_printer($id);
        $pi=new Panel("admin_printer_upx.html");
        $pi->add("TITLE-FORM",'Update printer data.');
		$pi->add("SERVER_URL",SERVER_URL);
		$pi->add("EID",$UTIL->ID_EID($printer['id']));
		$pi->add("NAME", $printer['name']);
		$pi->add("STATUS", $printer['status']);
		$pi->add("SHORT_DESC", $printer['description_short']);
		$pi->add("THICK", $printer['material_thickness']);
		$pi->add("WIDTH", $printer['printing_width']);
		$pi->add("RESOLUTION", $printer['resolution']);
		$pi->add("DIMENSIONS", $printer['dimensions']);
		$pi->add("WEIGHT", str_replace('<br>',"\r\n", $printer['weight']));
		$pi->add("MESSAGE", $printer['msj_mid_page']);
		$pi->add("DESCRIPTION", str_replace('<br>',"\r\n", $printer['description']));
		
		
		$pi->add("YT-VIDEO-TAG",$printer['youtube_vid_tag']);
		
		$pi->add("IMG-INPUT-PANEL",$UTIL->load_input_image("banner-new", $printer['banner'], $printer['alt_text_banner']));
		$pi->add("INPUT-ID-BANNER","banner-new");
		$pi->add("HDR-CLASS","d-none");
        
		$pi->add("PHOTOS",$PRINT->admin_photo_list($id));
		
		$pi->add("IMG_INPUT",$UTIL->load_inputfile("photo-new","Upload New Image"));
		$pi->add("INPUT-ID","photo-new");
		
		$p->add("CONTENT",$pi->pagina());
        
	$p->show();
