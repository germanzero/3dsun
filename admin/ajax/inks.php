<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'update-section':
    update_section();
    break;


}


/**
 * 
 */
function update_section(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $SEC = $_POST['SECTION'];
    $SEC_ID = $UTIL->EID_ID($_POST['EID']);
    $MSJ = "Section not Updated";
    $code = 3;
    $type =  "warning";
    //Insert
    if (class_exists('Section')) {
       $title       =  ($form["title-" . $SEC])     ? $form["title-" . $SEC]    : "";
       $subtitle    =  ($form["subtitle-" . $SEC])  ? $form["subtitle-" . $SEC] : "";
       $paragraf    =  ($form["paragraf-" . $SEC])  ? $form["paragraf-" . $SEC] : "";
       
       $SECTION = new Section();
       $FLAG    = $SECTION->section_update($SEC_ID , "", $title, $subtitle, $paragraf, "");
       $MSJ     = ($FLAG) ? "Section Updated." : "Section not Updated";
       $code    = ($FLAG) ? 1 : 2;
       $type    = ($FLAG) ? "success" : "warning";

    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code);
    echo json_encode($res);

}       
