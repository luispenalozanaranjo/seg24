<?php
$var = explode("/",$_SERVER['PHP_SELF']);
$pag = $var[count($var)-1];

if($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
	session_destroy();
	header('location: ../index.php');
}


if(isset($_SESSION['gloidperfil']) && $_SESSION['gloidperfil']!='')
{		

	$menu = '<ul class="css-tabs">';
	
	//Casos
		if($pag == 'visCasos.php' || $pag == 'insCasos.php' || $pag == 'modCasos.php')
		$clase = 'class="current"';
		else
		$clase = '';
		
		$menu.='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/inventario/visCasos.php" '.$clase.'>Casos EDT</a></li>';
	
	//Administración
		if($pag == 'visUsuario.php' || $pag == 'insUsuario.php' || $pag == 'modUsuario.php' ||
		$pag == 'visPerfil.php' || $pag == 'insPerfil.php' || $pag == 'modPerfil.php' ||
		$pag == 'visConfiguracion.php' || $pag == 'insConfiguracion.php' || $pag == 'modConfiguracion.php')
		$clase = 'class="current"';
		else
		$clase = '';
		
		$menu.='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/administracion/visUsuario.php" '.$clase.'>Administraci&oacute;n</a></li>';
	
	$menu.= '</ul>';
	echo $menu;
}
else {
	session_destroy();
	header('location: ../index.php');
}
?>
