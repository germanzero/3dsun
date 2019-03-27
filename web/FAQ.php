<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$PAGE = new Page();

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("FAQ")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("FAQ",$p);

//Content
$p->add("3D_CONTENT", $PAGE->web_faq_list());



$p->show();
