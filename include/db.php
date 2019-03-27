<?php

//Archivo que contiene toda la funcionalidad de base de datos
//============================================================================
// connection method

function &db_connect($host, $user, $pswd, $db) {
    if ($cnx = @mysql_connect($host, $user, $pswd, true)) {
        @mysql_select_db($db, $cnx);
        return $cnx;
    }
    return NULL;
}

//============================================================================
// connection
$PCNX = db_connect(DBHOST, DBUSER, DBPSWD, DBNAME);

//============================================================================
// close method
function db_close(&$cnx) {
    if ($cnx) {
        @mysql_close($cnx);
    }
}

//============================================================================
// select method
function &db_select($query, &$err, $cnx = NULL) {
    global $PCNX;
    $tab = NULL;
    $res = ($cnx) ? @mysql_query($query, $cnx) : @mysql_query($query, $PCNX);
    $err = @mysql_error();
    if ($res) {
        while ($row = @mysql_fetch_assoc($res)) {
            $tab[] = $row;
        }
        return $tab;
    }
}

// select method
function &db_call($query, &$err, $cnx = NULL) {
    global $PCNX;
    $tab = NULL;
    $res = ($cnx) ? @mysql_query($query, $cnx) : @mysql_query($query, $PCNX);
    $err = @mysql_error();
    if ($res) {
        while ($row = @mysql_fetch_assoc($res)) {
            $tab[] = $row;
        }
        return $tab;
    }
}


function db_fields($query, &$err, $cnx = NULL) {
    global $PCNX;
    $tab = NULL;
    $result = ($cnx) ? @mysql_query($query, $cnx) : @mysql_query($query, $PCNX);
    $err = @mysql_error();
    $struct['campos'] = mysql_num_fields($result);
    $struct['filas'] = mysql_num_rows($result);
    $struct['tabla'] = mysql_field_table($result, 0);
    for ($i = 0; $i < $struct['campos']; $i++) {
        $type[] = mysql_field_type($result, $i);
        $name[] = mysql_field_name($result, $i);
        $len[] = mysql_field_len($result, $i);
        $flags[] = mysql_field_flags($result, $i);
    }
    $struct['tipos'] = $type;
    $struct['nombres'] = $name;
    $struct['longs'] = $len;
    $struct['flags'] = $flags;
    return $struct;
}

//============================================================================
// insert method
function db_insert($query, &$err, $cnx = NULL) {
    global $PCNX;
    $res = ($cnx) ? @mysql_query($query, $cnx) : @mysql_query($query, $PCNX);
    $err = @mysql_error();
    return @mysql_insert_id();
}

//============================================================================
// update method
function db_update($query, &$err, $cnx = NULL) {
    global $PCNX;
    $res = ($cnx) ? @mysql_query($query, $cnx) : @mysql_query($query, $PCNX);
    $err = @mysql_error();
    return @mysql_affected_rows();
}

/* * **********************FUNCIONES DE MANEJO DE RECORDSETS*************************** */

//====================================================================================
// request method (GET, POST)
function request(&$param, $key, $type = "") {
    global $_REQUEST, $_FILES;
    $key_bd = (substr($key, 0, 2) == "r_") ? substr($key, 2) : $key;
    switch ($type) {
        case "numeric" : return add_numeric($param, $key_bd, $_REQUEST[$key]);
        case "date" : return add_date($param, $key_bd, $_REQUEST[$key]);
        case "email" : return add_email($param, $key_bd, $_REQUEST[$key]);
        case "image" : if ($_FILES[$key]["error"] != UPLOAD_ERR_OK)
                return $_FILES[$key]["error"];
            return add_image($param, $key_bd, $_FILES[$key]["name"], $_FILES[$key]["tmp_name"], $_FILES[$key]["type"]);
        case "file" : if ($_FILES[$key]["error"] != UPLOAD_ERR_OK)
                return $_FILES[$key]["error"];
            return add_file($param, $key_bd, $_FILES[$key]["name"], $_FILES[$key]["tmp_name"], $_FILES[$key]["type"]);
        case "pass" : return add_encripted_string($param, $key_bd, $_REQUEST[$key]);
        default : return add_string($param, $key_bd, $_REQUEST[$key]);
    }
}

//============================================================================
// add string method
function add_string(&$param, $key, $value) {
    $param[$key]["type"] = "string";
    $v = (is_array($value)) ? implode(",", $value) : $value;
    if (get_magic_quotes_gpc()) {
        $param[$key]["value"] = stripslashes($v);
    } else {
        $param[$key]["value"] = $v;
    }
    return TRUE;
}

//============================================================================
// add encripted string method
function add_encripted_string(&$param, $key, $value) {
    $param[$key]["type"] = "string";
    $v = (is_array($value)) ? implode(",", $value) : $value;
    if (get_magic_quotes_gpc()) {
        $param[$key]["value"] = md5(stripslashes($v));
    } else {
        $param[$key]["value"] = md5($v);
    }
    return TRUE;
}

//============================================================================
// add numeric method
function add_numeric(&$param, $key, $value) {
    $n = str_replace(",", ".", $value);
    if ($n && !is_numeric($n))
        return -1;
    $param[$key]["type"] = "numeric";
    $param[$key]["value"] = $n;
    return TRUE;
}

//============================================================================
// add mysql method
function add_mysql(&$param, $key, $value) {
    if (!isset($value) || $value == "")
        return -1;
    $param[$key]["type"] = "mysql";
    $param[$key]["value"] = $value;
    return TRUE;
}

