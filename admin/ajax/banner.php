<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

        case 'upload_slider':
        upload_slider();
        break;


        case 'upload_banner_bot':
        upload_banner_bot();
        break;
        
    
        case 'update_info_bot':
        update_info_bot();
        break;
}

function upload_banner_bot(){
    $BANNER = new Banner();
    $UTIL = new Util();
    $FILE = (object)$_FILES["archivo"];
    
    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        $path = SERVER_FOLDER . '/images/sections/banner_botonera/';
        
        if (!file_exists($path)) { 
            mkdir($path);
        }
      
        $FILENAME = $UTIL->random_string(24).'_'. $FILE->name;
        //$FILENAME = 'foto_banner_bot'.$UTIL->get_file_term_type($FILE->name);
        $FILENAME = $UTIL->remove_spaces($FILENAME, 1);
        $RUTA = $path . $FILENAME;
        
        if(move_uploaded_file($FILE->tmp_name, $RUTA)){ 
            $RESP = $BANNER->update_image_banner_bot($FILENAME);
            $code = "2";
            $msj = "Imagen del banner actualizada";
            
            if(substr_count ($RESP , "ERROR" )>0){
                $msj = "Ocurrió un problema subiendo el archivo. Espere e intente de nuevo";
                $code = "3";
            } 
        }

    }

    $response = array("msj" => $msj, "FILENAME"=>$FILENAME,  "ruta"=>SERVER_URL. '/images/sections/banner_botonera/'.$FILENAME, "code"=>$code);
    echo json_encode($response);
}

function update_info_bot(){
    $BANNER = new Banner();
    parse_str($_POST["formula"], $form);
    //var_dump($form);
    $title = $form["TITLE"];
    $subtitle = $form["SUBTITLE"];
    $info = $form["INFO_BOT"];
    $href = $form["REDIR_BOT"];
    
    if(!(substr_count ($href , "http://" )>0 or substr_count ($href , "https://" )>0)){
        $href = "http://" . $href;
    }

    $RESP = $BANNER->update_info_image_banner_bot($title, $subtitle, $info, $href);
    $code = 2;
    $msj = "Información del banner actualizada";
            
    if(substr_count ($RESP , "ERROR" )>0){
        $msj = "Ocurrió un problema subiendo el archivo. Espere e intente de nuevo";
        $code = "3";
    } 

    $response = array("msj" => $msj, "code"=>$code);
    echo json_encode($response);
}

function upload_slider(){
    $BANNER = new Banner();
    $UTIL = new Util();
    $FILE = (object)$_FILES["slider"];
    parse_str($_POST["formula"], $form);
    $msj = $form["txt-msj"];

    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        $path = SERVER_FOLDER . '/images/slider/';
        
        if (!file_exists($path)) { 
            mkdir($path);
        }

      
        $FILENAME = $UTIL->random_string(24).'_'. $FILE->name;
        
        if(move_uploaded_file($FILE->tmp_name, $path . $FILENAME)){ 
            $RESP = $BANNER->create_slider($FILENAME, $msj);

                if(substr_count ($RESP , "ERROR" )>0){
                    $msj = "Ocurrió un problema subiendo el archivo. Espere e intente de nuevo";
                    $code = "3";
                } 
        }

    }

    $sliders = $BANNER->get_sliders_admin();
    $response = array("msj" => $msj, "sliders" => $sliders, "FILENAME"=>$FILENAME);
    echo json_encode($response);
}

