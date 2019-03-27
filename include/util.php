<?php

function encrypt($string, $key) {
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result.= $char;
    }
    return base64_encode($result);
}

function decrypt($string, $key) {
    $result = '';
    $string = base64_decode($string);
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result.= $char;
    }
    return $result;
}

function sfpg_base64url_encode2($plain) {
    $base64 = base64_encode($plain);
    $base64url = strtr($base64, "+/", "-_");
    return rtrim($base64url, "=");
}

function sfpg_base64url_decode2($base64url) {
    $base64 = strtr($base64url, "-_", "+/");
    $plain = base64_decode($base64);
    return ($plain);
}

function sfpg_url_string2($dir = "", $img = "") {
    $res = $dir . "*" . $img . "*";
    return sfpg_base64url_encode2($res . md5($res . SECURITY_PHRASE));
}

function negritas($valor) {
    return "<strong>" . $valor . "</strong>";
}

function getPost($var) {
    return filter_input(INPUT_POST,$var, FILTER_DEFAULT);
}


function redirto($url){
    echo '<script> location.href="'.$url.'" </script>';
}


