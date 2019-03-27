<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Printer {

    private $cnx = null;
    private $util = null;
    public $th_w = "70";
    public $th_h = "50";
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }


    /**
     * 
     */
    public function get_th($img){
        if (is_file($img)) {
            $res = pathinfo($img);
            return $res['filename'] .'_'. $this->th_w . 'x' . $this->th_h . '.' . $res['extension'];
        }    
     
    }

    /**
     *     get_printer
     * 
    */
    public function get_printer($id){

        $sql = "SELECT * FROM printer WHERE id = $id ";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos[0];
    }

  /**
     *     get_printers
     * 
    */
    public function get_printers($where = ""){

        $sql = "SELECT * FROM printer $where ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos;
    }

    /**
     *     
     * update_printer
    */
    public function update_printer($id , $name ="", $status ="", $thick ="", $width ="", $resolution ="", $dimension ="", 
    $weight ="", $message ="", $description_short ="", $description ="", $youtube ="", $banner ="", $alt_text_banner = "")
    {

        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name);      
        $status = $mysqli->real_escape_string($status);      
        $thick = $mysqli->real_escape_string($thick);      
        $width = $mysqli->real_escape_string($width);      
        $resolution = $mysqli->real_escape_string($resolution);      
        $dimension = $mysqli->real_escape_string($dimension);      
        $weight = $mysqli->real_escape_string($weight);      
        $message = $mysqli->real_escape_string($message);      
        $description_short = $mysqli->real_escape_string($description_short);      
        //$description = $mysqli->real_escape_string($description);      
        $youtube = $mysqli->real_escape_string($youtube);      
        $banner = $mysqli->real_escape_string($banner);      
        $alt_text_banner = $mysqli->real_escape_string($alt_text_banner);      
        
        mysqli_set_charset($mysqli, "utf8");

        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($status != "")$this->cnx->add_string($registro,"status",trim($status));
        if($thick != "")$this->cnx->add_string($registro,"material_thickness",trim($thick));
        if($width != "")$this->cnx->add_string($registro,"printing_width",trim($width));
        if($resolution != "")$this->cnx->add_string($registro,"resolution",trim($resolution));
        if($dimension != "")$this->cnx->add_string($registro,"dimensions",trim($dimension));
        if($weight != "")$this->cnx->add_string($registro,"weight",trim($weight));
        if($message != "")$this->cnx->add_string($registro,"msj_mid_page",trim($message));
        if($description_short != "")$this->cnx->add_string($registro,"description_short",trim($description_short));
        if($description != "")$this->cnx->add_string($registro,"description",trim($description));
        if($youtube != "")$this->cnx->add_string($registro,"youtube_vid_tag",trim($youtube));
        if($banner != "")$this->cnx->add_string($registro,"banner",trim($banner));
        if($alt_text_banner != "")$this->cnx->add_string($registro,"alt_text_banner",trim($alt_text_banner));
        
        $sql = $this->cnx->query_update($registro,"printer","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return true;
    }
  
    /**
     *     
     * update_printer
    */
    public function create_printer($name ="", $status ="", $thick ="", $width ="", $resolution ="", $dimension ="", 
    $weight ="", $message ="", $description_short ="", $description ="", $youtube ="", $banner ="", $alt_text_banner = "")
    {

        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name);      
        $status = $mysqli->real_escape_string($status);      
        $thick = $mysqli->real_escape_string($thick);      
        $width = $mysqli->real_escape_string($width);      
        $resolution = $mysqli->real_escape_string($resolution);      
        $dimension = $mysqli->real_escape_string($dimension);      
        $weight = $mysqli->real_escape_string($weight);      
        $message = $mysqli->real_escape_string($message);      
        $description_short = $mysqli->real_escape_string($description_short);      
        $description = $mysqli->real_escape_string($description);      
        $youtube = $mysqli->real_escape_string($youtube);      
        $banner = $mysqli->real_escape_string($banner);      
        $alt_text_banner = $mysqli->real_escape_string($alt_text_banner);      
        
        mysqli_set_charset($mysqli, "utf8");

        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($status != "")$this->cnx->add_string($registro,"status",trim($status));
        if($thick != "")$this->cnx->add_string($registro,"material_thickness",trim($thick));
        if($width != "")$this->cnx->add_string($registro,"printing_width",trim($width));
        if($resolution != "")$this->cnx->add_string($registro,"resolution",trim($resolution));
        if($dimension != "")$this->cnx->add_string($registro,"dimensions",trim($dimension));
        if($weight != "")$this->cnx->add_string($registro,"weight",trim($weight));
        if($message != "")$this->cnx->add_string($registro,"msj_mid_page",trim($message));
        if($description_short != "")$this->cnx->add_string($registro,"description_short",trim($description_short));
        if($description != "")$this->cnx->add_string($registro,"description",trim($description));
        if($youtube != "")$this->cnx->add_string($registro,"youtube_vid_tag",trim($youtube));
        if($banner != "")$this->cnx->add_string($registro,"banner",trim($banner));
        if($alt_text_banner != "")$this->cnx->add_string($registro,"alt_text_banner",trim($alt_text_banner));
        
        $sql = $this->cnx->query_insert($registro,"printer");

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return $ID;
    }
  


    /*
     * 
     * Delete Printer
     */
    public function delete_printer($ID){

        $SLIDER_FOLDER = SERVER_FOLDER . "/files/products/printers/$ID/slider/";
        $PRINTER_FOLDER = SERVER_FOLDER . "/files/products/printers/$ID/";
        try{
            //Delete folder
            $this->util->delete_folder($SLIDER_FOLDER);
            $this->util->delete_folder($PRINTER_FOLDER);
            //Delete records
            $sql = "DELETE FROM picture WHERE printer_id = $ID ";
            $result = $this->cnx->query($sql);
            
            $sql2 = "DELETE FROM printer WHERE id = $ID ";
            $result2 = $this->cnx->query($sql2);

            return TRUE;
            
        }catch(Exception $e){
            return FALSE;
        }

    }


    /*
     * 
     */
    public function sub_menu_links($template = "sub_menu_link_item.html"){
        
        $printers = $this->get_printers("WHERE status = 'enabled'");
        $rows ="";
        foreach ($printers as $printer) {
            $pi = new Panel($template);
            $pi->add("ITEM_URL",SERVER_URL."/web/printer.php?EID=".urlencode($this->util->ID_EID($printer['id'])));
            $pi->add("ITEM_TITLE",$printer['name']);
            //$pi->add("ITEM_IMG", SERVER_URL . '/' .$this->get_th(SERVER_FOLDER . $printer['banner']));
            $imgs = $this->get_pictures($printer['id'], " and type = 'THUMB' ");
            $pi->add("ITEM_IMG", SERVER_URL . $imgs[0]['url']);
            $pi->add("ALT_IMG", $imgs[0]['alt_text']);
           
            $rows.= $pi->pagina();

        }

	    return $rows;
    }
    

    /*
     *     
     */
    public function get_pictures($printer_id, $AND = ""){

        $sql = "SELECT * FROM picture WHERE printer_id = $printer_id $AND ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos;
    }
    
    /*
     * 
     */
    public function get_slider($printer_id){
        
        $posts = $this->get_pictures($printer_id, " and type = 'SLIDER' ");
        $rows ="";
        
        foreach ($posts as $post) {
            $pi = new Panel("slide.html");
            $pi->add("PIC_URL",SERVER_URL.$post['url']);
            if($rows=="") $pi->add("ACTIVE","active");
            $pi->add("ALT",$post['alt_text']);
            $pi->add("MSJ_SLIDE","");
            $rows.= $pi->pagina();
        }
        $p = new Panel("slider_frame.html");
        $p->add("SLIDES", $rows);
	    return $p->pagina();
    }
    
    /*
     * 
     */
    public function adm_printer_ls(){
        
        $printers = $this->get_printers();
        $rows ="";
        foreach ($printers as $printer) {

            if($printer['status']=='enabled') {
                $st_class = 'success';
                $st_icon = 'times';
                $st_btn = 'disable';
            } else{
                $st_class = 'danger';
                $st_icon = 'check';
                $st_btn = 'enable';
            }

            $pi = new Panel("admin_printer_ls_row.html");
            $pi->add("ID",$printer['id']);
            $pi->add("NAME",$printer['name']);
            $pi->add("STATUS",$printer['status']);
            $pi->add("ST_BTN_TITLE",$st_btn);
            $pi->add("ST_BTN_ICON",$st_icon);
            $pi->add("ST_CLASS",$st_class);
            $pi->add("URL_EDIT",SERVER_URL."/admin/printer_upx.php?EID=".urlencode($this->util->ID_EID($printer['id'])));
           
            $rows.= $pi->pagina();

        }

        $result = rtrim($rows, '<tr class="spacer"></tr>');

	    return $result;

    }

    /*
     * 
     */
    function admin_photo_list($printer_id){
        
        $multimedias = $this->get_pictures($printer_id, " and type = 'SLIDER' ");
        $rows ="";
        foreach ($multimedias as $media) {

            $p = new Panel("admin_photo_list_item.html");
            $p->add("MEDIA_URL",$media['url']);
            $p->add("MEDIA_ID",$media['id']);
            $p->add("MEDIA_SRC",SERVER_URL.$media['url']);
            $p->add("MEDIA_ALT",$media['alt_text']);
            $p->add("MEDIA_DESC",substr($media['description'],0,25) );
            $p->add("MEDIA_VER",SERVER_URL.$media['url']);
            $rows.= $p->pagina();

        }
        return $rows;

    }

    /*
     * get_picture
     */
    public function get_picture($id){

        $sql = "SELECT * FROM picture WHERE id = $id ";
        $datos = $this->cnx->db_select_array($sql);
        $object = json_decode(json_encode($datos[0]));

        //return $datos;
        return $object;
    }


   /*
    * Delete Picture
    */
    function delete_picture($ID){

        $picture = $this->get_picture($ID);

        //Delete file
        if($this->util->delete_file($picture->url)){
            $sql = "DELETE FROM picture WHERE id = $ID ";
            $result = $this->cnx->query($sql);
            return TRUE;
        }else{
            return FALSE;
        }

    }


   /*
    * Insert Picture
    */
    public function insert_picture($printer_id, $url, $alt_text = "", $description = ""){
        
        $mysqli = $this->cnx->connect();
        $printer_id = $mysqli->real_escape_string($printer_id);      
        $url = $mysqli->real_escape_string($url);      
        $alt_text = $mysqli->real_escape_string($alt_text);      
        $description = $mysqli->real_escape_string($description);      
        mysqli_set_charset($mysqli, "utf8");

        if($printer_id != "")$this->cnx->add_string($registro,"printer_id",trim($printer_id));
        if($url != "")$this->cnx->add_string($registro,"url",trim($url));
        if($alt_text != "")$this->cnx->add_string($registro,"alt_text ",trim($alt_text));
        if($description != "")$this->cnx->add_string($registro,"description ",trim($description));
        
        $sql = $this->cnx->query_insert($registro,"picture");

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return $sql." ERROR: ".$err;
        else return $ID;
  
    }

}