<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'update-social':
    update_social();
    break;

    case 'update-footer':
    update_footer();
    break;

    case 'update-mail':
    update_email_settings();
    break;

    case 'get-list':
    get_list();
    break;

    case 'upload-brochure':
    upload_brochure();
    break;

    
}

/**
 * 
 * 
 */
function update_social(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $fcb = $form["cc-facebook"];
    $ytb = $form["cc-youtube"];
    $twt = $form["cc-twitter"];
    $nst = $form["cc-instagram"];
    $MSJ ="";
    $code = 3;
    $UTIL = new Util();
    //Update
       $FLAG1 = $UTIL->setValConfig("FACEBOOK_URL",$fcb);
       $FLAG2 = $UTIL->setValConfig("YOUTUBE_URL",$ytb);
       $FLAG3 = $UTIL->setValConfig("TWITTER_URL",$twt);
       $FLAG4 = $UTIL->setValConfig("INSTAGRAM_URL",$nst);
      
       $FLAG = $FLAG1 && $FLAG2 && $FLAG3 && $FLAG4;
       $MSJ =  ($FLAG) ? "Social networks updated" : "Social networks not updated";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";

 
    $res = array("msj" => $UTIL->web_alert($MSJ, $type), "code" => $code);
    echo json_encode($res);

}


/**
 * 
 * 
 */
function update_footer(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $add = $form["v-address"];
    $phn = $form["v-phone"];
    $mail = $form["v-email"];
    
    $MSJ ="";
    $code = 3;
    $UTIL = new Util();
    //Update
       $FLAG1 = $UTIL->setValConfig("ADDRESS_FOOTER",$add);
       $FLAG2 = $UTIL->setValConfig("PHONE_FOOTER",$phn);
       $FLAG3 = $UTIL->setValConfig("EMAIL_FOOTER",$mail);
      
       $FLAG = $FLAG1 && $FLAG2 && $FLAG3;
       $MSJ =  ($FLAG) ? "Footer info updated" : "Footer info not updated";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";

 
    $res = array("msj" => $UTIL->web_alert($MSJ, $type), "code" => $code);
    echo json_encode($res);

}



/**
 * 
 * 
 */
function update_email_settings(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $sus = $form["cc-subscription"];
    $con = $form["cc-contact"];
    $bro = $form["cc-brochure"];
    
    $MSJ ="";
    $code = 3;
    $UTIL = new Util();
    //Update
       $FLAG1 = $UTIL->setValConfig("SUBSCRIPTION_MAIL",$sus);
       $FLAG2 = $UTIL->setValConfig("CONTACT_MAIL",$con);
       $FLAG3 = $UTIL->setValConfig("BROCHURE_MAIL",$bro);
      
       $FLAG = $FLAG1 && $FLAG2 && $FLAG3;
       $MSJ =  ($FLAG) ? "Mail settings updated" : "Mail settings not updated";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";

 
    $res = array("msj" => $UTIL->web_alert($MSJ, $type), "code" => $code);
    echo json_encode($res);

}



/**
 * 
 * 
 */
function get_list(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $MSJ ="";
    $code = 3;
    $UTIL = new Util();

    //Update
       $FLAG1 = $UTIL->export_mail_list_file();
       $FLAG = $FLAG1;
       $MSJ =  ($FLAG) ? "Mail list exported: <a href='".SERVER_URL."/files/mail_list.txt' target='_blank' download>List here</a>" : "Mail list not exported";
       $code = ($FLAG) ? 1 : 2;
       $type = ($FLAG) ? "success" : "warning";

 
    $res = array("msj" => $UTIL->web_alert($MSJ, $type), "code" => $code);
    echo json_encode($res);

}


/**
 * 
 */
function upload_brochure(){

    $UTIL = new Util();
    $PAGE = new Page();
    
    $FILE = (object)$_FILES["input-brochure"];
    $msj ="";
    $FILE_FOLDER = "/files/Home/";
    $PATH = SERVER_FOLDER . $FILE_FOLDER;
    $code = 2;
    if ( 0 < $FILE->error ) {
        $msj =  'Error: ' . $FILE->error . '<br>';
    }else {
        if (!file_exists($PATH)) { 
            mkdir($PATH);
        }
        $FILENAME = "SUN3D_brochure.".$UTIL->get_file_term_type($FILE->name);
        $PATH = $PATH . $FILENAME;

        if(move_uploaded_file($FILE->tmp_name, $PATH)){  
            $file_path = $FILE_FOLDER . $FILENAME;
                 $msj = "Brochure updated";
                 $code = 1;
                 $type = "success";

        }else{
            $msj = "Brochure not updated ";
            $type = "warning";
            
        }

    }
    
    $res = array("msj" => $UTIL->web_alert($msj, $type), "code" => $code);
    echo json_encode($res);
}
