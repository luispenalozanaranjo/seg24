<?php
session_start();

$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";
$error 	=	0;

if( isset($_POST['id']) && $_POST['id']!='' && isset($_POST['nombre']) && $_POST['nombre']!='' 
	&& isset($_POST['estado']) && $_POST['estado']!='' ){
		
	require_once('../clases/Perfil.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('modperfil_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
		
	if(!$valida){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	} else {				
		//sanitiza variables
		$nombre	=	filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
		$estado	=	filter_var($_POST['estado'], FILTER_SANITIZE_STRING);	
		$id	=	filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);	
	
		$obj = new Perfil(null);
		$obj->Begin();
		$resultado = $obj->modificaPerfil($nombre,$estado,$id);
		$resultado2 = $obj->eliminaPermisoPerfil($id);
	
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
	
				$resultado3 = $obj->agregaPermisoPerfil($id,$i,$escritura,$lectura,$aprobacion);
				if($resultado2 == 0)
				$error++;
			}
		}	
	
		if( $resultado2 > 0 && $error == 0){
			$obj->Commit();
			$data = array("success" => true);
			/***************************/
			$auditoria_accion = "Update";
			$auditoria_tabla = "perfil";
			$auditoria_sql = "modificaPerfil(".$nombre.",".$estado.",".$id.")";
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
?>