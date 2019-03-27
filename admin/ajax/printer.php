<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'update-video':
    update_video();
    break;

    case 'load-photos':
    load_photos();
    break;

    case 'delete-photo':
    delete_photo();
    break;

    case 'update-printer':
    update_printer();
    break;

    case 'create-printer':
    create_printer();
    break;

    case 'delete-printer':
    delete_printer();
    break;

    case 'upload-picture':
    upload_picture("upload-picture");
    break;
    
    case 'upload-banner':
    upload_picture("upload-banner");
    break;

}


/**
 * 
 */
function create_printer(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $name = $form["name"];
    $status = $form["status"];
    $thick = $form["thick"];
    $width = $form["width"];
    $resolution = $form["resolution"];
    $dimension = $form["dimension"];
    $weight = $form["weight"];
    $message = $form["message"];
    $description_short = $form["description_short"];
    $description = $form["description"];
    $youtube = "";
    $banner = "";
    $MSJ ="";
    $code = 3;
    $id = 0;
    $url = "";
    //Insertando product
    if (class_exists('Printer')) {

       $PRINTER = new Printer();
       $FLAG = $PRINTER->create_printer($name, $status, $thick, $width, $resolution, $dimension, $weight, $message, $description_short, $description, $youtube, $banner);
       $MSJ =  ($FLAG > 0) ? "Printer created, redirecting..." : "Printer not created";
       $code = ($FLAG > 0) ? 1 : 2;
       $type = ($FLAG > 0) ? "success" : "warning";
       if($FLAG > 0) $eid = $UTIL->ID_EID($FLAG);
       $url = SERVER_URL . "/admin/printer_upx.php?EID=" . $eid;
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($MSJ,$type), "code" => $code, "url" => $url);
    echo json_encode($res);

}

/**
 * 
 */
