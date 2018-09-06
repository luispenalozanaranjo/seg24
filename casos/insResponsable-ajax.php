<?php
session_start();

//$data = array("success" => false);
$_SESSION['mensaje']=2;
$token	=	"";
$valida	=	"";
$falla 	= 	"";
$subida = 0;

if( isset($_POST['idcaso']) && $_POST['idcaso']!='' && isset($_POST['rut']) && $_POST['rut']!='' 
	&& isset($_POST['nombre']) && $_POST['nombre']!='' && isset($_POST['opcion']) && $_POST['opcion']!=''){
	require_once('../clases/Responsable.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('responsable_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	$valida = true;
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	} else {
		//sanitiza variables
		$idcaso		=	filter_var($_POST['idcaso'], FILTER_SANITIZE_NUMBER_INT);
		$rut	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		$nombre	=	filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
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
		
		if(isset($_POST['ffirma']))
		$ffirma = Util::formatFechaSQL2($_POST['ffirma']);
		else
		$ffirma = '';
				
		$uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/archivos';	
		$name="";
		if ( !file_exists($uploads_dir) )
		{
			mkdir($uploads_dir);	
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
		$name = $_POST['archivo_old'];
		
		$obj = new Responsable(null);	
		$obj->Begin();
		
		if($_POST['opcion'] == 'insert'){
			$resultado = $obj->ingresaResponsablesCaso($idcaso,$rut,$nombre,$fnacimiento,$parentezco,$fono,$ffirma);
	
			if( $resultado > 0 ){
				
				if($subida == 1)
				$resultado2 = $obj->ingresaArchivoCaso($idcaso,$name,$tam,$extension_archivo,$uploads_dir,$mime,1);
				else
				$resultado2 = 1;
				
				if($resultado2>0){
					$_SESSION['mensaje']=1;
					$obj->Commit();
					/***************************/
					/*$auditoria_accion="Update";
					$auditoria_tabla="acta";
					$auditoria_sql="modificaActa(".$val_rutentrega.",".$val_dventrega.",".$val_rutrecibe.",".$val_dvrecibe.",".$nota.",".$datepicker.",".$name.",".$_SESSION['glorut'].",".$_SESSION['glorutdiv'].",".$idacta.")";
					$auditoria_meta="Post";
					$auditoria = new Auditoria(null);
					$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);*/
					/***************************/
				}
				else			
				$obj->Rollback();
			}
			else			
			$obj->Rollback();
		}
		
		if($_POST['opcion'] == 'update'){
			$resultado = $obj->modificaResponsablesCaso($idcaso,$rut,$nombre,$fnacimiento,$parentezco,$fono,$ffirma);

			if($subida == 1)
			$resultado2 = $obj->modificaArchivoCaso($idcaso,$name,$tam,$extension_archivo,$uploads_dir,$mime,1);
			else
			$resultado2 = 1;

			if($resultado > 0 || $resultado2 > 0){
				$_SESSION['mensaje']=1;
				$obj->Commit();
					/***************************/
					/*$auditoria_accion="Update";
					$auditoria_tabla="acta";
					$auditoria_sql="modificaActa(".$val_rutentrega.",".$val_dventrega.",".$val_rutrecibe.",".$val_dvrecibe.",".$nota.",".$datepicker.",".$name.",".$_SESSION['glorut'].",".$_SESSION['glorutdiv'].",".$idacta.")";
					$auditoria_meta="Post";
					$auditoria = new Auditoria(null);
					$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);*/
					/***************************/
			}
			else			
			$obj->Rollback();
		}
		
		$obj->Close();
	}
	
	//echo json_encode($data);
	header("location: visContactabilidad.php?id=".$idcaso);
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>