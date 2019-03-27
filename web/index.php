<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$BLOG = new Blog();

//Section statements
$page_name = 'Home';
$SEC = new Section();

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("","video"));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("Home",$p);
//Content
$cont = new Panel("web_home_var.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");
//Ad-Banner
$section = $SEC->get_section("name","'Home Banner'");
$banner = $SEC->get_banner($section['id']);
$cont->add("CLASS-BANNER", ($banner['status']=="enabled") ? "":"d-none");
$cont->add("ALT-BANNER",$banner['alt_text']);
$cont->add("PATH-BANNER",SERVER_URL . $banner['path']);
$href = $banner['href'];

if(!(substr_count ($href , "http://" )>0 or substr_count ($href , "https://" )>0)){

    if(!(substr_count ($href , "http://" )>0)){
        $href = "http://" . $href;
    }else{
        if(!(substr_count ($href , "https://" )>0)){
            $href = "https://" . $href;
        }
    }

}
$cont->add("HREF-BANNER",$href);

//Section 1
$section1 = $SEC->get_section("name","'Home S1'");
$cont->add("TITLE_SEC_1",$section1['title']);
$cont->add("SUBTITLE_SEC_1",$section1['subtitle']);
$cont->add("PARAGRAF_SEC_1",$section1['paragraf']);
$cont->add("SLIDER_SEC_1",$SEC->get_slider($page_name,$section1['id']));

//Section 2
$section2 = $SEC->get_section("name","'Home S2'");
$cont->add("SUBTITLE_SEC_2",$section2['subtitle']);
$cont->add("PARAGRAF_SEC_2",$section2['paragraf']);

//Section 3
$sec3 = $SEC->get_section("name","'Home S3'");
$cont->add("TITLE_SEC_3",$sec3['title']);
$hover_boxes = $SEC->get_web_hover_boxes($page_name,$sec3['id']);
$cont->add("HOVER_BOXES_SEC_3",$hover_boxes);


$cont->add("ROWS_BLOG",$BLOG->web_home_post_list());

$p->add("3D_CONTENT", $cont->pagina());
$p->show();
