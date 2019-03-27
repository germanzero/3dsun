<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Blog {

    private $cnx = null;
    private $util = null;
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }

    /*
     *     
     * 
     */
    public function get_post($id){

        $sql = "SELECT p.id, p.title, p.subtitle, p.content, p.pic_header, p.`status`, u.id,
        p.page_title, p.page_desc, p.meta_tags,
        DATE_FORMAT(p.publish_date,'%M %e, %Y ') AS PUBLISH,  
        CONCAT(u.name,' ',u.lastname) AS AUTOR 
        FROM post p, `user` u 
        WHERE u.id = p.autor
        AND p.id = $id ";
        $datos = $this->cnx->db_select_array($sql);
    
        return $datos[0];
    }

    /*
     *     
     * 
     */
    public function get_posts($autor ="*", $publish="*", $limit="10", $status ="*"){
       
        if($autor!="*"){
            $where.= " AND autor = $autor";
        }
        
        if($publish!="*"){
            $where.= " AND p.publish_date >= '$publish 00:00:01' ";
            $where.= "AND p.publish_date < '$publish 23:59:59'";
        }
        
        if($status!="*"){
            $where.= " AND p.`status` = $status ";
        }
       
        
    
        $sql = "SELECT p.id as POST_ID, p.title, p.subtitle, p.content, p.pic_header, p.`status`, u.id,
                p.page_title, p.page_desc, p.meta_tags,
                DATE_FORMAT(p.publish_date,'%M %e, %Y ') AS PUBLISH,  
                CONCAT(u.name,' ',u.lastname) AS AUTOR 
                FROM post p, `user` u 
                WHERE u.id = p.autor
                $where 
                ORDER by publish_date DESC
                limit $limit ";
        $datos = $this->cnx->db_select_array($sql);
       
        return $datos;
    }

    /*
     *     
     * 
     */
    public function get_comments($id){

        $sql = "SELECT p.id, p.name, p.email, p.content
                FROM comment p
                WHERE p.post_id = $id ";
        $datos = $this->cnx->db_select_array($sql);
        return $datos;
    }

    /*
     * 
     */
    public function web_home_post_list(){
        
        $posts = $this->get_posts("*","*",3,"'Published'");
        $rows ="";
        foreach ($posts as $post) {
            $pi = new Panel("web_home_blog_list_item.html");
            $pi->add("SERVER_URL",SERVER_URL);
            
            $pi->add("TITLE",$post['title']);
            $pi->add("DATE",$post['PUBLISH']);
            $pi->add("SRC_IMG",SERVER_URL.$post['pic_header']);
            $pi->add("HREF",SERVER_URL."/web/news_detail.php?EID=".$this->util->ID_EID($post['id']));
            
            $rows.= $pi->pagina();

        }

        $result = rtrim($rows, '<hr class="division">');

	    return $result;

    }

    /*
     * 
     */
    public function adm_post_ls(){
        
        $posts = $this->get_posts("*","*",5);
        $rows ="";
        unset($post);
         
        foreach ($posts as $post) {

            if($post['status']=='Published') {
                $st_class = 'process';
                $st_icon = 'download';
                $st_btn = 'Unpublish';
            } else{
                $st_class = 'denied';
                $st_icon = 'upload';
                $st_btn = 'Publish';
            }

            $pi = new Panel("admin_post_ls_row.html");
            $pi->add("ID",$post['POST_ID']);
            $pi->add("EID",urlencode($this->util->ID_EID($post['POST_ID'])));
            $pi->add("TITLE",$post['title']);
            $pi->add("AUTHOR",$post['AUTOR']);
            $pi->add("DATE",$post['PUBLISH']);
            $pi->add("STATUS",$post['status']);
            $pi->add("ST_BTN_TITLE",$st_btn);
            $pi->add("ST_BTN_ICON",$st_icon);
            $pi->add("ST_CLASS",$st_class);
         
            $rows.= $pi->pagina();
            unset($post);
        }

        $result = rtrim($rows, '<tr class="spacer"></tr>');

	    return $result;

    }

    /*
     *
     *  
     */
    public function web_post_list(){
        
        $posts = $this->get_posts("*","*",5,"'Published'");
        $rows ="";
        unset($post);
        foreach ($posts as $post) {
            $pi = new Panel("web_blog_list_item.html");
            $pi->add("SERVER_URL",SERVER_URL);
            $pi->add("PIC-HEADER",$post['pic_header']);
            $pi->add("TITLE",$post['title']);
            $pi->add("SUBTITLE",$post['subtitle']);
            $pi->add("CONTENT",substr($post['content'], 0, 90)."...");
            $pi->add("DATE",$post['PUBLISH']);
            $pi->add("AUTOR",$post['AUTOR']);
            $pi->add("EID",urldecode($this->util->ID_EID($post['POST_ID'])));

            $rows.=$pi->pagina();
            unset($post);
        }

        $p = new Panel("web_blog_list.html");
        $p->add("SERVER_URL",SERVER_URL);
        $p->add("ROWS",$rows);
	    return $p->pagina();

    }
    
    /*
     * 
     */
    public function comment_post_list($id){
        $comments = $this->get_comments($id);
        $rows ="";
        foreach ($comments as $comment) {
            $pi = new Panel("web_comment_item.html");
            $pi->add("NAME",$comment['name']);
            $pi->add("EMAIL",$comment['email']);
            $pi->add("CONTENT",$comment['content']);
            $rows.=$pi->pagina();
        }
	    return $rows;
    }

    public function create_comment($post_id, $name ="",$email ="",$content =""){
        
        $mysqli = $this->cnx->connect();
        $name = $mysqli->real_escape_string($name); 
        $email = $mysqli->real_escape_string($email); 
        $content = $mysqli->real_escape_string($content);      
          
        
        mysqli_set_charset($mysqli, "utf8");
        if($name != "")$this->cnx->add_string($registro,"name",trim($name));
        if($email != "")$this->cnx->add_string($registro,"email",trim($email));
        if($content != "")$this->cnx->add_string($registro,"content",trim($content));
        $this->cnx->add_numeric($registro,"post_id",$post_id);
        
       
        $sql = $this->cnx->query_insert($registro,"comment");

            $ID = $this->cnx->db_insert($sql,$err);
            if($err) return FALSE;//$sql." ERROR: ".$err;
            else return $ID;
           
      
    }


    /*
    * 
    * 
    * 
    * 
    */
    public function create_post($title ="",$subtitle ="",$content ="", $page_title = "", $tags = "", $desc = "", $sta = ""){
        
        $mysqli = $this->cnx->connect();
        $title = $mysqli->real_escape_string($title); 
        $autor = $mysqli->real_escape_string($_SESSION['user_id']); 
        $subtitle = $mysqli->real_escape_string($subtitle);      
        //$content = $mysqli->real_escape_string($content);      
        $page_title = $mysqli->real_escape_string($page_title);      
        $tags = $mysqli->real_escape_string($tags);      
        $desc = $mysqli->real_escape_string($desc);      
        $sta = $mysqli->real_escape_string($sta);      
        
        mysqli_set_charset($mysqli, "utf8");
        $this->cnx->add_numeric($registro,"autor",$autor);
        if($title != "")$this->cnx->add_string($registro,"title",trim($title));
        if($subtitle != "")$this->cnx->add_string($registro,"subtitle",trim($subtitle));
        if($content != "")$this->cnx->add_string($registro,"content",trim($content));
        if($page_title != "")$this->cnx->add_string($registro,"page_title",trim($page_title));
        if($desc != "") $this->cnx->add_string($registro,"page_desc",trim($desc));
        if($tags != "") $this->cnx->add_string($registro,"meta_tags",trim($tags));
        if($sta != "") $this->cnx->add_string($registro,"status",trim($sta));
        $sql = $this->cnx->query_insert($registro,"post");

            $ID = $this->cnx->db_insert($sql,$err);
            if($err) return FALSE;//$sql." ERROR: ".$err;
            else return $ID;
           
      
    }
    
    /* 
    * 
    * 
    * 
    */
    public function update_post($id, $title ="", $subtitle ="", $content ="", $page_title = "", $tags = "", $desc = "", $sta = "",$pic_header = "" ){
        
        $mysqli = $this->cnx->connect();
        $title = $mysqli->real_escape_string($title); 
        $autor = $mysqli->real_escape_string($_SESSION['user_id']); 
        $subtitle = $mysqli->real_escape_string($subtitle);      
        //$content = $mysqli->real_escape_string($content);      
        $page_title = $mysqli->real_escape_string($page_title);      
        $tags = $mysqli->real_escape_string($tags);      
        $desc = $mysqli->real_escape_string($desc);      
        $sta = $mysqli->real_escape_string($sta);      
        $pic_header = $mysqli->real_escape_string($pic_header);      
        
        mysqli_set_charset($mysqli, "utf8");
        $this->cnx->add_numeric($registro,"autor",$autor);
        if($title != "")$this->cnx->add_string($registro,"title",trim($title));
        if($subtitle != "")$this->cnx->add_string($registro,"subtitle",trim($subtitle));
        if($content != "")$this->cnx->add_string($registro,"content",trim($content));
        if($page_title != "")$this->cnx->add_string($registro,"page_title",trim($page_title));
        if($desc != "") $this->cnx->add_string($registro,"page_desc",trim($desc));
        if($tags != "") $this->cnx->add_string($registro,"meta_tags",trim($tags));
        if($sta != "") $this->cnx->add_string($registro,"status",trim($sta));
        if($pic_header != "") $this->cnx->add_string($registro,"pic_header",trim($pic_header));
        $sql = $this->cnx->query_update($registro,"post","id=".$id);

            $ID = $this->cnx->db_insert($sql,$err);
            if($err) return $sql." ERROR: ".$err;
            else return $ID;
           
      
    }

    /*
     * 
     */
    public function delete_actual_file($id, $type){
        //type: pdf,ft

        $prod = $this->get_product($id);
        $EID = $this->ID_EID($id);
        $path = SERVER_FOLDER;
        if($type==="pdf"){
            $filename = '/uploads/products/'.$EID.'/'.$prod->catalogo;
        }elseif($type==="ft"){
            $filename = '/uploads/products/'.$EID.'/'.$prod->ficha;
        }
        $filename = SERVER_FOLDER.$filename;
        if(!is_dir ($filename)){
            if(unlink($filename)) return "DELETED->".$filename;
            else return "NOT UL->".$filename;
        }else{
            return"ISDIR->". $filename;
        } 

    }

    function upload_header_image($FILE,$path,$fname="header_image"){
        $UTIL = new Util();
       // $FILE = (object)$_FILES["archivo"];
        
        if ( 0 < $FILE->error ) {
            $msj =  'Error: ' . $FILE->error . '<br>';
        }else {
            
            if (!file_exists($path)) { 
                mkdir($path);
            }
          /*
            $FILENAME = $UTIL->random_string(24).'_'. $FILE->name;
            //$FILENAME = 'foto_banner_bot'.$UTIL->get_file_term_type($FILE->name);
            $FILENAME = $UTIL->remove_spaces($FILENAME, 1);*/
            $FILENAME = $fname.".".$UTIL->get_file_term_type($FILE->name);
            $path = $path . $FILENAME;
            
            if(move_uploaded_file($FILE->tmp_name, $path)){ 
                $msj = "Header updated";
               
                $code = 1;
            }else {
                $code = 2;
                $msj = "Header not updated";
            }
    
        }
    
        $response = array("code" => $code,"msj" => $msj, "path" => $path, "filename" =>$FILENAME);
        return $response;
    }
    

}