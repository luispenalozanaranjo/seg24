<?php
function show_($var, $dietf) {
    echo "<p style='background-color: #555555; color: #FFFFFF; font-family: Courier New; font-size: 11px; border: 1px solid #FF0000; padding: 1px 1px 1px 10px;'>$var</p>";
    if ($dietf)
        die ();
}
session_start();
$_SESSION['mensaje']=2;
$_SESSION['habilita_revision'] = 0;
date_default_timezone_set("America/Santiago"); 

//show_('llege aca',false);

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!='' && isset($_POST['opcion']) && $_POST['opcion']!='' && isset($_POST['gestor_territorial']) && $_POST['gestor_territorial']!=''){
		
	require_once('../clases/Evaluacion.class.php');
	require_once('../clases/Usuario.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Trazabilidad.class.php');
	require_once('../clases/Revision.class.php');
	require_once('../clases/Util.class.php');
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetEvaluacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//$valida = true;
	//show_('entre aca',false);
	if(!$valida){
	 	session_destroy();
		header('location: ../index.php');
	}else{	

    if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$asset = 'Evaluaciòn';
	}
    else if($_SESSION['idetapa']=='5')
	$etapa = 2;
	$asset = 'Reevaluaciòn';

	if(isset($_POST['infoadicional']) && $_POST['infoadicional']!='')
	$infoadicional = $_POST['infoadicional'];
	else
	$infoadicional = '';
	
	if(isset($_POST['estado']) && $_POST['estado']=='Rechazada'){
	if($_SESSION['idetapa']=='3'){
	$estado = 1;
	} else {
	$estado = 2;
	}
	}
	else{
	if($_SESSION['idetapa']=='3'){
	$estado = 1;
	} else {
	$estado = 2;
	}
	}
	
		/*if(isset($_POST['estado']) && $_POST['estado']=='Rechazada')
	$estado = 2;
	else
	$estado = 2;*/
	
    $rev = new Revision(null);
	$rev->agregaAssetComentario($etapa,$_SESSION['idcaso'],$_SESSION['glorut'],'Enviado a Gestor Territorial',2);
	
	$tr = new Trazabilidad(null);
	$traz=$tr->entregaFechaInicioAssetResponsable($_SESSION['idcaso']);
	/*$trazDerivacion = $tr->entregaFechaDerivacionResponsable($_SESSION['idcaso']);*/
    $tr->Close();
	foreach($traz as $trazabilidad)
	$fecha_asset=$trazabilidad['tr_fecha'];
	$nombre_responsable= $trazabilidad['us_nombre'];
	$creacion_caso=$trazabilidad['ca_finsercion'];

	/*foreach($trazDerivacion as $trazabilidad_derivacion)
	$fecha_derivacion=$trazabilidad_derivacion['tr_fecha'];*/

	$obj = new Evaluacion(null);
	$notaEvaluacion=$obj->notaTotalEvaluacion($_SESSION['idcaso']);
	
	foreach($notaEvaluacion as $total)
	{
	 $total_nota=$total['total_puntos'];	
	}
	//show_('entre alla',false);
	//show_($estado.'   '.$etapa,true);
	$obj->Begin();
	$rs = $obj->modificaAssetEvaluacion($estado,$_SESSION['idcaso'],$infoadicional);
	//se cambia el estado de la revisión a Pendiente para esperar la aprobación del Gestor Territorial
	$rs2 = $obj->modificaEstadoRevision($_SESSION['idcaso'],$estado,'Pendiente');

	
	echo "estado: ".$estado." - rs:".$rs." -rs2:".$rs2;
	
	if($rs >= 0 && $rs2 > 0){
		$obj->Commit();
		$_SESSION['mensaje']=1;
		$_SESSION['habilita_revision'] = 1;
		
		/********************INICIO INGRESO TRAZABILIDAD*****************************
		require_once('../clases/Trazabilidad.class.php');
		$trazabilidad = new Trazabilidad(null);
		$resultado5 = $trazabilidad->agregaTrazabilidad('Derivacion',$_SESSION['idcaso']);
		$trazabilidad->Close();
		/********************FIN INGRESO TRAZABILIDAD*****************************/
		
		if($_SESSION['idetapa']=='3'){
		$asset = 'Evaluaciòn';
		}
		else if($_SESSION['idetapa']=='5'){
		$asset = 'Reevaluaciòn';
		}
		
		//show_($asset.'     '.$_SESSION['idetapa'],false);
		//se envía el email de aviso al gestor
		$usuario = new Usuario(null);
		$resultado = $usuario->entregaUsuario($_POST['gestor_territorial']);
		foreach($resultado as $res)
		$email = $res['us_email'];
		//show_($_POST['gestor_territorial'].'   '.$email,true);
		$usuario->Close();
		//show_($asset.'   '.$_SESSION['idetapa'],true);
		$titulo = $asset.' Pendiente Caso Nº '.$_SESSION['idcaso'];
		// message
		$mensaje ='
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
            <td colspan="3">Se le informa que la '.$asset.' del ASSET para el caso n&deg;  '.$_SESSION['idcaso'].', ya se encuentra disponible para su revisi&oacute;n.<br />
Con los siguientes puntos:
            </td>
        </tr>
        <tr>
          <td width="41%"><li>Fecha de ingreso a sistema</td>
          <td width="5%">:</td>
          <td width="54%">'.$creacion_caso.'</td>
        </tr>
        <tr>
          <td><li>Fecha de inicio de '.$asset.'</td>
          <td>:</td>
          <td>'.$fecha_asset.'</td>
        </tr>
        <tr>
          <td><li>Fecha de t&eacute;rmino '.$asset.'</td>
          <td>:</td>
          <td>'.date('Y-m-d G:i:s').'</td>
        </tr>
        <tr>
          <td><li>Responsable de '.$asset.'</td>
          <td>:</td>
          <td>'.$nombre_responsable.'</td>
        </tr>
        <tr>
          <td><li>Puntaje obtenido en '.$asset.'</td>
          <td>:</td>
          <td>'.$_POST['totalnotas'].' Puntos </td>
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
</table>
';
		
		// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Cabeceras adicionales
		//$cabeceras .= 'To: <'.$email.'>' . "\r\n";
		$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
		mail($email, $titulo, $mensaje, $cabeceras);
		
		/***************************/
		$auditoria_accion = "Update";
		$auditoria_tabla = "evaluacion";
		$auditoria_sql = "modificaAssetEvaluacion(".$idcaso.",".$_SESSION['idcaso'].", ".$infoadicional.")";
		$auditoria_meta = "Post";
		$auditoria = new Auditoria(null);
		$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
		$auditoria->Close();
		/***************************/
	}
	else
	$obj->Rollback();
	
	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-14");

	}
	
}else{
	session_destroy();
	header('location: ../index.php');
}
?>