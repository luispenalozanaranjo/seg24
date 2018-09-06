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

$resultado = $caso->entregaGestorProfesionalCaso($idcaso); 
//$resultado = $caso->entregaGestorComunaCaso2($idcaso);
//$resultado = $caso->entregaCaso($idcaso);
$ges = 0;
if(count($resultado)>0){	
/*	foreach($resultado as $res){
		//se consulta por el gestor territorial de la comuna, si no existe, se almacena el rut del gestor territorial del nivel central (tabla configuracion)
		//con la finalidad de enviar un correo electrÃ³nico de aviso para aprobaciÃ³n de la evaluaciÃ³n.
		if($res['pe_idperfil'] == 3){
		$gestor_territorial = $res['rutgestor'];
		echo $gestor_territorial;
		$ges++;
		}
			
	}*/
	foreach($resultado as $res){
		//$gestor_profesional=$res['us_rut'];
		$gestor_territorial=$res['us_rut'];
		$ges++;
		
	}
	
	if($ges == 0)
	$gestor_territorial = $_SESSION['gestor_territorial'];
	
    
	$obj = new Evaluacion(null);
	//$rs = $obj->entregaAssetEvaluacion($idcaso,1);
	//echo $etapa;
	
	$rs = $obj->entregaAssetEvaluacion($idcaso,$etapa);
	$rs_escala = $obj->validaArgumentosEscalasAsset($idcaso,$etapa);
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
	
	foreach($rs_escala as $res_escal){
		/*Analisis*/
		$an_fini                   =$res_escal['an_fecinicio'];
		$an_fecfin                 =$res_escal['an_fectermino'];
		/*Hogar*/
		$ho_sin_domicilio          =$res_escal['ho_sin_domicilio'];
		$ho_incumplimiento         =$res_escal['ho_incumplimiento'];
		$ho_hogar_deprivado        =$res_escal['ho_hogar_deprivado'];
		$ho_vive_delincuentes      =$res_escal['ho_vive_delincuentes'];
		$ho_situacion_calle        =$res_escal['ho_situacion_calle'];
		$ho_desorganizado          =$res_escal['ho_desorganizado'];
		$ho_otros                  =$res_escal['ho_otros'];
		/*Relaciones*/
		$re_involucrado            =$res_escal['re_involucrado'];
		$re_alcohol                =$res_escal['re_alcohol'];
		$re_drogas                 =$res_escal['re_drogas'];
		$re_comunicacion           =$res_escal['re_comunicacion'];	
		$re_supervision            =$res_escal['re_supervision'];
		$re_experiencia            =$res_escal['re_experiencia'];
		$re_testigo                =$res_escal['re_testigo'];
		$re_duelo                  =$res_escal['re_duelo'];
		$re_cuidado                =$res_escal['re_cuidado'];	
		$re_otros                  =$res_escal['re_otros'];	
		/*Educacion*/
		$ed_complementarios        =$res_escal['ed_complementarios'];	
        $ed_necesidades            =$res_escal['ed_necesidades'];
		$ed_certificado            =$res_escal['ed_certificado']; 
		$ed_alfabetizacion         =$res_escal['ed_alfabetizacion'];
		$ed_aritmeticas            =$res_escal['ed_aritmeticas']; 
		$ed_actitudnegativa        =$res_escal['ed_actitudnegativa']; 
		$ed_faltaadherencia        =$res_escal['ed_faltaadherencia'];
		$ed_victimabullying        =$res_escal['ed_victimabullying'];
		$ed_victimariobullying     =$res_escal['ed_victimariobullying']; 
		$ed_relacionpobre          =$res_escal['ed_relacionpobre']; 
		$ed_actitudpadres          =$res_escal['ed_actitudpadres']; 
		$ed_otro                   =$res_escal['ed_otro']; 
		/*Barrio*/
		$ba_evidenciatrafico       =$res_escal['ba_evidenciatrafico']; 
		$ba_tensionetnica          =$res_escal['ba_tensionetnica']; 
		$ba_localidadaislada       =$res_escal['ba_localidadaislada']; 
		$ba_faltainstalaciones     =$res_escal['ba_faltainstalaciones']; 
		$ba_otro                   =$res_escal['ba_otro']; 
		/*Estilo de Vida*/
		$es_faltaamistad           =$res_escal['es_faltaamistad']; 
		$es_actividadriesgo        =$res_escal['es_actividadriesgo'];
		$es_asocpredominante       =$res_escal['es_asocpredominante']; 
		$es_dineroinsuficiente     =$res_escal['es_dineroinsuficiente']; 
		$es_faltaasociacion        =$res_escal['es_faltaasociacion']; 
		$es_tiempolibre            =$res_escal['es_tiempolibre']; 
		$es_otro                   =$res_escal['es_otro']; 
		/*Sustancias*/
		$su_tabaco                 =$res_escal['su_tabaco']; 
		$su_cannabis               =$res_escal['su_cannabis']; 
		$su_anfetamina             =$res_escal['su_anfetamina']; 
		$su_lcd                    =$res_escal['su_lcd']; 
		$su_heroina                =$res_escal['su_heroina']; 
		$su_alcohol                =$res_escal['su_alcohol']; 
		$su_pastabase              =$res_escal['su_pastabase']; 
		$su_tranquilizante         =$res_escal['su_tranquilizante']; 
		$su_inhalantes             =$res_escal['su_inhalantes']; 
		$su_metadona               =$res_escal['su_metadona']; 
		$su_solventes              =$res_escal['su_solventes']; 
		$su_cocaina                =$res_escal['su_cocaina']; 
		$su_extasis                =$res_escal['su_extasis']; 
		$su_crack                  =$res_escal['su_crack']; 
		$su_esteroides             =$res_escal['su_esteroides']; 
		$su_otro                   =$res_escal['su_otro']; 
		$su_nnariesgo              =$res_escal['su_nnariesgo']; 
		$su_usopositivo            =$res_escal['su_usopositivo']; 
		$su_educacion              =$res_escal['su_educacion']; 
		$su_infracciones           =$res_escal['su_infracciones']; 
		$su_otros                  =$res_escal['su_otros']; 
		/*Salud Fisica*/
		$sf_condiciones            =$res_escal['sf_condiciones']; 
		$sf_inmadurez              =$res_escal['sf_inmadurez']; 
		$sf_acceso                 =$res_escal['sf_acceso']; 
		$sf_riesgo                 =$res_escal['sf_riesgo']; 
		$sf_otro                   =$res_escal['sf_otro']; 
		/*Salud Mental*/
		$sm_acontecimientos        =$res_escal['sm_acontecimientos']; 
		$sm_circunstancias         =$res_escal['sm_circunstancias']; 
		$sm_preocupaciones         =$res_escal['sm_preocupaciones']; 
		$sm_diagnostico            =$res_escal['sm_diagnostico']; 
		$sm_derivacion             =$res_escal['sm_derivacion']; 
		$sm_afectado               =$res_escal['sm_afectado']; 
		$sm_provocadano            =$res_escal['sm_provocadano']; 
		$sm_suicidio               =$res_escal['sm_suicidio'];
		/*Percepcion*/
		$pe_identidad              =$res_escal['pe_identidad'];
		$pe_autoestima             =$res_escal['pe_autoestima']; 
		$pe_desconfianza           =$res_escal['pe_desconfianza'];
		$pe_discriminado           =$res_escal['pe_discriminado']; 
		$pe_discriminador          =$res_escal['pe_discriminador']; 
		$pe_criminal               =$res_escal['pe_criminal']; 
		/*Comportamiento*/
		$com_faltacomprension      =$res_escal['com_faltacomprension']; 
		$com_impulsividad          =$res_escal['com_impulsividad']; 
		$com_emociones             =$res_escal['com_emociones']; 
		$com_faltaasertividad      =$res_escal['com_faltaasertividad']; 
		$com_temperamental         =$res_escal['com_temperamental']; 
		$com_habilidades           =$res_escal['com_habilidades']; 
		$com_propiedad             =$res_escal['com_propiedad']; 
		$com_agresion              =$res_escal['com_agresion']; 
		$com_sexual                =$res_escal['com_sexual']; 
		$com_manipulacion          =$res_escal['com_manipulacion']; 
		/*Actitud*/
		$ac_negacion               =$res_escal['ac_negacion']; 
		$ac_reticente              =$res_escal['ac_reticente']; 
		$ac_comprensionvictima     =$res_escal['ac_comprensionvictima']; 
		$ac_faltaremordimiento 	   =$res_escal['ac_faltaremordimiento']; 
		$ac_comprensionimpacto     =$res_escal['ac_comprensionimpacto']; 
		$ac_infraccionaceptable    =$res_escal['ac_comprensionimpacto']; 
		$ac_objetivoaceptable      =$res_escal['ac_objetivoaceptable']; 
		$ac_infraccioninevitable   =$res_escal['ac_infraccioninevitable']; 
		/*Motivacion*/
		$mo_comprendecomportamiento=$res_escal['mo_comprendecomportamiento']; 
		$mo_resolverproblemas      =$res_escal['mo_resolverproblemas']; 
		$mo_comprendeconsecuencias =$res_escal['mo_comprendeconsecuencias']; 
		$mo_identificaincentivos   =$res_escal['mo_identificaincentivos']; 
		$mo_muestraevidencia       =$res_escal['mo_muestraevidencia']; 
		$mo_apoyofamiliar          =$res_escal['mo_apoyofamiliar']; 
		$mo_cooperacion            =$res_escal['mo_cooperacion'];
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
		
		$resul_evi = $caso->entregaAssetEvidencias($idcaso,$valor);
        
		if(count($resul_evi)>0){
			$infoadicional = '';
			/*var_dump($resul_evi);*/
			foreach($resul_evi as $resp){
				if($resp['an_detalle_causal']!='')$infoadicional.= $resp['an_detalle_causal']."\n\n";
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
<div id="content-wrapper">
<section>
<div class="contenedor">
  <h2></h2>
                	
                    <div class="caja">
        				<h3> Resumen de Factores de Riesgo Din&aacute;micos <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  

                    <form name="form_evaluacion" id="form_evaluacion" method="post" action="insAssetEvaluacion-ajax.php" onsubmit="return validaAssetResumen()">
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
                            <input type="text" size="2" readonly value="<?php echo $notahogar;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td width="15%"><label>2. Relaciones familiares y personales</label></td>
                            <td width="10%">
                            <input type="text" size="2" readonly value="<?php echo $notarelacion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td width="15%"><label>3. Educaci&oacute;n, capacitaci&oacute;n y empleo</label></td>
                            <td width="10%">
                            <input type="text" size="2" readonly value="<?php echo $notaeducacion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>4. Barrio</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notabarrio;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>5. Estilo de vida</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notaestilo;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>6. Uso de sustancias</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notasustancias;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>7. Salud f&iacute;sica</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notasalud;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>8. Salud mental y emocional</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notasalud2;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>9. Percepci&oacute;n de si mismo y de otros</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notapercepcion;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        </tr>
                        <tr>
                        	<td><label>10. Pensamiento y comportamiento</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notacomportamiento;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                            <td><label>11. Actitudes hacia la infracci&oacute;n/ transgresi&oacute;n a la norma</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notaactitud;?>" class="alert-active" style="
    width: 55px;
">
                            </td>
                        	<td><label>12. Motivaci&oacute;n al cambio</label></td>
                            <td>
                            <input type="text" size="2" readonly value="<?php echo $notamotivacion;?>" class="alert-active" style="
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
                        	<td colspan="6"><textarea cols="150" rows="15" name="infoadicional" id="infoadicional"><?php echo $infoadicional;?></textarea>
                            <br></label>
                            </td>
                        </tr> 
                        </table>
                    </td>
                    </tr>
                    <?php if($verifica_calificacion == 0){?>
                    <tr>
                        <td align="left"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="Enviar a Gestor Territorial" />
                                            <!--Analisis-->
                                            <input type="hidden" name="an_fecinicio" value="<?php echo $an_fini; ?>" />
                                            <input type="hidden" name="an_fectermino" value="<?php echo $an_fecfin; ?>" />
                                            <!--Hogar-->
                                            <input type="hidden" name="ho_sin_domicilio" value="<?php echo $ho_sin_domicilio; ?>" />
                                            <input type="hidden" name="ho_incumplimiento" value="<?php echo $ho_incumplimiento; ?>" />
                                            <input type="hidden" name="ho_hogar_deprivado" value="<?php echo $ho_hogar_deprivado; ?>" />
                                            <input type="hidden" name="ho_vive_delincuentes" value="<?php echo $ho_vive_delincuentes; ?>" />
                                            <input type="hidden" name="ho_situacion_calle" value="<?php echo $ho_situacion_calle; ?>" />
                                            <input type="hidden" name="ho_desorganizado" value="<?php echo $ho_desorganizado; ?>" />
                                            <input type="hidden" name="ho_otros" value="<?php echo $ho_otros; ?>" />
                                            <!--Relaciones-->
                                            <input type="hidden" name="re_involucrado" value="<?php echo $re_involucrado; ?>" />
                                            <input type="hidden" name="re_alcohol" value="<?php echo $re_alcohol; ?>" />
                                            <input type="hidden" name="re_drogas" value="<?php echo $re_drogas; ?>" />
                                            <input type="hidden" name="re_comunicacion" value="<?php echo $re_comunicacion; ?>" />
                                            <input type="hidden" name="re_supervision" value="<?php echo $re_supervision; ?>" />
                                            <input type="hidden" name="re_experiencia" value="<?php echo $re_experiencia; ?>" />
                                            <input type="hidden" name="re_testigo" value="<?php echo $re_testigo; ?>" />
                                            <input type="hidden" name="re_duelo" value="<?php echo $re_duelo; ?>" />
                                            <input type="hidden" name="re_cuidado" value="<?php echo $re_cuidado; ?>" />
                                            <input type="hidden" name="re_otros" value="<?php echo $re_otros; ?>" />
                                            <!--Educacion-->
                                            <input type="hidden" name="ed_complementarios" value="<?php echo $ed_complementarios; ?>" />
                                            <input type="hidden" name="ed_necesidades" value="<?php echo $ed_necesidades; ?>" />
                                            <input type="hidden" name="ed_certificado" value="<?php echo $ed_certificado ; ?>" />
                                            <input type="hidden" name="ed_alfabetizacion" value="<?php echo $ed_alfabetizacion ; ?>" />
                                            <input type="hidden" name="ed_aritmeticas" value="<?php echo $ed_aritmeticas; ?>" />
                                            <input type="hidden" name="ed_actitudnegativa" value="<?php echo $ed_actitudnegativa; ?>" />
                                            <input type="hidden" name="ed_faltaadherencia" value="<?php echo $ed_faltaadherencia; ?>" />
                                            <input type="hidden" name="ed_victimabullying" value="<?php echo $ed_victimabullying; ?>" />
                                            <input type="hidden" name="ed_victimariobullying" value="<?php echo $ed_victimariobullying; ?>" />
                                            <input type="hidden" name="ed_relacionpobre" value="<?php echo $ed_relacionpobre; ?>" />
                                            <input type="hidden" name="ed_actitudpadres" value="<?php echo $ed_actitudpadres; ?>" />
                                            <input type="hidden" name="ed_otro" value="<?php echo $ed_otro; ?>" />
                                            <!--Barrio-->
                                            <input type="hidden" name="ba_evidenciatrafico" value="<?php echo $ba_evidenciatrafico; ?>" />
                                            <input type="hidden" name="ba_tensionetnica" value="<?php echo $ba_tensionetnica; ?>" />
                                            <input type="hidden" name="ba_localidadaislada" value="<?php echo $ba_localidadaislada; ?>" />
                                            <input type="hidden" name="ba_faltainstalaciones" value="<?php echo $ba_faltainstalaciones; ?>" />
                                            <input type="hidden" name="ba_otro" value="<?php echo $ba_otro; ?>" />
                                            <!--Estilo de Vida-->
                                            <input type="hidden" name="es_faltaamistad" value="<?php echo $es_faltaamistad; ?>" />
                                            <input type="hidden" name="es_actividadriesgo" value="<?php echo $es_actividadriesgo; ?>" />
                                            <input type="hidden" name="es_asocpredominante" value="<?php echo $es_asocpredominante; ?>" />
                                            <input type="hidden" name="es_dineroinsuficiente" value="<?php echo $es_dineroinsuficiente; ?>" />
                                            <input type="hidden" name="es_faltaasociacion" value="<?php echo $es_faltaasociacion; ?>" />
                                            <input type="hidden" name="es_tiempolibre" value="<?php echo $es_tiempolibre; ?>" />
                                            <input type="hidden" name="es_otro" value="<?php echo $es_otro; ?>" />
                                            <!--Sustancias-->
                                            <input type="hidden" name="su_tabaco" value="<?php echo $su_tabaco; ?>" />
                                            <input type="hidden" name="su_cannabis" value="<?php echo $su_cannabis; ?>" />
                                            <input type="hidden" name="su_anfetamina" value="<?php echo $su_anfetamina; ?>" />
                                            <input type="hidden" name="su_lcd" value="<?php echo $su_lcd; ?>" />
                                            <input type="hidden" name="su_heroina" value="<?php echo $su_heroina; ?>" />
                                            <input type="hidden" name="su_alcohol" value="<?php echo $su_alcohol; ?>" />
                                            <input type="hidden" name="su_pastabase" value="<?php echo $su_pastabase; ?>" />
                                            <input type="hidden" name="su_tranquilizante" value="<?php echo $su_tranquilizante; ?>" />
                                            <input type="hidden" name="su_inhalantes" value="<?php echo $su_inhalantes; ?>" />
                                            <input type="hidden" name="su_metadona" value="<?php echo $su_metadona; ?>" />
                                            <input type="hidden" name="su_solventes" value="<?php echo $su_solventes; ?>" />
                                            <input type="hidden" name="su_cocaina" value="<?php echo $su_cocaina; ?>" />
                                            <input type="hidden" name="su_extasis" value="<?php echo $su_extasis; ?>" />
                                            <input type="hidden" name="su_crack" value="<?php echo $su_crack; ?>" />
                                            <input type="hidden" name="su_esteroides" value="<?php echo $su_esteroides; ?>" />
                                            <input type="hidden" name="su_otro" value="<?php echo $su_otro; ?>" />
                                            <input type="hidden" name="su_nnariesgo" value="<?php echo $su_nnariesgo; ?>" />
                                            <input type="hidden" name="su_usopositivo" value="<?php echo $su_usopositivo; ?>" />
                                            <input type="hidden" name="su_educacion" value="<?php echo $su_educacion; ?>" />
                                            <input type="hidden" name="su_infracciones" value="<?php echo $su_infracciones; ?>" />
                                            <input type="hidden" name="su_otros" value="<?php echo $su_otros; ?>" />
                                            <!--Salud Fisica-->
                                            <input type="hidden" name="sf_condiciones" value="<?php echo $sf_condiciones; ?>" />
                                            <input type="hidden" name="sf_inmadurez" value="<?php echo $sf_inmadurez; ?>" />
                                            <input type="hidden" name="sf_acceso" value="<?php echo $sf_acceso; ?>" />
                                            <input type="hidden" name="sf_riesgo" value="<?php echo $sf_riesgo; ?>" />
                                            <input type="hidden" name="sf_otro" value="<?php echo $sf_otro; ?>" />
                                            <!--Salud Mental-->
                                            <input type="hidden" name="sm_acontecimientos" value="<?php echo $sm_acontecimientos; ?>" />
                                            <input type="hidden" name="sm_circunstancias" value="<?php echo $sm_circunstancias; ?>" />
                                            <input type="hidden" name="sm_preocupaciones" value="<?php echo $sm_preocupaciones; ?>" />
                                            <input type="hidden" name="sm_diagnostico" value="<?php echo $sm_diagnostico; ?>" />
                                            <input type="hidden" name="sm_derivacion" value="<?php echo $sm_derivacion; ?>" />
                                            <input type="hidden" name="sm_afectado" value="<?php echo $sm_afectado; ?>" />
                                            <input type="hidden" name="sm_provocadano" value="<?php echo $sm_provocadano; ?>" />
                                            <input type="hidden" name="sm_suicidio" value="<?php echo $sm_suicidio; ?>" />
                                            <!--Percepcion-->
                                            <input type="hidden" name="pe_identidad" value="<?php echo $pe_identidad; ?>" />
                                            <input type="hidden" name="pe_autoestima" value="<?php echo $pe_autoestima; ?>" />
                                            <input type="hidden" name="pe_desconfianza" value="<?php echo $pe_desconfianza; ?>" />
                                            <input type="hidden" name="pe_discriminado" value="<?php echo $pe_discriminado; ?>" />
                                            <input type="hidden" name="pe_discriminador" value="<?php echo $pe_discriminador; ?>" />
                                            <input type="hidden" name="pe_criminal" value="<?php echo $pe_criminal; ?>" />
                                            <!--Comportamiento-->
                                            <input type="hidden" name="com_faltacomprension" value="<?php echo $com_faltacomprension; ?>" />
                                            <input type="hidden" name="com_impulsividad" value="<?php echo $com_impulsividad; ?>" />
                                            <input type="hidden" name="com_emociones" value="<?php echo $com_emociones; ?>" />
                                            <input type="hidden" name="com_faltaasertividad" value="<?php echo $com_faltaasertividad; ?>" />
                                            <input type="hidden" name="com_temperamental" value="<?php echo $com_temperamental; ?>" />
                                            <input type="hidden" name="com_habilidades" value="<?php echo $com_habilidades; ?>" />
                                            <input type="hidden" name="com_propiedad" value="<?php echo $com_propiedad; ?>" />
                                            <input type="hidden" name="com_agresion" value="<?php echo $com_agresion; ?>" />
                                            <input type="hidden" name="com_sexual" value="<?php echo $com_sexual; ?>" />
                                            <input type="hidden" name="com_manipulacion" value="<?php echo $com_manipulacion; ?>" />
                                            <!--Actitud-->
                                            <input type="hidden" name="ac_negacion" value="<?php echo $ac_negacion; ?>" />
                                            <input type="hidden" name="ac_reticente" value="<?php echo $ac_reticente; ?>" />
                                            <input type="hidden" name="ac_comprensionvictima" value="<?php echo $ac_comprensionvictima; ?>" />
                                            <input type="hidden" name="ac_faltaremordimiento" value="<?php echo $ac_faltaremordimiento; ?>" />
                                            <input type="hidden" name="ac_comprensionimpacto" value="<?php echo $ac_comprensionimpacto; ?>" />
                                            <input type="hidden" name="ac_infraccionaceptable" value="<?php echo $ac_infraccionaceptable; ?>" />
                                            <input type="hidden" name="ac_objetivoaceptable" value="<?php echo $ac_objetivoaceptable; ?>" />
                                            <input type="hidden" name="ac_infraccioninevitable" value="<?php echo $ac_infraccioninevitable; ?>" />
                                            <!--Motivacion-->
                                            <input type="hidden" name="mo_comprendecomportamiento" value="<?php echo $mo_comprendecomportamiento; ?>" />
                                            <input type="hidden" name="mo_resolverproblemas" value="<?php echo $mo_resolverproblemas; ?>" />
                                            <input type="hidden" name="mo_comprendeconsecuencias" value="<?php echo $mo_comprendeconsecuencias; ?>" />
                                            <input type="hidden" name="mo_identificaincentivos" value="<?php echo $mo_identificaincentivos; ?>" />
                                            <input type="hidden" name="mo_muestraevidencia" value="<?php echo $mo_muestraevidencia; ?>" />
                                            <input type="hidden" name="mo_apoyofamiliar" value="<?php echo $mo_apoyofamiliar; ?>" />
                                            <input type="hidden" name="mo_cooperacion" value="<?php echo $mo_cooperacion; ?>" />
                                            </td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onClick="window.location.href='visCasos.php'" type="button" Value="Volver" class="boton"></td>
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
	alert("OcurriÃ³ un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	<?php }
	$_SESSION['mensaje']="";
}?>
<?php 
}else{
	session_destroy();
	header('location: ../index.php');
}
?>