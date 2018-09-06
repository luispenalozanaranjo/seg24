<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/SaludMental.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetSaludMental_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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

	$obj = new SaludMental(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetSaludMental($etapa,$_SESSION['idcaso'],$_POST['acontecimientos'],$_POST['circunstancias'],$_POST['preocupaciones'],
		$_POST['evidencia1'],$_POST['diagnostico'],$_POST['derivacion'],$_POST['evidencia2'],$_POST['afectado'],$_POST['provocadano'],
		$_POST['suicidio'],$_POST['evidencia3'],$_POST['calificacion']);
		
		$obj->modificaCalificacionSaludMental($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Insert";
			$auditoria_tabla = "salud_mental";
			$auditoria_sql = "agregaAssetSaludMental(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetSaludMental($etapa,$_SESSION['idcaso'],$_POST['acontecimientos'],$_POST['circunstancias'],$_POST['preocupaciones'],
		$_POST['evidencia1'],$_POST['diagnostico'],$_POST['derivacion'],$_POST['evidencia2'],$_POST['afectado'],$_POST['provocadano'],
		$_POST['suicidio'],$_POST['evidencia3'],$_POST['calificacion']);
		
		$obj->modificaCalificacionSaludMental($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Update";
			$auditoria_tabla = "salud_mental";
			$auditoria_sql = "modificaAssetSaludMental(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-9");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>