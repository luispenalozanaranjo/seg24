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
<div id="menuv">
    <ul>
            <li><a href="visCasos.php" <?php echo $class_usuario;?>>Casos</a></li>
            <li><a href="visPerfil.php" <?php echo $class_perfil;?>>Perfiles</a></li>
            <li><a href="visConfiguracion.php" <?php echo $class_configuracion;?>>Configuración</a></li>
    </ul>
</div>