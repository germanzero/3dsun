<?php	
	//============================================================================
	// includes
	ini_set("session.gc_maxlifetime", "18000"); 
	session_start();
	

	include_once "../include/global.php"; 	// db utilities
	//============================================================================
	// search user in database
	echo "<h3>CARGANDO...</h3>";
	if (  isset($_POST['r_Login']) && isset($_POST['r_Pass']) && $_POST['r_Pass'] != "" && $_POST['r_Pass'] != "" ) {
		$sql = "SELECT idusuario, idperfil, login, pass, nombre, snombre, apellido, sapellido, cedula, foto FROM usuario WHERE login LIKE '".$_POST['r_Login']."' AND pass LIKE '".md5($_POST['r_Pass'])."' ";
		//$sql.= " AND pass_usu='".md5($_POST['r_PASS'])."' AND status_usu ='ACTIVO' ";
		//echo md5($_POST['r_PASS']);
		query_char("utf8");
		$rs_user = db_select($sql,$err);
		
		if(sizeof($rs_user)>0) {
			
			$_SESSION['uid'] = $rs_user[0]['idusuario'];
			$_SESSION['rol'] = $rs_user[0]['idperfil'];
			$_SESSION['nombre_usu'] = $rs_user[0]['nombre']."&nbsp;".substr($rs_user[0]['snombre'],0,1);
			$_SESSION['apellido_usu'] = $rs_user[0]['apellido']."&nbsp;".substr($rs_user[0]['sapellido'],0,1);
			$_SESSION['cedula']	= $rs_user[0]['cedula'];
			$_SESSION['foto']	= $rs_user[0]['foto'];
			
			//$sql_top = "SELECT P.idperfil, P.nombre_per	from   perfil P	where P.idperfil =".$rs_user[0]['idperfil'];
			//$rs_top = db_select($sql_top, $err);
			
			//$_SESSION['usu'] 		= $rs_user[0]['nombre_usu']." ".$rs_user[0]['apellido_usu'];
			//$_SESSION['rol'] 		= $rs_top[0]['nombre_per'];
			//$_SESSION['fecha'] 	=	"Caracas, ".print_date(1);
			redirto("../admin/index.php");
			
			
		}
		else {
			
			alerta("Nombre de usuario y/o contrase�a incorrecto(s)");
			redirto("index.php?L=".$_POST['r_Login']);
			
		}
	}
