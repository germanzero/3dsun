<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

        case 'load_selects':
        load_selects();
        break;

        case 'create_product':
        create_product();
        break;
    
        case 'update_product':
        update_product();
        break;
    
        case 'add_tec_spec':
        add_tec_spec();
        break;
    
        case 'add_spec_fea':
        add_spec_fea();
        break;

        case 'add_tec':
        add_tec();
        break;
        
        case 'add_apl':
        add_apl();
        break;
        
        case 'add_prel':
        add_prel();
        break;

        case 'del_tec_spec':
        del_fea(1);
        break;
        
        case 'del_spec_fea':
        del_fea(2);
        break;

        case 'del_tec':
        del_fea(3);
        break;

        case 'del_apl':
        del_fea(4);
        break;

        case 'del_prel':
        del_fea(5);
        break;

        case 'upload_file':
        upload_file();
        break;
        
        case 'delete_actual_file':
        delete_actual_file();
        break;
        
        case 'save_link_web':
        save_link_web();
        break;
    
        case 'del_media':
        del_media();
        break;

        case 'del_product':
        del_product();
        break;
}

function delete_actual_file(){

    $PROD = new Producto();
    $filetype = $_POST["filetype"];
    $ID = $PROD->EID_ID(trim($_POST["eid"]));

    $DELF = $PROD->delete_actual_file($ID, $filetype);
    
    if(!strstr($DELF,'DELETED')){
        $msj = "Intente de nuevo";
        $code = 1;
    }else{
        $msj = "Archivo Eliminado";
        $code = 2;
    }
    $res = array("msj" => $msj, "code"=>$code);
    echo json_encode($res);
}

function load_selects(){

    $BRAND = new Brand();
    $select = $BRAND->get_brands_select();

    $CAT = new Category();
    $cat_select = $CAT->get_cats_select();

    $PROD = new Producto();
    $tec_select = $PROD->get_tecs_select();
    $apl_select = $PROD->get_apls_select();
    $cat_val_select = $PROD->get_cat_val_select();

    if(isset($_POST["IDP"])){
        $IDP = $_POST["IDP"];
        $prel_select = $PROD->get_prel_select($PROD->EID_ID($IDP));
        
    }else  $prel_select = "";
   
    $response = array("brand_select" => $select, "cat_select" => $cat_select, "cat_val_select" => $cat_val_select, "tec_select" => $tec_select, "apl_select" => $apl_select, "prel_select" => $prel_select);
    echo json_encode($response);
}

function save_link_web(){
    $link = $_POST["link"];
    $EID = $_POST["eid"];
    //Insertando product
    if (class_exists('Producto')) {

       $PROD = new Producto();
       $id =  $PROD->EID_ID($EID); 
       $ID = $PROD->product_update($id, "","", "","", "","","", "",$link);
       $msj = "Se ha agregado el link al producto";

    }else{
        $msj = "NO se pudo agregar el link, intente de nuevo";
        $ID = "Clase no existe";
    }

 

    $res = array( "formula.name"=>$form["name"], "eid"=>$EID,  "msj" => $msj);
    echo json_encode($res);
}

function del_fea($type){
    
        $UTIL = new Util();
        $PROD = new Producto();
    
        //Recibo elemento y el id de producto ele, idp
        $ele = $_POST["ele"];
        //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
        $id = $PROD->EID_ID($_POST["idp"]);
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
        }elseif($type==5){
            if($PROD->del_prel($ele,$id)>0){
                $msj = "Eliminado con éxito.";
                $list = $PROD->prel_list($id);
        
            }else {
                $msj = "Ocurrió un problema, intente de nuevo.";
                $list = $PROD->prel_list($id);
            }
        }
    
        $msj = $PROD->del_tec($ele,$id);
        $response = array("list" => $list, "msj" => $msj);
        echo json_encode($response);
    
}
    