//============================================================================
// add date method (dd/mm/yyyy)
function add_date(&$param, $key, $value) {
    if (!ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})$", $value, $r))
        return -1;
    if (!checkdate((int) $r[2], (int) $r[1], (int) $r[3]))
        return -2;
    $param[$key]["type"] = "date";
    $param[$key]["value"] = $value;
    return TRUE;
}

//============================================================================
// add datetime method ('YYYY-MM-DD HH:MM:SS')
function add_datetime_now(&$param, $key) {
    $param[$key]["type"] = "datetime";
    $param[$key]["value"] = date('Y-m-d h:i:s');
    return TRUE;
}

//============================================================================
// add email method
function add_email(&$param, $key, $value) {
    if (!preg_match("/^[.\w-]+@([\w-]+\.)+[a-zA-Z]{2,6}$/", $value))
        return -1;
    $param[$key]["type"] = "email";
    $param[$key]["value"] = strtolower($value);
    return TRUE;
}

//============================================================================
// add file method (enctype="multipart/form-data")
function add_file(&$param, $key, $name, $path, $mime) {
    if (!$file = fopen($path, "rb"))
        return -1;

    $param[$key]["type"] = "file";
    $param[$key]["name"] = $name;
    $param[$key]["mime"] = $mime;
    $param[$key]["size"] = filesize($path);
    $param[$key]["path"] = $path;

    $binary = fread($file, filesize($path));
    $param[$key]["value"] = bin2hex($binary);

    fclose($file);
    return TRUE;
}

//============================================================================
// add image method (enctype="multipart/form-data")
function add_image(&$param, $key, $name, $path, $mime) {
    if (!$file = fopen($path, "rb"))
        return -1;
    list($width, $height, $type, $attr) = getimagesize($path);

    $param[$key]["type"] = "image";
    $param[$key]["name"] = $name;
    $param[$key]["mime"] = $mime;
    $param[$key]["size"] = filesize($path);
    $param[$key]["width"] = $width;
    $param[$key]["height"] = $height;
    $param[$key]["path"] = $path;

    $binary = fread($file, filesize($path));
    $param[$key]["value"] = bin2hex($binary);

    fclose($file);
    return TRUE;
}

//============================================================================
// get value method
function &get_value(&$param, $key) {
    return $param[$key]["value"];
}

//============================================================================
// get image/file method (hex to binary)	
function &get_file(&$param, $key) {
    return pack("H*", $param[$key]["value"]);
}

//============================================================================
// get sql value method
function &value_sql(&$param, $key) {
    switch ($param[$key]["type"]) {
        case "file" :
        case "image" : return "0x" . $param[$key]["value"];
        case "numeric" : return $param[$key]["value"];
        case "date" : ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $param[$key]["value"], $r);
            return "\"" . $r[3] . "-" . $r[2] . "-" . $r[1] . "\"";
        case "email" :
        default : return "\"" . mysql_escape_string($param[$key]["value"]) . "\"";
    }
}

//============================================================================
// get insert query method
function query_insert(&$param, $table) {
    foreach ($param as $key => $p) {
        $attr[] = strtoupper($key);
        if ($p["value"]) {
            switch ($p["type"]) {
                case "file" :
                case "image" : $val[] = "0x" . $p["value"];
                    break;
                case "numeric" : $val[] = $p["value"];
                    break;
                case "mysql" : $val[] = $p["value"];
                    break;
                case "date" : ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $p["value"], $r);
                    $val[] = "\"" . $r[3] . "-" . $r[2] . "-" . $r[1] . "\"";
                    break;
                case "email" :
                default : $val[] = "\"" . mysql_escape_string($p["value"]) . "\"";
            }
        } else {
            $val[] = "NULL";
        }
    }
    return "INSERT INTO " . $table . " (" . implode(", ", $attr) . ") VALUES (" . implode(", ", $val) . ")";
}

//============================================================================
// get update query method (id like "key=value")
function query_update(&$param, $table, $id) {
    foreach ($param as $key => $p) {
        $tmp = $key . "=";
        if ($p["value"]) {
            switch ($p["type"]) {
                case "file" :
                case "image" : $tmp.= "0x" . $p["value"];
                    break;
                case "numeric" : $tmp.= $p["value"];
                    break;
                case "date" : ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $p["value"], $r);
                    $tmp.= "\"" . $r[3] . "-" . $r[2] . "-" . $r[1] . "\"";
                    break;
                case "mysql" : $val[] = $p["value"];
                    break;
                case "email" :
                default : $tmp.= "\"" . mysql_escape_string($p["value"]) . "\"";
            }
        } else {
            $tmp.= "NULL";
        }
        $attr[] = $tmp;
    }
    return "UPDATE " . $table . " SET " . implode(", ", $attr) . " WHERE " . $id;
}

//============================================================================
// query method (id like "key=value")
function query_delete($table, $id) {

    return "DELETE FROM " . $table . " WHERE " . $id;
}

//=============================================================================
//
	function query_char($type) {

    db_select("SET NAMES " . $type, $err);
    if ($err)
        return FALSE;
    else
        return TRUE;
}

function transaccion_ini() {

    db_select("START TRANSACTION");
    if ($err)
        return FALSE;
    else
        return TRUE;
}

function transaccion_commit() {

    db_select("COMMIT");
    if ($err)
        return FALSE;
    else
        return TRUE;
}

function transaccion_rollback() {

    db_select("ROLLBACK");
    if ($err)
        return FALSE;
    else
        return TRUE;
}
