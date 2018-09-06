<?php
session_start();
$var = explode("/",$_SERVER['PHP_SELF']);
$pag = $var[count($var)-1];

$class_analisis 	= '';
$class_hogar 	= '';
$class_relacion 	= '';
$class_educacion 	= '';
$class_barrio 	= '';
$class_estilo 	= '';
$class_sustancias 	= '';
$class_salud 	= '';
$class_salud2 	= '';
$class_percepcion 	= '';
$class_comportamiento 	= '';
$class_actitud 	= '';
$class_motivacion 	= '';
$class_resumen 	= '';
$class_revision = '';
$estado = '';

if ($pag == 'visAssetAnalisis.php')
$class_analisis = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetHogar.php')
$class_hogar = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetRelacion.php')
$class_relacion = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetEducacion.php')
$class_educacion = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetBarrio.php')
$class_barrio = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetEstilo.php')
$class_estilo = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetSustancias.php')
$class_sustancias = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetSalud.php')
$class_salud = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetSaludMental.php')
$class_salud2 = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetPercepcion.php')
$class_percepcion = 'class="ui-tabs-anchorn"';
if ($pag == 'visAssetComportamiento.php')
$class_comportamiento = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetActitud.php')
$class_actitud = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetMotivacion.php')
$class_motivacion = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetEvaluacion.php')
$class_resumen = 'class="ui-tabs-anchor"';
if ($pag == 'visAssetRevision.php')
$class_revision = 'class="ui-tabs-anchor"';

$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$idetapa =  filter_var($_GET['idetapa'], FILTER_SANITIZE_STRING);
$_SESSION['estado']=1;
$_SESSION['idcaso'] = $idcaso;
$_SESSION['idetapa'] = $idetapa;

if($_SESSION['idetapa']=='3'){
	$_SESSION['estado'] = 1;
} else if($_SESSION['idetapa']=='5'){
	$_SESSION['estado'] = 2;
}

require_once('../clases/Evaluacion.class.php');
$obj = new Evaluacion(null);
$rs = $obj->entregaAssetEvaluacion($idcaso,$_SESSION['estado']);
foreach( $rs as $res )
$estado = $res['re_estado'];

$obj->Close();
?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="../images/gob.jpg" rel="icon" type="image/x-icon" />
    <title><?php echo $_SESSION['glosistema'];?></title>
		<link rel="stylesheet" type="text/css" href="../css/global.css" />
		<link rel="stylesheet" type="text/css" href="../css/grilla.css">
		<link rel="stylesheet" type="text/css" href="../css/grilla-base.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
        
<script type="text/javascript">
setTimeout ("redireccionar('../index.php')", <?php echo $_SESSION['gloexpiracion'];?>); //tiempo expresado en milisegundos
</script>
<script src="../js/jquery-1.12.4.js"></script>
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/datepicker/js/jquery.ui.datepicker-es.js"></script>
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
</head>
<body>
<?php include('../header.php');?>
<?php require_once('../menu.php');?>
<br>
<div id="tabs" class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
    <li><a href="#tabs-1" <?php print $class_analisis; ?>>Analisis</a></li>
    <li><a href="#tabs-2" <?php print $class_hogar; ?>>Hogar</a></li>
    <li><a href="#tabs-3" <?php print $class_relacion; ?>>Relaciones</a></li>
    <li><a href="#tabs-4" <?php print $class_educacion; ?>>Educacion</a></li>
    <li><a href="#tabs-5" <?php print $class_barrio; ?>>Barrio</a></li>
    <li><a href="#tabs-6" <?php print $class_estilo; ?>>Estilo Vida</a></li>
    <li><a href="#tabs-7" <?php print $class_sustancias; ?>>Sustancias</a></li>
    <li><a href="#tabs-8" <?php print $class_salud; ?>>S. Fisica</a></li>
    <li><a href="#tabs-9" <?php print $class_salud2 ?>>S. Mental</a></li>
    <li><a href="#tabs-10" <?php print $class_percepcion ?>>Percepcion</a></li>
    <li><a href="#tabs-11" <?php print $class_comportamiento ?>>Comportamiento</a></li>
    <li><a href="#tabs-12" <?php print $class_actitud; ?>>Actitud</a></li>
    <li><a href="#tabs-13" <?php print $class_motivacion; ?>>Motivacion</a></li>
    <li><a href="#tabs-14" <?php print $class_resumen; ?>>Resumen</a></li>
    <?php if($estado!=''){ print "<li style='border:groove;border-color:#3bafe2'><a href='#tabs-15' $class_revision; >Revision</a></li>"; } ?>
  </ul>
  <div id="tabs-1">
    <?php require_once('visAssetAnalisis.php'); ?>
  </div>
  <div id="tabs-2">
    <?php require_once('visAssetHogar.php'); ?>
  </div>
  <div id="tabs-3">
    <?php require_once('visAssetRelacion.php'); ?>
  </div>
  <div id="tabs-4">
    <?php require_once('visAssetEducacion.php'); ?>
  </div>
  <div id="tabs-5">
    <?php require_once('visAssetBarrio.php'); ?>
  </div>
    <div id="tabs-6">
    <?php require_once('visAssetEstilo.php'); ?>
  </div>
    <div id="tabs-7">
    <?php require_once('visAssetSustancias.php'); ?>
  </div>
    <div id="tabs-8">
    <?php require_once('visAssetSalud.php'); ?>
  </div>
    <div id="tabs-9">
    <?php require_once('visAssetSaludMental.php'); ?>
  </div>
    <div id="tabs-10">
    <?php require_once('visAssetPercepcion.php'); ?>
  </div>
    <div id="tabs-11">
    <?php require_once('visAssetComportamiento.php'); ?>
  </div>
    <div id="tabs-12">
    <?php require_once('visAssetActitud.php'); ?>
  </div>
    <div id="tabs-13">
    <?php require_once('visAssetMotivacion.php'); ?>
  </div>
    <div id="tabs-14">
    <?php require_once('visAssetEvaluacion.php'); ?>
  </div>
  <?php if($estado!=''){ 
         print "<div id='tabs-15'>";  ?>
   <?php require_once('visAssetRevision.php'); ?>
   <?php print "</div>"; } ?>
</div>
</div>
<?php 
if(isset($_SESSION['mensaje'])){
	if($_SESSION['mensaje']==2){?>
	<script>
	alert("Ocurrió un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	<?php }
	$_SESSION['mensaje']="";
}?>
</body>
</html>