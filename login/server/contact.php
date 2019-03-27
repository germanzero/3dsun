<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'login_admin':
    login_admin();
    break;
            
}
 



function login_admin(){

    
    $USER = new User();
    $href = "";
    
    parse_str($_POST["formula"], $form);
    $mail = $form["email"];
    $pass = $form["password"];

    $RES = $USER->login_admin($mail,$pass);
    if($RES["code"]!=1){
        $msj = $RES["msj"];
        $code = "3";
        $type = "warning";

    }else{
        $type = "success";
        $msj = "Accediendo...";
        $code = "2";
        
        $href = SERVER_URL ."/admin/index.php";

    } 
    $UTIL = new Util();
    $msj = $UTIL->web_alert($msj,$type);

    $response = array("msj" => $msj, "code" => $code, "href"=>$href);
    echo json_encode($response);
}



