<?php
session_start();
//valida permisos de usuario
//include("../accesos.php");

$data	=	array("success" => false,"mensaje"=>"no pasa la validacion");	

$error	=	0;

if(in_array(1, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0) && isset($_GET['id']) && $_GET['id']>0 ){

	require_once('../clases/Casos.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
		
		//sanitiza variables
		$id	=	filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	
		$obj = new Casos(null);
		$obj->Begin();
		
		$nombre = $_SESSION['glonombre']." ".$_SESSION['glopaterno']." ".$_SESSION['glomaterno'];
		//$rs = $obj->eliminaCaso($id);
		if($obj->eliminaCaso($id)==0){
		$error++;
		$data = array("success" => false,"mensaje"=>"error al eliminaCaso");
		}
		
		if( $error == 0 ){
			$obj->Commit();
			$data = array("success" => true,"mensaje"=>"Caso eliminado correctamente");
			/***************INGRESO AUDITORIA************/
				$auditoria_accion = "Delete";
				$auditoria_tabla = "Caso";
				$auditoria_sql = "eliminaCaso(".$nombre.", Caso:".$id.")";
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

}
else{
	session_destroy();
	header('location: ../index.php');
}
?>