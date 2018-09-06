<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Hogar.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetHogar_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	}else{	
	
	if(isset($_POST['chkviviendanna'])){
		$cadena='';
		foreach($_POST['chkviviendanna'] as $chk)
		$cadena.=$chk.',';
		$chkviviendanna = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkviviendanna = '';	

    if($_SESSION['idetapa']=='3'){
	$etapa = 1;
    } else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
    }

	$obj = new Hogar(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetHogar($etapa,$_SESSION['idcaso'],$_POST['condicion'],$_POST['sin_domicilio'],$_POST['incumplimiento'],$_POST['hogar_deprivado'],$_POST['vive_delincuentes'],$_POST['situacion_calle'],$_POST['desorganizado'],$_POST['otros'],$_POST['evidencia'],$_POST['calificacion'],$chkviviendanna);
		
		$obj->modificaCalificacionHogar($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "hogar";
			$auditoria_sql = "agregaAssetHogar(".$_SESSION['idcaso'].",".$_POST['condicion'].",".$_POST['sin_domicilio'].",".$_POST['calificacion'].")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetHogar($etapa,$_SESSION['idcaso'],$_POST['condicion'],$_POST['sin_domicilio'],$_POST['incumplimiento'],
		$_POST['hogar_deprivado'],$_POST['vive_delincuentes'],$_POST['situacion_calle'],$_POST['desorganizado'],$_POST['otros'],
		$_POST['evidencia'],$_POST['calificacion'],$chkviviendanna);
		
		$obj->modificaCalificacionHogar($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Update";
			$auditoria_tabla = "hogar";
			$auditoria_sql = "modificaAssetHogar(".$_SESSION['idcaso'].",".$_POST['condicion'].",".$_POST['sin_domicilio'].",".$_POST['calificacion'].")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-2");
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>