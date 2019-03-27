<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$LASER = new Laser();
$PAGE = new Page();

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("Laser Cutting")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Laser Cutting",$p);

//Content
$cont = new Panel("web_laser_cuttings.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");
        
$cont->add("LNK-LASER",$LASER->sub_menu_links("sub_menu_printer_link_item.html"));
        

$p->add("3D_CONTENT", $cont->pagina());



$p->show();
