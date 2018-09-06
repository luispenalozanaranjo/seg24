<?php
session_start();
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";
$error 	=	0;

if( isset($_POST['nombre']) && $_POST['nombre']!='' && isset($_POST['estado']) && $_POST['estado']!='' ){
		
	require_once('../clases/Perfil.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('insperfil_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	if(!$valida){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	} else {	
		//sanitiza variables
		$nombre	=	filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
		$estado	=	filter_var($_POST['estado'], FILTER_SANITIZE_STRING);	
	
		$obj = new Perfil(null);
		$obj->Begin();
		$resultado = $obj->agregaPerfil($nombre,$estado);
		$id = $obj->Identity();
	
		if($id > 0){
			for($i=1; $i<=6; $i++){
				if(isset($_POST['lectura_'.$i]))
				$lectura = 1;
				else
				$lectura = 0;
				
				if(isset($_POST['escritura_'.$i]))
				$escritura = 1;
				else
				$escritura = 0;
				
				if(isset($_POST['aprobacion_'.$i]))		
				$aprobacion = 1;
				else
				$aprobacion = 0;
	
				$resultado2 = $obj->agregaPermisoPerfil($id,$i,$escritura,$lectura,$aprobacion);
				if($resultado2 == 0)
				$error++;
			}
		}	
	
		if( $resultado > 0 && $error == 0){
			$obj->Commit();
			$data = array("success" => true);
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "perfil";
			$auditoria_sql = "agregaPerfil(".$nombre.",".$estado.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}	
		else{
			$obj->Rollback();
		}
		
		$obj->Close();
	}
	echo json_encode($data);
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