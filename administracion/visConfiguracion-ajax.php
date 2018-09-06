<?php
session_start();
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
		
$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";

if( isset($_POST['cierre_de']) && $_POST['cierre_de']!='' && isset($_POST['cierre_mst']) && $_POST['cierre_mst']!=''
	&& isset($_POST['verde_de']) && $_POST['verde_de']!='' && isset($_POST['verde_mst']) && $_POST['verde_mst']!='' 
	&& isset($_POST['amarilla_de']) && $_POST['amarilla_de']!='' && isset($_POST['amarilla_mst']) && $_POST['amarilla_mst']!='' 
	&& isset($_POST['roja_de']) && $_POST['roja_de']!='' && isset($_POST['roja_mst']) && $_POST['roja_mst']!=''
	&& isset($_POST['minimo']) && $_POST['minimo']!='' && isset($_POST['maximo']) && $_POST['maximo']!=''
	&& isset($_POST['gestor']) && $_POST['gestor']!='' && isset($_POST['puntaje']) && $_POST['puntaje']!='' ){
		
	require_once('../clases/Configuracion.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('insconfiguracion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
			
	//sanitiza variables
	$cierre_de	=	filter_var($_POST['cierre_de'], FILTER_SANITIZE_NUMBER_INT);
	$cierre_mst	=	filter_var($_POST['cierre_mst'], FILTER_SANITIZE_NUMBER_INT);
	$verde_de	=	filter_var($_POST['verde_de'], FILTER_SANITIZE_NUMBER_INT);
	$verde_mst	=	filter_var($_POST['verde_mst'], FILTER_SANITIZE_NUMBER_INT);
	$amarilla_de	=	filter_var($_POST['amarilla_de'], FILTER_SANITIZE_NUMBER_INT);
	$amarilla_mst	=	filter_var($_POST['amarilla_mst'], FILTER_SANITIZE_NUMBER_INT);
	$roja_de	=	filter_var($_POST['roja_de'], FILTER_SANITIZE_NUMBER_INT);
	$roja_mst	=	filter_var($_POST['roja_mst'], FILTER_SANITIZE_NUMBER_INT);
	$minimo	=	filter_var($_POST['minimo'], FILTER_SANITIZE_NUMBER_INT);
	$maximo	=	filter_var($_POST['maximo'], FILTER_SANITIZE_NUMBER_INT);
	$puntaje	=	filter_var($_POST['puntaje'], FILTER_SANITIZE_NUMBER_INT);
	$gestor	=	filter_var($_POST['gestor'], FILTER_SANITIZE_STRING);	
	
	$obj = new Configuracion(null);
	$resultado = $obj->modificaConfiguracion($cierre_de,$verde_de,$amarilla_de,$roja_de,$cierre_mst,$verde_mst,$amarilla_mst,$roja_mst,$puntaje,$minimo,$maximo,$gestor);
	$obj->Close();

	if( $resultado > 0 ){
		$data = array("success" => true);
		/***************************/
		$auditoria_accion = "Update";
		$auditoria_tabla = "configuracion";
		$auditoria_sql = "modificaConfiguracion(".$cierre_de.",".$verde_de.",".$amarilla_de.",".$roja_de.",".$cierre_mst.",".$verde_mst.",".$amarilla_mst.",".$roja_mst.",".$puntaje.",".$minimo.",".$maximo.",".$gestor.")";
		$auditoria_meta = "Post";
		$auditoria = new Auditoria(null);
		$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
		$auditoria->Close();
		/***************************/
	}
	echo json_encode($data);
}	
else{
	session_destroy();
	header('location: ../index.php');
}

 }else{
	session_destroy();
	header('location: ../index.php');
}
?>