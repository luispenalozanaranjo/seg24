<?php
session_start();
$_SESSION['mensaje']=2;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!='' && isset($_SESSION['idetapa']) && $_SESSION['idetapa']!=''){
		
	require_once('../clases/Analisis.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetAnalisis_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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
	
	//sanitiza variables	
	if($_POST['fecinicio']!='')
	$fecinicio	=	Util::formatFechaSQL2($_POST['fecinicio']);
	else
	$fecinicio = '';
	
	if($_POST['fectermino']!='')
	$fectermino	=	Util::formatFechaSQL2($_POST['fectermino']);
	else
	$fectermino = '';
	
	if(isset($_POST['chkevaluacion'])){
		$cadena='';
		foreach($_POST['chkevaluacion'] as $chkev)
		$cadena.=$chkev.',';
		$chkevaluacion = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkevaluacion = '';
	
	if(isset($_POST['chkmedidasnna'])){
		$cadena='';
		foreach($_POST['chkmedidasnna'] as $chkmed)
		$cadena.=$chkmed.',';
		$chkmedidasnna = substr($cadena,0,(strlen($cadena)-1));
	}
	else
	$chkmedidasnna = '';
	
		if(!isset($_POST['detalle_dificultad']))$_POST['detalle_dificultad']='';
		if(!isset($_POST['detalle_porobtener']))$_POST['detalle_porobtener']='';
		if(!isset($_POST['detalle_causal']))$_POST['detalle_causal']='';
		if(!isset($_POST['victima_especifica']))$_POST['victima_especifica']='';
		if(!isset($_POST['victima_vulnerable']))$_POST['victima_vulnerable']='';
		if(!isset($_POST['victima_repetida']))$_POST['victima_repetida']='';
		if(!isset($_POST['victima_desconocida']))$_POST['victima_desconocida']='';
		if(!isset($_POST['motivacion_racial']))$_POST['motivacion_racial']='';
		if(!isset($_POST['detalle_relacion']))$_POST['detalle_relacion']='';
		if(!isset($_POST['delito']))$_POST['delito']='';
		if(!isset($_POST['lugar_delito']))$_POST['lugar_delito']='';
		if(!isset($_POST['metodo_delito']))$_POST['metodo_delito']='';
		if(!isset($_POST['planificacion_delito']))$_POST['planificacion_delito']='';
		if(!isset($_POST['arma_delito']))$_POST['arma_delito']='';
		if(!isset($_POST['valor_delito']))$_POST['valor_delito']='';
		if(!isset($_POST['alcohol_delito']))$_POST['alcohol_delito']='';
		if(!isset($_POST['grupal_delito']))$_POST['grupal_delito']='';
		if(!isset($_POST['intencion_delito']))$_POST['intencion_delito']='';
		if(!isset($_POST['diferencias_delito']))$_POST['diferencias_delito']='';
		if(!isset($_POST['vulnerabilidad_delito']))$_POST['vulnerabilidad_delito']='';
		if(!isset($_POST['agravante_delito']))$_POST['agravante_delito']='';
		if(!isset($_POST['impacto_delito']))$_POST['impacto_delito']='';
		if(!isset($_POST['consecuencia_delito']))$_POST['consecuencia_delito']='';
		if(!isset($_POST['circunstancia_delito']))$_POST['circunstancia_delito']='';
		if(!isset($_POST['motivaciones_delito']))$_POST['motivaciones_delito']='';
		if(!isset($_POST['actitudes_delito']))$_POST['actitudes_delito']='';
		if(!isset($_POST['creencia_delito']))$_POST['creencia_delito']='';
		if(!isset($_POST['similitud_previa']))$_POST['similitud_previa']='';
		if(!isset($_POST['detalle_similitud']))$_POST['detalle_similitud']='';
		if(!isset($_POST['aumento_gravedad']))$_POST['aumento_gravedad']='';
		if(!isset($_POST['detalle_aumento']))$_POST['detalle_aumento']='';
		if(!isset($_POST['especializacion']))$_POST['especializacion']='';
		if(!isset($_POST['detalle_especializacion']))$_POST['detalle_especializacion']='';
		if(!isset($_POST['interrupcion']))$_POST['interrupcion']='';
		if(!isset($_POST['detalle_interrupcion']))$_POST['detalle_interrupcion']='';
		if(!isset($_POST['intentos_desistir']))$_POST['intentos_desistir']='';
		if(!isset($_POST['detalle_intentos']))$_POST['detalle_intentos']='';
		if(!isset($_POST['detalle_transgresion']))$_POST['detalle_transgresion']='';
		if(!isset($_POST['primera_detencion']))$_POST['primera_detencion']='';
		if(!isset($_POST['primera_condena']))$_POST['primera_condena']='';
		if(!isset($_POST['condenas_previas']))$_POST['condenas_previas']='';
		if(!isset($_POST['tiempo_medida']))$_POST['tiempo_medida']='';
		if(!isset($_POST['instancias_incumplimientos']))$_POST['instancias_incumplimientos']='';
		if(!isset($_POST['evidencia']))$_POST['evidencia']='';
 
	$obj = new Analisis(null);
	if($_POST['opcion']=='insert'){
			  
			  
		$rs = $obj->agregaAssetAnalisis($etapa,$_SESSION['idcaso'],$fecinicio,$fectermino,$_POST['detalle_dificultad'],
		$_POST['detalle_porobtener'],$_POST['detalle_causal'],$_POST['victima_especifica'],$_POST['victima_vulnerable'],$_POST['victima_repetida'],
		$_POST['victima_desconocida'],$_POST['motivacion_racial'],$_POST['detalle_relacion'],$_POST['delito'],$_POST['lugar_delito'],
		$_POST['metodo_delito'],$_POST['planificacion_delito'],$_POST['arma_delito'],$_POST['valor_delito'],$_POST['alcohol_delito'],
		$_POST['grupal_delito'],$_POST['intencion_delito'],$_POST['diferencias_delito'],$_POST['vulnerabilidad_delito'],$_POST['agravante_delito'],
		$_POST['impacto_delito'],$_POST['consecuencia_delito'],$_POST['circunstancia_delito'],$_POST['motivaciones_delito'],$_POST['actitudes_delito'],
		$_POST['creencia_delito'],$_POST['similitud_previa'],$_POST['detalle_similitud'],$_POST['aumento_gravedad'],$_POST['detalle_aumento'],
		$_POST['especializacion'],$_POST['detalle_especializacion'],$_POST['interrupcion'],$_POST['detalle_interrupcion'],$_POST['intentos_desistir'],
		$_POST['detalle_intentos'],$_POST['detalle_transgresion'],$_POST['primera_detencion'],$_POST['primera_condena'],$_POST['condenas_previas'],
		$_POST['tiempo_medida'],$_POST['instancias_incumplimientos'],$_POST['evidencia'],$chkevaluacion,$chkmedidasnna,$_POST['chkdetalleotro']);
		//print_r($rs);
		if( $rs > 0 ){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "analisis";
			$auditoria_sql = "agregaAssetAnalisis(".$_SESSION['idcaso'].",".$fecinicio.",".$fectermino.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	if($_POST['opcion']=='update'){
		$rs = $obj->modificaAssetAnalisis($etapa,$_SESSION['idcaso'],$fecinicio,$fectermino,$_POST['detalle_dificultad'],
		$_POST['detalle_porobtener'],$_POST['detalle_causal'],$_POST['victima_especifica'],$_POST['victima_vulnerable'],$_POST['victima_repetida'],
		$_POST['victima_desconocida'],$_POST['motivacion_racial'],$_POST['detalle_relacion'],$_POST['delito'],$_POST['lugar_delito'],
		$_POST['metodo_delito'],$_POST['planificacion_delito'],$_POST['arma_delito'],$_POST['valor_delito'],$_POST['alcohol_delito'],
		$_POST['grupal_delito'],$_POST['intencion_delito'],$_POST['diferencias_delito'],$_POST['vulnerabilidad_delito'],$_POST['agravante_delito'],
		$_POST['impacto_delito'],$_POST['consecuencia_delito'],$_POST['circunstancia_delito'],$_POST['motivaciones_delito'],$_POST['actitudes_delito'],
		$_POST['creencia_delito'],$_POST['similitud_previa'],$_POST['detalle_similitud'],$_POST['aumento_gravedad'],$_POST['detalle_aumento'],
		$_POST['especializacion'],$_POST['detalle_especializacion'],$_POST['interrupcion'],$_POST['detalle_interrupcion'],$_POST['intentos_desistir'],
		$_POST['detalle_intentos'],$_POST['detalle_transgresion'],$_POST['primera_detencion'],$_POST['primera_condena'],$_POST['condenas_previas'],
		$_POST['tiempo_medida'],$_POST['instancias_incumplimientos'],$_POST['evidencia'],$chkevaluacion,$chkmedidasnna,$_POST['chkdetalleotro']);
		
		if( $rs > 0 ){
			$_SESSION['mensaje']=1;
			/***************************/
			$auditoria_accion = "Update";
			$auditoria_tabla = "analisis";
			$auditoria_sql = "modificaAssetAnalisis(".$_SESSION['idcaso'].",".$fecinicio.",".$fectermino.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
	}
	
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-1");
	
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>