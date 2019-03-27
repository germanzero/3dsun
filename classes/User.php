<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../classes/Dbutil.php" );
class User{

    private $cnx = null;
    
    function __construct() {
        $this->cnx = new Dbutil();
    }
    
    function is_logged_in($type = "client"){
 
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0){
            if($type == "admin"){
                if($_SESSION['role_id']==1){
                    return true;
                }else return false;
            }else return true;
            
        }else{
            return false;
        }

    }

    public function get_user($user_id){
        $mysqli = $this->cnx->connect();
        mysqli_set_charset($mysqli, "utf8");
        $sql = "SELECT * FROM user WHERE id = $user_id ";
        $datos = $this->cnx->db_select_array($sql);
        return $datos;
    }


    public function get_users($where = ""){
        $mysqli = $this->cnx->connect();
        mysqli_set_charset($mysqli, "utf8");
        $sql = "SELECT * FROM user $where";
        $datos = $this->cnx->query($sql,1);
        return $datos;
    }


    function user_create($name, $lastname, $email, $pass, $role="1", $tel="", $cel=""){
                
        //Compruebo que existe el correo
        $sql1 = "SELECT u.email FROM user u  WHERE  email = '$email' ";
        $datos1 = $this->cnx->db_select_array($sql1);
        if(sizeof($datos1)>0){
            $msj = "La dirección de correo suministrado ya se encuentra registrada. Intente logearse o registrarse con una nueva dirección de correo.";
            $code = 2;
        
        }else{

            $mysqli = $this->cnx->connect();
                $name = $mysqli->real_escape_string($name);      
                $lastname = $mysqli->real_escape_string($lastname);      
                $email = $mysqli->real_escape_string($email);      
                $pass = md5($mysqli->real_escape_string($pass));      
                
                //$role = $mysqli->real_escape_string($role);      
                $tel = $mysqli->real_escape_string($tel);      
                $cel = $mysqli->real_escape_string($cel);      
                $spec = $mysqli->real_escape_string($spec);      
                mysqli_set_charset($mysqli, "utf8");
                
                //$sql = "INSERT INTO user(name, lastname, email, pass, tel, cel) VALUES ('$name', '$lastname', '$email', '$pass', $role, '$tel', '$cel') ";
                if($name != "") $this->cnx->add_string($registro,"name",trim($name));
                if($lastname != "") $this->cnx->add_string($registro,"lastname",trim($lastname));
                if($email != "") $this->cnx->add_string($registro,"email",trim($email));
                if($pass != "") $this->cnx->add_string($registro,"pass",trim($pass));
                if($tel != "") $this->cnx->add_string($registro,"tel",trim($tel));
                if($cel != "") $this->cnx->add_string($registro,"cel",trim($cel));
                $this->cnx->add_numeric($registro,"role_id",trim($role));
                $sql = $this->cnx->query_insert($registro,"user");
                $ID = $this->cnx->db_insert($sql,$err);
                if($err){
                    $msj = "ERROR: ocurrió un problema técnico durante el registro, agradecemos intente de nuevo.".$err;
                    $code = 2;
                    //return $sql." ERROR ".$err;
                }else{
                    $msj = $ID;
                    $code = 1;
                    //return $ID;
                } 


        }

        $response = array("msj" => $msj, "code" => $code);
        return $response;
        
    }

    function isInteger($input){
        return(ctype_digit(strval($input)));
    }

    function login_web($mail, $pass){
        $mysqli = $this->cnx->connect();
        $mail = $mysqli->real_escape_string($mail);  
        $pass = $mysqli->real_escape_string($pass);  
        mysqli_set_charset($mysqli, "utf8");
        
        //Compruebo que existe el correo
        $sql1 = "SELECT u.email FROM user u  WHERE  email = '$mail' ";
        $datos1 = $this->cnx->db_select_array($sql1);
        if(!sizeof($datos1)>0){
            $msj = "No es posible logearse, su correo no esta registrado.";
            $code = 2;
        
        }else{

            $sql = "SELECT u.id as UID, r.id as RID, u.name as UNAME, r.name as RNAME, u.*, r.* FROM user u, role r  WHERE  r.id = u.role_id AND email = '$mail' AND pass = md5('$pass') ";
            //$datos = $this->cnx->query($sql);
            $datos = $this->cnx->db_select_array($sql);
            if(!sizeof($datos)>0){
                $msj = "Su combinación clave-correo no coincide, verifique.";
                $code = 3;
            }else{
                $ID = $datos[0]['UID'];

                if($this->isInteger($ID) and ($ID>0)) {
                    $_SESSION['user_id'] = $datos[0]['UID'];
                    $_SESSION['user_name'] = $datos[0]['UNAME']." ". $datos[0]['lastname'];
                    $_SESSION['role_id'] = $datos[0]['RID'];
                    $_SESSION['role_name'] = $datos[0]['RNAME'];

                    $_SESSION['user_email'] = $datos[0]['email'];


                    //return $datos[0];
                    $msj = "Login Correcto";
                    $code = 1;
                }else {
                    //return " ERROR ".$sql;
                    $msj = " ERROR ";
                    $code = 4;
                }

            }

        }
        //1
        $response = array("msj" => $msj, "code" => $code);
        return $response;
    }

    //{CLS_LNK} {DATA_TARGET} {SRC_ICON} {CLS_SPAN} {TXT_NAME}
    function link_login($lang){

        $TRAD = new Traductor();
        $keys = array("W-SIGN","W-SALIR");
        $trads = $TRAD->get_page_translations($keys, $lang);

        //Defaults
        $CLS_LNK = "";
        $DATA_TARGET = "#box-modal-login";
        $SRC_ICON = SERVER_URL."/images/top/sin_login.png";
        $CLS_SPAN = "";
        $TXT_NAME = "Login";
        $DATA_TOGGLE = "modal";
        $isIn = '99';
        $LINKS = '';

        //Logeado
        if($this->isInteger($_SESSION['user_id']) AND $_SESSION['user_id']!="0"){
            $CLS_LNK = "login-link-post";
            $DATA_TARGET = "";
            $SRC_ICON = SERVER_URL."/images/top/con_login.png";
            $CLS_SPAN = "txt-name-login-post";
            $TXT_SALUDO = "Hola!";
            $TXT_NAME = "<br>".$_SESSION['user_name'];
            $isIn = '1';
            $DATA_TOGGLE = "dropdown";
            $LNK_SALIR = SERVER_URL. "/web/logout.php";
            $LINKS.= '';

            $cot = new Panel("dd_link_top.html");
            $cot->add("HREF", $LNK_SALIR);
            $cot->add("TXT", $trads['W-SALIR']);
            $LINKS.= $cot->pagina();
            $LINKS.= '';
            
        }else{
        
            $LNK_REG = SERVER_URL. "/web/registro.php";
            $cot = new Panel("dd_link_top.html");
            $cot->add("HREF", $LNK_REG);
            $cot->add("TXT", $trads['W-SIGN']);
            $LINKS.= $cot->pagina();

        }

            $top = new Panel("web_login_link.html");
            $top->add("CLS_LNK", $CLS_LNK);
            $top->add("DATA_TARGET",$DATA_TARGET);
            $top->add("DATA_TOGGLE",$DATA_TOGGLE);
            $top->add("LINKS",$LINKS);
            $top->add("SRC_ICON",$SRC_ICON);
            $top->add("CLS_SPAN",$CLS_SPAN);
            $top->add("TXT_SALUDO",$TXT_SALUDO);
            $top->add("TXT_NAME",$TXT_NAME);
            $top->add("LGD_HD",$isIn);
            return $top->pagina();

    }


    function login_admin($mail, $pass){
        $mysqli = $this->cnx->connect();
        $mail = $mysqli->real_escape_string($mail);  
        $pass = $mysqli->real_escape_string($pass);  
        mysqli_set_charset($mysqli, "utf8");
        
        $href = "";
        //Compruebo que existe el correo
        $sql1 = "SELECT u.email FROM user u  WHERE  email = '$mail' ";
        $datos1 = $this->cnx->db_select_array($sql1);
        if(!sizeof($datos1)>0){
            $msj = "No es posible logearse, su correo no esta registrado.";
            $code = 2;
        
        }else{

            $sql = "SELECT u.id as UID, r.id as RID, u.name as UNAME, r.name as RNAME, u.*, r.* FROM user u, role r  WHERE  r.id = u.role_id AND email = '$mail' AND pass = md5('$pass') AND role_id = 1 ";
            //$datos = $this->cnx->query($sql);
            $datos = $this->cnx->db_select_array($sql);
            if(!sizeof($datos)>0){
                $msj = "Su combinación clave-correo no coincide, o usted no tiene accesos administrativos, verifique.";
                $code = 3;
            }else{
                $ID = $datos[0]['UID'];

                if($this->isInteger($ID) && ($ID>0)) {
                    session_start();
                    $_SESSION['user_id'] = $datos[0]['UID'];
                    $_SESSION['user_name'] = $datos[0]['UNAME']." ". $datos[0]['lastname'];
                    $_SESSION['role_id'] = $datos[0]['RID'];
                    $_SESSION['role_name'] = $datos[0]['RNAME'];
                    //return $datos[0];
                    //var_dump($datos[0]);
                    $msj = "Login Correcto";
                    $code = 1;
                   
                }else {
                    //return " ERROR ".$sql;
                    $msj = " ERROR ";
                    $code = 4;
                }

            }

        }
        //1
        $response = array("msj" => $msj, "code" => $code, "datos" => $datos[0], "session" => $_SESSION);
        return $response;
    }


    public function content_user_list($tipo = ""){
        
        $where ="WHERE active = 1";
        $titulo = "Usuarios";
        if($tipo != ""){
            $where.=" AND role_id = $tipo";
            if($tipo === "1"){
                $titulo = "Administradores";
            }elseif($tipo === "2"){
                $titulo = "Usuarios registrados en la web";
            }
        }

        $users = $this->get_users($where);
        $rows ="";
        foreach ($users as $user) {
           
            $rows.='<tr><td>'.$user['id'].'</td>
                        <td>'.$user['name'].'</td>
                        <td><a href="mailto:'.$user['email'].'" class="btn" target="_blank">'.$user['email'].'</a></td>
                        <td>'.$user['tel'].'</td>
                        <td>&nbsp;</td></tr>';

        }

        $p = new Panel("admin_user_ls.html");
        $p->add("SERVER_URL",SERVER_URL);
        $p->add("TITLE",$titulo);
        $p->add("ROWS",$rows);
	    return $p->pagina();

    }




    function load_user_details($p){
        
        $p->add("UNAME",$_SESSION['user_name']);
		$p->add("ROLE",$_SESSION['role_name']);
		$p->add("UMAIL",$_SESSION['user_email']);
		$p->add("DESC","");

        return $p;

    }


}