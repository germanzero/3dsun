<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$LASER = new Laser();

$EID = urldecode($_GET['EID']);
$ID = $UTIL->EID_ID($EID);
$printer = $LASER->get_laser($ID);


/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("", SERVER_URL . $printer['banner']));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Laser Cutting",$p);

//Content 
$cont = new Panel("web_laser_cutting.html");
$cont->add("SERVER_URL", SERVER_URL);
$cont->add("BTN-SCROLL","");
$cont->add("EID",$EID);
$cont->add("LASERS_LINK",$LASER->sub_menu_links());
$cont->add("NAME",$printer['name']);
$cont->add("DESC-SHORT",$printer['description_short']);
$cont->add("DESC",$printer['description']);
$cont->add("TEC-PAR",$printer['technical_parameters']);

$p->add("3D_CONTENT", $cont->pagina());



$p->show();