function add_tec_spec(){
    
    $UTIL = new Util();
    $PROD = new Producto();

    //Recibo la tec spec y el id de producto txt, idp
    $txt = $_POST["txt"];
    //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
    $id = str_replace(CRYPT,"",$UTIL->decrypt($_POST["idp"]));
    //$msj = "txt: $txt  /  idp: $id";

    if($PROD->create_tec_spec($txt,$id)>0){
        $msj = "Agregado con éxito.";
        $list = $PROD->tec_spec_list($id);

    }else {
        $msj = "Ocurrió un problema, intente de nuevo.";
        $list = $PROD->tec_spec_list($id);
    }

    $response = array("list" => $list, "msj" => $msj);
    echo json_encode($response);
}

function add_spec_fea(){
    $UTIL = new Util();
    $PROD = new Producto();

    //Recibo la tec spec y el id de producto txt, idp
    $txt = $_POST["txt"];
    //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
    $id = str_replace(CRYPT,"",$UTIL->decrypt($_POST["idp"]));
    //$msj = "txt: $txt  /  idp: $id";

    if($PROD->create_spec_fea($txt,$id)>0){
        $msj = "Agregado con éxito.";
        $list = $PROD->spec_fea_list($id);

    }else {
        $msj = "Ocurrió un problema, intente de nuevo.";
        $list = $PROD->spec_fea_list($id);
    }

    $response = array("list" => $list, "msj" => $msj);
    echo json_encode($response);
}
    
function add_tec(){
    
    $UTIL = new Util();
    $PROD = new Producto();

    //Recibo la tec y el id de producto txt, idp
    $tec = $_POST["txt"];
    //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
    $id = $PROD->EID_ID($_POST["idp"]);
    $msj = "txt: $txt  /  idp: $id";
    
    $resp = $PROD->add_tec($tec,$id);
    if($resp>0){
        $msj = "Agregado con éxito.";
        $list = $PROD->tec_list($id);

    }else {
        $msj = "Ocurrió un problema, intente de nuevo.".$resp;
        $list = $PROD->tec_list($id);
    }

    $response = array("list" => $list, "msj" => $msj);
    echo json_encode($response);
}
   
function add_apl(){
    
    $UTIL = new Util();
    $PROD = new Producto();

    //Recibo la apl y el id de producto txt, idp
    $apl = $_POST["txt"];
    //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
    $id = $PROD->EID_ID($_POST["idp"]);
    $msj = "txt: $txt  /  idp: $id";
    $resp = $PROD->add_apl($apl,$id);
    if($resp>0){
        $msj = "Agregado con éxito.";
        $list = $PROD->apl_list($id);

    }else {
        $msj = "Ocurrió un problema, intente de nuevo.";
        $list = $PROD->apl_list($id);
    }

    $response = array("list" => $list, "msj" => $msj);
    echo json_encode($response);
}
   
function add_prel(){
    
    $UTIL = new Util();
    $PROD = new Producto();

    //Recibo la prel y el id de producto txt, idp
    $prel = $_POST["txt"];
    //$id = str_replace(CRYPT_RIGHT, "",str_replace(CRYPT_LEFT,"",$UTIL->decrypt($_POST["idp"])));
    $id = $PROD->EID_ID($_POST["idp"]);
    $msj = "txt: $txt  /  idp: $id";
    $resp = $PROD->add_prel($prel,$id);
    if($resp>0){
        $msj = "Agregado con éxito.";
        $list = $PROD->prel_list($id);

    }else {
        $msj = "Ocurrió un problema, intente de nuevo.";
        $list = $PROD->prel_list($id);
    }

    $response = array("list" => $list, "msj" => $msj);
    echo json_encode($response);
}


