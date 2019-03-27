<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'update-video':
    update_video();
    break;

    case 'upload-photo':
    upload_slide();
    break;

    case 'delete-photo':
    delete_photo();
    break;
    
    case 'update-alt-photo':
    update_alt_photo();
    break;

    case 'load-photos':
    load_photos();
    break;

    case 'save-s1':
    save_s1();
    break;

    case 'save-s2':
    save_s2();
    break;
    
    case 'save-s3':
    save_s3();
    break;

    case 'update-banner':
    update_banner();
    break;

    case 'upload-banner':
    upload_banner();
    break;


}


/**
 * 
 */
function save_s1(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $SEC_ID = $UTIL->EID_ID($form['EID-SECTION']);
    $title = $form["title"];
    $subtitle = $form["subtitle"];
    $paragraf = $form["paragraf"];
    $msj ="";
    $code = 3;
    $type = "warning";
    //Insertando product
    if (class_exists('Section')) {

       $SECTION = new Section();
       //$util = new Util();
       //$ID =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $RESP = $SECTION->section_update($SEC_ID , "", $title, $subtitle, $paragraf, "");
       $msj  = ($RESP) ? "Section updated" : "Section not updated";
       $type = ($RESP) ? "success" : "warning";
       $code = ($RESP) ? 1 : 2;
       
    }else{
        $msj = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code);
    echo json_encode($res);

}

/**
 * 
 */
function save_s2(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $SEC_ID = $UTIL->EID_ID($form['EID-SECTION']);
    $subtitle = $form["subtitle"];
    $paragraf = $form["paragraf"];
    $msj ="";
    $code = 3;
    $type = "warning";
    //Insertando product
    if (class_exists('Section')) {

       $SECTION = new Section();
       //$util = new Util();
       //$ID =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $RESP = $SECTION->section_update($SEC_ID , "", "", $subtitle, $paragraf, "");
       $msj  = ($RESP) ? "Section updated" : "Section not updated";
       $type = ($RESP) ? "success" : "warning";
       $code = ($RESP) ? 1 : 2;
       
    }else{
        $msj = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code);
    echo json_encode($res);

}

/**
 * 
 */
function save_s3(){
    //var_dump($form);
    //var_dump($_POST);
    parse_str($_POST["formula"],$form);
    $UTIL = new Util();
    $SEC_ID = $UTIL->EID_ID($form['EID-SECTION']);
    $title = $form["title"];
    $msj ="";
    $code = 3;
    $type = "warning";
    //Insertando product
    if (class_exists('Section')) {

       $SECTION = new Section();
       //$util = new Util();
       //$ID =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $RESP = $SECTION->section_update($SEC_ID , "", $title, "", "", "");
       $msj  = ($RESP) ? "Section updated" : "Section not updated";
       $type = ($RESP) ? "success" : "warning";
       $code = ($RESP) ? 1 : 2;
       
    }else{
        $msj = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code);
    echo json_encode($res);

}

/**
 * 
 * 
 */
function load_photos(){
    $UTIL = new Util();
    $SECTION = new Section();
    $SEC_ID = $UTIL->EID_ID($_POST['EID-SECTION']);
    $photos = $SECTION->admin_photo_list("HOME",$SEC_ID);
    $res = array("photos" => $photos);
    echo json_encode($res);
}

/**
 * 
 */
function delete_photo(){
    $UTIL = new Util();
    $SECTION = new Section();
    $FLAG = $SECTION->delete_slider($_POST['ID-SLIDER']);
    $MSJ =  ($FLAG) ? "Photo deleted":"Photo not deleted";
    $code = ($FLAG) ? 1:2;
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
function upload_slide(){
    $UTIL = new Util();
    $SECTION = new Section();
    //var_dump($_POST);
    //var_dump($_FILES);
    $FILE = (object)$_FILES["photo-new"];
    $SEC_ID = $UTIL->EID_ID($_POST['EID-SECTION']);
    $msj ="";
    $FILE_FOLDER = "/files/Home/$SEC_ID/slider/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    
    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        if (!file_exists($PATH)) { 
            mkdir($PATH);
        }
        $FILENAME = $UTIL->random_string(24)."_$SEC_ID.".$UTIL->get_file_term_type($FILE->name);
        $PATH = $PATH . $FILENAME;

        if(move_uploaded_file($FILE->tmp_name, $PATH)){  
            $ruta = $FILE_FOLDER . $FILENAME;
            $alt = $_POST["photo-new-alt"];
            //Update DB
            if($SECTION->insert_slider($SEC_ID, $ruta, $alt)>0){
                $msj = "Photo uploaded";
                $code = 1;
                

            }else{
                $msj = "Photo not created";
                $code = 2;
            }
            sleep(5);
        }else{
            $msj = "Photo not uploaded ".$FILE->tmp_name."  -  ".$PATH;
            $code = 2;
            
        }

    }
    
    $res = array("msj" => $msj, "code" => $code, "img" => SERVER_URL . $ruta);
    echo json_encode($res);
}

