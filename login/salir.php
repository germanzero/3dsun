<?PHP	
include( "../include/global.php" );
$_SESSION[]=array();
session_unset();
session_destroy();
redirto("index.php");