function create_product(){
  
    parse_str($_POST["formula"], $form);
    //var_dump(get_declared_classes());
    $name = $form["name"];
    $desc = $form["desc"];
    $brand = $form["select_brand"];
    $cat = $form["select_cat"];
    $cat_val = $form["cat_val"];
    
    //Insertando product
    if (class_exists('Producto')) {
       $PROD = new Producto();
       //($name, $desc, $icon = "", $brand = "", $cat = "", $cat_val = "", $catalogo ="", $ficha = "")
       $ID = $PROD->product_create($name, $desc,"", $brand, $cat, $cat_val);
       
       $code = "2";
       if(substr_count ($ID , "ERROR" )>0){
           $msj = "Ocurrió un problema cargando el producto.".$ID;
           $code = "3";
       } else{

            $UTIL = new Util();
            $EID = $UTIL->ID_EID($ID);
    
            //Move Icon image
            $ICON = (object)$_FILES["logo"];
    
            if ( 0 < $ICON->error ) {
                //var_dump($ICON);
                $msj = 'Error: ' . $_FILES['logo']['error'] . '<br>';
            }else {
                $path = SERVER_FOLDER . '/uploads/products/' . $EID . '/';
                if (!file_exists($path)) { 
                    mkdir($path);
                }
        
                if(move_uploaded_file($_FILES['logo']['tmp_name'], $path . $ICON->name)){    
                    $PROD->product_update($ID, $name, $desc,$ICON->name, $brand, $cat);  
                }else $FILENAME = "dummy";
                
            }   

       }   

      


    }else{
        $EID = "Clase no existe";
    }
    
    $res = array("formula.name"=>$form["name"], "eid"=>$EID, "msj"=>$msj, "code"=>$code);
    echo json_encode($res);

}

function update_product(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    $EID = $form['HD_ID_PRD'];
    $HD_IMAGE_PRD = $form["HD_IMAGE_PRD"];
    $name = $form["NAME_PRD"];
    $desc = $form["DESC_PRD"];
    $brand = $form["select_brand"];
    $cat = $form["select_cat"];
    $cat_val = $form["CAT_VAL"];
    $ICON = (object)$_FILES["logo"];
    //$name, $desc, $icon, $brand, $cat

    $update_icon = ($_POST["update_icon"] === 'true');
    
    //Insertando product
    if (class_exists('Producto')) {

        if($update_icon){
           //Move Icon image
            if ( 0 < $ICON->error ) {
                echo 'Error: ' . $_FILES['logo']['error'] . '<br>';
            }else {
                $path = SERVER_FOLDER . '/uploads/products/' . $EID . '/';
                if (!file_exists($path)) { 
                    mkdir($path);
                }

                if(move_uploaded_file($ICON->tmp_name, $path . $ICON->name)){   
                    unlink($HD_IMAGE_PRD);
                    //$PROD->product_update($ID, $name, $desc,$ICON->name, $brand, $cat);  
                    $FILENAME = $ICON->name;
                }else $FILENAME = "";
            }
        } 


       $PROD = new Producto();
       $util = new Util();
       $id =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $ID = $PROD->product_update($id, $name, $desc, $FILENAME, $brand, $cat, $cat_val);
       $msj = "Producto actualizado con exito";
    }else{
        $msj = "Producto NO actualizado, intente de nuevo";
        $ID = "Clase no existe";
    }

 

    $res = array( "formula.name"=>$form["name"], "eid"=>$EID,  "msj" => $msj);
    echo json_encode($res);
}

