<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$PRINT = new Printer();

$EID = urldecode($_GET['EID']);
$ID = $UTIL->EID_ID($EID);
$printer = $PRINT->get_printer($ID);


/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("", SERVER_URL . $printer['banner']));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("UV Printer",$p);

//Content 
$cont = new Panel("web_printer.html");
$cont->add("SERVER_URL", SERVER_URL);
$cont->add("BTN-SCROLL","");
$cont->add("PRINTERS_LINK",$PRINT->sub_menu_links());
$cont->add("NAME",$printer['name']);
$cont->add("DESC-SHORT",$printer['description_short']);
$cont->add("MATERIAL-THICKNESS",$printer['material_thickness']);
$cont->add("PRINTING-WIDTH",$printer['printing_width']);
$cont->add("RESOLUTION",$printer['resolution']);
$cont->add("DIMENSIONS",$printer['dimensions']);
$cont->add("WEIGHT",$printer['weight']);
$cont->add("MSJ-MID-PAGE",$printer['msj_mid_page']);
$cont->add("DESCRIPTION",$printer['description']);
$cont->add("YT-VIDEO-TAG",$printer['youtube_vid_tag']);
$cont->add("EID",$EID);
$cont->add("SLIDER",$PRINT->get_slider($ID));

        

$p->add("3D_CONTENT", $cont->pagina());



$p->show();
