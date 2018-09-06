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
			//$name = md5($_FILES['archivo']['name']."-". date('Y-m-d H:i:s')).".".$extension_archivo;
			$numfile = str_pad($idacta, 6, "0", STR_PAD_LEFT);
			$name = "Consentimiento_".$idcaso.".".$extension_archivo;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name"))
			$subida = 1;
		}
		else
		$name = $_POST['archivo_old'];
		
		$obj = new Responsable(null);				
		$resultado = $obj->modificaActa($val_rutentrega,$val_dventrega,$val_rutrecibe,$val_dvrecibe,$nota,$datepicker,$name,$_SESSION['glorut'],$_SESSION['glorutdiv'],$estado,$idacta);
		
		if( $resultado > 0 ){
			if($subida == 1 && $estado=='Impresa')
			$obj->cambiaEstado('Cerrada',$idacta);

			$obj->eliminaActivoHasActa($idacta);
			
			$tmp = new TmpActivo(null);
			$query = $tmp->muestraTmpActivos(session_id());
			$cadena_acciones = "";
			
			foreach( $query as $temporales){
				$salida=$obj->agregaActivoHasActa($temporales['ac_inventario'],$idacta,$temporales['nota_activo']);
				$cadena_acciones.= "agregaActivoHasActa(".$temporales['ac_inventario'].",".$idacta.",".$temporales['nota_activo'].") - ";
				
				if($salida==0)
				$falla++;
			}
			
			$cadena_acciones = substr($cadena_acciones,0,(strlen($cadena_acciones)-3));
			
			if($falla==0){
				//$data = array("success" => true);
				$_SESSION['mensaje']=1;
				$obj->Commit();
				/***************************/
				$auditoria_accion="Update";
				$auditoria_tabla="acta";
				$auditoria_sql="modificaActa(".$val_rutentrega.",".$val_dventrega.",".$val_rutrecibe.",".$val_dvrecibe.",".$nota.",".$datepicker.",".$name.",".$_SESSION['glorut'].",".$_SESSION['glorutdiv'].",".$idacta.")";
				$auditoria_meta="Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				
				$auditoria_accion="Insert";
				$auditoria_tabla="activo_has_acta";
				$auditoria_sql=$cadena_acciones;
				$auditoria_meta="Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				/***************************/
			}
			else			
			$obj->Rollback();
		}
		$obj->Close();
	}
	
	//echo json_encode($data);
	header("location: visActa.php");
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>