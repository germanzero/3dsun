<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'update-section':
    update_section();
    break;

    case 'load-photos':
    load_photos();
    break;

    case 'delete-photo':
    delete_photo();
    break;

    case 'upload-photo':
    upload_photo();
    break;
       
    case 'update-alt-photo':
    update_alt_photo();
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
       $title =  ($form["title-" . $SEC]) ? $form["title-" . $SEC] : "";
       $subtitle =  ($form["subtitle-" . $SEC]) ? $form["subtitle-" . $SEC] : "";
       $paragraf =  ($form["paragraf-" . $SEC]) ? $form["paragraf-" . $SEC] : "";
       
       $SECTION = new Section();
       $FLAG = $SECTION->section_update($SEC_ID , "", $title, $subtitle, $paragraf, "");
       $MSJ =  ($FLAG) ? "Section Updated." : "Section not Updated";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";

    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code);
    echo json_encode($res);

}       


/**
 * 
 * 
 */
function load_photos(){
    $UTIL = new Util();
    $SEC = new Section();
    $ID = $UTIL->EID_ID($_POST['EID']);
    $photos = $SEC->admin_photo_list('Applications',$ID);
    $res = array("photos" => $photos);
    echo json_encode($res);
}

/**
 * 
 */
function delete_photo(){
    $UTIL = new Util();
    $SEC = new Section();
    $FLAG = $SEC->delete_slider($_POST['ID-SLIDER']);
    $MSJ =  ($FLAG) ? "Photo deleted." : "Photo not deleted.";
    $code = ($FLAG) ? 1 : 2;
    $type = ($FLAG) ? "success" : "warning";
    $res = array("msj" => $UTIL->web_alert($MSJ,$type), "code" => $code);
    echo json_encode($res);
}


/**
 * 
 */
function update_alt_photo(){
    $UTIL = new Util();
    $SECTION = new Section();
    $FLAG = $SECTION->update_slider($_POST['ID-SLIDER'],$_POST['ALT-TEXT']);
    $MSJ =  ($FLAG) ? "Photo alt updated":"Photo alt not updated";
    $code = ($FLAG) ? 1:2;
    $type = ($FLAG) ? "success" : "warning";
    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code);
    echo json_encode($res);
}




/**
 * 
 */
function upload_photo(){
    $UTIL = new Util();
    $SECTION = new Section();
    //var_dump($_POST);
    //var_dump($_FILES);
    $SEC_ID = $UTIL->EID_ID($_POST['EID']);
    $SEC = $_POST['SECTION'];
    $FILE = (object)$_FILES["photo-new-" . $SEC];
    
    $msj ="";
    $FILE_FOLDER = "/files/Applications/$SEC_ID/slider/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    $type = "warning";
    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        if (!file_exists($PATH)) { 
            mkdir($PATH);
        }
        $FILENAME = $FILE->name;
        $PATH = $PATH . $FILENAME;

        if(move_uploaded_file($FILE->tmp_name, $PATH)){  
            $ruta = $FILE_FOLDER . $FILENAME;
            $alt_text = $_POST['photo-new-' . $SEC . '-alt'];
            $message = $_POST['photo-message'];
            //Update DB
            $FLAG = $SECTION->insert_slider($SEC_ID, $ruta, $alt_text, $message);
            $MSJ =  ($FLAG) ? "Photo uploaded." : "Photo not uploaded.";
            $code = ($FLAG) ? 1 : 2;
            $type = ($FLAG) ? "success" : "warning";
        }else{
            $msj = "Photo not uploaded ";
            $code = 2;
            
        }

    }
    
    $res = array("msj" => $UTIL->web_alert($MSJ,$type), "code" => $code, "img" => SERVER_URL . $ruta);
    echo json_encode($res);
}

