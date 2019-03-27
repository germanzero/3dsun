<?php

include_once( "../../include/global.php" );

$accion = $_POST["accion"];
switch ($accion) {

    case 'create-post':
    create_post();
    break;

    case 'update-post':
    update_post();
    break;

    case 'update-header':
    update_header();
    break;


}


function create_post(){
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $title = $form["title"];
    $subtitle = $form["subtitle"];
    $content = $form["content"];
    $page_title = $form["page_title"];
    $tags = $form["tags"];
    $desc = $form["page_desc"];
    $sta = $form["select_state"];
    $MSJ ="";
    $type = "warning";
    //Insertando post
    if (class_exists('Blog')) {
       $UTIL = new Util();
       $BLOG = new Blog();
       $ID = $BLOG->create_post($title, $subtitle, $content, $page_title, $tags, $desc, $sta);
       if($ID > 0){
        $MSJ = "Post created";
        $code = 1;
        $type = "success";
        //Upload header-image 
            $FILE = (object)$_FILES["input-img-crx"];
            $PATH =  "/blog-files/$ID/";
            $RESP = upload_now($PATH, $FILE, $UTIL->random_string(24)."_header_image");
            $MSJ = $RESP["msj"];
            $code = $RESP["code"];
            
            if($code==1){
                 //update record
                 $BLOG = new Blog();
                 $RES = $BLOG->update_post($ID,"","","","","","","",$PATH.$RESP["filename"]);
                 if(strpos($RES,"ERROR")===FALSE){
                    //$MSJ = "Post created";
                 }else{
                     $code = 1;
                     
                 } 
     
            } 


       }else{
        $MSJ = "Post not created";
        $code = 2;
       } 
       
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $UTIL->web_alert($MSJ,$type), "code" => $code);
    echo json_encode($res);

}

function update_post(){
    $UTIL = new Util();
    parse_str($_POST["formula"],$form);
    //var_dump($form);
    //var_dump($_FILES);
    $EID = $form["eid-post"];
    $title = $form["title"];
    $subtitle = $form["subtitle"];
    $content = $form["content"];
    $page_title = $form["page_title"];
    $tags = $form["tags"];
    $desc = $form["page_desc"];
    $sta = $form["select_state"];
    $MSJ ="";
    $ID = $UTIL->EID_ID($EID); 

    if (class_exists('Blog')) {

       $BLOG = new Blog();
       $RESP = $BLOG->update_post($ID, $title, $subtitle, $content, $page_title, $tags, $desc, $sta);
       if(strpos($RESP,"ERROR")===FALSE){
        $MSJ = "Post updated ";
        $code = 1;
       }else{
        $MSJ = "Post not updated";
        $code = 2;
       } 
       
    }else{
        $MSJ = "Class not exists";
        $code = 2;
    }

    $res = array("msj" => $MSJ, "code" => $code);
    echo json_encode($res);

}

function update_header(){
    $UTIL = new Util();
    //var_dump($_POST);
    //var_dump($_FILES);
    $EID = $_POST["eid-post"];
    $FILE = (object)$_FILES["input-img"];
    $MSJ ="";
    $ID = $UTIL->EID_ID($EID); 

    $PATH =  "/blog-files/$ID/";
    $RESP = upload_now($PATH, $FILE, $UTIL->random_string(24)."_header_image");

    if (class_exists('Blog')) {
       $MSJ = $RESP["msj"];
       $code = $RESP["code"];
       if($code==1){
            

            //update record
            $BLOG = new Blog();
            $RES = $BLOG->update_post($ID,"","","","","","","",$PATH.$RESP["filename"]);
            if(strpos($RES,"ERROR")===FALSE){
                $MSJ = "Post updated ";
                $code = 1;

                $URL = SERVER_URL . $PATH . $RESP["filename"];
                $pim = new Panel("admin_actual_img.html");
                $pim->add("MEDIA_URL","");
                $pim->add("MEDIA_ID","");
                $pim->add("MEDIA_SRC", $URL);
                $pim->add("MEDIA_ALT", $RESP["filename"]);
                $pim->add("MEDIA_DESC", "");
                $pim->add("MEDIA_VER", $URL);
                $img = $pim->pagina();

            }else{
                $MSJ = "Post not updated";
                $code = 2;
            } 

       } 
       
    }else{
        $MSJ = "Header not updated - ClassNE";
        $code = 2;
    }

    $res = array("msj" => $MSJ, "code" => $code, "img" => $img);
    echo json_encode($res);

}

function upload_now($PATH, $FILE, $NAME){
        $BLOG = new Blog();
        $RESP = $BLOG->upload_header_image($FILE, SERVER_FOLDER . $PATH, $NAME);
        $MSJ = $RESP["msj"];
        $code = $RESP["code"];
        if($code==1){
         $FILENAME  = SERVER_URL . $PATH . $RESP["filename"];
         $file_name = $RESP["filename"];
        } 
  
     $res = array("msj" => $MSJ, "code" => $code, "filename" =>$file_name);
     return $res;

}

       
