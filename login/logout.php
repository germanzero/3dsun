<?PHP	
include( "../include/global.php" );
$_SESSION[]=array();
session_unset();
session_destroy();
redirto("../web/index.php");
        
