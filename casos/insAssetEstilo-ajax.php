<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Estilo.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetEstilo_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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

	$obj = new Estilo(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetEstilo($etapa,$_SESSION['idcaso'],$_POST['faltaamistad'],$_POST['actividadriesgo'],$_POST['asocpredominante'],
		$_POST['dineroinsuficiente'],$_POST['faltaasociacion'],$_POST['tiempolibre'],$_POST['otro'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionEstilo($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Insert";
			$auditoria_tabla = "estilo";
			$auditoria_sql = "agregaAssetEstilo(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetEstilo($etapa,$_SESSION['idcaso'],$_POST['faltaamistad'],$_POST['actividadriesgo'],$_POST['asocpredominante'],
		$_POST['dineroinsuficiente'],$_POST['faltaasociacion'],$_POST['tiempolibre'],$_POST['otro'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionEstilo($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Update";
			$auditoria_tabla = "estilo";
			$auditoria_sql = "modificaAssetEstilo(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-6");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>