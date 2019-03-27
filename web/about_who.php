<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$PAGE = new Page();
/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("Who We Are")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Who We Are",$p);


//Content
$cont = new Panel("web_who.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");
        

$p->add("3D_CONTENT", $cont->pagina());



$p->show();
