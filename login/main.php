<? session_start();?>
<?	include( "../include/global.php" );
	titulos("","Principal");
	$p=new Panel("main.html");
	$h=$p->setBlock("MENU");
	$menu=$_SESSION['MENU'];
	foreach ($menu as $key=>$valor) {
		$p->add("IDM",$key);
		$p->add("LAB",$valor['LABEL']);
		$p->concat("MENU",$h);
	}
	$p->show();
?>