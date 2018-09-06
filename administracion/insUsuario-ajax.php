<?php
session_start();
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";
$error = 0;
if( isset($_POST['rut']) && $_POST['rut']!='' && isset($_POST['nombre']) && $_POST['nombre']!=''
	&& isset($_POST['paterno']) && $_POST['paterno']!='' && isset($_POST['materno']) && $_POST['materno']!='' 
	&& isset($_POST['clave']) && $_POST['clave']!='' && isset($_POST['email']) && $_POST['email']!='' 
	&& isset($_POST['perfil']) && $_POST['perfil']!='' && isset($_POST['estado']) && $_POST['estado']!='' ){
		
	require_once('../clases/Usuario.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('insusuario_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//valida el RUT
	$valrut = Util::validaRUT($_POST['rut']);
	
	if(!$valida || $valrut == 'NO'){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	} else {			
		//sanitiza variables
		$rut	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		$nombre	=	filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
		$paterno	=	filter_var($_POST['paterno'], FILTER_SANITIZE_STRING);
		$materno	=	filter_var($_POST['materno'], FILTER_SANITIZE_STRING);
		$perfil	=	filter_var($_POST['perfil'], FILTER_SANITIZE_NUMBER_INT);
		$estado	=	filter_var($_POST['estado'], FILTER_SANITIZE_STRING);	
		
		/*if(isset($_POST['comuna']) && $_POST['comuna']>0)
		$comuna = $_POST['comuna'];
		else
		$comuna = '';
		*/
		
		
		$obj = new Usuario(null);
		$obj->Begin();
		/*if($comuna!='')
		$resultado = $obj->agregaUsuario($rut,$nombre,$paterno,$materno,$_POST['clave'],$_POST['email'],$comuna,$perfil,$estado);
		else
		$resultado = $obj->agregaUsuarioV2($rut,$nombre,$paterno,$materno,$_POST['clave'],$_POST['email'],$perfil,$estado);*/
		if($obj->agregaUsuarioV2($rut,$nombre,$paterno,$materno,$_POST['clave'],$_POST['email'],$perfil,$estado)==0){
		$error++;
		$data = array("success" => false, "mensaje" => 'Ocurrió un error al agregaUsuarioV2');
		}
		
		$cmbcomunas=$_POST["comuna"]; 
		if(count($cmbcomunas>0)){
			
			$obj->eliminaUsuarioHasComuna($rut);
			
			for ($i=0;$i<count($cmbcomunas);$i++)    
			{     
			//echo "<br> Comuna " . $i . ": " . $cmbcomunas[$i];   
				if($obj->agregaUsuarioHasComuna($rut,$cmbcomunas[$i])==0){
				$error++; 
				$data = array("success" => false, "mensaje" => 'Ocurrió un error al agregaUsuarioHasComuna');
				}
			} 
		}
		
		

		//if( $resultado > 0 ){
		if( $error == 0 ){
			$obj->Commit();
			$data = array("success" => true);
			/***************************/
			$auditoria_accion = "Insert";
			$auditoria_tabla = "usuario";
			$auditoria_sql = "agregaUsuario(".$rut.",".$nombre.",".$paterno.",".$materno.",".$_POST['clave'].",".$_POST['email'].",".$cmbcomunas.",".$perfil.",".$estado.")";
			$auditoria_meta = "Post";
			$auditoria = new Auditoria(null);
			$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
			$auditoria->Close();
			/***************************/
		}else{
			$obj->Rollback();
			//$data = array("success" => false);
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