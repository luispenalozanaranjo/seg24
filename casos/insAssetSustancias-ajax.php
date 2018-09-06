<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Sustancias.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetSustancias_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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

	$obj = new Sustancias(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetSustancias($etapa,$_SESSION['idcaso'],$_POST['tabaco'],$_POST['tabaco_edad'],$_POST['alcohol'],$_POST['alcohol_edad'],
		$_POST['solventes'],$_POST['solventes_edad'],$_POST['cannabis'],$_POST['cannabis_edad'],$_POST['pastabase'],$_POST['pastabase_edad'],
		$_POST['cocaina'],$_POST['cocaina_edad'],$_POST['anfetamina'],$_POST['anfetamina_edad'],$_POST['tranquilizante'],$_POST['tranquilizante_edad'],
		$_POST['extasis'],$_POST['extasis_edad'],$_POST['lcd'],$_POST['lcd_edad'],$_POST['inhalantes'],$_POST['inhalantes_edad'],$_POST['crack'],
		$_POST['crack_edad'],$_POST['heroina'],$_POST['heroina_edad'],$_POST['metadona'],$_POST['metadona_edad'],$_POST['esteroides'],
		$_POST['esteroides_edad'],$_POST['otros'],$_POST['otros_edad'],$_POST['nnariesgo'],$_POST['usopositivo'],$_POST['educacion'],
		$_POST['infracciones'],$_POST['otro'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionSustancias($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Insert";
			$auditoria_tabla = "sustancias";
			$auditoria_sql = "agregaAssetSustancias(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetSustancias($etapa,$_SESSION['idcaso'],$_POST['tabaco'],$_POST['tabaco_edad'],$_POST['alcohol'],$_POST['alcohol_edad'],
		$_POST['solventes'],$_POST['solventes_edad'],$_POST['cannabis'],$_POST['cannabis_edad'],$_POST['pastabase'],$_POST['pastabase_edad'],
		$_POST['cocaina'],$_POST['cocaina_edad'],$_POST['anfetamina'],$_POST['anfetamina_edad'],$_POST['tranquilizante'],$_POST['tranquilizante_edad'],
		$_POST['extasis'],$_POST['extasis_edad'],$_POST['lcd'],$_POST['lcd_edad'],$_POST['inhalantes'],$_POST['inhalantes_edad'],$_POST['crack'],
		$_POST['crack_edad'],$_POST['heroina'],$_POST['heroina_edad'],$_POST['metadona'],$_POST['metadona_edad'],$_POST['esteroides'],
		$_POST['esteroides_edad'],$_POST['otros'],$_POST['otros_edad'],$_POST['nnariesgo'],$_POST['usopositivo'],$_POST['educacion'],
		$_POST['infracciones'],$_POST['otro'],$_POST['evidencia'],$_POST['calificacion']);
		
		$obj->modificaCalificacionSustancias($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Update";
			$auditoria_tabla = "sustancias";
			$auditoria_sql = "modificaAssetSustancias(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-7");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>