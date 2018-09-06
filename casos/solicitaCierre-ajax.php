<?php
session_start();
$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";

if( isset($_POST['idcaso']) && $_POST['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['tipoventana']) && $_POST['tipoventana']!=''){
		
	require_once('../clases/Casos.class.php');
	require_once('../clases/Usuario.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	//sanitiza variables
	$idcaso	=	filter_var($_POST['idcaso'], FILTER_SANITIZE_NUMBER_INT);
	
	if(isset($_POST['motivo']))
	$motivo	=	filter_var($_POST['motivo'], FILTER_SANITIZE_STRING);
	else
	$motivo	=	'';
	
	if(isset($_POST['obscierre']))
	$obscierre	=	filter_var($_POST['obscierre'], FILTER_SANITIZE_STRING);
	else
	$obscierre	=	'';
	
	if(isset($_POST['satisfactorio']))
	$satisfactorio	=	filter_var($_POST['satisfactorio'], FILTER_SANITIZE_STRING);
	else
	$satisfactorio	=	'';
	
	if(isset($_POST['comcierre']))
	$comcierre	=	filter_var($_POST['comcierre'], FILTER_SANITIZE_STRING);
	else
	$comcierre	=	'';
			
	$obj = new Casos(null);
	//$resultado = $obj->entregaCaso($idcaso);
	$resultado = $obj->entregaGestorComunaCaso2($idcaso);	
	foreach($resultado as $res){
		//se consulta por el gestor territorial de la comuna, si no existe, se almacena el rut del gestor territorial del nivel central 
		//(tabla configuracion), con la finalidad de enviar un correo electrónico de aviso para aprobación del cierre.
		if($res['pe_idperfil'] == 3)
		$gestor_territorial = $res['us_rut'];
		else
		$gestor_territorial = $_SESSION['gestor_territorial'];
		
		$etapa = $res['ca_etapa'];
		$idetapa = $res['ca_idetapa'];
	}
	$obj->Close();
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('solcierre_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	if(!$valida || count($resultado)==0){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	} else {		
		if($_POST['tipoventana'] == 'profesional' && $_SESSION['gloidperfil']==2){
			
			$obj = new Casos(null);
			$rs = $obj->agregaSolicitudCierre($idcaso,$motivo,$obscierre);
			
			if($rs > 0){
				$data = array("success" => true);
				
				require_once('../clases/Trazabilidad.class.php');
				$trazabilidad = new Trazabilidad(null);
				$traz=$trazabilidad->entregaFechaInicioAssetResponsable($_POST['idcaso']);
				$trazabilidad->Close();
		
		       foreach($traz as $trazabilidad)
	           $fecha_asset=$trazabilidad['tr_fecha'];
	           $nombre_responsable=$trazabilidad['us_nombre'];
	           $creacion_caso=$trazabilidad['ca_finsercion'];
				//se envía el email de aviso al gestor
				
				
					require_once('../clases/Evaluacion.class.php');
	$obj = new Evaluacion(null);
	//$rs = $obj->entregaAssetEvaluacion($idcaso,1);
	$rs = $obj->entregaAssetEvaluacion($_POST['idcaso'],1);
	$obj->Close();
	
	$infoadicional	=	'';
	$notahogar	=	'';
	$notarelacion	=	'';
	$notaeducacion		=	'';
	$notabarrio	=	'';
	$notaestilo	=	'';
	$notasustancias	=	'';
	$notasalud	=	'';
	$notasalud2	=	'';
	$notapercepcion	=	'';
	$notacomportamiento	=	'';
	$notaactitud	=	'';
	$notamotivacion	=	'';
	$estado = '';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$infoadicional =	$res['ev_infoadicional'];
		$notahogar = $res['ev_notahogar'];
		$notarelacion = $res['ev_notarelacion'];
		$notaeducacion =	$res['ev_notaeducacion'];
		$notabarrio = $res['ev_notabarrio'];
		$notaestilo = $res['ev_notaestilo'];
		$notasustancias = $res['ev_notasustancias'];
		$notasalud = $res['ev_notasalud'];
		$notasalud2 = $res['ev_notasalud2'];
		$notapercepcion = $res['ev_notapercepcion'];
		$notacomportamiento = $res['ev_notacomportamiento'];
		$notaactitud = $res['ev_notaactitud'];
		$notamotivacion = $res['ev_notamotivacion'];
		$estado = $res['re_estado'];
		$opcion = 'update';
		
		$totalnotas = $notahogar+$notarelacion+$notaeducacion+$notabarrio+$notaestilo+$notasustancias+$notasalud+$notasalud2+$notapercepcion+$notacomportamiento+$notaactitud+$notamotivacion;
	}
	
	
				$usuario = new Usuario(null);
				$resultado = $usuario->entregaUsuario($gestor_territorial);
				foreach($resultado as $res)
				$email = $res['us_email'];
				$usuario->Close();
				
				$titulo = utf8_decode('Cierre Caso N° '.$idcaso);
				// message
				$mensaje = '<html><body>
		
		<table width="600" border="1" cellspacing="0" cellpadding="20" bordercolor="#999999">
<tr>
  <td>
    <table width="100%" border="0">
    <tr><td colspan="3" align="left">
        <img src="http://'.$_SERVER['HTTP_HOST'].'/images/logo.png" width="150"  />
    </td></tr>
    <tr>
            <td colspan="3" align="right">
            Santiago, '.date('d').' de '.Util::entregaMesCompleto(date('m')).' de '.date('Y').'
            </td>
        </tr>
        <tr>
            <td colspan="3"> 
            Estimados<br><b><u>Presente</u></b>
            </td>
        </tr>
        <tr>
            <td colspan="3">Se le informa que se ha solicitado un cierre para el caso n&deg;  '.$idcaso.'
            </td>
        </tr>
        <tr>
          <td width="41%"><li>Fecha de ingreso a sistema</td>
          <td width="5%">:</td>
          <td width="54%">'.$creacion_caso.'</td>
        </tr>
        <tr>
          <td><li>Fecha de inicio de evaluaciòn</td>
          <td>:</td>
          <td>'.$fecha_asset.'</td>
        </tr>
        <tr>
          <td><li>Fecha de término evaluación</td>
          <td>:</td>
          <td>'.date('Y-m-d G:i:s').'</td>
        </tr>
        <tr>
          <td><li>Responsable de evaluación</td>
          <td>:</td>
          <td>'.$nombre_responsable.'</td>
        </tr>
        <tr>
          <td><li>Puntaje obtenido en evaluación</td>
          <td>:</td>
          <td>'.$total_nota.' Puntos </td>
        </tr>	
    <tr>
        <td colspan="3"><br><br>
        Este correo se generó automaticamente no es necesario que lo responda.
        <br><br>
         Por favor recuerde acceder al sistema. 
          
          <p>
          <a href="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'/</a>
          </p>
        </td>
    </tr>
</table>
</td>
</tr>
</table>		</body>
		</html>';
				
				// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
				$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
				$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
				// Cabeceras adicionales
				//$cabeceras .= 'To: <'.$email.'>' . "\r\n";
				$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
				mail($email, $titulo, $mensaje, $cabeceras);
				
				/***************************/
				$auditoria_accion = "Insert";
				$auditoria_tabla = "solicitud_cierre";
				$auditoria_sql = "agregaSolicitudCierre(".$idcaso.",".$motivo.",".$obscierre.")";
				$auditoria_meta = "Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				$auditoria->Close();
				/***************************/
			}
		}
		
		if($_POST['tipoventana'] == 'gestor' && $_SESSION['gloidperfil']==3){
			
			$obj = new Casos(null);
			$obj->Begin();
			$rs = $obj->modificaSolicitudCierre($idcaso,$satisfactorio,$comcierre);
			$rs2 = $obj->modificaCasoEtapa($idcaso,'Finalizado');
			
			if($rs > 0 && $rs2 > 0){
				$obj->Commit();
				$data = array("success" => true);
				/***************************/
				$auditoria_accion = "Update";
				$auditoria_tabla = "solicitud_cierre";
				$auditoria_sql = "modificaSolicitudCierre(".$idcaso.",".$satisfactorio.",".$comcierre.")";
				$auditoria_meta = "Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				$auditoria->Close();
				/***************************/
			}
			else
			$obj->Rollback();
		}
		$obj->Close();	
	}
	echo json_encode($data);
}	
else{
	session_destroy();
	$data = array("success" => false, "mensaje" => 'ERROR');
}
?>