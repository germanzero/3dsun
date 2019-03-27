<?php

function hidden($name, $value) {
    return "<input type='hidden' name='" . $name . "' id='" . $name . "' value='" . $value . "'>\n";
}

//============================================================================// 
//
	function input_link_img($name, $src, $click = "", $href = "") {
    $html = "<a href='" . $href . "' onClick='" . $click . "'><img id='" . $name . "' src='../images/" . $src . "' border='0' /></a>";
    return $html;
}

//============================================================================// 
//
	function input_butonsub($name, $value = "", $class = "", $oc = "") {
    $html = "<input name='" . $name . "' type='submit' value='" . $value . "' class='$class' $oc >";
    return $html;
}

//============================================================================// 
//
	function input_img($name, $value, $click, $alt = "") {
    $html = "<img id='" . $name . "' src='../images/" . $value . "'  onClick='" . $click . "' alt='" . $alt . "' />";
    return $html;
}

//============================================================================// 
//
	function input_img_set($value, $attr = "", $js = "") {
    $html = "<img id='IMG_SET' src='" . $value . "'  '" . $attr . "' " . $js . " alt='Img_set' />";
    return $html;
}

//============================================================================// 
//
	function input_img_sub($name, $value, $class = "", $click = "") {
    $html = "<input type='image' id='$name' src='../images/" . $value . "'  alt='$value' onClick='$click' class='$class' />";
    return $html;
}

//============================================================================
//
	function input_buton($name, $value = "", $click, $class = "") {
    $html = "<input name='" . $name . "' type='button' value='" . $value . "' onClick='" . $click . "' class='$class' />";
    return $html;
}

//============================================================================
//
	function input_buton_img($name, $src = "", $click) {
    $html = "<input name='" . $name . "' type='button' src='../images/" . $src . "' onClick='" . $click . "'>";
    return $html;
}

