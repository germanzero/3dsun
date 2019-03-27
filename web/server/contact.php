<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'contacto_web':
    contacto_web();
    break;
    case 'comment_web':
    comment_web();
    break;
        /*
    case 'registro_web':
    registro_web();
    break;*/

    case 'login_web':
    login_web();
    break;
    
    case 'suscribirse_web':
    suscribirse_web();
    break;

    case 'get-brochure':
    get_brochure();
    break;
    
            
}


function suscribirse_web(){
    
    $UTIL = new Util();
    $DBUTIL = new Dbutil();
    $SUBSCRIPCION_MAIL = $UTIL->getValConfig("SUBSCRIPTION_MAIL");
    $MONITORING_MAIL = $UTIL->getValConfig("MONITORING_MAIL");
    $FROM_MAIL = $UTIL->getValConfig("FROM_MAIL");
    $email = $_POST["email"];
    
        //sent to: 
        $to = $SUBSCRIPCION_MAIL;//Correos administrativos
        //$cc = ""; //Correos informativos
        $cco = $MONITORING_MAIL; //Correos monitoreo
        $from = $FROM_MAIL;
        $titulo = "Subscription request: ".$email;


        $html = new Panel("mail_suscripcion.html");
        $html->add("CORREO", $email);
        

        $envio = $UTIL->send_html_mail($to,$from,$titulo,$html->pagina(),$cc,$cco);

        if($envio['code']==1){
            //mail sent
            $code = 1;
            $msj = "Subscription request sent!";
            $type = "success";

             //Save to DB email
            $mysqli = $DBUTIL->connect();
            $email = $mysqli->real_escape_string($email);
            $DBUTIL->add_string($registro,"email ",trim($email));      
            $sql = $DBUTIL->query_insert($registro,"web_mail_list");
            try{
                $ID = $DBUTIL->db_insert($sql,$err);
            }catch(Exception $e){

            }


        }else{
            //Mail not sent
            $code = 2;
            $msj = "Could not subscribe, try again.";
            $type = "success";
        }

    
    $response = array("msj" => $UTIL->web_alert($msj,$type), "code"=>$code);
    echo json_encode($response);
}

function login_web(){
    
    $UTIL = new Util();
    $USER = new User();
    $mail = $_POST["ML"];
    $pass = $_POST["PS"];
    $RES = $USER->login_web($mail,$pass);
    if($RES["code"]!=1){
        $msj = $RES["msj"];
        $code = "3";
        $type = "warning";

    }else{
        $type = "success";
        $msj = "Login ok";
        $code = "2";
        
        $link_login = $USER->link_login();

    } 
    $msj = $UTIL->web_alert($msj,$type);

    $response = array("msj" => $msj, "code" => $code, "link_login"=>$link_login);
    echo json_encode($response);
}

function contacto_web(){
    
    $UTIL = new Util();
    $intereses = "";
    $CONTACT_MAIL = $UTIL->getValConfig("CONTACT_MAIL");
    $MONITORING_MAIL = $UTIL->getValConfig("MONITORING_MAIL");
    $FROM_MAIL = $UTIL->getValConfig("FROM_MAIL");

    parse_str($_POST["formula"], $form);
    $name = $form["con-name"];
    $phone = $form["con-phone"];
    $direction = $form["con-direction"];
    $email = $form["con-email"];
    $coments = $form["con-msg"];

        //Envío correo a: 
        $para = $CONTACT_MAIL;//Correos administrativos
        //$cc = ""; //Correos informativos
        $cco = $MONITORING_MAIL; //Correos monitoreo
        $de = $FROM_MAIL;
        $titulo = "Contact request: ".$name;


        $html = new Panel("mail_contacto.html");
        $html->add("NOMBRE", $name );
        $html->add("CORREO", $email);
        $html->add("TELEFONOS", $phone );
        $html->add("DIRECCION", $direction);
        $html->add("COMENTARIOS", $coments);
        

        $envio = $UTIL->send_html_mail($para,$de,$titulo,$html->pagina(),$cc,$cco);

        if($envio['code']==1){
            //Se envió el mail
            $code = 1;
            $msj = "Contact request sent!";
            $type = "success";


        }else{
            //No se envió el mail
            $code = 2;
            $msj = "Could not send the message, try again. ";
            $type = "success";
        }

    
    $response = array("msj" => $UTIL->web_alert($msj,$type), "code"=>$code);
    echo json_encode($response);
}

