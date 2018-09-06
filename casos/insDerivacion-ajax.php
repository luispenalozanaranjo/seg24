<?php

session_start();
$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && (isset($_POST['seleccion1']) || isset($_POST['seleccion2']) || isset($_POST['seleccion3']))){

	require_once('../clases/Derivacion.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Casos.class.php');
	require_once('../clases/Util.class.php');
	
/*	$motivo = '';*/
	$institucion = NULL;
	$fecderivacion = '';
	$fecingreso = '';
	$opcriterio = '';
	$criterio = '';
	$fecmst = '';
	$observacion = '';
	$chkcriterio = '';
	$seleccion = '';
	$cadena='';
	
	if(isset($_POST['seleccion1']) && $_POST['seleccion1'] == 'No cumple requisitos'){
		$seleccion = 'No cumple requisitos';
		
/*		if(isset($_POST['motivo']))
		$motivo = $_POST['motivo'];*/
		
/*		if(isset($_POST['motivo']))
		$observacion = $_POST['obsrequisitos'];*/
		
		if(isset($_POST['institucionop1']))
		$institucion = $_POST['institucionop1'];
		
		if(isset($_POST['fecderivacionop1']))
		$fecderivacion = Util::formatFechaSQL2($_POST['fecderivacionop1']);
		
		if(isset($_POST['fecingresoop1']))
		$fecingreso = Util::formatFechaSQL2($_POST['fecingresoop1']);
		
		if(isset($_POST['obsrequisitos']))
		$observacion = $_POST['obsrequisitos'];
		
	}
	else if(isset($_POST['seleccion2']) && $_POST['seleccion2'] == 'Derivacion externa'){
		$seleccion = 'Derivacion externa';
		
		if(isset($_POST['institucion']))
		$institucion = $_POST['institucion'];
		
		if(isset($_POST['fecderivacion']))
		$fecderivacion = Util::formatFechaSQL2($_POST['fecderivacion']);
		
		if(isset($_POST['fecingreso']))
		$fecingreso = Util::formatFechaSQL2($_POST['fecingreso']);
		
		if(isset($_POST['obsexterna']))
		$observacion = $_POST['obsexterna'];
	}
	else if(isset($_POST['seleccion3']) && $_POST['seleccion3'] == 'Derivacion MST'){
		$seleccion = 'Derivacion MST';
		
		if(isset($_POST['opcriterio']))
		$opcriterio = $_POST['opcriterio'];
		
		if(isset($_POST['criterio'])){
		$cadena='';
		foreach($_POST['criterio'] as $chk)
		$cadena.=$chk.',';
		$chkcriterio = substr($cadena,0,(strlen($cadena)-1));
		}
		
		if(isset($_POST['institucionMST'])&& $_POST['institucionMST']!='')
		$institucion = $_POST['institucionMST'];
		
		if(isset($_POST['fecingreso']))
		$fecingreso = Util::formatFechaSQL2($_POST['fecingreso']);
		
		if(isset($_POST['fecmst']))
		$fecmst = Util::formatFechaSQL2($_POST['fecmst']);
		
		if(isset($_POST['obsmst']))
		$observacion = $_POST['obsmst'];
	}
	
	$casos = new Casos(null);
	
	
	$obj = new Derivacion(null);
	$obj->Begin();
	
	
	if(((isset($_POST['opcion1']) && $_POST['opcion1'] == 'insert') || (isset($_POST['opcion2']) && $_POST['opcion2'] == 'insert') || (isset($_POST['opcion3']) && $_POST['opcion3'] == 'insert')) && ($fecingreso!=''))
	
	$rs = $obj->agregaDerivacion($_SESSION['idcaso'],$seleccion,$observacion, $fecderivacion,$fecingreso,$fecmst,$opcriterio,$chkcriterio,$institucion);
	
	if(((isset($_POST['opcion1']) && $_POST['opcion1'] == 'update') || (isset($_POST['opcion2']) && $_POST['opcion2'] == 'update') || (isset($_POST['opcion3']) && $_POST['opcion3'] == 'update'))&&($fecingreso!=''))
	$rs = $obj->modificaDerivacion($_SESSION['idcaso'],$seleccion,$observacion,$fecderivacion,$fecingreso,$fecmst,$opcriterio,$chkcriterio,$institucion);
	
	//echo "$obj->modificaDerivacion(".$_SESSION['idcaso'].",".$seleccion.",".$observacion.",".$fecderivacion.",".$fecingreso.",".$fecmst.",".$opcriterio.",".$chkcriterio.",".$institucion.",".$motivo.")";
	
	//si la opción escogida es "No cumple requisitos", se pasa a etapa de cierre
	//si la opción escogida es "Derivación externa", se pasa a etapa de cierre siempre que se tenga la fecha de ingreso al programa
	//si la opción escogida es "No cumple requisitos", se pasa a etapa de cierre siempre que se tenga la fecha de ingreso al programa
	if(isset($_POST['seleccion1']) && $_POST['seleccion1'] == 'No cumple requisitos' && $_POST['fecingresoop1']!=''){
		
	if($institucion==23){
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Cierre');
	} else {
	$rs4 = $obj->agregaDerivacionReevaluacion(2,$_SESSION['idcaso']);
	$rs5 = $obj->agregaRevisionDerivacionReevaluacion(2,$_SESSION['idcaso']);
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Reevaluacion');
	$casos->modificaRutEvaluacion($_SESSION['glorut'], $_SESSION['idcaso'],2);
	}
	
	}
	else{
	if($institucion==23){
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Cierre');
	} else {
	$rs4 = $obj->agregaDerivacionReevaluacion(2,$_SESSION['idcaso']);
	$rs5 = $obj->agregaRevisionDerivacionReevaluacion(2,$_SESSION['idcaso']);
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Reevaluacion');
	$casos->modificaRutEvaluacion($_SESSION['glorut'], $_SESSION['idcaso'],2);
	}
	$rs2 = 1;
	}
	
	if(isset($_POST['seleccion2']) && $_POST['seleccion2'] == 'Derivacion externa' && $_POST['fecingreso']!=''){
		
	$rs4 = $obj->agregaDerivacionReevaluacion(2,$_SESSION['idcaso']);//Agrego en Observaciones
	$rs5 = $obj->agregaRevisionDerivacionReevaluacion(2,$_SESSION['idcaso']);//Agrego en Revision
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Reevaluacion');
	$casos->modificaRutEvaluacion($_SESSION['glorut'], $_SESSION['idcaso'],2);
	
	}
	else{
	$rs2 = 1;
	}
	
	if(isset($_POST['seleccion3']) && $_POST['seleccion3'] == 'Derivacion MST' && $_POST['fecingreso']!='' && isset($_POST['opcion3']) && $_POST['opcion3'] == 'insert'){
		
	$rs4 = $obj->agregaDerivacionReevaluacion(2,$_SESSION['idcaso']);	
	//print_r("rs4:".$rs4);
	$rs5 = $obj->agregaRevisionDerivacionReevaluacion(2,$_SESSION['idcaso']);	
	//print_r("rs5:".$rs5);
	$rs2 = $obj->modificaCasoEtapa($_SESSION['idcaso'],'Reevaluacion');
	$casos->modificaRutEvaluacion($_SESSION['glorut'], $_SESSION['idcaso'],2);
	$rs2=1;
	}
	else{
	$rs2 = 1;
	
	}

	if( $rs > 0 && $rs2 > 0){
		$obj->Commit();
		//$obj->Rollback();
		
		$data = array("success" => true,"mensaje"=> $cadena);
		/********************INICIO INGRESO TRAZABILIDAD****************************/
		$nomuser = $_SESSION['glonombre'].' '.$_SESSION['glopaterno'].' '.$_SESSION['glomaterno'];
		require_once('../clases/Trazabilidad.class.php');
		$trazabilidad = new Trazabilidad(null);
		$resultado5 = $trazabilidad->agregaTrazabilidad('Reevaluacion',$_SESSION['idcaso'],$_SESSION['glorut'],$nomuser);
		$traz=$trazabilidad->entregaFechaInicioAssetResponsable($_SESSION['idcaso']);
		$trazabilidad->Close();
		
		foreach($traz as $trazabilidad)
	$fecha_asset=$trazabilidad['tr_fecha'];
	$nombre_responsable=$trazabilidad['us_nombre'];
	$creacion_caso=$trazabilidad['ca_finsercion'];
	
	
		require_once('../clases/Evaluacion.class.php');
	$obj = new Evaluacion(null);
	//$rs = $obj->entregaAssetEvaluacion($idcaso,1);
	$rs = $obj->entregaAssetEvaluacion($_SESSION['idcaso'],1);
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
	
		/********************FIN INGRESO TRAZABILIDAD*****************************/
		
		if(isset($_POST['fecmst']) && $_POST['fecmst']=!''){
		//se envía el email de aviso al gestor
		require_once('../clases/Usuario.class.php');
		$usuario = new Usuario(null);
		$resultado = $usuario->entregaUsuario($_POST['gestor_territorial']);
		foreach($resultado as $res)
		$email = $res['us_email'];
		$usuario->Close();
		
		$titulo = utf8_decode('Derivacion Caso N°; '.$_SESSION['idcaso']);
		// message
		$arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$mensaje = '
<html><body>
		
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
            <td colspan="3">Se le informa que debido a una derivaci&oacute;n efectiva, est&aacute; habilitado el cierre para el caso n&deg;  '.$_SESSION['idcaso'].', ya se encuentra disponible para su revisi&oacute;n.<br />
Con los siguientes puntos:
            </td>
        </tr>
        <tr>
          <td width="41%"><li>Fecha de ingreso a sistema</td>
          <td width="5%">:</td>
          <td width="54%">'.$creacion_caso.'</td>
        </tr>
        <tr>
          <td><li>Fecha de inicio de evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$fecha_asset.'</td>
        </tr>
        <tr>
          <td><li>Fecha de término evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.date('Y-m-d G:i:s').'</td>
        </tr>
        <tr>
          <td><li>Responsable de evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$nombre_responsable.'</td>
        </tr>
        <tr>
          <td><li>Puntaje obtenido en evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$totalnotas.' Puntos </td>
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
		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Cabeceras adicionales
		//$cabeceras .= 'To: <'.$email.'>' . "\r\n";
		$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
		//mail($email, $titulo, $mensaje, $cabeceras);
		
		if(!mail($email, $titulo, $mensaje, $cabeceras))
			$data = array("success" => false, "mensaje" => "Ocurrió un error al enviar el correo electrónico derivacion efectiva. Inténtelo nuevamente.");

		}
		
		
		if(isset($_POST['fecingreso']) && $_POST['fecingreso']=!''){
		//se envía el email de aviso al gestor
		require_once('../clases/Usuario.class.php');
		$usuario = new Usuario(null);
		$resultado = $usuario->entregaUsuario($_POST['gestor_territorial']);
		foreach($resultado as $res)
		$email = $res['us_email'];
		$usuario->Close();
		
		$titulo = 'Derivación Caso N°'.$_SESSION['idcaso'];
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
            <td colspan="3">Se le informa que se ha realizado una derivaci&oacute;n, para el caso n&ordm;  '.$_SESSION['idcaso'].'.<br />
Con los siguientes puntos:
            </td>
        </tr>
        <tr>
          <td width="41%"><li>Fecha de ingreso a sistema</td>
          <td width="5%">:</td>
          <td width="54%">'.$creacion_caso.'</td>
        </tr>
        <tr>
          <td><li>Fecha de inicio de evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$fecha_asset.'</td>
        </tr>
        <tr>
          <td><li>Fecha de término evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.date('Y-m-d G:i:s').'</td>
        </tr>
        <tr>
          <td><li>Responsable de evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$nombre_responsable.'</td>
        </tr>
        <tr>
          <td><li>Puntaje obtenido en evaluaci&oacute;n</td>
          <td>:</td>
          <td>'.$totalnotas.' Puntos </td>
        </tr>	
    <tr>
        <td colspan="3"><br><br>
        Este correo se gener&oacute; automaticamente no es necesario que lo responda.
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
		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Cabeceras adicionales
		//$cabeceras .= 'To: <'.$email.'>' . "\r\n";
		$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
		//mail($email, $titulo, $mensaje, $cabeceras);
		if(!mail($email, $titulo, $mensaje, $cabeceras))
			$data = array("success" => false, "mensaje" => "Ocurrió un error al enviar el correo electrónico derivacion. Inténtelo nuevamente.");
			
			
		
		}
		/***************************/
		$auditoria_accion = "Insert";
		$auditoria_tabla = "derivacion";
		$auditoria_sql = "agregaDerivacion(".$_SESSION['idcaso'].",".$seleccion.",".$observacion.",".$fecderivacion.",".$fecingreso.",".$fecmst.",".$criterio.",".$chkcriterio.",".$institucion.")";
		$auditoria_meta = "Post";
		$auditoria = new Auditoria(null);
		$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
		$auditoria->Close();
		/***************************/
	}
	else
	$obj->Rollback();
	
	$obj->Close();
	echo json_encode($data);
	//header("location: visDerivacion.php?id=".$_SESSION['idcaso']);
}	
else{
	session_destroy();
	$data = array("success" => false, "mensaje" => 'ERROR');	
}
?>