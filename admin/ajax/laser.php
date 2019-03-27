<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {


    case 'delete-photo':
    delete_photo();
    break;

    case 'update-laser':
    update_laser();
    break;

    case 'create-laser':
    create_laser();
    break;

    case 'delete-laser':
    delete_laser();
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
function create_laser(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $name = $form["name"];
    $status = $form["status"];
    $description_short = $form["description_short"];
    $description = $form["description"];
    $technical = $form["technical"];
    $youtube = "";
    $banner = "";
    $MSJ ="";
    $code = 3;
    $id = 0;
    $url = "";
    //Insertando product
    if (class_exists('Laser')) {

       $LASER = new Laser();
       $FLAG = $LASER->create_laser($name, $status, $description_short, $description, $technical, $banner);
       $MSJ =  ($FLAG > 0) ? "Laser created, redirecting..." : "Laser not created";
       $code = ($FLAG > 0) ? 1 : 2;
       $type = ($FLAG > 0) ? "success" : "warning";
       if($FLAG > 0) $eid = $UTIL->ID_EID($FLAG);
       $url = SERVER_URL . "/admin/laser_upx.php?EID=" . $eid;
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
function update_laser(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_POST);
    $UTIL = new Util();
    $ID = $UTIL->EID_ID($_POST['EID']);
    $name = $form["name"];
    $status = $form["status"];
    $description_short = $form["description_short"];
    $description = $form["description"];
    $technical = $form["technical"];
    
    $youtube = "";
    $banner = "";
    $MSJ ="";
    $code = 3;
    //Insertando product
    if (class_exists('Laser')) {

       $LASER = new Laser();
       $FLAG = $LASER->update_laser($ID , $name, $status, $description_short, $description, $technical, $banner);
       $MSJ =  ($FLAG) ? "Laser Updated." : "Laser not Updated";
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
function delete_laser(){
    $UTIL = new Util();
    $LASER = new Laser();
    $ID = trim($_POST['ID']);
    if(is_numeric($ID)){
       $FLAG = $LASER->delete_laser($ID);

    }
    $MSJ =  ($FLAG > 0) ? "Laser deleted..." : "Laser not deleted";
    $code = ($FLAG > 0) ? 1 : 2;
    $type = ($FLAG > 0) ? "success" : "warning";
    $printers = $LASER->adm_laser_ls();
    $res = array("msj" =>  $UTIL->web_alert($MSJ,$type), "code" => $code, "printers"=>$printers);
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
        $FILE_FOLDER = "/files/products/lasers/$ID/slider/";
    }elseif($accion == "upload-banner"){
        $FILE = (object)$_FILES["banner-new"];
        $FILE_FOLDER = "/files/products/lasers/$ID/";
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

                 //generate Thumbnail
                    try{
                        $IMAGE = new Image();
                        $IMAGE->generateThumbnail(SERVER_FOLDER . $ruta, $PRINTER->th_w, $PRINTER->th_h, 80);
                    }catch(Exception $e){
                        
                    }

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





