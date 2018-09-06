<?php
$var = explode("/",$_SERVER['PHP_SELF']);
$pag = $var[count($var)-1];

if($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
	session_destroy();
	header('location: ../index.php');
}


if(isset($_SESSION['gloidperfil']) && $_SESSION['gloidperfil']!='')
{		

	//Casos
		if($pag == 'visCasos.php' || $pag == 'insCasos.php' || $pag == 'modCasos.php'){
		$clase = 'class="menu "';
		}else{
		$clase = '';	
		}

	$menu = '<nav>
			<div class="contenedor" id="contenedorMenu" style="
    width: 1210px;">
				<ul class="menu">';

		
		$menu.='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/casos/visCasos.php" '.$clase.'>Casos EDT</a></li>';
			    $menu.='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/casos/visContactabilidadBusqueda.php" '.$clase.'>Contactabilidad</a></li>';
	
	//Administración
	if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		if($pag == 'visUsuario.php' || $pag == 'insUsuario.php' || $pag == 'modUsuario.php' ||
		$pag == 'visPerfil.php' || $pag == 'insPerfil.php' || $pag == 'modPerfil.php' ||
		$pag == 'visConfiguracion.php' || $pag == 'insConfiguracion.php' || $pag == 'modConfiguracion.php')
		$clase = 'class="ui-state-default ui-corner-top "';
		else
		$clase = '';
		
		$menu.='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/administracion/visUsuario.php">Administraci&oacute;n</a></li>';
	}
	
	$menu.= '</ul>
			</div>
		</nav>';
	echo $menu;
}
else {
	session_destroy();
	header('location: ../index.php');
}
?>
