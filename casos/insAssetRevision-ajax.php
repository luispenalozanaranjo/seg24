<?php
function show_($var, $dietf) {
    echo "<p style='background-color: #555555; color: #FFFFFF; font-family: Courier New; font-size: 11px; border: 1px solid #FF0000; padding: 1px 1px 1px 10px;'>$var</p>";
    if ($dietf)
        die ();
}

session_start();
$_SESSION['mensaje']=2;

if(isset($_POST['Aprueba']))
$estado = 'Aprobada';
else if(isset($_POST['Rechaza']))
$estado = 'Rechazada';
else
$estado = 'Pendiente';

$error = 0;

if( isset($_SESSION['idcaso']) && $_SESSION['idcaso']!='' && isset($_SESSION['glorut']) && $_SESSION['glorut']!=''){
		
	require_once('../clases/Revision.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	require_once('../clases/Usuario.class.php');
	require_once('../clases/Casos.class.php');
	
	if(isset($_POST['comentario']) && $_POST['comentario']!='')
	$comentario = $_POST['comentario'];
	else
	$comentario = '';

    
	if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$nombre_etapa = 'Derivacion';
    } else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
	$nombre_etapa = 'Cierre';
    }

	$obj = new Revision(null);
	$obj->Begin();
	$rs1 = $obj->modificaAssetRevision($etapa,$_SESSION['idcaso'],$estado);
	$rs2 = $obj->agregaAssetComentario($etapa,$_SESSION['idcaso'],$_SESSION['glorut'],$_POST['comentario'],$estado);
	
	//Si el estado es de aprobación del asset, se pasa a etapa de derivación
	if($estado == 'Aprobada'){
		$rs3 = $obj->modificaCasoEtapa($_SESSION['idcaso'],$nombre_etapa);

	}
	else
		$rs3 = 1;
	/***********************INICIO envio de correo si es rechazada o aprobada**********************/
	
	if($estado == 'Aprobada' || $estado == 'Rechazada'){	
	
	if($estado == 'Aprobada'){
	$titulo = 'Aprobación Caso N° '.$_SESSION['idcaso'];
	$motivo = 'aprobada';
	}else{
	$titulo = 'Rechazo Caso N° '.$_SESSION['idcaso'];
	$motivo = 'rechazada';
	}
	
	
		

		//show_($_POST['gestor_territorial'].'  '.$email,true);
		require_once('../clases/Trazabilidad.class.php');
		$trazabilidad = new Trazabilidad(null);
		$traz=$trazabilidad->entregaFechaInicioAssetResponsable($_SESSION['idcaso']);
		$trazabilidad->Close();
		
		foreach($traz as $trazabilidad){
		$fecha_asset=$trazabilidad['tr_fecha'];
		$nombre_responsable=$trazabilidad['us_nombre'];
		$creacion_caso=$trazabilidad['ca_finsercion'];}
	//show_($_POST['gestor_territorial'].'  '.$email,true);	
	
	//se envía el email de aviso al gestor
		/*
		$usuario = new Usuario(null);
		$resultado = $usuario->entregaUsuario($_POST['gestor_territorial']);
		foreach($resultado as $res)
		$email = $res['us_email'];
		$usuario->Close();
		*/
		$caso = new Casos(null);
		$resultado = $caso->entregaGestorComunaCaso($_SESSION['idcaso']);
		$caso->Close();
if(count($resultado)>0){
		
	foreach($resultado as $res){
		//echo $perfil;
  
        if($res['pe_idperfil'] == 2){
		
		$email = $res['us_email'];
		// message
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
						<td colspan="3">Se le informa que la revisi&oacute;n del ASSET para el caso n&deg;  '.$_SESSION['idcaso'].', fue '.$motivo.' e ingreso el siguiente comentario:<br><br>'.$comentario.'
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
					  <td> '.$_POST['total_puntos'].' Puntos </td>
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
			</table>		
		 </body>
		</html>';
		
		// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Cabeceras adicionales
		//$cabeceras .= 'To: <'.$email.'>' . "\r\n";
		$cabeceras .= 'From: SEG24Horas <spd-reportes24horas@interior.gov.cl>' . "\r\n";
		if(!mail($email, $titulo, $mensaje, $cabeceras))
		$error++;
		
		}
	}
	
}else{
$error++;
echo "no existen profesionales EDT";
}
	
	}// fin if($estado == 'Aprobada' || $estado == 'Rechazada'){
	/***********************INICIO envio de correo si es rechazada o aprobada**********************/

	if($rs1 > 0 && $rs2 >0 && $rs3 > 0 && $error==0){
		$obj->Commit();
		$_SESSION['mensaje']=1;
		
		if($estado == 'Aprobada'){
		/********************INICIO INGRESO TRAZABILIDAD*****************************/
		$nomuser = $_SESSION['glonombre'].' '.$_SESSION['glopaterno'].' '.$_SESSION['glomaterno'];
		require_once('../clases/Trazabilidad.class.php');
		$trazabilidad = new Trazabilidad(null);
		$resultado5 = $trazabilidad->agregaTrazabilidad($nombre_etapa,$_SESSION['idcaso'],$_SESSION['glorut'],$nomuser);
		$trazabilidad->Close();
		/********************FIN INGRESO TRAZABILIDAD*****************************/
		}
		
		/***************************/
		$auditoria_accion = "Insert";
		$auditoria_tabla = "revision";
		$auditoria_sql = "agregaAssetRevision(".$etapa.",".$_SESSION['idcaso'].",".$estado.")";
		$auditoria_meta = "Post";
		$auditoria = new Auditoria(null);
		$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
		$auditoria->Close();
		/***************************/
	}
	else
	$obj->Rollback();

	$obj->Close();	
	header("location: visAsset.php?id=".$_SESSION['idcaso']."&idetapa=".$_SESSION['idetapa']."#tabs-15");
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>