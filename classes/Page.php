<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Page {

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
    public function get_faqs(){

        $sql = "SELECT * FROM faq ";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos;
    }
  
    /**
     *     
     * 
    */
    public function get_page($id){

        $sql = "SELECT * FROM page WHERE id = $id limit 1";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos[0];
    }
  
    /**
     *     
     * 
    */
    public function get_page_where($where){

        $sql = "SELECT * FROM page WHERE $where";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos;
    }
  
   
    /**
     * 
     */
    function get_seo_pages(){
        
        $sql = "SELECT * FROM page ORDER BY name ";
        $datos = $this->cnx->db_select_array($sql);

        $id_parent = "SEO-PAGES";
        $rows = "";
        foreach ($datos as $page) {

            $p = new Panel("admin_accordion_item.html");
            $p->add("TITLE",$page['name']);
            
            $p->add("ID_PARENT",$id_parent);
            $p->add("ID_1","card-collapse-".$page['id']);
            $p->add("ID_2","collapse-".$page['id']);
               
                $pi = new Panel("admin_seo_page.html");
                $pi->add("SERVER_URL",SERVER_URL);
                $pi->add("TITLE",$page['page_title']);
                $pi->add("META",$page['meta_tags']);
                $pi->add("PARAGRAF",$page['page_desc']);
                $pi->add("ID_PAGE",$page['id']);
                $pi->add("IMG-INPUT-PANEL",$this->util->load_input_image("input-img-".$page['id'], $page['banner_header']));
		        $pi->add("IMG-INPUT-ID","input-img-".$page['id']);

            $p->add("BODY",$pi->pagina());
           
            $rows.= $p->pagina();
        }

        $po = new Panel("admin_accordion.html");
        $po->add("ID_PARENT",$id_parent);
        $po->add("CARDS",$rows);

        return $po->pagina();

    }

    /* *
       * 
       * 
       * 
       * */
    public function page_update($id, $title ="", $tags = "", $desc = "", $banner_header = ""){
        
        $mysqli = $this->cnx->connect();
        $title = $mysqli->real_escape_string($title);      
        $tags = $mysqli->real_escape_string($tags);      
        $desc = $mysqli->real_escape_string($desc);      
        $banner_header = $mysqli->real_escape_string($banner_header);      
       
        mysqli_set_charset($mysqli, "utf8");

        if($title != "")$this->cnx->add_string($registro,"page_title",trim($title));
        if($tags != "") $this->cnx->add_string($registro,"meta_tags",trim($tags));
        if($desc != "") $this->cnx->add_string($registro,"page_desc",trim($desc));
        if($banner_header != "") $this->cnx->add_string($registro,"banner_header",trim($banner_header));
        
        $sql = $this->cnx->query_update($registro,"page","id=".$id);

        $ID = $this->cnx->db_insert($sql,$err);
        if($err) return false;//$sql." ERROR: ".$err;
        else return true;
  
    }

    /**
     * 
     * get_banner_header
     * 
     */
    public function get_banner_header($name){
        $where  = " name = '".$name."' ";
        $datos = $this->get_page_where($where);
        return SERVER_URL . $datos[0]['banner_header'];
    }



    /*
     * 
     * web_faq_list
     *  
     */
    public function web_faq_list(){
        
        $faqs = $this->get_faqs();
        $rows ="";
        unset($faq);
        $i = 1;
        foreach ($faqs as $faq) {
            $pi = new Panel("web_faq_item.html");
            $pi->add("NUMBER",$i);
            $pi->add("TITLE",$faq['question']);
            $pi->add("CONTENT",$faq['answer']);
            $pi->add("URL",$faq['url']);
            $pi->add("ID",$faq['id']);

            $rows.=$pi->pagina();
            unset($faq);
            $i++;
        }

        $p = new Panel("web_faq.html");
        $p->add("SERVER_URL",SERVER_URL);
        $p->add("FAQS",$rows);
	    return $p->pagina();

    }

    /*
     *   
     * load_admin_faqs
     *  
     */
    public function load_admin_faqs(){
        
        $faqs = $this->get_faqs();
        $rows ="";
        unset($faq);
        $i = 1;
        foreach ($faqs as $faq) {
            $pi = new Panel("admin_faq_item.html");
            $pi->add("NUMBER",$i);
            $pi->add("TITLE",$faq['question']);
            $pi->add("CONTENT",$faq['answer']);
            $pi->add("URL",$faq['url']);
            $pi->add("ID",$faq['id']);

            $rows.=$pi->pagina();
            unset($faq);
            $i++;
        }

      
	    return $rows;

    }



  
    /*
       * 
       * 
       * 
       * 
       */
      function create_faq($question ="",$answer ="",$url =""){
        
        $mysqli = $this->cnx->connect();
        $question = $mysqli->real_escape_string($question); 
        $answer = $mysqli->real_escape_string($answer); 
        $url = $mysqli->real_escape_string($url);      
        
        mysqli_set_charset($mysqli, "utf8");
        if($question != "")$this->cnx->add_string($registro,"question",trim($question));
        if($answer != "")$this->cnx->add_string($registro,"answer",trim($answer));
        if($url != "")$this->cnx->add_string($registro,"url",trim($url));
        $sql = $this->cnx->query_insert($registro,"faq");

            $ID = $this->cnx->db_insert($sql,$err);
            if($err) return FALSE;//$sql." ERROR: ".$err;
            else return TRUE;
           
      
    }



    /**
     * 
     * Delete Faq
     */
    function delete_faq($ID){
        $sql = "DELETE FROM faq WHERE id = $ID ";
        $result = $this->cnx->query($sql);
        if(!$this->cnx->error()){
            return TRUE;
        }else return FALSE;
        

    }

}