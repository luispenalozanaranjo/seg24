<?php
$CSRFKey = "Seg#24H.2k15";
$token = sha1('recupera_'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_HOST'].$CSRFKey.date('Ymd'));

if ( isset($_POST['auth_token']) && $_POST['auth_token'] == $token)
{		
	if( isset($_POST['rut']) && isset($_POST['code']) && $_POST['rut']!='' && $_POST['code']!='' ){
		
		//sanitiza variables
		$_POST['rut']	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);
		$_POST['code']	=	filter_var($_POST['code'], FILTER_SANITIZE_STRING);	
		
		require_once('../clases/Util.class.php');
		
		if( (Util::validaRUT($_POST['rut']) == 'SI') )
		{
			require_once('../clases/Usuario.class.php');
			$usuario = new Usuario(null);	
			$resultado = $usuario->entregaUsuarioActivo($_POST['rut']);
			
			if( count($resultado) > 0 ){
				//Se define una cadena de caractares. Te recomiendo que uses esta.
				$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
				//Obtenemos la longitud de la cadena de caracteres
				$longitudCadena=strlen($cadena);
				//Se define la variable que va a contener la contraseña
				$pass = "";
				//Se define la longitud de la contraseña
				$longitudPass=10;
				//Creamos la contraseña
				for($i=1 ; $i<=$longitudPass ; $i++){
					//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
					$pos=rand(0,$longitudCadena-1);
					//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena 		
					$pass .= substr($cadena,$pos,1);
				}
				
				foreach( $resultado as $res){
					$email	= $res['us_email'];
				}
			
				include("../securimage/securimage.php");
				$img = new Securimage();
				$valid = $img->check($_POST['code']);
				
				if($valid != "" && $valid == true) {
					$resultado = $usuario->reseteaClave($_POST['rut'],$pass);
					if( $resultado > 0 ){
						$para  = $email;
						$titulo = 'Cambio de clave SEG24Horas';
						// message
						$mensaje = '
						<html>
						<head>
						  <title>Cambio de clave SEG24Horas</title>
						</head>
						<body>
						  <p>Su nueva clave de acceso es la siguiente: <b>'.$pass.'</b><br>
						  Recuerde cambiarla una vez que acceda al sistema.</p>
						</body>
						</html>
						';
						
						// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
						// Cabeceras adicionales
						$cabeceras .= 'To: <'.$email.'>' . "\r\n";
						$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
						//$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
						if(mail($para, $titulo, $mensaje, $cabeceras))
						$data = array("success" => true, "salida" => "");
						else
						$data = array("success" => false, "salida" => "Ocurrió un error al enviar el correo electrónico. Inténtelo nuevamente.");
					}
				}
				else {
					$data = array("success" => false, "salida" => "Las letras de la imagen no corresponden");
				}
			}	
			else{
				$data = array("success" => false, "salida" => "RUT no registrado en el sistema");
			}
			echo json_encode($data);
		}
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
