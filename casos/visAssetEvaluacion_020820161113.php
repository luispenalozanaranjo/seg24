<?php

function show_($var, $dietf) {
    echo "<p style='background-color: #555555; color: #FFFFFF; font-family: Courier New; font-size: 11px; border: 1px solid #FF0000; padding: 1px 1px 1px 10px;'>$var</p>";
    if ($dietf)
        die ();
}

session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Evaluacion.class.php');
require_once('../clases/Util.class.php');


if(in_array(4, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][3] == 1 || $_SESSION['glopermisos']['lectura'][3] == 1)){

$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$idetapa =  filter_var($_GET['idetapa'], FILTER_SANITIZE_STRING);

$_SESSION['idcaso'] = $idcaso;
$_SESSION['idetapa'] = $idetapa;

if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$asset = "ASSET";
	$valor = "Evaluacion";
} else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
	$asset = "Reevaluaciòn ASSET";
	$valor = "Reevaluacion";
}

$caso = new Casos(null);

$resultado = $caso->entregaGestorComunaCaso2($idcaso);
//$resultado = $caso->entregaCaso($idcaso);
$ges = 0;
if(count($resultado)>0){	
	foreach($resultado as $res){
		//se consulta por el gestor territorial de la comuna, si no existe, se almacena el rut del gestor territorial del nivel central (tabla configuracion)
		//con la finalidad de enviar un correo electrÃ³nico de aviso para aprobaciÃ³n de la evaluaciÃ³n.
		if($res['pe_idperfil'] == 3){
		$gestor_territorial = $res['rutgestor'];
		$ges++;
		}
			
	}
	
	if($ges == 0)
	$gestor_territorial = $_SESSION['gestor_territorial'];
	
    
	$obj = new Evaluacion(null);
	//$rs = $obj->entregaAssetEvaluacion($idcaso,1);
	//echo $etapa;
	
	$rs = $obj->entregaAssetEvaluacion($idcaso,$etapa);
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
	$totalnotas = '';
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
	//show_($infoadicional,TRUE);
	//se verifica que todos los mÃ³dulos del ASSET tengan calificaciÃ³n, que el estado de la revisiÃ³n sea nula y que el perfil sea "Profesional" 
	//para habilitar el botÃ³n "Enviar a Gestor Territorial".
	//echo "Permiso ".$_SESSION['glopermisos']['escritura'][1]." / Modulo:";
	//print_r($_SESSION['glopermisos']['modulo']);
	if (in_array(4, $_SESSION['glopermisos']['modulo']) && $_SESSION['glopermisos']['escritura'][1] == 1)
	$enviaragestor=1;
	else
	$enviaragestor=0;
	//print 'Nota Hogar es: '.$notahogar.'   '.$notarelacion.'   '.$notaeducacion.'   '.$notabarrio.'   '.$notaestilo.'     '.$notasustancias.'    '.$notasalud.'    '.'     '.$estado.'     '.$enviaragestor.'<br>';
	
	if($notahogar >= 0 && $notarelacion >= 0 && $notaeducacion >= 0 && $notabarrio >= 0 && $notaestilo >= 0 && $notasustancias >= 0 && $notasalud >= 0 && $notasalud2 >= 0 && $notapercepcion >= 0 && $notacomportamiento >= 0 && $notaactitud >= 0 && $notamotivacion >= 0 && ($estado == ''|| $estado == 'Rechazada' ) && $enviaragestor == 1){ //$_SESSION['gloidperfil'] == 2
	$verifica_calificacion = 0;
	
	}else
	$verifica_calificacion = 1;
	
	if($verifica_calificacion==0){
		//echo $idcaso.'   '.$valor;
		$resul_evi = $caso->entregaAssetEvidencias($idcaso,$valor);
        
		if(count($resul_evi)>0){
			$infoadicional = '';
			foreach($resul_evi as $resp){
				if($resp['an_evidencia']!='')$infoadicional.= 'ANALISIS:'."\n".$resp['an_evidencia']."\n\n";
				if($resp['ho_evidencia']!='')$infoadicional.= 'HOGAR: '."\n".$resp['ho_evidencia']."\n\n";
				if($resp['re_evidencia']!='')$infoadicional.= 'RELACIONES: '."\n".$resp['re_evidencia']."\n\n";
				if($resp['ed_evidencia1']!='')$infoadicional.= 'EDUCACION: '."\n".$resp['ed_evidencia1']."\n\n";
				if($resp['ed_evidencia2']!='')$infoadicional.= 'EDUCACION: '."\n".$resp['ed_evidencia2']."\n\n";
				if($resp['ba_evidencia']!='')$infoadicional.= 'BARRIO: '."\n".$resp['ba_evidencia']."\n\n";
				if($resp['es_evidencia']!='')$infoadicional.= 'ESTILO DE VIDA: '."\n".$resp['es_evidencia']."\n\n";
				if($resp['su_evidencia']!='')$infoadicional.= 'SUSTANCIAS: '."\n".$resp['su_evidencia']."\n\n";
				if($resp['sf_evidencia']!='')$infoadicional.= 'SALUD FISICA: '."\n".$resp['sf_evidencia']."\n\n";
				if($resp['sm_evidencia1']!='')$infoadicional.= 'SALUD MENTAL: '."\n".$resp['sm_evidencia1']."\n\n";
				if($resp['sm_evidencia2']!='')$infoadicional.= 'SALUD MENTAL: '."\n".$resp['sm_evidencia2']."\n\n";
				if($resp['sm_evidencia3']!='')$infoadicional.= 'SALUD MENTAL: '."\n".$resp['sm_evidencia3']."\n\n";
				if($resp['pe_evidencia']!='')$infoadicional.= 'PERCEPCION: '."\n".$resp['pe_evidencia']."\n\n";
				if($resp['com_evidencia']!='')$infoadicional.= 'COMPORTAMIENTO: '."\n".$resp['com_evidencia']."\n\n";
				if($resp['ac_evidencia']!='')$infoadicional.= 'ACTITUD: '."\n".$resp['ac_evidencia']."\n\n";
				if($resp['mo_evidencia']!='')$infoadicional.= 'MOTIVACION: '."\n".$resp['mo_evidencia'];
	 		
			} // fin foreach($resul_evi as $resp){
		} // fin if(count($resul_evi)>0){
	} // fin if($verifica_calificacion==0){

}
else{
	session_destroy();
	header('location: ../index.php');
}

