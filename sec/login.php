<?php
session_start();
//.$_SERVER['HTTP_HOST'].''
$CSRFKey = "Seg#24H.2k15";
$token = sha1('index_'.$_SERVER['REMOTE_ADDR'].''.$_SERVER['HTTP_USER_AGENT'].''.$CSRFKey.''.date('Ymd'));

if ( isset($_POST['auth_token']) && $_POST['auth_token'] == $token)
{		
	if( isset($_POST['rut']) && isset($_POST['clave']) && $_POST['rut']!='' && $_POST['clave']!='' )
	{	
		require_once('../clases/Usuario.class.php');
		require_once('../clases/Perfil.class.php');
		require_once('../clases/Util.class.php');
		require_once('../clases/Configuracion.class.php');
		require_once('../securimage/securimage.php');
		
		//sanitiza variables
		$_POST['rut']	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		$_POST['clave']	=	filter_var($_POST['clave'], FILTER_SANITIZE_STRING);
		$paso_siguiente = 0;	
			
		if( isset($_POST['code']) && $_POST['code']!='0' ){
			$img = new Securimage();
			$valid = $img->check($_POST['code']);
			
			if($valid != "" && $valid == true)
			$paso_siguiente = 1;
			else
			$paso_siguiente = 0;
		}
		else	
		$paso_siguiente = 1;
		
		
		if( $paso_siguiente == 1 && (Util::validaRUT($_POST['rut']) == 'SI') ){
			$usuario = new Usuario(null);
			$resultado = $usuario->validaUsuario($_POST['rut'],$_POST['clave']);
			
			if( count($resultado) > 0 ){
						
				// se resetean los intentos fallidos del usuario	
				$usuario->reseteaIntento($_POST['rut']);			
				//se reestablece la cookie
				setcookie("contador", "", time() - 3600, "/");
				// se recorre el arreglo con los datos del usuario
				foreach( $resultado as $res){
					$_SESSION['glorut']			=	$res['us_rut'];
					$_SESSION['gloclave']		=	$res['us_clave'];
					$_SESSION['glonombre']		=	$res['us_nombre'];
					$_SESSION['glopaterno']		=	$res['us_paterno'];
					$_SESSION['glomaterno']		=	$res['us_materno'];
					$_SESSION['gloemail']		=	$res['us_email'];
					$_SESSION['gloestado']		=	$res['us_estado'];
					$_SESSION['gloidcomuna']	=	$res['co_idcomuna'];
					$_SESSION['gloidregion']	=	$res['re_idregion'];
					$_SESSION['glocomuna']		=	$res['co_descripcion'];
					$_SESSION['gloidperfil']	=	$res['pe_idperfil'];
					$_SESSION['gloperfil']		=	$res['pe_descripcion'];
					$_SESSION['glointento']		=	$res['us_intento'];
					$_SESSION['globloqueo']		=	$res['us_bloqueo'];
					$_SESSION['gloacceso']		=	$res['us_primeracceso'];
					//indica si el usuario es gestor territorial a nivel central (desde tabla configuración)
					$_SESSION['glogestorcentral']		=	$res['gestor'];
					$_SESSION['glosistema']		=	"SEG24Horas";
					$_SESSION['glologo']		=	"<img src='../images/logo.png'>";
					$_SESSION['gloexpiracion'] 	= 	43200000;//43200000 12 horas 3600000 60 minutos - 1800000 //30 minutos
					
					$_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
					
					$resul = $usuario->entregaUsuarioHasComuna($_POST['rut']);
					if(count($resul)>0){
							foreach($resul as $resc){				
								$cmbcomunas[]=$resc['co_idcomuna'];
							}
					}else{
						$cmbcomunas[]='';
					}
					$_SESSION['glocmbcomunas'] = $cmbcomunas;
		
				}	
				
				$perfil = new Perfil(null);
				$salida = $perfil->entregaPerfilModulo($_SESSION['gloidperfil']);
				$_SESSION['glopermisos'] = array();
				foreach($salida as $per){
					$_SESSION['glopermisos']['modulo'][] = $per['mo_idmodulo'];
					$_SESSION['glopermisos']['escritura'][] = $per['escritura'];
					$_SESSION['glopermisos']['lectura'][] = $per['lectura'];
					$_SESSION['glopermisos']['aprobacion'][] = $per['aprobacion'];
				}
				
				$configuracion = new Configuracion(null);
				$salida = $configuracion->muestraConfiguracion();
				foreach($salida as $conf){
					if($conf['descripcion'] == 'solicitud_cierre_DE')
					$_SESSION['solicitud_cierre_DE'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_verde_DE')
					$_SESSION['alerta_verde_DE'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_amarilla_DE')
					$_SESSION['alerta_amarilla_DE'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_roja_DE')
					$_SESSION['alerta_roja_DE'] = $conf['valor'];
					
					if($conf['descripcion'] == 'solicitud_cierre_MST')
					$_SESSION['solicitud_cierre_MST'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_verde_MST')
					$_SESSION['alerta_verde_MST'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_amarilla_MST')
					$_SESSION['alerta_amarilla_MST'] = $conf['valor'];
					
					if($conf['descripcion'] == 'alerta_roja_MST')
					$_SESSION['alerta_roja_MST'] = $conf['valor'];
					
					if($conf['descripcion'] == 'puntaje_derivacion_MST')
					$_SESSION['puntaje_derivacion_MST'] = $conf['valor'];
					
					if($conf['descripcion'] == 'num_visitas_minimo')
					$_SESSION['num_visitas_minimo'] = $conf['valor'];
					
					if($conf['descripcion'] == 'num_visitas_maximo')
					$_SESSION['num_visitas_maximo'] = $conf['valor'];
					
					if($conf['descripcion'] == 'gestor_territorial')
					$_SESSION['gestor_territorial'] = $conf['valor'];
				}
				
				
				
				$data = array("success" => true, "salida" => "sec/index.php");
				
			}
			else{
				// se agrega un intento fallido a la cuenta del usuario
				$usuario->agregaIntento($_POST['rut']);
				$intentos = $usuario->entregaIntento($_POST['rut']);
				
				foreach( $intentos as $datos){
					$numero_intento 	= $datos['us_intento'];
					$estado_bloqueo 	=  $datos['us_bloqueo'];
				}
				
				if( $estado_bloqueo == 'Si' )
				$mensaje = "Su cuenta ha sido bloqueada. Intente recuperar su clave";
				else
				$mensaje = "Login o clave incorrecta, favor intentelo nuevamente";
				
				$data = array("success" => false, "salida" => $mensaje);
				
			}
		}
		else{
			$data = array("success" => false, "salida" => "Las letras de la imagen no corresponden");
		}
		
		echo json_encode($data);
	}	
	else{
		session_destroy();
		header('location: ../index.php');
	}
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>