function del_brand($type){
    
        $UTIL = new Util();
        $PROD = new Producto();
    
        //Recibo elemento y el id de producto ele, idp
        $ele = $_POST["ele"];
        $id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
        $msj1 = "ele: $ele  /  idp: $id";
      
        if($type==1){
            if($PROD->del_tec_spec($ele,$id)>0){
                $msj = "Eliminado con éxito.";
                $list = $PROD->tec_spec_list($id);
        
            }else {
                $msj = "Ocurrió un problema, intente de nuevo.";
                $list = $PROD->tec_spec_list($id);
            }
        }elseif($type==2){
            if($PROD->del_spec_fea($ele,$id)>0){
                $msj = "Eliminado con éxito.";
                $list = $PROD->spec_fea_list($id);
        
            }else {
                $msj = "Ocurrió un problema, intente de nuevo.";
                $list = $PROD->spec_fea_list($id);
            }
        }elseif($type==3){
            if($PROD->del_tec($ele,$id)>0){
                $msj = "Eliminado con éxito.";
                $list = $PROD->tec_list($id);
        
            }else {
                $msj = "Ocurrió un problema, intente de nuevo.";
                $list = $PROD->tec_list($id);
            }
        
        }elseif($type==4){
            if($PROD->del_apl($ele,$id)>0){
                $msj = "Eliminado con éxito.";
                $list = $PROD->apl_list($id);
        
            }else {
                $msj = "Ocurrió un problema, intente de nuevo.";
                $list = $PROD->apl_list($id);
            }
        }
    
        $msj = $PROD->del_tec($ele,$id);
        $response = array("list" => $list, "msj" => $msj);
        echo json_encode($response);
    
}
    
    
function create_brand(){
  
    parse_str($_POST["formula"], $form);
    //var_dump($form);
    $name = $form["NAME_BRD"];
    $site = $form["SITE_BRD"];
    $desc = $form["DESC_BRD"];
    $info = $form["INFO_BRD"];
    $home = $form["HOME_BRD"];
    $spec = $form["SPEC_BRD"];
    
    if($home == "on") $home = 1;
    else $home = 0;
    //Move Icon image
    $ICON = (object)$_FILES["icon"];
    //Move logo image
    $LOGO = (object)$_FILES["logo"];
    //Move banner image
    $BANNER = (object)$_FILES["banner"];
    

    //Insertando product
    if (class_exists('Brand')) {
       $BRAND = new Brand();
       $ID = $BRAND->brand_create($name, $site, "", "", $home, $desc, $info, "1", "", $spec);
       $util = new Util();
       $EID = $util->encrypt($ID.CRYPT);

       if ( 0 < $ICON->error ) {
         $msj = 'Error: ' . $ICON->error . '<br>';
        }else {
            $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
            if (!file_exists($path)) { 
                mkdir($path);
            }
    
            if(move_uploaded_file($ICON->tmp_name, $path . $ICON->name)){    
                $FILENAME = $ICON->name;    
                $BRAND->brand_update($ID, $name, $site, $ICON->name, "", $home, $desc, "", "1");
            }else $FILENAME = "dummy";
            
        } 

        if ( 0 < $LOGO->error ) {
             $msj = 'Error: ' .  $LOGO->error . '<br>';
            }else {
                $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
                if (!file_exists($path)) { 
                    mkdir($path);
                }
        
                if(move_uploaded_file($LOGO->tmp_name, $path . $LOGO->name)){    
                    //($id, $name, $site = "", $icon = "", $logo = "", $home = "0", $desc="", $info = "" $active = "1")
                    $BRAND->brand_update($ID, $name, $site, "", $LOGO->name, $home, "", "", "1");
                }else $FILENAME = "dummy";
                
        } 
            
        if ( 0 < $BANNER->error ) {
                $msj = 'Error: ' . $BANNER->error . '<br>';
        }else {
            $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
            if (!file_exists($path)) { 
                mkdir($path);
            }
    
            if(move_uploaded_file($BANNER->tmp_name, $path . $BANNER->name)){    
                //($id, $name, $site = "", $icon = "", $logo = "", $home = "0", $desc="", $info = "" $active = "1",  $banner = "", $spec)
                $BRAND->brand_update($ID, $name, $site, "", "", $home, "", "", "1",$BANNER->name);
            }else $FILENAME = "dummy";
            
        } 

    }else{
        $EID = "Clase no existe";
    }


    $res = array("formula.name"=>$form["name"], "eid"=>$EID, "id"=>$ID, "msj"=>$msj);
    //$res = array("mientras"=>"seLevantan");
    echo json_encode($res);

}




function update_brand(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $EID = $form["HD_ID_BRD"];
    $name = $form["NAME_BRD"];
    $spec = $form["SPEC_BRD"];
    $desc = $form["DESC_BRD"];
    $info = $form["INFO_BRD"];
    $site = $form["SITE_BRD"];
    $exc = $form["EXC_BRD"];
    if($exc == "on") $exc = 1;
    else $exc = 0;
    $FILENAME = "";
    $FILENAME_LOGO = "";
    $FILENAME_BANNER = "";
    
    $update_icon = ($_POST["update_icon"] === 'true');
    $update_logo = ($_POST["update_logo"] === 'true');
    $update_banner = ($_POST["update_banner"] === 'true');
    

    //Insertando product
    if (class_exists('Brand')) {


        if($update_icon){
            
                $ICON = (object)$_FILES["icon"];
                
                //Move Icon image
                if ( 0 < $ICON->error ) {
                    echo 'Error: ' . $_FILES['icon']['error'] . '<br>';
                }else {
            
                    $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
                    if (!file_exists($path)) { 
                        mkdir($path);
                    }
            
                    if(move_uploaded_file($_FILES['icon']['tmp_name'], $path . $ICON->name)){    
                        $FILENAME = $ICON->name;    
                    }else $FILENAME = "dummy";
            
                }
        }

        if($update_logo){
            
                $LOGO = (object)$_FILES["logo"];
                
                //Move Icon image
                if ( 0 < $LOGO->error ) {
                    echo 'Error: ' . $LOGO->error . '<br>';
                }else {
            
                    $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
                    if (!file_exists($path)) { 
                        mkdir($path);
                    }
            
                    if(move_uploaded_file($LOGO->tmp_name, $path . $LOGO->name)){    
                        $FILENAME_LOGO = $LOGO->name;    
                    }else $FILENAME_LOGO = "dummy";
            
                }
        }

        if($update_banner){
            
                $BANNER = (object)$_FILES["banner"];
                
                //Move Icon image
                if ( 0 < $BANNER->error ) {
                    echo 'Error: ' . $BANNER->error . '<br>';
                }else {
            
                    $path = SERVER_FOLDER . '/uploads/brands/' . $EID . '/';
                    if (!file_exists($path)) { 
                        mkdir($path);
                    }
            
                    if(move_uploaded_file($BANNER->tmp_name, $path . $BANNER->name)){    
                        $FILENAME_BANNER = $BANNER->name;    
                    }else $FILENAME_BANNER = "dummy";
            
                }
        }


       $BRAND = new Brand();
       $util = new Util();
       $ID =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $ID = $BRAND->brand_update($ID, $name, $site, $FILENAME, $FILENAME_LOGO, $home, $desc, $info, "1", $FILENAME_BANNER, $spec);
       //brand_update($id, $name, $site = "", $icon = "", $logo = "", $home = "0", $desc = "", $info = "",  $active = "1", $banner = "", $spec = "")
       //$EID = $util->encrypt($ID.CRYPT);
    }else{
        $ID = "Clase no existe";
    }

    $res = array("formula.name"=>$form["name"], "eid"=>$EID, "msj" => $ID);
    echo json_encode($res);
}

       
