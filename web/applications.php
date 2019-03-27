<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$SEC = new Section();
$PAGE = new Page();
/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("Applications")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//SEO
$p = $UTIL->load_page_seo("Applications",$p);

//Content
$cont = new Panel("web_applications.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");


$sec1 = $SEC->get_section("name","'Apps S1'");
$cont->add("TITLE_SEC_1",$sec1['title']);
$cont->add("SUBTITLE_SEC_1",$sec1['subtitle']);
$cont->add("PARAGRAF_SEC_1",$sec1['paragraf']);

$sec2 = $SEC->get_section("name","'Apps S2'");
$cont->add("TITLE_SEC_2",$sec2['title']);
$cont->add("PARAGRAF_SEC_2",$sec2['paragraf']);
$cont->add("SLIDER_SEC_2",$SEC->get_slider("Applications",$sec2['id']));

$sec3 = $SEC->get_section("name","'Apps S3'");
$cont->add("TITLE_SEC_3",$sec3['title']);
$cont->add("PARAGRAF_SEC_3",$sec3['paragraf']);
$cont->add("SLIDER_SEC_3",$SEC->get_slider("Applications",$sec3['id']));

$sec4 = $SEC->get_section("name","'Apps S4'");
$cont->add("TITLE_SEC_4",$sec4['title']);
$cont->add("PARAGRAF_SEC_4",$sec4['paragraf']);
$cont->add("SLIDER_SEC_4",$SEC->get_slider("Applications",$sec4['id']));


$p->add("3D_CONTENT", $cont->pagina());



$p->show();
