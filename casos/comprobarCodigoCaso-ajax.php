<?php
session_start();

$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro',"borrar"=>0);
$token	=	"";
$valida	=	"";
$error	=	0;

if( (isset($_POST['nombre']) && $_POST['nombre']!='' && isset($_POST['paterno']) && $_POST['paterno']!='') ||( isset($_POST['rut']) && $_POST['rut']!='') ){
		
	require_once('../clases/Casos.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('inscasos_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//valida el RUT
	if(isset($_POST['rut']) && $_POST['rut']!='')
	$valrut = Util::validaRUT($_POST['rut']);
	else
	$valrut = 'Si';

	if(!$valida){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	}
	else if($valrut == 'NO'){
		$data = array("success" => false, "mensaje" => 'RUT no válido',"borrar"=>1);
	} 
	else {	
	
		 //if($valrut == 'NO')
		//$data = array("success" => false, "mensaje" => 'RUT no válido');
				
		//sanitiza variables
		$nombre		=	ucwords(strtolower(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING)));
		$paterno	=	ucwords(strtolower(filter_var($_POST['paterno'], FILTER_SANITIZE_STRING)));
		$materno	=	ucwords(strtolower(filter_var($_POST['materno'], FILTER_SANITIZE_STRING)));
		
		$caracter = array(".", "-");
		$rut_temp	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		$rut = str_replace($caracter, "", $rut_temp);
		
		if($nombre!='')
		$nom	= $nombre." ".$paterno." ".$materno;
		else
		$nom	=	'';

		$obj = new Casos(null);
		//$obj->Begin();
		$resultado = $obj->comprobarCodigoCaso($rut,$nom);
		//print_r($resultado);

		if( count($resultado) > 0 ){
			foreach($resultado as $res){
				$codigo	=	$res['ca_codigo'];
			}
			
			$data = array("success" => true, "mensaje" => $codigo);
		}else
			$data = array("success" => false, "mensaje" => "El ciudadano no existe en el sistema","borrar"=>2);
		

		
		$obj->Close();
	}
	
	echo json_encode($data);
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>