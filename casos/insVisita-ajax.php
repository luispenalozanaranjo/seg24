<?php
session_start();

if (in_array(3, $_SESSION['glopermisos']['modulo']) && 
($_SESSION['glopermisos']['escritura'][2] == 1 || $_SESSION['glopermisos']['lectura'][2] == 1 )){
	
$_SESSION['mensaje']=2;
$subida = 0;
//&& isset($_POST['hora']) && $_POST['hora']!='' && isset($_POST['minuto']) && $_POST['minuto']!=''
if( isset($_POST['idcaso']) && $_POST['idcaso']!='' && isset($_POST['fecha']) && $_POST['fecha']!='' && isset($_POST['resultado']) && $_POST['resultado']!=''){
		
	require_once('../clases/Visita.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	require_once('../clases/Trazabilidad.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('visita_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
		//echo "error de token";
	} else {	
		//sanitiza variables
		$idcaso	=	filter_var($_POST['idcaso'], FILTER_SANITIZE_NUMBER_INT);
		//$hora	=	filter_var($_POST['hora'], FILTER_SANITIZE_NUMBER_INT);
		$hora = '00';
		$minuto = '00';
		//$minuto	=	filter_var($_POST['minuto'], FILTER_SANITIZE_NUMBER_INT);
		$resultado	=	filter_var($_POST['resultado'], FILTER_SANITIZE_NUMBER_INT);
		$fecha	=	Util::formatFechaSQL2($_POST['fecha']).' '.$hora.':'.$minuto;
		$idvisita	=	filter_var($_POST['idvisita'], FILTER_SANITIZE_NUMBER_INT);
		
		if(isset($_POST['rut']))
		$rut	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		else
		$rut = '';
		
		if(isset($_POST['nombre']))
		$nombre	=	filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
		else
		$nombre = '';
		
		if(isset($_POST['fnacimiento']))
		$fnacimiento = Util::formatFechaSQL2($_POST['fnacimiento']);
		else
		$fnacimiento = '';
		
		if(isset($_POST['parentezco']))
		$parentezco = $_POST['parentezco'];
		else
		$parentezco = '';
		
		if(isset($_POST['fono']))
		$fono = $_POST['fono'];
		else
		$fono = '';
		
		if(isset($_POST['sugerencias']))
		$sugerencias	=	filter_var($_POST['sugerencias'], FILTER_SANITIZE_STRING);
		else
		$sugerencias = '';
				
		$uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/archivos';	
		$name="";
		if ( !file_exists($uploads_dir) )
		{
			mkdir($uploads_dir);
			chmod($uploads_dir, 0777);
		}else{
			chmod($uploads_dir, 0777);
		}
			
		if( filesize($_FILES["archivo"]["tmp_name"])>0 && filesize($_FILES["archivo"]["tmp_name"])<=16777216 && $_FILES["archivo"]["name"]!='' ){
			$tmp_name = $_FILES["archivo"]["tmp_name"];
			$extension_archivo  = Util::extensionArchivo($_FILES["archivo"]["name"]);
			$mime = $_FILES["archivo"]['type'];
			$tam2 =  $_FILES["archivo"]['size'];
			$tam =($tam2/1024);
			$name = md5($_FILES['archivo']['name']."-". date('Y-m-d H:i:s')).".".$extension_archivo;
			//$name = "Consentimiento_".$idcaso.".".$extension_archivo;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name"))
			$subida = 1;
		}
		else
		$name = '';
	
		$obj = new Visita(null);
		$obj->Begin();
		
		if($idvisita>0)
		$rs = $obj->modificaVisitaCaso($idcaso,$idvisita,$fecha,$resultado,$_SESSION['glorut'],$rut,$nombre,$fnacimiento,$parentezco,$fono,$name,$sugerencias);
		else
		$rs = $obj->agregaVisitaCaso($idcaso,$fecha,$resultado,$_SESSION['glorut'],$rut,$nombre,$fnacimiento,$parentezco,$fono,$name,$sugerencias);
		
		//print_r($rs);
		
		if($subida == 1){
			$tr=new Trazabilidad(null);
			$traza=$tr->entregaEtapaCaso($idcaso);
			$traza_cant=$tr->cantidadEntregaCaso($idcaso);
			foreach($traza as $t)
			
			if(count($traza_cant) <= 2){
			$obj->ingresaArchivoCaso($idcaso,$name,$tam,$extension_archivo,$uploads_dir,$mime,1);
			$rs2 = $obj->modificaCasoEtapa($idcaso,'Evaluacion');
			
			} else {
			$obj->ingresaArchivoCaso($idcaso,$name,$tam,$extension_archivo,$uploads_dir,$mime,1);
			$rs2 = $obj->modificaCasoEtapa($idcaso,$t['tr_estado']/*"'".$t['tr_estado']."'"*/);
			
			}
		}
		else
		$rs2 = 1;
	    
		if( $rs > 0 && $rs2 > 0){
			$obj->Commit();
			$_SESSION['mensaje']=1;
			
			if($subida == 1){
			/********************INICIO INGRESO TRAZABILIDAD*****************************/
			$nomuser = $_SESSION['glonombre'].' '.$_SESSION['glopaterno'].' '.$_SESSION['glomaterno'];
				require_once('../clases/Trazabilidad.class.php');
				$trazabilidad = new Trazabilidad(null);
				/*If hacerlo aca*/
				if($t['tr_estado']=='Contactabilidad'){
				$resultado5 = $trazabilidad->agregaTrazabilidad('Evaluacion',$idcaso,$_SESSION['glorut'],$nomuser);
				}
				if($t['tr_estado']=='Evaluacion'){
				$resultado5 = $trazabilidad->agregaTrazabilidad('Derivacion',$idcaso,$_SESSION['glorut'],$nomuser);
				}
				if($t['tr_estado']=='Derivacion'){
				$resultado5 = $trazabilidad->agregaTrazabilidad('Reevaluacion',$idcaso,$_SESSION['glorut'],$nomuser);
				}
				if($t['tr_estado']=='Reevaluacion'){
				$resultado5 = $trazabilidad->agregaTrazabilidad('Cierre',$idcaso,$_SESSION['glorut'],$nomuser);
				}
				if($t['tr_estado']=='Cierre'){
				$resultado5 = $trazabilidad->agregaTrazabilidad('Finalizado',$idcaso,$_SESSION['glorut'],$nomuser);
				}
				$trazabilidad->Close();
			/********************FIN INGRESO TRAZABILIDAD*****************************/
			}
			
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "visita";
			
			if($idvisita>0)
			$auditoria_sql = "modificaVisitaCaso(".$idcaso.",".$idvisita.",".$fecha.",".$resultado.",".$_SESSION['glorut'].",".$rut.",".$nombre.",".$fnacimiento.",".$parentezco.",".$fono.",".$name.")";
			else
			$auditoria_sql = "agregaVisitaCaso(".$idcaso.",".$fecha.",".$resultado.",".$_SESSION['glorut'].",".$rut.",".$nombre.",".$fnacimiento.",".$parentezco.",".$fono.",".$name.")";
			
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}
		else
		$obj->Rollback();
		
		$obj->Close();
	}
	header("location: visContactabilidad.php?id=".$idcaso);
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