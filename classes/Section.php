<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Section {

    private $cnx = null;
    private $util = null;

    //$sec_path URL section must be completed this way: SERVER_URL/files/PAGE_NAME/SECTION_ID/
    private $sec_path = SERVER_URL."/files"; 
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }

    /**
     *     
     * 
    */
    public function get_section($key,$value){

        $sql = "SELECT * FROM section WHERE $key = $value limit 1";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos[0];
    }
    
    /**
     *     
     *slides at $this->sec_path/PAGE_NAME/SECTION_ID/slider/ 
    */
    public function get_slider($page_name,$section_id){
   
        $slides = $this->get_slides($section_id);
        $slides_html ="";
        $num_slide = 1;
        foreach ($slides as $slide) {
           
            $pi = new Panel("slide.html");
            $active = ($num_slide == 1) ? "active" : "";
            $pi->add("ACTIVE",$active);
            $pi->add("PIC_URL",SERVER_URL . $slide['ruta']);
            $pi->add("MSJ_SLIDE",$slide['message']);
            $pi->add("ALT",$page_name."-".$section_id."-".$slide['id']);
           
            $slides_html.= $pi->pagina();
            $num_slide++;
        }

        $p = new Panel("slider_frame.html");
        $p->add("CAROUSEL_ID",$page_name."_".$section_id);
        $p->add("SLIDES",$slides_html);

        return $p->pagina();
    }
    
    public function get_slides($section_id){

        $sql = "SELECT * FROM slider WHERE section_id = $section_id ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos;
    }

    public function get_slide($id){

        $sql = "SELECT * FROM slider WHERE id = $id ";
        $datos = $this->cnx->db_select_array($sql);
        $object = json_decode(json_encode($datos[0]));

        //return $datos;
        return $object;
    }

    /**
     * get specific banner 
     */
    public function get_banner($section_id){

        $sql = "SELECT * FROM banner WHERE section_id = $section_id LIMIT 1 ";
        $datos = $this->cnx->db_select_array($sql);
        return $datos[0];
    }

    /*
    * banner Update
    */
    public function update_banner($id, $path = "", $href ="", $alt_text = "", $status = ""){
        
        $mysqli = $this->cnx->connect();
        $path = $mysqli->real_escape_string($path);      
        $href = $mysqli->real_escape_string($href);      
        $alt_text = $mysqli->real_escape_string($alt_text);      
        $status = $mysqli->real_escape_string($status);      
        
        $title = $mysqli->real_escape_string($title);      
        $subtitle = $mysqli->real_escape_string($subtitle);      
        $info = $mysqli->real_escape_string($info);      
        
        mysqli_set_charset($mysqli, "utf8");

        if($title != "")$this->cnx->add_string($registro,"title",trim($title));
        if($subtitle != "")$this->cnx->add_string($registro,"subtitle",trim($subtitle));
        if($info != "")$this->cnx->add_string($registro,"info",trim($info));
        
        if($path != "")$this->cnx->add_string($registro,"path",trim($path));
        if($href != "")$this->cnx->add_string($registro,"href",trim($href));
        if($alt_text != "")$this->cnx->add_string($registro,"alt_text",trim($alt_text));
        if($status != "")$this->cnx->add_string($registro,"status",trim($status));
        
        $sql = $this->cnx->query_update($registro,"banner","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;// $sql." ERROR: ".$err;
        else return true;

    }
   

    /**
     * 
     *images are ate $this->sec_path/PAGE_NAME/SECTION_ID/slider/ 
    */
    public function get_web_hover_boxes($page_name,$section_id){
   
        $slides = $this->get_slides($section_id);
        $slides_html = "";
        $count = 0;
        foreach ($slides as $slide) {
            $count++;
            $pi = new Panel("web_hover_box.html");
            $pi->add("URL_SRC",SERVER_URL . $slide['ruta']);
            $pi->add("MSSG",$slide['alt_text']);
            $slides_html.= $pi->pagina();
            


        }

      
        return $slides_html;
    }


    /*
     * 
     * Admin Photo List
     * 
     */
    function admin_photo_list($page_name,$section_id){
        
        $multimedias = $this->get_slides($section_id);
        //$key = $this->util->getValConfig('UTIL_ENC_KEY');
        $rows ="";
        foreach ($multimedias as $media) {

            $p = new Panel("admin_photo_list_item.html");
            $p->add("MEDIA_URL",$media['url']);
            $p->add("MEDIA_ID",$media['id']);
            $p->add("MEDIA_SRC",SERVER_URL . $media['ruta']);
            $p->add("MEDIA_ALT",input_text_ml("alt-text-".$media['id'], "", 100, $media['alt_text'], "form-control d-inline"));
            $p->add("MEDIA_DESC",substr($media['message'],0,25) );
            $p->add("MEDIA_VER",SERVER_URL . $media['ruta']);
            $rows.= $p->pagina();

        }
        return $rows;

    }


    /* *
       * 
       * Section Update
       * 
       * */
    public function section_update($id, $name = "", $title ="", $subtitle = "", $paragraf = "", $picture = ""){
        
        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name);      
        $title = $mysqli->real_escape_string($title);      
        $subtitle = $mysqli->real_escape_string($subtitle);      
        //$paragraf = $mysqli->real_escape_string($paragraf);      
        $picture = $mysqli->real_escape_string($picture);      
       
        mysqli_set_charset($mysqli, "utf8");

        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($title != "")$this->cnx->add_string($registro,"title",trim($title));
        if($subtitle != "")$this->cnx->add_string($registro,"subtitle",trim($subtitle));
        if($paragraf != "")$this->cnx->add_string($registro,"paragraf",trim($paragraf));
        if($picture != "")$this->cnx->add_string($registro,"picture",trim($picture));
        
        $sql = $this->cnx->query_update($registro,"section","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;// $sql." ERROR: ".$err;
        else return true;
  
    }


   /* *
       * 
       * Insert Slider
       * 
       * */
      public function insert_slider($section_id, $ruta = "", $alt_text = "", $message =""){
        
        $mysqli = $this->cnx->connect();
        $alt_text = $mysqli->real_escape_string($alt_text);      
        $message = $mysqli->real_escape_string($message);      
        $ruta = $mysqli->real_escape_string($ruta);      
        $section_id = $mysqli->real_escape_string($section_id);      
        mysqli_set_charset($mysqli, "utf8");

        if($section_id != "")$this->cnx->add_string($registro,"section_id",trim($section_id));
        if($ruta != "")$this->cnx->add_string($registro,"ruta",trim($ruta));
        if($alt_text != "")$this->cnx->add_string($registro,"alt_text",trim($alt_text));
        if($message != "")$this->cnx->add_string($registro,"message",trim($message));
        
        $sql = $this->cnx->query_insert($registro,"slider");

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return $sql." ERROR: ".$err;
        else return $ID;
  
    }

/* *
       * 
       * Insert Slider
       * 
       * */
      public function update_slider($id, $alt_text){
        
        $mysqli = $this->cnx->connect();
        $alt_text = $mysqli->real_escape_string($alt_text);      
        mysqli_set_charset($mysqli, "utf8");

        if($alt_text != "")$this->cnx->add_string($registro,"alt_text",trim($alt_text));
        
        $sql = $this->cnx->query_update($registro,"slider","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return true;//$ID;
  
    }


    /**
     * 
     * Delete Slider
     */
    function delete_slider($ID){

        $slide = $this->get_slide($ID);

        //Delete file
        if($this->util->delete_file($slide->ruta)){
            $sql = "DELETE FROM slider WHERE id = $ID ";
            $result = $this->cnx->query($sql);
            return TRUE;
        }else{
            if(file_exists($slide->ruta)){
                return FALSE;
                
            }else{
                $sql = "DELETE FROM slider WHERE id = $ID ";
                $result = $this->cnx->query($sql);
                return TRUE;
            } 
           
        }

    }



    
}