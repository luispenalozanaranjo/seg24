<?php
$var = explode("/",$_SERVER['PHP_SELF']);
$pag = $var[count($var)-1];

$class_usuario 	= '';
$class_perfil 	= '';
$class_configuracion 	= '';

if (strpos($pag, 'Usuario') == true)
$class_activo = 'class="seleccion"';
if (strpos($pag, 'Perfil') == true)
$class_admin = 'class="seleccion"';
if (strpos($pag, 'Configuracion') == true)
$class_dueno = 'class="seleccion"';
?>
<a href="visUsuario.php" <?php echo $class_usuario;?>>Usuarios</a>&nbsp;&nbsp;|
&nbsp;&nbsp;<a href="visPerfil.php" <?php echo $class_perfil;?>>Perfiles</a>&nbsp;&nbsp;|
&nbsp;&nbsp;<a href="visConfiguracion.php" <?php echo $class_configuracion;?>>Configuraci&oacute;n</a>&nbsp;&nbsp;