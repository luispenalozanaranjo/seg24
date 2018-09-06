<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Relacion.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetRelacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	}else{
	
	if(isset($_POST['chkmiembros'])){
		$cadena='';
		foreach($_POST['chkmiembros'] as $chk)
		$cadena.=$chk.',';
		$chkmiembros = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkmiembros = '';
	
	if($_SESSION['idetapa']=='3'){
	$etapa = 1;
    } else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
    }

	$obj = new Relacion(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetRelacion($etapa,$_SESSION['idcaso'],$_POST['involucrado'],$_POST['experiencia'],$_POST['alcohol'],$_POST['testigo'],
		$_POST['drogas'],$_POST['duelo'],$_POST['comunicacion'],$_POST['cuidado'],$_POST['supervision'],$_POST['otros'],$_POST['evidencia'],
		$_POST['calificacion'],$chkmiembros);
		
		$obj->modificaCalificacionRelacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "relacion";
			$auditoria_sql = "agregaAssetRelacion(".$_SESSION['idcaso'].",".$_POST['involucrado'].",".$_POST['experiencia'].",".$_POST['calificacion'].")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetRelacion($etapa,$_SESSION['idcaso'],$_POST['involucrado'],$_POST['experiencia'],$_POST['alcohol'],$_POST['testigo'],
		$_POST['drogas'],$_POST['duelo'],$_POST['comunicacion'],$_POST['cuidado'],$_POST['supervision'],$_POST['otros'],$_POST['evidencia'],
		$_POST['calificacion'],$chkmiembros);
		
		$obj->modificaCalificacionRelacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Update";
			$auditoria_tabla = "relacion";
			$auditoria_sql = "modificaAssetRelacion(".$_SESSION['idcaso'].",".$_POST['involucrado'].",".$_POST['experiencia'].",".$_POST['calificacion'].")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-3");
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>