//============================================================================
// 
function input_text($name, $len, $value = "", $read_only = "") {
    $html = "<input type='text' class='formularios' name='$name' id='$name' size='$len' value= '$value' $read_only >\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_text_ml($name, $len, $ml, $value = "", $class = "", $read_only = "") {
    $html = "<input type='text' class='$class' name='$name' id='$name' size='$len' maxlength='$ml' value= '$value' $read_only >\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_password($name, $label, $len, $value = "", $class = "", $read_only = "") {
    $html = "<input type='password' class='$class' name='$name' id='$name' size='$len' \n";
    $html.= "	value= '$value' $read_only >\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_textarea($name, $fila, $colu, $class = "", $value = "", $read_only = "") {
    $html = "<textarea class='$class' rows='$fila' cols='$colu' name='$name' id='$name' \n";
    $html.= "$read_only>$value</textarea>\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_texteditor($name, $label, $fila, $colu = "600", $value = "") {
    $oFCKeditor = new FCKeditor($name);
    $oFCKeditor->BasePath = "../include/fckeditor/";
    $oFCKeditor->Value = $value;
    $oFCKeditor->Width = $colu;
    $var = $oFCKeditor->CreateHtml();

    $html = "<span class='contenido2'>&nbsp;$label</span><br>&nbsp;&nbsp; \n";
    $html.= $oFCKeditor->CreateHtml();
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_select($name, &$items, &$ids, $value = "", $read_only = "") {

    $html = "<select name='" . $name . "' id='" . $name . "' class='formularios' $read_only >\n";
    for ($i = 0; $i < sizeof($items); $i++) {
        $m = "";
        if ($ids[$i] == $value)
            $m = "selected=true";
        $html.= "<option value='" . $ids[$i] . "'" . $m . ">" . $items[$i] . "</option>\n";
    }
    $html.= "</select>\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_action_select($name, &$items, &$ids, $action, $value = "", $read_only = "") {
    $html = "<select name='" . $name . "' id='" . $name . "' class='formularios' $read_only  $action >\n";
    for ($i = 0; $i < sizeof($items); $i++) {
        $m = "";
        if ($ids[$i] == $value)
            $m = "selected";
        $html.= "<option value='" . $ids[$i] . "'" . $m . ">" . $items[$i] . "</option>\n";
    }
    $html.= "</select>\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_only_select($name, &$items, &$ids, $action, $read_only = "") {
    $html = "&nbsp;&nbsp;<select name='" . $name . "' class='formularios' $read_only $action>\n";
    for ($i = 0; $i < sizeof($items); $i++) {
        $m = "";
        if ($ids[$i] == $value)
            $m = "selected";
        $html.= "<option value='" . $ids[$i] . "'" . $m . ">" . $items[$i] . "</option>\n";
    }
    $html.= "</select>\n";
    return $html;
}

//============================================================================
// 
function input_checkbox($name, $chequeado = "", $read_only = "") {

    if ($chequeado == "1")
        $chequeado = "checked=true";
    if ($chequeado == "0")
        $chequeado = "";

    $html = "<input type='checkbox' name='" . $name . "' id='" . $name . "'  " . $chequeado . " $read_only>";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_checkbox1($name, $label, $array_chk) {
    $html = "<span class='contenido2'>&nbsp;$label</span> <br>\n";
    foreach ($array_chk as $var => $chk) {
        $html.= '<input name="' . $name . '[]" type="checkbox" value="' . $chk['value'] . '" ' . $chk['checked'] . ' />';
        $html.= "<span class='labels1'>" . $chk['label'] . ":</span><br>";
    }
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_radio($name, $value, $label, $chequeado = "", $read_only = "") {

    if ($chequeado == "1")
        $chequeado = "checked=true";
    if ($chequeado == "0")
        $chequeado = "";

    $html = "<input type='radio' id='" . $name . "'  name='" . $name . "' value='" . $value . "' " . $chequeado . " $read_only>\n";
    $html.= "<span class='contenido2'>$label</span> \n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
//
	function input_radio_text($name, $value1, $label, $chequeado = "", $namet, $len, $value = "", $read_only = "") {

    if ($chequeado == "1")
        $chequeado = "checked=true";
    if ($chequeado == "0")
        $chequeado = "";
    $html = "<input type='radio' name='" . $name . "' value='" . $value1 . "' " . $chequeado . " $read_only>\n";
    $html.= "<span class='contenido2'>$label</span> \n";
    $html.= "<input type='text' class='textfields' name='$namet' size='$len' \n";
    $html.= "value= '$value' $read_only >\n";
    $html.= obligatorio($name);
    return $html;
}

//============================================================================
//

	function obligatorio($name) {
    return ((substr($name, 0, 2) == 'r_') ? "<img src='../images/empty.gif' name='" . substr_replace($name, "img_", 0, 2) . "' border='0' width='10' height='10'>\n" : "");
}

//============================================================================
//
	function input_date($name, $label, $len, $value = "", $read_only = "") {
    $html = "<span class='contenido2'>&nbsp;$label</span><br>&nbsp;&nbsp; \n";
    $html = "<input class='formularios' type='text' name='" . $name . "' id='" . $name . "' value='" . $value . "' $read_only size='$len'>\n";
    $html.= "<img src='../images/calendario.gif' border='0' id='zpcal_" . $name . "' onmouseover='this.style.cursor=\"pointer\"' onmouseout='this.style.cursor=\"default\"'>\n";
    $html.= "<script type='text/javascript'>\n";
    $html.= "Calendar.setup({ ";
    $html.= "	inputField     :    '" . $name . "',";     // id del campo de texto
    $html.= "	ifFormat     :     '%Y-%m-%d',";     // formato de la fecha que se escriba en el campo de texto 
    $html.= "	button     :    'zpcal_" . $name . "'";     // el id del bot&oacute;n que lanzar&aacute; el calendario 
    $html.= "}); ";
    $html.= "</script>";
    $html.=obligatorio($name);
    return $html;
}

//============================================================================
// 
function input_image($name, $label, $value = "", $del = true, $max_file_size = 1500000) {
    $html = "<span class='contenido2'>&nbsp;$label</span> \n";
    $html.= "	<input type='hidden' name='MAX_FILE_SIZE' value='" . $max_file_size . "' class='formularios'> \n";
    $html.= "	<input type='file' name='" . $name . "' class='formularios' size='35'>\n";
    $html.=obligatorio($name);
    return $html;
}

/* * **************************************************************
  ////////Botones////////////
 * *************************************************************** */

function boton_eliminar($link_dl) {
    $html = '<a href="#" onClick=\'return remove("' . $link_dl . '");\'> ';
    $html.='<span  class="links_lista" style="font-size:8px">Borrar ';
    $html.='<img src="../images/inactivo.gif" width="8" height="8" border="0" /></a>';
    return $html;
}

function boton_action($value = "Enviar", $action = '') {
    $html = '<input name="Boton" type="button" ' . $action . ' value="' . $value . '"  class="formularios" />';
    return $html;
}

function input_file($class, $name, $label, $id, $value = "", $del = true) {
    $html = "<span class='$class'>&nbsp;" . $label . "</span>" . obligatorio($name) . "\n
		<input type=\"file\" name=\"" . $name . "\" class='$class' size=20 >\n";
    return $html;
}

function input_file_no_label($name, $size, $value = "") {
    $html = obligatorio($name) . "<input type=\"file\" id=\"" . $name . "\" name=\"" . $name . "\" size=" . $size . " value=\"" . $value . "\" >\n";
    return $html;
}

function input_buton_action($name, $click, $value = "") {
    $html = '<input name="' . $name . '" type="button" value="' . $value . '" onclick="' . $click . '">';
    return $html;
}

function buton_file($name, $click, $value = "") {
    $html = "<input name='" . $name . "' type='button' value='" . $value . "' onClick='" . $click . "'>";
    return $html;
}

//============================================================================
//
	function input_editorHTML($name, $value) {
    $html = "<textarea id='$name' name='$name'>$value</textarea>";
    $html.= "<script type='text/javascript'>\n";
    $html.= "CKEDITOR.replace( '$name',";
    $html.= "{";
    $html.= "toolbar : [ [ 'Source', '-', 'Cut', 'Copy', 'Paste', '-', 'TextColor', 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'NumberedList', 'BulletedList', 'Outdent', 'Indent', '-', 'Link', 'Unlink', 'Anchor', '-' ],";
    $html.= "['Styles', 'Format', 'Font', 'FontSize', '-', 'Image', 'Flash', 'Table', 'HorizontalRule']";
    $html.= "],";
    $html.= "height:400,";
    $html.= "filebrowserBrowseUrl : '../filemanager/index.php'";
    $html.= "});";
    $html.= "</script>";
    return $html;
}

function calendario_hora($name, $image = "../images/cal/cal.gif", $value = "") {
    $html = "<input type='text' id='$name' name='$name' maxlength='25' size='17' value= '$value' readonly='readonly'>";
    $html.= "<a href=\"javascript:NewCssCal('" . $name . "','yyyymmdd','',true)\">";
    $html.= "<img src='" . $image . "' width='16' height='16' alt='Seleccione Fecha'></a>";
    return $html;
}

function calendario_no_hora($name, $image = "../images/cal/cal.gif", $value = "") {
    $html = "<input type='text' id='$name' name='$name' maxlength='25' size='10' value= '$value' readonly='readonly'>";
    $html.= "<a href=\"javascript:NewCssCal('" . $name . "','yyyymmdd','')\">";
    $html.= "&nbsp;<img src='" . $image . "'  border='0' alt='Seleccione Fecha'></a>";
    return $html;
}

//============================================================================

function validar_input($name, $validar) {
    $code = "";
    //$code.= "<script>var $name = new LiveValidation('$name', { validMessage: 'Listo!', wait: 500});";
    $code.= "<script>var $name = new LiveValidation('$name', { validMessage: ' ', wait: 2000});";


    for ($i = 0; $i < sizeof($validar); $i++) {

        if (array_key_exists("Presente", (object) $validar)) {
            $code.= "$name.add(Validate.Presence, {failureMessage: \"" . $validar['Presente'] . "\"});";
        }
        if (array_key_exists("Numerico", (object) $validar)) {
            $code.= "$name.add( Validate.Numericality, { onlyInteger: true } );";
            $code.= "$name.add(Validate.Presence, {failureMessage: \"" . $validar['Numerico'] . "\"});";
        }
        if (array_key_exists("Email", (object) $validar)) {
            $code.= "$name.add(Validate.Presence, {failureMessage: \"" . $validar['Email'] . "\"});";
            $code.= "$name.add(Validate.Email);";
        }
        if (array_key_exists("Minimo", (object) $validar)) {
            $code.= "$name.add( Validate.Numericality, { minimum: " . $validar['Minimo'] . " } );";
        }
    }
    return $code.="</script>";
}

//============================================================================
//============================================================================
// 
function input_text_val($name, $len, $ml, $value = "", $class = "", $read_only = "", $validar = "") {
    $html = "<input type='text' class='$class' name='$name' id='$name' size='$len' maxlength='$ml' value= '$value' $read_only >\n";

    if (sizeof($validar) > 0) {

        $html.= validar_input($name, $validar);
    }

    return $html;
}



    function input_tel($name, $codar_val = "", $value = "", $class = "", $read_only = "", $validar = "",$tipo="") {

        $ca = combo_codar($name."_CODAR", $codar_val,$tipo);

        $html = $ca . "<input type='text' class='$class' name='$name' id='$name' size='7' maxlength='7' value= '$value' $read_only >\n";

        if (sizeof($validar) > 0) {

            $html.= validar_input($name, $validar);
        }



        return $html;
    }
?>
