<?php
session_start();
$data = array("success" => true);

if(isset($_POST['codigo']))
$_SESSION['filtroCA_codigo'] = $_POST['codigo'];

if(isset($_POST['delito']))
$_SESSION['filtroCA_delito'] = $_POST['delito'];

if(isset($_POST['profesional']))
$_SESSION['filtroCA_profesional'] = $_POST['profesional'];

if(isset($_POST['etapa']))
$_SESSION['filtroCA_etapa'] = $_POST['etapa'];

if(isset($_POST['rut']))
$_SESSION['filtroCA_rut'] = $_POST['rut'];

if(isset($_POST['nombre']))
$_SESSION['filtroCA_nombre'] = $_POST['nombre'];

if(isset($_POST['viaingreso']))
$_SESSION['filtroCA_viaingreso'] = $_POST['viaingreso'];

if(isset($_POST['region']))
$_SESSION['filtroCA_region'] = $_POST['region'];

if(isset($_POST['comuna']))
$_SESSION['filtroCA_comuna'] = $_POST['comuna'];

if(isset($_POST['numcaso']))
$_SESSION['filtroCA_numcaso'] = $_POST['numcaso'];

/*if(isset($_POST['fnacimiento']))
$_SESSION['filtroCA_fnacimiento'] = $_POST['fnacimiento'];*/

if(isset($_POST['direccion']))
$_SESSION['filtroCA_direccion'] = $_POST['direccion'];

if(isset($_POST['finsercion_inicio']))
$_SESSION['filtroCA_finsercion_inicio'] = $_POST['finsercion_inicio'];

if(isset($_POST['finsercion_fin']))
$_SESSION['filtroCA_finsercion_fin'] = $_POST['finsercion_fin'];

echo json_encode($data);
?>