/**
 * 
 */
function update_video(){
    $UTIL = new Util();
    $FILE = (object)$_FILES["input-video"];
    $low = $_POST['low'];
    $msj ="";
    $FILE_FOLDER = "/files/Home/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    
    if ( 0 < $FILE->error ) {
         $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
         
         if (!file_exists($PATH)) { 
             mkdir($PATH);
         }

         if ($low == "low") { 
            $name = "Home_Definitivo_Sun_3D_low";
         }else{
            $name = "Home_Definitivo_Sun_3D";
         }
         
         $FILENAME = $UTIL->random_string(24)."_$name.".$UTIL->get_file_term_type($FILE->name);
         $PATH = $PATH . $FILENAME;
         
         if(move_uploaded_file($FILE->tmp_name, $PATH)){ 
            $msj = "Video not uploaded";
            
            $code = 2;

            //Delete actual file
            $anterior = SERVER_FOLDER . $UTIL->getValConfig("HOME_VIDEO");
            if(!is_dir ($anterior)){
                $flag_file = unlink($anterior);
                
            }else{
                $msj.= " [ISDIR $filename] ";
            } 
    
            //Update DB
            $FLAG = $UTIL->setValConfig("HOME_VIDEO", $FILE_FOLDER . $FILENAME);
            $MSJ =  ($FLAG) ? "Video Updated." : "Video not Updated";
            $code = ($FLAG) ? 1 : 2;
            $type = ($FLAG) ? "success" : "warning";

         }else {
             $code = 2;
             $msj = "Video not uploaded";
         }
 
     }


    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code, "video" => SERVER_URL . $FILE_FOLDER . $FILENAME);
    echo json_encode($res);

}


/**
 * 
 */
function upload_banner($accion){
    $UTIL = new Util();
    $SEC = new Section();
    //var_dump($_POST);
    //var_dump($_FILES);
    $type = "warning";
    $ID = $UTIL->EID_ID($_POST['EID']);
    $ID_BAN = $UTIL->EID_ID($_POST['EID-BAN']);
    $alt_text = $_POST['banner-new-alt'];
    $msj ="";
    $FILE = (object)$_FILES["banner-new"];
    $FILE_FOLDER = "/files/Home/$ID/";

    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    
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
            //$message = $_POST['photo-message'];
            //Update DB
            $flag_picture = $SEC->update_banner($ID_BAN, $ruta, "", $alt_text, "");
            $msj = ($flag_picture) ? "Banner updated" : "Banner not updated";
            $type = ($flag_picture) ? "success" : "warning";
            $code = ($flag_picture) ? 1 : 2;

        }else{
            $msj = "Photo not uploaded ";
            $code = 2;
            
        }

    }
    
    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code, "url" => SERVER_URL . $ruta, "alt_text" => $alt_text);
    echo json_encode($res);
}


/**
 * 
 */
function update_banner(){
    $UTIL = new Util();
    $SEC = new Section();
    //var_dump($_POST);
    //var_dump($_FILES);
    $msj ="";
    $type = "warning";
    $ID = $UTIL->EID_ID($_POST['EID']);
    $ID_BAN = $UTIL->EID_ID($_POST['EID-BAN']);
    $href = $_POST['HREF'];
    $status = $_POST['status'];
    
    $flag_picture = $SEC->update_banner($ID_BAN, "", $href, "", $status);
    $msj = ($flag_picture) ? "Banner updated" : "Banner not updated";
    $type = ($flag_picture) ? "success" : "warning";
    $code = ($flag_picture) ? 1 : 2;

    
    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code, "url" => SERVER_URL . $ruta);
    echo json_encode($res);
}

