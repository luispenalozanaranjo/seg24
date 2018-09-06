<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
		
	require_once('../clases/Educacion.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetEducacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	}else{
	
	if(isset($_POST['chkeducacion'])){
		$cadena='';
		foreach($_POST['chkeducacion'] as $chk)
		$cadena.=$chk.',';
		$chkeducacion = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkeducacion = '';	
	
	if(isset($_POST['chkinasistencia'])){
		$cadena='';
		foreach($_POST['chkinasistencia'] as $chk)
		$cadena.=$chk.',';
		$chkinasistencia = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkinasistencia = '';
	
	if($_SESSION['idetapa']=='3'){
	$etapa = 1;
    } else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
    }

	$obj = new Educacion(null);
	if($_POST['opcion']=='insert'){
		$rs = $obj->agregaAssetEducacion($etapa,$_SESSION['idcaso'],$_POST['horasdedicadas'],$_POST['horasefectivas'],$_POST['inasistencia'],
		$_POST['complementarios'],$_POST['alfabetizacion'],$_POST['necesidades'],$_POST['aritmeticas'],$_POST['certificado'],
		$_POST['evidencia1'],$_POST['actitudnegativa'],$_POST['relacionpobre'],$_POST['faltaadherencia'],$_POST['actitudpadres'],
		$_POST['victimabullying'],$_POST['victimariobullying'],$_POST['otro'],$_POST['evidencia2'],$_POST['calificacion'],
		$chkeducacion,$_POST['chkdetalleotro'],$chkinasistencia,$_POST['chkdetalleotrasinasistencias']);
		print_r($rs);
		
		$obj->modificaCalificacionEducacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Insert";
			$auditoria_tabla = "educacion";
			$auditoria_sql = "agregaAssetEducacion(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetEducacion($etapa,$_SESSION['idcaso'],$_POST['horasdedicadas'],$_POST['horasefectivas'],$_POST['inasistencia'],
		$_POST['complementarios'],$_POST['alfabetizacion'],$_POST['necesidades'],$_POST['aritmeticas'],$_POST['certificado'],
		$_POST['evidencia1'],$_POST['actitudnegativa'],$_POST['relacionpobre'],$_POST['faltaadherencia'],$_POST['actitudpadres'],
		$_POST['victimabullying'],$_POST['victimariobullying'],$_POST['otro'],$_POST['evidencia2'],$_POST['calificacion'],
		$chkeducacion,$_POST['chkdetalleotro'],$chkinasistencia,$_POST['chkdetalleotrasinasistencias']);
		
		$obj->modificaCalificacionEducacion($etapa,$_SESSION['idcaso'],$_POST['calificacion']);
		
		if($rs > 0){
			$_SESSION['mensaje']=1;
			/***************************
			$auditoria_accion = "Update";
			$auditoria_tabla = "educacion";
			$auditoria_sql = "modificaAssetEducacion(".$idcaso.",".$fvisita.",".$resultado.",".$profesional.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-4");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>