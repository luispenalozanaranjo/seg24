<?php
session_start();
$data = array();

if(in_array(8, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][6] == 1 || $_SESSION['glopermisos']['lectura'][6] == 1) && $_SESSION['gloidperfil']==3){
	
	require_once('../clases/Casos.class.php');
	$idcaso	= filter_var($_GET['caso'], FILTER_SANITIZE_NUMBER_INT);
	
	$obj = new Casos(null);
	$resultado = $obj->entregaCasoV2($idcaso);
	$obj->Close();

	if($row = $resultado){
		$data = array(
		  "motivo" => $row['sol_motivo'],
		  "observacion" => $row['sol_observacion'],
		  "usuario" => $row['us_nombre'].' '.$row['us_paterno'].' '.$row['us_materno']
		);
	}
	
	echo json_encode($data);
}
else
session_destroy();
?>