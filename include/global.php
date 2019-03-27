<?php

//error_reporting(0);
session_start();

//define('SERVER_URL', "http://www.sun3dcorporation.com");
define('SERVER_URL', "http://localhost/3dsun");
define('SERVER_FOLDER', $_SERVER['DOCUMENT_ROOT']."/3dsun");

//Funcionamiento
include_once SERVER_FOLDER."/include/Template.inc";			//clase Engine de Templates
include_once SERVER_FOLDER."/include/Panel.inc";			//Clase de funciones para el engine de templates
include_once SERVER_FOLDER."/include/r_form.php";			//Repositorio de funciones para los formularios
include_once SERVER_FOLDER."/include/conf.php";	    		//Configuracion de los datos de acceso a la base de datos
//include_once SERVER_FOLDER."/include/db.php"; 	  		//Utilidades para bases de datos


define('CAPTCHAS','6LepIB8TAAAAAECZv9VNrEfUOWvmhWeZwPe3PM9u');

define('CRYPT','AppleSauce44');
define('CRYPT_RIGHT','_6Lsd5etF4');
define('CRYPT_LEFT','845a9w85t4r_');

define('DEFAULT_LANG','en');                                //Lenguaje por default


//utiles
include_once SERVER_FOLDER."/include/util.php";				    //funciones variadas de librería en php


//Autoloader de clases.

function autoload_classes($class_name) {
    $filename = SERVER_FOLDER . '/classes/' . $class_name . '.php';
    if (is_file($filename)) {
        include_once $filename;
    }
}
spl_autoload_register("autoload_classes");
