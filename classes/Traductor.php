<?php

//defined("APPREAL") OR die("Access denied");

/**
 * 
 * @author GDMC
 */
include_once( "../include/global.php" );
class Traductor {

    private $cnx = null;
    private $util = null;
    
    public function __construct() {
        $this->cnx = new Dbutil();
        $this->util = new Util();
    }

    /**
     * 
     */
    public function traducir($key,$lang){
        $mysqli = $this->cnx->connect();
        mysqli_set_charset($mysqli, "utf8");
        $sql = "SELECT * FROM traductor_simple WHERE palabra = '$key' ";
        $datos = $this->cnx->db_select_array($sql);
        //return $sql;
        return $datos[0][$lang];
    }
    
    /**
     * 
     */
    public function get_page_translations($arraykey,$lang){

        $size = sizeof($arraykey);
        $in = '';
        for ($i=0; $i < $size; $i++) { 
            $in.="'".$arraykey[$i]."',";
        }
        $in = basename($in,',');
        $mysqli = $this->cnx->connect();
        mysqli_set_charset($mysqli, "utf8");
        $sql = "SELECT ts.* FROM traductor_simple ts WHERE ts.palabra IN ($in) ";
        
        $datos = $this->cnx->db_select_array($sql);
        //return $sql;
        $resp = $this->get_translations($datos,$lang);
        return $resp;
    }
/**
 *     [1] => Array
 *       (
 *           [id] => 9
 *           [palabra] => HOME
 *           [es] => Inicio
 *           [en] => Home
 *       )
 *
 * 
*/
    public function get_translations($datos,$lang){

        $resp = array();
        foreach($datos as $dato){
            $key = $dato['palabra'];
            $val = $dato[$lang];
            $resp[$key] = $val;
        }
       
        return $resp;
    }
    

    
}