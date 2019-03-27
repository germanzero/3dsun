<?php
session_start();
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    die();
}

if($_GET['es']==1){
    define("TEMPLATE_ROOT", "../template_es");
    $_SESSION["TEMPLATE_ROOT"] = "../template_es";
}else{
    unset($_SESSION["TEMPLATE_ROOT"]);
}
redirect("web/index.php");