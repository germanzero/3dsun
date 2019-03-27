<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$PAGE = new Page();
$product = "";
$msj = "";
if(isset($_GET['TP'])){
    $type = $_GET['TP'];
    if($type=='L' OR $type=='P'){
        $EID = $_GET['EID'];
        $ID = $UTIL->EID_ID($EID);
        if($type=='L'){
            $LASER = new Laser();
            $record = $LASER->get_laser($ID);
        }

        if($type=='P'){
            $PRINT = new Printer();
            $record = $PRINT->get_printer($ID);
        }
        
        $product = $record['name'];

    }elseif($type=='INKS'){
        $product = "Nano UV inks®";
    
    }elseif($type=='PRIMERS'){
        $product = "Nano UV Primers®";
    
    }
    $msj = "Interested in: ". $product;
}

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("Contact Us")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Contact Us",$p);

//Content
$cont = new Panel("web_contact.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");
$cont->add("MSJ_VAL",$msj);
        

$p->add("3D_CONTENT", $cont->pagina());



$p->show();
