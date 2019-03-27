<?	
	
	session_start();
	include( "../include/global.php" );

	
	///////////////////////////////////////////////////////////////////////////////////////////
	//EL TOP///////////////////////////////////////////////////////////////////////////////////
	$top=new Panel("top.html");			
	$top->add("t_titulos","<strong>Administrador</strong>");	
	$top->add("t_otros","");	
	$top->add("t_usu","<span class=\"session_cont\">".$_SESSION['usu']."</span>");
	$top->add("t_rol","<span class=\"session_cont\">".$_SESSION['rol']."</span>");			//variables que se guardan desde el LOGIN
	$top->add("t_fecha","<span class=\"session_cont\">".$_SESSION['fecha']."</span>");
	$top->add("t_load","");
	
	///////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////
	$ml=new Panel("menu_l.html");   //Menu Lateral
	
	$top->add("t_contenido","<br><br><br><span class=\"welcome\"><strong>Bienvenido al Administrador de Predicciones...</strong>Seleccione un modulo para continuar.</span>");
	//////////////////////////////////////////////////////////////////////////////////////////////
	////Show a top y panel 'p' a t_contenido 
	//$top->add("t_contenido",$p->pagina());																						
	$top->add("t_otros",$ml->pagina());	
	$top->show(); 
	
?>	