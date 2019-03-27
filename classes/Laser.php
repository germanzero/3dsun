<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Laser {

    private $cnx = null;
    private $util = null;
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }



    /**
     *     
     * 
    */
    public function get_laser($id){

        $sql = "SELECT * FROM laser WHERE id = $id ";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos[0];
    }

    
    /**
     *     
     * 
    */
    public function get_lasers(){

        $sql = "SELECT * FROM laser ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos;
    }

    /**
     * 
     */
    public function sub_menu_links($template = "sub_menu_link_item.html"){
        
        $posts = $this->get_lasers();
        $rows ="";
        foreach ($posts as $post) {
            $pi = new Panel($template);
            $pi->add("ITEM_URL",SERVER_URL."/web/laser.php?EID=".urlencode($this->util->ID_EID($post['id'])));
            $pi->add("ITEM_TITLE",$post['name']);
            $imgs = $this->get_pictures($post['id'], " and type = 'THUMB' ");
            $pi->add("ITEM_IMG", SERVER_URL . $imgs[0]['url']);
            $pi->add("ALT_IMG", $imgs[0]['alt_text']);
            $rows.= $pi->pagina();

        }

	    return $rows;
    }
    
    /*
     *     
     */
    public function get_pictures($laser_id, $AND = ""){

        $sql = "SELECT * FROM picture WHERE laser_id = $laser_id $AND ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos;
    }
    
    


    /*
     * 
     */
    public function adm_laser_ls(){
        
        $printers = $this->get_lasers();
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
            $pi->add("URL_EDIT",SERVER_URL."/admin/laser_upx.php?EID=".urlencode($this->util->ID_EID($printer['id'])));
           
            $rows.= $pi->pagina();

        }

        $result = rtrim($rows, '<tr class="spacer"></tr>');

	    return $result;

    }


      /**
     *     
     * update_laser
    */
    public function update_laser($id , $name ="", $status ="", $description_short ="", $description ="", $technical ="", $banner ="", $alt_text_banner = "")
    {

        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name);      
        //$description_short = $mysqli->real_escape_string($description_short);      
        //$description = $mysqli->real_escape_string($description);      
        //$technical = $mysqli->real_escape_string($technical);      
        $banner = $mysqli->real_escape_string($banner);      
        $alt_text_banner = $mysqli->real_escape_string($alt_text_banner);      
        
        mysqli_set_charset($mysqli, "utf8");

        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($status != "")$this->cnx->add_string($registro,"status",trim($status));
        if($description_short != "")$this->cnx->add_string($registro,"description_short",trim($description_short));
        if($description != "")$this->cnx->add_string($registro,"description",trim($description));
        if($technical != "")$this->cnx->add_string($registro,"technical_parameters",trim($technical));
        if($banner != "")$this->cnx->add_string($registro,"banner",trim($banner));
        if($alt_text_banner != "")$this->cnx->add_string($registro,"alt_text_banner",trim($alt_text_banner));
        
        $sql = $this->cnx->query_update($registro,"laser","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return true;
    }
  
    /**
     *     
     * create_laser
    */
    public function create_laser($name ="", $status ="",  $description_short ="", $description ="", $technical ="", $banner ="", $alt_text_banner = "")
    {

        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name);      
        //$description_short = $mysqli->real_escape_string($description_short);      
        //$description = $mysqli->real_escape_string($description);      
        $banner = $mysqli->real_escape_string($banner);      
        $alt_text_banner = $mysqli->real_escape_string($alt_text_banner);      
        
        mysqli_set_charset($mysqli, "utf8");

        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($status != "")$this->cnx->add_string($registro,"status",trim($status));
        if($description_short != "")$this->cnx->add_string($registro,"description_short",trim($description_short));
        if($description != "")$this->cnx->add_string($registro,"description",trim($description));
        if($technical != "")$this->cnx->add_string($registro,"technical_parameters",trim($technical));
        if($banner != "")$this->cnx->add_string($registro,"banner",trim($banner));
        if($alt_text_banner != "")$this->cnx->add_string($registro,"alt_text_banner",trim($alt_text_banner));
        
        $sql = $this->cnx->query_insert($registro,"laser");

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return $ID;
    }
  


    /*
     * 
     * Delete Laser
     */
    public function delete_laser($ID){

        $PRINTER_FOLDER = SERVER_FOLDER . "/files/products/lasers/$ID/";
        try{
            //Delete folder
            $this->util->delete_folder($PRINTER_FOLDER);
            
            $sql2 = "DELETE FROM laser WHERE id = $ID ";
            $result2 = $this->cnx->query($sql2);

            return TRUE;
            
        }catch(Exception $e){
            return FALSE;
        }

    }

}