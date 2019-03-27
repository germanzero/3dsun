<?php

/**
 * Description of Util
 *
 */
include_once( "../include/Template.inc" );
include_once( "../include/Panel.inc" );
include_once( "../classes/Dbutil.php" );

/*
include_once( "../classes/User.php" );*/
class Util {

    private $cnx = null;

    function __construct() {
        $this->cnx = new Dbutil();
    }

    /**
     * Retrieves the key from DB
     */
    function getValConfig($key){
        $sql = "SELECT *  FROM config c WHERE c.name = '".$key."' ";
        $datos = $this->cnx->query($sql,1);
        return $datos[0]['value'];
    }

    /**
     * Set value for the key
     */
    function setValConfig($key,$val){
        $sql = "UPDATE config SET value = '$val' WHERE name = '".$key."' ";
        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR:".$err;
        else return true;
    }

    function parse_email_footer($EMAIL_FOOTER){
        $ARRAY_EMAIL = explode(";",$EMAIL_FOOTER);
        $EMAIL_FOOTER = '';
        foreach ($ARRAY_EMAIL as $EMAIL) {
            $EMAIL_FOOTER.= '<a href="mailto:'.$EMAIL.'" class="footer-text d-block"
            >'.$EMAIL.'</a>';
        }
        return $EMAIL_FOOTER;
    }

    /**
     *
     */
    function load_fingers($nets = ["'ADDRESS_FOOTER'","'PHONE_FOOTER'","'EMAIL_FOOTER'","'FACEBOOK_URL'","'TWITTER_URL'","'INSTAGRAM_URL'","'YOUTUBE_URL'"]){
        $sql = "SELECT *  FROM config c WHERE c.name in (".implode(",",$nets).") ";
        $datos = $this->cnx->query($sql,1);

        foreach ($datos as $net) {
            $_SESSION[$net['name']] = $net['value'];
        }

        $_SESSION['EMAIL_FOOTER'] = $this->parse_email_footer($_SESSION['EMAIL_FOOTER']);

        return true;
    }




    /*
     *
     * Funcion para encryptar una cadena dada una llave
     *
     */

    public function encrypt($string, $key = "") {
        $result = '';
        if($key == "") $key = $this->getValConfig('UTIL_ENC_KEY');
        if($key != ""){
            for ($i = 0; $i < strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                $char = chr(ord($char) + ord($keychar));
                $result.= $char;
            }
            return base64_encode($result);
        }else return "Problema con el key";
    }


    /*
     *
     * Funcion para desencryptar una cadena dada una llave
     *
     */
    public function decrypt($string, $key = "") {
        $result = '';
        if($key == "") $key = $this->getValConfig('UTIL_ENC_KEY');
        if($key != ""){
            $string = base64_decode($string);
            for ($i = 0; $i < strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                $char = chr(ord($char) - ord($keychar));
                $result.= $char;
            }
            return $result;
        }else return "Problema con el key";
    }

    /*
     * Obtener un timestamp
     */
    public function marca_tiempo_str() { // funcion que devuelve un string con el timestamp
        $fecha = date_create('now');
        return date_format($fecha, 'U');
    }

