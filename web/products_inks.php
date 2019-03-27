<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$SEC = new Section();
$PAGE = new Page();

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);


//Top SERVER_URL."/images/banner/products.jpg"
$p->add("3D_TOP", $UTIL->get_top("", $PAGE->get_banner_header("Inks")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Inks",$p);

//Content
$cont = new Panel("web_inks.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");

$sec1 = $SEC->get_section("name","'Inks S1'");
$cont->add("TITLE_SEC_1",$sec1['title']);
$cont->add("SUBTITLE_SEC_1",$sec1['subtitle']);
$cont->add("PARAGRAF_SEC_1",$sec1['paragraf']);

$sec2 = $SEC->get_section("name","'Inks S2'");
$cont->add("TITLE_SEC_2",$sec2['title']);
$cont->add("PARAGRAF_SEC_2",$sec2['paragraf']);

$sec3 = $SEC->get_section("name","'Inks S3'");
$cont->add("SUBTITLE_SEC_3",$sec3['subtitle']);
$cont->add("PARAGRAF_SEC_3",$sec3['paragraf']);


$p->add("3D_CONTENT", $cont->pagina());



$p->show();
