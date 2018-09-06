<?php
session_start();
if( isset($_POST['clave1']) && isset($_POST['actual']) && $_POST['clave1']!='' && $_POST['actual']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' ){

	//valida token
	require_once('../clases/Util.class.php');
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('repclave_'.$navegador['navegador'].''.$navegador['version'].''.date("YmdHi"));
	$valida	= Util::verificaToken($cadena, $token, 300);
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	} else {		
	
		if(sha1($_POST['actual']) == $_SESSION['gloclave']){
			require_once('../clases/Usuario.class.php');
			$usuario = new Usuario(null);
			$resultado = $usuario->modificaClave($_SESSION['glorut'],$_POST['clave1']);
		
			if( count($resultado) > 0 ){
				$_SESSION['gloacceso']	=	'No';
				$data = array("success" => true);
			}
			else{
				$data = array("success" => false, "salida" => "Ocurrió un error al cambiar su clave. Inténtelo nuevamente");
			}
		}
		else{
		$data = array("success" => false, "salida" => "La clave actual no corresponde a la enviada a su correo electrónico");
		}
	echo json_encode($data);	
	}
}	
else{
	session_destroy();	
	header('Location: ../index.php');
}
?>