function update_printer(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $ID = $UTIL->EID_ID($_POST['EID']);
    $name = $form["name"];
    $status = $form["status"];
    $thick = $form["thick"];
    $width = $form["width"];
    $resolution = $form["resolution"];
    $dimension = $UTIL->eol($form["dimension"]);
    $weight = $UTIL->eol($printer['weight']);
    $message = $form["message"];
    $description_short = $form["description_short"];
    $description = $form["description"];
    
    $youtube = "";
    $banner = "";
    $MSJ ="";
    $code = 3;
    //Insertando product
    if (class_exists('Printer')) {

       $PRINTER = new Printer();
       $FLAG = $PRINTER->update_printer($ID , $name, $status, $thick, $width, $resolution, $dimension, $weight, $message, $description_short, $description, $youtube, $banner);
       $MSJ =  ($FLAG) ? "Printer Updated." : "Printer not Updated";
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
 */
function delete_printer(){
    $UTIL = new Util();
    $PRINTER = new Printer();
    $ID = trim($_POST['ID']);
    if(is_numeric($ID)){
       $FLAG = $PRINTER->delete_printer($ID);

    }
    $MSJ =  ($FLAG > 0) ? "Printer deleted..." : "Printer not deleted";
    $code = ($FLAG > 0) ? 1 : 2;
    $type = ($FLAG > 0) ? "success" : "warning";
    $printers = $PRINTER->adm_printer_ls();
    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code, "printers"=>$printers);
    echo json_encode($res);
}

/**
 * 
 */
function update_video(){
    //parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $ID = $UTIL->EID_ID($_POST['EID']);
    
    $MSJ ="";
    $code = 3;
    $url = "";
    $embed = "";
    //Insertando product
    if (class_exists('Printer')) {

        $parts = parse_url($_POST["video"]);
        parse_str($parts['query'], $query);
        $youtube = $query['v'];

       if ($youtube != "" AND strrpos($parts['host'], "youtube")){ 
            $PRINTER = new Printer();
            $FLAG = $PRINTER->update_printer($ID , "", "", "", "", "", "", "", "", "", "", $youtube);
            if($FLAG){
                $url = "https://www.youtube.com/watch?v=$youtube";
                $embed = "https://www.youtube.com/embed/$youtube?rel=0";
            }
       
            $MSJ =  ($FLAG) ? "Video Updated." : "Video not Updated";
            $code = ($FLAG) ? 1 : 2;
            $type = ($FLAG) ? "success" : "warning";

        }else{
        $MSJ = "The url has a problem, please check";
        $code = 2;
    } 
       
       
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }



    $res = array("msj" => $UTIL->web_alert($MSJ,$type), "code" => $code, "url" => $url, "embed" => $embed );
    echo json_encode($res);

}


/**
 * 
 * 
 */
function load_photos(){
    $UTIL = new Util();
    $PRINTER = new Printer();
    $ID = $UTIL->EID_ID($_POST['EID']);
    $photos = $PRINTER->admin_photo_list($ID);
    $res = array("photos" => $photos);
    echo json_encode($res);
}

/**
 * 
 */
function delete_photo(){
    $UTIL = new Util();
    $PRINTER = new Printer();
    if($PRINTER->delete_picture($_POST['ID-SLIDER'])){
        $msj = "Photo deleted";
        $code = 1;
    }else{
        $msj = "Photo not deleted";
        $code = 2;
    }
    $res = array("msj" => $msj, "code" => $code);
    echo json_encode($res);
}


/**
 * 
 */
function upload_picture($accion){
    $UTIL = new Util();
    $PRINTER = new Printer();
    //var_dump($_POST);
    //var_dump($_FILES);
    $type = "warning";
    $ID = $UTIL->EID_ID($_POST['EID']);
    $msj ="";
    if($accion == "upload-picture"){
        $FILE = (object)$_FILES["photo-new"];
        $FILE_FOLDER = "/files/products/printers/$ID/slider/";
    }elseif($accion == "upload-banner"){
        $FILE = (object)$_FILES["banner-new"];
        $FILE_FOLDER = "/files/products/printers/$ID/";
    }   
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
            if($accion == "upload-picture"){
                $alt_text = $_POST['photo-new-alt'];
                $flag_picture = ($PRINTER->insert_picture($ID, $ruta, $alt_text, "")>0) ? TRUE : FALSE ;
            }elseif($accion == "upload-banner"){
                $alt_text = $_POST['banner-new-alt'];
                $flag_picture = $PRINTER->update_printer($ID , "", "", "", "", "", "", "", "", "", "", "", $ruta, $alt_text);
            } 
            
            if($flag_picture){
                $msj = "Photo uploaded";
                $code = 1;

            }else{
                $msj = "Photo not created";
                $code = 2;
            }
            
            $type = ($flag_picture) ? "success" : "warning";


        }else{
            $msj = "Photo not uploaded ";
            $code = 2;
            
        }

    }
    
    $res = array("msj" => $UTIL->web_alert($msj,$type), "code" => $code, "url" => SERVER_URL . $ruta, "accion"=>$accion);
    echo json_encode($res);
}



/**
 * 
 * 
 */
function upload_video(){
    $UTIL = new Util();
    //var_dump($_POST);
    //var_dump($_FILES);
    $FILE = (object)$_FILES["input-video"];
    $low = $_POST['low'];
    $msj ="";
    $FILE_FOLDER = "/files/products/printers/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    
    if ( 0 < $FILE->error ) {
         $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
         
         if (!file_exists($PATH)) { 
             mkdir($PATH);
         }
        /*
            $FILENAME = $UTIL->random_string(24).'_'. $FILE->name;
            //$FILENAME = 'foto_banner_bot'.$UTIL->get_file_term_type($FILE->name);
            $FILENAME = $UTIL->remove_spaces($FILENAME, 1);
        */
        
        $name = "NAMEs";
        
         
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
            if($UTIL->setValConfig("HOME_VIDEO", $FILE_FOLDER . $FILENAME)){
                $msj = "Video updated";
                $code = 1;
            }else{
                $msj.= " [NOTUPD] ";
            } 
         }else {
             $code = 2;
             $msj = "Video not updated";
         }
 
     }


    $res = array("msj" => $msj, "code" => $code, "video" => SERVER_URL . $FILE_FOLDER . $FILENAME);
    echo json_encode($res);

}


