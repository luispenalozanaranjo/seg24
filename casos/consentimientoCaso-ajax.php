<?php
session_start();
//valida permisos de usuario
//include("../accesos.php");

$data	=	array("success" => false,"mensaje"=>"no pasa la validacion");	

$error	=	0;


/*print $_SESSION['glopermisos']['escritura'][0].'   '.$_SESSION['glopermisos']['lectura'][0].'    '.$_GET['id'].'    '.in_array(1, $_SESSION['glopermisos']['modulo']);
show_($_GET['id'],TRUE);*/

	require_once('../clases/Casos.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
		
		//sanitiza variables
		$id	    =	filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$idcaso	=	filter_var($_GET['idcaso'], FILTER_SANITIZE_NUMBER_INT);
	
		$obj = new Casos(null);
		$obj->Begin();
		//echo $id;
		$nombre = $_SESSION['glonombre']." ".$_SESSION['glopaterno']." ".$_SESSION['glomaterno'];
		//$rs = $obj->eliminaCaso($id);
		//print $_GET['etapa'];
		
		if(($obj->eliminaConsentimientoCasoVisita($id,$idcaso)==0)){
		$error++;
		
		$data = array("success" => false,"mensaje"=>"error al eliminaCaso");
		}
	
		if( $error == 0 ){
			$obj->Commit();
			$data = array("success" => true,"mensaje"=>"Consentimiento eliminado correctamente");
			
			/***************INGRESO AUDITORIA************/
				$auditoria_accion = "Delete";
				$auditoria_tabla = "Caso";
				$auditoria_sql = "eliminaConsentimientoCasoVisita(".$id.",".$idcaso.")";
				$auditoria_meta = "Get";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				$auditoria->Close();
			/***************INGRESO AUDITORIA************/	
			
		}else{
			$obj->Rollback();
			}
			
		$obj->Close();	
		
	echo json_encode($data);


?>