function comment_web(){
    $BLOG = new Blog();
    $UTIL = new Util();
    $intereses = "";
    $CONTACT_MAIL = $UTIL->getValConfig("CONTACT_MAIL");
    $MONITORING_MAIL = $UTIL->getValConfig("MONITORING_MAIL");
    $FROM_MAIL = $UTIL->getValConfig("FROM_MAIL");

    parse_str($_POST["formula"], $form);
    $name = $form["Name"];
    $email = $form["Email"];
    $coments = $form["Message"];

    //Insert comment
    $ID = $UTIL->EID_ID($_POST["EID"]);
    $BLOG->create_comment($ID, $name,$email,$coments);

        //Envío correo a: 
        $para = $CONTACT_MAIL;//Correos administrativos
        //$cc = ""; //Correos informativos
        $cco = $MONITORING_MAIL; //Correos monitoreo
        $de = $FROM_MAIL;
        $titulo = "Comment: ".$name;
        $url = SERVER_URL."/web/news_detail.php?EID=".$_POST["EID"];

        $html = new Panel("mail_comment.html");
        $html->add("NOMBRE", $name );
        $html->add("CORREO", $email);
        $html->add("COMENTARIOS", $coments);
        $html->add("URL", $url);
        

        $envio = $UTIL->send_html_mail($para,$de,$titulo,$html->pagina(),$cc,$cco);

        if($envio['code']==1){
            //Se envió el mail
            $code = 1;
            $msj = "Comment sent!";
            $type = "success";
            
            
        }else{
            //No se envió el mail
            $code = 2;
            $msj = "Could not send the message, try again. ";
            $type = "success";
        }
       
        $list = $BLOG->comment_post_list($ID);
    
    $response = array("msj" => $UTIL->web_alert($msj,$type), "code"=>$code, "list"=>$list);
    echo json_encode($response);
}

function registro_web(){
    $UTIL = new Util();
    parse_str($_POST["formula"], $form);
    //var_dump(get_declared_classes());
    $name = $form["name_reg"];
    $lastname = $form["lastname_reg"];
    $email = $form["email_reg"];
    $pass = $form["pass_reg"];
    $tel = $form["fijo_reg"];
    $cel = $form["movil_reg"];
    $ID  = 0;
    //Insertando product
    if (class_exists('User')) {
       $USER = new User();
       $RES = $USER->user_create($name, $lastname, $email, $pass, "2", $tel, $cel);
       
    //   $code = "2";
    //   $msj = "Usuario registrado, realice Login para solicitar cotizaciones de productos";
    //   $type = "success";
    //
    //   if(substr_count ($RESP , "ERROR" )>0){
    //        $msj = "Ocurrió un problema. Espere e intente de nuevo ";
    //        $code = "3";
    //        $type = "warning";
    //
    //    } 

        if($RES["code"]!=1){
            $msj = $RES["msj"];
            $code = "3";
            $type = "warning";
    
        }else{
            $type = "success";
            $msj = "Usuario registrado, realice Login para continuar";
            $code = "2";

            $CORREO_REGISTRO = $UTIL->getValConfig("CORREO_REGISTRO");
            $CORREO_MONITOREO = $UTIL->getValConfig("CORREO_MONITOREO");
            $FROM_MAIL = $UTIL->getValConfig("FROM_MAIL");

             //Envío correo a: 
                $para = $CORREO_REGISTRO;//Correos administrativos
                //$cc = ""; //Correos informativos
                $cco = $CORREO_MONITOREO; //Correos monitoreo
                $de = $FROM_MAIL;
                $titulo = "Solicitud de contacto: ".$name;
                
                $html = new Panel("mail_registro.html");
                $html->add("NOMBRE", $name." ".$lastname );
                $html->add("CORREO", $email);
                $html->add("TELEFONOS", $tel );
                $html->add("TELEFONOS2", $cel );
                

                $envio = $UTIL->send_html_mail($para,$de,$titulo,$html->pagina(),$cc,$cco);

    
        } 

      
    }else{
        $msj = "Clase no existe";
        $code = "4";
        $type = "warning";
    }
    
    $res = array("id"=>$ID,"code"=>$code, "msj"=>$UTIL->web_alert($msj, $type));
    echo json_encode($res);

}

function get_brochure(){
    $UTIL = new Util();
    $DBUTIL = new Dbutil();
    $email = $_POST["email"];

    $BROCHURE_MAIL = $UTIL->getValConfig("BROCHURE_MAIL");
    $MONITORING_MAIL = $UTIL->getValConfig("MONITORING_MAIL");
    $FROM_MAIL = $UTIL->getValConfig("FROM_MAIL");

     //Envío correo a: 
     $for = $email;//Correos administrativos
     $cc = $BROCHURE_MAIL; //Correos informativos
     $cco = $MONITORING_MAIL; //Correos monitoreo
     $from = $FROM_MAIL;
     $titulo = "Brochure request: ".$email;
     
     $html = new Panel("brochure_request.html");
     
     $envio = $UTIL->send_html_mail($for,$from,$titulo,$html->pagina(),$cc,$cco);

     
     //Save to DB email
     $mysqli = $DBUTIL->connect();
     $email = $mysqli->real_escape_string($email);
     $DBUTIL->add_string($registro,"email ",trim($email));      
     $sql = $DBUTIL->query_insert($registro,"web_mail_list");
     try{
        $ID = $DBUTIL->db_insert($sql,$err);
     }catch(Exception $e){

     }


    $res = array("email"=>$email ." - ". $envio['msj'] . " - " . $envio['code'] );
    echo json_encode($res);
}


