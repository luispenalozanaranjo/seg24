<?php
session_start();
$data = array("success" => true);

if(isset($_POST['nombre']))
$_SESSION['filtroUS_nombre'] = $_POST['nombre'];

if(isset($_POST['estado']))
$_SESSION['filtroUS_estado'] = $_POST['estado'];

if(isset($_POST['perfil']))
$_SESSION['filtroUS_perfil'] = $_POST['perfil'];

echo json_encode($data);
?>