    /*
     * Funcion para vaciar el contenido de un directorio sin borrar el mismo
     */
    public function vaciar_carpeta($dir) { //
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if (is_file($dir . $file)) {
                unlink($dir . $file);
            }
        }
        return TRUE;
    }


    /**
     * Funcion para cambiar acentos por codigos html
     */
    public function change_special_chars($cadena) {
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","\\\"","\""),
                                        array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","\"","&quot;"), $cadena);
    }

    /**
     * Funcion para saber si es el servidor de produccion
     */
    public function is_produccion() {

        $pos = strpos(SERVER_URL, "http://www..com");
        if ($pos !== false) {
                return true;
        } else {
                return false;
        }

    }

    public function get_file_type_id($strfile){
        //1 doc, 2 txt, 3 png, 4 pdf, 5 jpg
        $arr = explode(".", $strfile);
        $tipo = array_pop($arr);
        switch ($tipo) {

            case 'doc':
            return 1;
            break;

            case 'DOC':
            return 1;
            break;

            case 'txt':
            return 2;
            break;

            case 'TXT':
            return 2;
            break;

            case 'png':
            return 3;
            break;

            case 'PNG':
            return 3;
            break;

            case 'pdf':
            return 4;
            break;

            case 'PDF':
            return 4;
            break;

            case 'jpg':
            return 5;
            break;

            case 'JPG':
            return 5;
            break;

            default:
            return null;
        }

    }

    /*
     * Get file term
     */
    public function get_file_term_type($strfile){
        //1 doc, 2 txt, 3 png, 4 pdf, 5 jpg
        $arr = explode(".", $strfile);
        $tipo = array_pop($arr);
        return $tipo;

    }

    /*
     * remove_spaces
     */
    public function remove_spaces($string, $type){
        //1: spaces, 2: all whitespaces

        if($type==1)$string = str_replace(' ', '', $string);
        elseif($type==2)$string = preg_replace('/\s+/', '', $string);
        else $string = str_replace(' ', '', $string);

        return $string;
    }

    public function guidv4($data){
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function get_unique($data){
        return guidv4(openssl_random_pseudo_bytes($data));
    }

    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)].rand(9);
        }

        return $key;
    }

    /**
     * type: primary, secondary, success, danger, warning, info, light, dark

    */
    public function web_alert($msj, $type){
        return '<div class="alert alert-'.$type.' alert-dismissible fade show w-100 m-0" role="alert">
                    '.$msj.'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    }

     /**
     * from ID to EID
     * EID: encrypted ID
     */
    public function ID_EID($id){
        return $this->encrypt($id.CRYPT);
     }
     /**
      * from EID to ID
      * EID: encrypted ID
      */
     public function EID_ID($EID){
         return str_replace(CRYPT,"",$this->decrypt($EID));
      }

      public function get_top($lang="es",$banner="",$alt_text=""){

        if($banner=="video"){
            $ban = new Panel("banner_video.html");
            $ban->add("SERVER_URL",SERVER_URL);
            $ban->add("HOME_VIDEO",SERVER_URL . $this->getValConfig("HOME_VIDEO"));
            $ban->add("HOME_VIDEO_LOW",SERVER_URL . $this->getValConfig("HOME_VIDEO_LOW"));
            $banner = $ban->pagina();
        }else if($banner!=""){
            $ban = new Panel("banner_static.html");
            $ban->add("SERVER_URL",SERVER_URL);
            $ban->add("SRC_URL",$banner);
            $ban->add("ALT_TEXT",$alt_text);
            $banner = $ban->pagina();
        }

        $top = new Panel("web_top.html");
        $top->add("SERVER_URL",SERVER_URL);

        $top->add("HOME","HOME");
        $top->add("ABOUT","ABOUT US");
        $top->add("PRODUCTS","PRODUCTS");
        $top->add("OPPORT","OPPORTUNITIES");
        $top->add("BLOG","NEWS");
        $top->add("CONTACT","CONTACT US");
        $top->add("BANNER",$banner);

        return $top->pagina();

      }

      public function get_footer($lang="es"){
        /*if(session_status() == PHP_SESSION_NONE OR $_SESSION['ADDRESS_FOOTER']=='') $this->load_fingers();
        else echo "SST->" . session_status();
*/
        $this->load_fingers();
        $foot = new Panel("web_footer.html");
        $foot->add("SERVER_URL",SERVER_URL);
        $foot->add("FACEBOOK_URL",$_SESSION['FACEBOOK_URL']);
        $foot->add("TWITTER_URL",$_SESSION['TWITTER_URL']);
        $foot->add("INSTAGRAM_URL",$_SESSION['INSTAGRAM_URL']);
        $foot->add("YOUTUBE_URL",$_SESSION['YOUTUBE_URL']);
        $foot->add("ADDRESS_FOOTER",$_SESSION['ADDRESS_FOOTER']);
        $foot->add("PHONE_FOOTER",$_SESSION['PHONE_FOOTER']);
        $foot->add("EMAIL_FOOTER",$_SESSION['EMAIL_FOOTER']);

        return $foot->pagina();
      }


    /**
     *
     */
    public function get_sliders(){
        $mysqli = $this->cnx->connect();
        mysqli_set_charset($mysqli, "utf8");
        $sql = "SELECT * FROM slider ";
        $datos = $this->cnx->db_select_array($sql);
        return $datos;
    }

    /**
     *
     */
    public function get_sliders_web(){
        $html = "";
        $sliders = $this->get_sliders();
        foreach ($sliders as $slider) {
            $p = new Panel("web_slider_item.html");
            $p->add("RUTA",SERVER_URL . $slider['ruta']);
            //$p->add("MENSAJE", $this->util->change_special_chars($slider['mensaje']));
            $p->add("MENSAJE", $slider['mensaje']);
            $html.= $p->pagina();
        }

        return $html;
    }

    public function send_html_mail($para, $from, $titulo, $mensaje,$cc ="", $cco = ""){

        $cabeceras = "From: " . strip_tags($from) . "\r\n";
        $cabeceras .= "Reply-To: ". strip_tags($from) . "\r\n";
        $cabeceras .= "Cc: $cc\r\n";
        $cabeceras .= "Bcc: $cco\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";

        $success =  mail($para, $titulo, $mensaje, $cabeceras);
        if (!$success) {
            $msj = " ERROR: ".error_get_last()['message'];
            $code = 2;
        }else{
            $code = 1;
            $msj = "";
        }

        $response = array("msj" => $msj, "code" => $code);
        return  $response;


    }



    public function set_lang(){
        if(isset($_GET['lang'])){
            $lang = $_GET['lang'];
            if($lang != "en" and $lang != "es") {
                $lang = DEFAULT_LANG;
            }
        }else{
            if(isset($_SESSION['lang'])){
                $lang = $_SESSION['lang'];
            }else $lang = DEFAULT_LANG;
        }

        $_SESSION['lang'] = $lang;

        return $lang;
    }


    public function load_inputfile($id, $text = "Click here to select image", $class = "fileContainer btn btn-dark"){
        $p = new Panel("image_input.html");
        $p->add("ID", $id);
        $p->add("TEXT", $text);
        $p->add("CLASS", $class);
        $p->add("SERVER_URL",SERVER_URL);
        return $p->pagina();
    }


    function load_input_image($id, $post_val, $alt_text =""){

        $p = new Panel("image_input_panel.html");

        if($post_val==null OR $post_val==""){
            $hdr_img = "https://via.placeholder.com/250x90.png?text=Upload+image";
            $hdr_class = "d-none";
        }else{
            $hdr_img = SERVER_URL . $post_val;
            $hdr_class = "d-none";
        }
        $p->add("IMG_INPUT",$this->load_inputfile($id, "Select Image", "fileContainer btn btn-dark", $hdr_class));
        $p->add("ID-INPUT",$id);


        $pim = new Panel("admin_actual_img.html");
        $pim->add("MEDIA_URL","");
        $pim->add("MEDIA_ID","");
        $pim->add("MEDIA_SRC",$hdr_img);
        $pim->add("MEDIA_ALT",$alt_text);
        $pim->add("MEDIA_DESC","");
        $pim->add("MEDIA_VER",$hdr_img);

        $hdr_img = $pim->pagina();

        $p->add("IMG-ACTUAL",$hdr_img);
        return $p->pagina();;

    }


    function load_page_seo($name,$panel){

        $PAGE = new Page();
        $pg = $PAGE->get_page_where("name = '".$name."' limit 1");
        $panel->add("PAGE-TITLE",$pg[0]['page_title']);
        $panel->add("DESCRIPTION",$pg[0]['page_desc']);
        $panel->add("KEYWORDS",$pg[0]['meta_tags']);
        return $panel;

    }


    /**
     * Delete file
     */
    public function delete_file($file){

        $filename = SERVER_FOLDER . $file;
        if(!is_dir ($filename)){
            if(unlink($filename)) return TRUE;
            else return FALSE;
        }else{
            return FALSE;
        }

    }

    /*
    * delete_folder('/path/for/the/directory/');
    * php delete function that deals with directories recursively
    */
    public function delete_folder($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

            foreach( $files as $file ){
                delete_files( $file );
            }

            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }


    /**
     *
     *
     */
    public function eol($var){ return str_replace("\r\n",'<br>',$var); }


/***
 *
 */
   public function export_mail_list_file(){
        try{
 /*
            //$baseurl = $baseurl.'/%/%/%.%';
            $myfile = fopen(SERVER_FOLDER . "/files/mail_list.txt", "w") or die("Unable to open file!");

            // Query the data and assign the variable before writing
            $DBUTIL = new Dbutil();
            $results = $DBUTIL->db_select_array("SELECT * FROM `web_mail_list` a ORDER BY a.email ASC ");

            // json_encode() to turn the retrieved array into a JSON string for writing to text file
            $output = json_encode($results);



            // write the file
            fwrite($myfile, $output);
            fclose($myfile);
*/

        // Query the data and assign the variable before writing
        $DBUTIL = new Dbutil();
        $results = $DBUTIL->db_select_array("SELECT email FROM `web_mail_list` a ORDER BY a.email ASC ");
        //var_dump($results);
        $file = SERVER_FOLDER . "/files/mail_list.txt";
        unlink($file);
        $jump = "\r\n";
        $h = fopen($file, "w+");
        foreach ($results as $value) {
            fputcsv($h, $value);
            fwrite($h, $jump);
        }
        fclose($h);

            // return the result (array) for further use in application
            return true;
        }catch(Exception $e){
            return false;
        }


    }

}
