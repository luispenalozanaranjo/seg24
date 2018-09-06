<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Motivacion.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetMotivacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	}else{
		
	if($_SESSION['idetapa']=='3'){
	$etapa = 1;
    } else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
    }

	$obj = new Motivacion(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetMotivacion($etapa,$_SESSION['idcaso'],$_POST['comprendecomportamiento'],$_POST['resolverproblemas'],
		$_POST['comprendeconsecuencias'],$_POST['identificaincentivos'],$_POST['muestraevidencia'],$_POST['apoyofamiliar'],
		$_POST['cooperacion'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionMotivacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Insert";
			$auditoria_tabla = "motivacion";
			$auditoria_sql = "agregaAssetMotivacion(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetMotivacion($etapa,$_SESSION['idcaso'],$_POST['comprendecomportamiento'],$_POST['resolverproblemas'],
		$_POST['comprendeconsecuencias'],$_POST['identificaincentivos'],$_POST['muestraevidencia'],$_POST['apoyofamiliar'],
		$_POST['cooperacion'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionMotivacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Update";
			$auditoria_tabla = "motivacion";
			$auditoria_sql = "modificaAssetMotivacion(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-13");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>