<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$BLOG = new Blog();
$PAGE = new Page();

$EID = $_GET['EID'];
$ID = $UTIL->EID_ID($EID);

$post = $BLOG->get_post($ID);

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("News")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//News detail SEO
$p->add("PAGE-TITLE",$post['page_desc']);
$p->add("KEYWORDS",$post['meta_tags']);
$p->add("DESCRIPTION",$post['page_desc']);

//Content
$cont = new Panel("web_blog_detail.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("PIC-HEADER",$post['pic_header']);
$cont->add("TITLE",$post['title']);
$cont->add("SUBTITLE",$post['subtitle']);
$cont->add("CONTENT",$post['content']);
$cont->add("DATE",$post['PUBLISH']);
$cont->add("AUTOR",$post['AUTOR']);
$cont->add("EID",$UTIL->ID_EID($post['id']));
$cont->add("COMMENTS",$BLOG->comment_post_list($ID));


$p->add("3D_CONTENT", $cont->pagina());

$p->show();
