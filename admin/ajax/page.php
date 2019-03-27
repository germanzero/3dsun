<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'seo-page-update':
    update_settings_page();
    break;

    case 'update-header':
    upload_header_banner();
    break;
    
    case 'create-faq':
    create_faq();
    break;

    case 'load-faqs':
    load_faqs();
    break;
    
    case 'delete-faq':
    delete_faq();
    break;

}



/**
 * 
 */
function upload_header_banner(){
    $UTIL = new Util();
    $PAGE = new Page();
    
    $ID = $_POST["ID"];
    $FILE = (object)$_FILES["input-img-".$ID];
    $msj ="";
    $FILE_FOLDER = "/files/pages/$ID/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    
    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        if (!file_exists($PATH)) { 
            mkdir($PATH);
        }
        $FILENAME = $UTIL->random_string(6)."_banner_header.".$UTIL->get_file_term_type($FILE->name);
        $PATH = $PATH . $FILENAME;

        if(move_uploaded_file($FILE->tmp_name, $PATH)){  
            $file_path = $FILE_FOLDER . $FILENAME;

            //generate Thumbnail
            $UTIL->generateThumbnail($PATH, 250, 180, 90);

            //Update DB
            if($PAGE->page_update($ID, "","","", $file_path)){
                $msj = "Banner updated";
                $code = 1;

            }else{
                $msj = "Banner not updated";
                $code = 2;
            }
            //sleep(5);
        }else{
            $msj = "Banner not uploaded ";
            $code = 2;
            
        }

    }
    
    $res = array("msj" => $msj, "code" => $code, "img" => SERVER_URL . $file_path);
    echo json_encode($res);
}


/**
 * 
 * 
 */
function update_settings_page(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $ID = $_POST["ID"];
    $title = $form["title-".$ID];
    $tags = $form["tags-".$ID];
    $desc = $form["desc-".$ID];
    $MSJ ="";
    $code = 3;
    //Insertando product
    if (class_exists('Page')) {

       $PAGE = new Page();
       //$util = new Util();
       //$ID =  str_replace(CRYPT,"",$util->decrypt($EID)); 
       $RESP = $PAGE->page_update($ID,  $title, $tags, $desc);
       if($RESP){
        $MSJ = "Page Updated";
        $code = 1;
       }else{
        $MSJ = "Page not updated";
        $code = 2;
       } 
       
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $MSJ, "code" => $code);
    echo json_encode($res);

}



/**
 * Create FAQ
 * 
 */
function create_faq(){
    $UTIL = new Util();
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $question = $form["question"];
    $url = $form["url"];
    $answer = $form["answer"];
    $MSJ ="";
    $type = "warning";
    //Insert faq
    if (class_exists('Page')) {
       $PAGE = new Page();
       $FLAG = $PAGE->create_faq($question, $answer, $url);
       $MSJ =  ($FLAG) ? "FAQ created" : "FAQ not created";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($MSJ, $type), "code" => $code);
    echo json_encode($res);

}

/**
 * 
 * 
 */
function load_faqs(){
    $PAGE = new Page();
    $faqs = $PAGE->load_admin_faqs();
    $res = array("faqs" => $faqs);
    echo json_encode($res);
}


/**
 * Delete faq
 * 
 */
function delete_faq(){
    $UTIL = new Util();
    $PAGE = new Page();
    $FLAG = $PAGE->delete_faq($_POST['ID-FAQ']);
    $msj = ($FLAG) ? "FAQ deleted" : "FAQ not deleted";  
    $code = ($FLAG) ? 1 : 2 ;   
    $type = ($FLAG) ? "success" : "warning";   
    $res = array("msj" => $UTIL->web_alert($msj, $type), "code" => $code);
    echo json_encode($res);
}

       