function upload_file(){
    
    $PROD = new Producto();
    $UTIL = new Util();
    $FILE = (object)$_FILES["archivo"];
    $filetype = $_POST["filetype"];
    $EID = $_POST["eid"];
    $ID =  $PROD->EID_ID($EID); 
    $medias = "";
    $code = "1";
        //Move File
        if ( 0 < $FILE->error ) {
            //echo 'Error: ' . $_FILES['archivo']['error'] . '<br>';
            $code = "-7";
            $msj = 'Error: ' . $_FILES['archivo']['error'] .' / Error->'.$FILE->error.' * '.print_r($_FILES);
        }else {
            $path = SERVER_FOLDER . '/uploads/products/' . $EID . '/';
            if ($filetype === "media") { 
                $path.='media/';
            }
            
            if (!file_exists($path)) { 
                mkdir($path);
            }

          
            $FILENAME = $UTIL->random_string(24).'_'. $FILE->name;
            //$FILENAME = 'Norand_'. $FILE->name;
            
            if(move_uploaded_file($FILE->tmp_name, $path . $FILENAME)){    
                //$PROD->product_update($ID, $name, $desc,$ICON->name, $brand, $cat);  
                
                
                if($filetype === "pdf"){
                    $DELF = $PROD->delete_actual_file($ID, $filetype);
                    //($idp, $name, $desc, $icon = "", $brand = "", $cat = "", $cat_val = "", $catalogo ="", $ficha = "", $link = "")
                    $RESP = $PROD->product_update($ID, "", "", "", "", "","", $FILENAME , "");
                    $src = SERVER_URL . "/images/icons/96/pdf.png";
                }elseif($filetype === "ft"){
                    $DELF = $PROD->delete_actual_file($ID, $filetype);
                    $RESP = $PROD->product_update($ID, "", "", "", "", "", "", "", $FILENAME );
                    $src = $UTIL->icon_by_filetype($FILENAME);
                }elseif($filetype === "media"){
                    $type = $UTIL->get_file_type_id($FILENAME);
                    $NAME =  $_POST["namefile"];
                    $RESP = $PROD->create_multimedia($NAME, $FILENAME, "", $ID, $type);

                }   

                if ($filetype === "media") { 
                    $href = SERVER_URL . "/uploads/products/" . $EID. "/media/" . $FILENAME;
                    $link = "<a target=\"_blank\" href=\"" . SERVER_URL . "/uploads/products/" . $EID . "/media/" . $FILENAME . "\">Descargar</a>";
                    $msj = "Archivo subido con éxito!<br>$link";
                    $medias = $PROD->admin_multimedia_list($ID);
                    
                }else{
                    $link = "<a target=\"_blank\" href=\"" . SERVER_URL . "/uploads/products/" . $EID . "/" . $FILENAME . "\">Descargar</a>&nbsp;";
                    $link.= "<a id=\"del-ft\" class=\"text-danger\" href=\"javascript:delete_actual('$filetype');\">[Eliminar]</a>";
                    
                    $href = SERVER_URL . "/uploads/products/" . $EID. "/" . $FILENAME;
                    $msj = "Archivo subido con éxito!<br>$link";
                        
                }
               
                $code = "2";
                if(substr_count ($RESP , "ERROR" )>0){
                    $msj = "Ocurrió un problema subiendo el archivo. Espere e intente de nuevo".$RESP;
                    $code = "3";
                }    

            }else{
                 $FILENAME = "";
                 $code = "5";
            }

        }

    $res = array( "eid"=>$EID,  "msj" => $msj, "src" => $src, "href" => $href, "code" => $code, "medias"=>$medias);
    echo json_encode($res);
}

function del_media(){

    $PROD = new Producto();
    $UTIL = new Util();
    $EID = $_POST["eid"];
    $ID =  $PROD->EID_ID($EID); 
    $media = $_POST['media'];
    $url = $_POST['url'];
    
    $path = SERVER_FOLDER . '/uploads/products/' . $EID . '/media/'.$url;
    
    
    if($PROD->del_media($media)>0){
        $msj = "Eliminado con éxito.";
        $medias = $PROD->admin_multimedia_list($ID);
        
        if (file_exists($path)) { 
            unlink($path);
        }

    }else{
        $msj = "No se pudo eliminar intente de nuevo.";
    }

    $res = array( "msj" => $msj, "code" => $code, "medias"=>$medias);
    echo json_encode($res);
}

function del_product(){

    $PROD = new Producto();
    $UTIL = new Util();
    $EID = $_POST["eid"];
    $ID =  $PROD->EID_ID($EID); 
    
    $path = SERVER_FOLDER . '/uploads/products/' . $EID . '/';
    
    if($PROD->product_delete($ID)>0){
        $msj = "Eliminado con éxito.";
        
        if($EID != "" AND $EID != null){
            if (file_exists($path)) { 
                unlink($path);
            }
        }
        

    }else{
        $msj = "No se pudo eliminar intente de nuevo.";
    }

    $res = array( "msj" => $msj, "code" => $code);
    echo json_encode($res);
}

