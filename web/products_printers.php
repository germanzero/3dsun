<?php
//session_start();
include ("../include/global.php");

$UTIL = new Util();
$PRINT = new Printer();
$PAGE = new Page();

/* Panel Principal */
$p = new Panel("index.html");
$p->add("SERVER_URL",SERVER_URL);

//Top
$p->add("3D_TOP", $UTIL->get_top("",$PAGE->get_banner_header("UV Printers")));
//Footer
$p->add("3D_FOOTER", $UTIL->get_footer());
//Seo
$p = $UTIL->load_page_seo("UV Printers",$p);


//Content
$cont = new Panel("web_uvprinters.html");
$cont->add("SERVER_URL",SERVER_URL);
$cont->add("BTN-SCROLL","");
$cont->add("LNK-PRINTERS",$PRINT->sub_menu_links("sub_menu_printer_link_item.html"));

$p->add("3D_CONTENT", $cont->pagina());

$p->show();