$navegador = Util::detectaNavegador();
$cadena = md5('assetEvaluacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

/*echo "$notahogar >= 0 && $notarelacion >= 0 && $notaeducacion >= 0 && $notabarrio >= 0 && $notaestilo >= 0 && $notasustancias >= 0 && $notasalud >= 0 && $notasalud2 >= 0 && $notapercepcion >= 0 && $notacomportamiento >= 0 && $notaactitud >= 0 && $notamotivacion >= 0 && ($estado == ''|| $estado == 'Rechazada' ) && $enviaragestor == 1";*/
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="../images/gob.jpg" rel="icon" type="image/x-icon" />
<title><?php echo $_SESSION['glosistema'];?></title>
		<link rel="stylesheet" type="text/css" href="../css/global.css" />
		<link rel="stylesheet" type="text/css" href="../css/grilla.css">
		<link rel="stylesheet" type="text/css" href="../css/grilla-base.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript">
setTimeout ("redireccionar('../index.php')", <?php echo $_SESSION['gloexpiracion'];?>); //tiempo expresado en milisegundos
</script>
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
</head>
<body>
<div id="content-wrapper">
            <?php include('../header.php');?>

                <?php require_once('../menu.php');?>

                	<?php require_once('../sub_menu_asset.php');?>
<section>
<div class="contenedor">
  <h2><?php print $asset; ?></h2>
                	
                    <div class="caja">
        				<h3> Resumen de Factores de Riesgo Din&aacute;micos <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  

                    <form name="form" id="form" method="post" action="insAssetEvaluacion-ajax.php" onsubmit="return validaAssetResumen()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <input type="hidden" name="estado" value="<?php echo $estado;?>">
                    <input type="hidden" name="gestor_territorial" value="<?php echo $gestor_territorial;?>">
                    <input type="hidden" name="gestor_central" value="<?php echo $_SESSION['gestor_territorial'];?>">
                    
                    <table width="100%" align="center" border="0">
                    <tr>                
                        <td align="left" class="datos_sgs">
                        <table width="100%">
                        <?php if($estado == 'Rechazada'){?> 
                        <tr style="border-top:1px solid #C1DAD7;">
                            <td align="left" style="color:#900;font-size:14px"><b>Nota: El <?php print $asset; ?> se encuentra rechazado por el gestor territorial.</b></td>
                        </tr>
                        <?php }?>
                        </table>
                  		
                        <table width="100%" border="0">
                        <tr>
                        	<td width="15%"><label>1. Condiciones del hogar</label></td>
                            <td width="10%">
                            <input type="text" size="2" name="nota_hogar" readonly value="<?php echo $notahogar;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td width="15%"><label>2. Relaciones familiares y personales</label></td>
                            <td width="10%">
                            <input type="text" size="2" name="nota_relacion" readonly value="<?php echo $notarelacion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td width="15%"><label>3. Educaci&oacute;n, capacitaci&oacute;n y empleo</label></td>
                            <td width="10%">
                            <input type="text" size="2" name="nota_educacion" readonly value="<?php echo $notaeducacion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>4. Barrio</label></td>
                            <td>
                            <input type="text" size="2" name="nota_barrio" readonly value="<?php echo $notabarrio;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>5. Estilo de vida</label></td>
                            <td>
                            <input type="text" size="2" name="nota_estilo" readonly value="<?php echo $notaestilo;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>6. Uso de sustancias</label></td>
                            <td>
                            <input type="text" size="2" name="nota_sustancias" readonly value="<?php echo $notasustancias;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>7. Salud f&iacute;sica</label></td>
                            <td>
                            <input type="text" size="2" name="nota_salud" readonly value="<?php echo $notasalud;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>8. Salud mental y emocional</label></td>
                            <td>
                            <input type="text" size="2" name="nota_salud2" readonly value="<?php echo $notasalud2;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>9. Percepci&oacute;n de si mismo y de otros</label></td>
                            <td>

                            <input type="text" size="2" name="nota_percepcion" readonly value="<?php echo $notapercepcion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>10. Pensamiento y comportamiento</label></td>
                            <td>
                            <input type="text" size="2" name="nota_comportamiento" readonly value="<?php echo $notacomportamiento;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>11. Actitudes hacia la infracci&oacute;n/ transgresi&oacute;n a la norma</label></td>
                            <td>
                            <input type="text" size="2" name="nota_actitud" readonly value="<?php echo $notaactitud;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>12. Motivaci&oacute;n al cambio</label></td>
                            <td>
                            <input type="text" size="2" name="nota_motivacion" readonly value="<?php echo $notamotivacion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                         <tr>
                        	<td><label><b>Puntaje final</b></label></td>
                            <td colspan="5">
                            <input type="text" size="2" name="totalnotas" readonly value="<?php echo $totalnotas;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                         <tr>
                        	<td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
							<td colspan="6"><label><b>Informaci&oacute;n adicional importante</b></label></td>
                        </tr>
                        <tr>
                        	<td colspan="6"><textarea cols="150" rows="15" name="infoadicional" id="infoadicional"><?php echo "Detalle de la causal de ingreso al PSI 24 horas (Refierase a la conducta de derivaci�n)".'<br>'.$infoadicional;?></textarea>
                            <br></label>
                            </td>
                        </tr> 
                        </table>
                    </td>
                    </tr>
                    <?php if($verifica_calificacion == 0){?>
                    <tr>
                        <td height="34" align="left"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="Enviar a Gestor Territorial" /></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onClick="window.location.href='visCasos.php'" type="button" Value="Volver &raquo;" class="boton"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                      </tr>
                    <?php }?>
                    </table>    
                    </form>  
                    <br><br>  
     
<br><br>  
</div>
</section>
<div class="clearfloat"></div>
<div id="footer">
	<?php include('../footer.php');?>
</div>
<!-- footer -->
</div>
<?php 
if(isset($_SESSION['mensaje'])){
	if($_SESSION['mensaje']==2){?>
	<script>
	alert("Ocurrio un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	<?php }
	$_SESSION['mensaje']="";
}?>
</body>
</html>
<?php 
}else{
	session_destroy();
	header('location: ../index.php');
}

?>