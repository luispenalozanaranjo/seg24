<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Analisis.class.php');
require_once('../clases/Derivacion.class.php');
require_once('../clases/Util.class.php');


$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$idetapa =  filter_var($_GET['idetapa'], FILTER_SANITIZE_STRING);
$fcreacion_inicio = '';
$tipo = '';
$fecha_reevaluacion = '';

$_SESSION['idcaso'] = $idcaso;
$_SESSION['idetapa'] = $idetapa;
$_SESSION['estado']=1;

if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$asset = "ASSET";
} else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
	$asset = "Reevaluaci&oacute;n ASSET";
}


        $derivacion = new Derivacion(null);
		$salida = $derivacion->entregaDerivacion($idcaso);
		
		foreach($salida as $res){
			$tipo = $res['de_tipo'];
		}
		
		if(($tipo=='No cumple requisitos')&&($_SESSION['idetapa']=='5')){
		$resultado_fderivacion = $derivacion->validaFechaDerivacion($idcaso);
		foreach($resultado_fderivacion as $res_f){
			$fecha_reevaluacion = $res_f['de_fderivacion'];
		}
		} else if(($tipo=='Derivacion externa')&&($_SESSION['idetapa']=='5')){
		
		$resultado_fderivacion2 = $derivacion->validaFechaDerivacion($idcaso);
		foreach($resultado_fderivacion2 as $res_f){
			$fecha_reevaluacion = $res_f['de_fderivacion'];
		}
		} elseif(($tipo=='Derivacion MST')&&($_SESSION['idetapa']=='5')){
		
		$resultado_fderivacionMST = $derivacion->validaFechaReevaluacionTermino($idcaso);
		foreach($resultado_fderivacionMST as $res_f){
			$fecha_reevaluacion = $res_f['de_fingresoprograma'];
			
		}
		} 

$caso = new Casos(null);
$resultado = $caso->entregaCaso($idcaso);

$resultado_vfinicio = $caso->validaFechaContactabilidad($idcaso); 
foreach($resultado_vfinicio as $fcreacion){
	$fcreacion_caso = $fcreacion['vi_fecha'];
}

$resultado_afinicio = $caso->validaFechaAnalisisInicio($idcaso); 
foreach($resultado_afinicio as $fcreaciona_ini){
	$fcreacion_inicio = $fcreaciona_ini['an_fecinicio'];
}


if(count($resultado)>0){	
	$analisis = new Analisis(null);
	$res_analisis = $analisis->entregaAssetAnalisis($idcaso,$etapa);
	$res_fechaEvaluacion = $analisis->verificarAssetFechaAnalisis($idcaso,1);
	$analisis->Close();
	
	$fecinicio	=	'';
	$fectermino	=	'';
	$detalle_dificultad	=	'';
	$detalle_porobtener	=	'';
	$detalle_causal		=	'';
	$victima_especifica	=	'';
	$victima_vulnerable	=	'';
	$victima_repetida	=	'';
	$victima_desconocida	=	'';
	$motivacion_racial	=	'';
	$detalle_relacion	=	'';
	$delito			=	'';
	$lugar_delito	=	'';
	$metodo_delito	=	'';
	$planificacion_delito	=	'';
	$arma_delito	=	'';
	$valor_delito	=	'';
	$alcohol_delito	=	'';					      
	$grupal_delito	=	'';	
	$intencion_delito	=	'';	
	$diferencias_delito	=	'';	
	$vulnerabilidad_delito	=	'';	
	$agravante_delito	=	'';	
	$impacto_delito		=	'';	
	$consecuencia_delito	=	'';	
	$circunstancia_delito	=	'';	
	$motivaciones_delito	=	'';	
	$actitudes_delito	=	'';
	$creencia_delito	=	'';
	$similitud_previa 	= '';
	$detalle_similitud 	= '';
	$aumento_gravedad 	= '';
	$detalle_aumento 	= '';
	$especializacion 	= '';
	$detalle_especializacion = '';
	$interrupcion = '';
	$detalle_interrupcion = '';
	$intentos_desistir = '';
	$detalle_intentos = '';
	$detalle_transgresion = '';
	$primera_detencion = '';
	$primera_condena = '';
	$condenas_previas = '';
	$tiempo_medida = '';
	$instancias_incumplimientos = '';
	$evidencia = '';
	$chkevaluacion = '';
	$chkmedidasnna = '';
	$chkdetalleotro = '';
	$fecevaluacion = '';
	$opcion = 'insert';
	
	foreach( $res_analisis as $res ){
		$fecinicio	=	Util::formatFecha($res['an_fecinicio']);
		$fectermino	=	Util::formatFecha($res['an_fectermino']);
		$detalle_dificultad	=	$res['an_detalle_dificultad'];
		$detalle_porobtener	=	$res['an_detalle_porobtener'];
		$detalle_causal		=	$res['an_detalle_causal'];
		$victima_especifica	=	$res['an_victima_especifica'];
		$victima_vulnerable	=	$res['an_victima_vulnerable'];
		$victima_repetida	=	$res['an_victima_repetida'];
		$victima_desconocida	=	$res['an_victima_desconocida'];
		$motivacion_racial	=	$res['an_motivacion_racial'];
		$detalle_relacion	=	$res['an_detalle_relacion'];
		$delito			=	$res['an_delito'];
		$lugar_delito	=	$res['an_lugar_delito'];
		$metodo_delito	=	$res['an_metodo_delito'];
		$planificacion_delito	=	$res['an_planificacion_delito'];
		$arma_delito	=	$res['an_arma_delito'];
		$valor_delito	=	$res['an_valor_delito'];
		$alcohol_delito	=	$res['an_alcohol_delito'];					      
		$grupal_delito	=	$res['an_grupal_delito'];
		$intencion_delito	=	$res['an_intencion_delito'];
		$diferencias_delito	=	$res['an_diferencias_delito'];
		$vulnerabilidad_delito	=	$res['an_vulnerabilidad_delito'];
		$agravante_delito	=	$res['an_agravante_delito'];
		$impacto_delito		=	$res['an_impacto_delito'];
		$consecuencia_delito	=	$res['an_consecuencia_delito'];
		$circunstancia_delito	=	$res['an_circunstancia_delito'];
		$motivaciones_delito	=	$res['an_motivaciones_delito'];
		$actitudes_delito	=	$res['an_actitudes_delito'];
		$creencia_delito	=	$res['an_creencia_delito'];
		$similitud_previa 	= $res['an_similitud_previa'];
		$detalle_similitud 	= $res['an_detalle_similitud'];
		$aumento_gravedad 	= $res['an_aumento_gravedad'];
		$detalle_aumento 	= $res['an_detalle_aumento'];
		$especializacion 	= $res['an_especializacion'];
		$detalle_especializacion = $res['an_detalle_especializacion'];
		$interrupcion = $res['an_interrupcion'];
		$detalle_interrupcion = $res['an_detalle_interrupcion'];
		$intentos_desistir = $res['an_intentos_desistir'];
		$detalle_intentos = $res['an_detalle_intentos'];
		$detalle_transgresion = $res['an_detalle_transgresion'];
		$primera_detencion = $res['an_primera_detencion'];
		$primera_condena = $res['an_primera_condena'];
		$condenas_previas = $res['an_condenas_previas'];
		$tiempo_medida = $res['an_tiempo_medida'];
		$instancias_incumplimientos = $res['an_instancias_incumplimientos'];
		$evidencia = $res['an_evidencia'];
		$chkevaluacion = explode(",", $res['an_chkevaluacion']);
		$chkmedidasnna = explode(",", $res['an_chkmedidasnna']);
		$chkdetalleotro = $res['an_chkdetalleotro'];
		$opcion = 'update';
	}
	
	foreach( $res_fechaEvaluacion as $res_analisis ){
		$fecha_evaluacion = $res_analisis['tr_fecha'];
	}

	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetAnalisis_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}

?>

<script type="text/javascript">
$(document).ready(function(){
	//load(1);

	
	$("#datepicker0").datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		buttonImage: '../images/icon/iconCalendario.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		/*yearRange: '-10:-0',*/
/*		maxDate: "-18Y",
		minDate: "-100Y"*/
		maxDate:"<?php echo date("d-m-Y"); ?>",
        minDate:"<?php if($_SESSION['idetapa']=='3'){if($fcreacion_inicio=='') { echo $fcreacion_caso; } else { echo $fcreacion_inicio;}} elseif($_SESSION['idetapa']=='5'){ echo $fecha_reevaluacion; }?>"
	
	});
	
	$("#datepicker1").datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		buttonImage: '../images/icon/iconCalendario.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		/*yearRange: '-10:-0',*/
/*		maxDate: "-18Y",
		minDate: "-100Y"*/
		maxDate: "<?php echo date("d-m-Y"); ?>",
        minDate: "<?php if($_SESSION['idetapa']=='3'){echo $fcreacion_caso; } elseif($_SESSION['idetapa']=='5'){ echo $fecha_reevaluacion; }?>"
	});
			
});

function load(page){
    $.ajax({
		url:'visAssetAnalisis-ajax.php?action=ajax&page='+page,
		success:function(data){
			$("#resultado").html(data).fadeIn('slow');
		}
	})
}

</script>
</head>
<body>
<div id="content-wrapper">  		
<section>
<div class="contenedor">  
  <div class="menuExtra">
       				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <h2></h2>
                	
                    <div class="caja">
       				  <h3>An&aacute;lisis <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>
                    </div>
      <form name="form_analisis" id="form_analisis" method="post" action="insAssetAnalisis-ajax.php" onSubmit="return validarAssetFechas()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left"><table width="100%">
                        <caption style="color:#2571B7">1. Informaci&oacute;n usada para evaluaci&oacute;n</caption>
                        <tr>
                            <td width="12%" class="alt"><label>Fecha inicio evaluaci&oacute;n</label></td>
                            <td width="20%">
                            <input type="text" name="fecinicio" id="datepicker0" size="8" maxlength="10" readonly value="<?php echo $fecinicio;?>"/>
                            </td>
                            <td width="14%" class="alt"><label>Fecha t&eacute;rmino evaluaci&oacute;n</label></td>
                            <td width="26%">
                            <input type="text" name="fectermino" id="datepicker1" size="8" maxlength="10" readonly value="<?php echo $fectermino;?>"/>
                            </td>
                            <td width="28%">&nbsp;</td>
                          </tr>
                        </table>
                        <table width="100%">
                        <tr>
                        	<td width="2%"><input type="checkbox" name="chkevaluacion[]" value="NNA evaluado" <?php if($chkevaluacion!=''){if(in_array("NNA evaluado", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>NNA evaluado</label></td>
                            <td width="2%"><input type="checkbox" name="chkevaluacion[]" value="Otros programas municipales" <?php if($chkevaluacion!=''){if(in_array("Otros programas municipales", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Otros programas municipales</label></td>
                            <td width="2%"><input type="checkbox" name="chkevaluacion[]" value="Profesional Vida Nueva" <?php if($chkevaluacion!=''){if(in_array("Profesional Vida Nueva", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Profesional 24 Horas</label></td>     
                            <td width="2%"><input type="checkbox" name="chkevaluacion[]" value="Familiar o adulto responsable" <?php if($chkevaluacion!=''){if(in_array("Familiar o adulto responsable", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Familiar o adulto responsable</label></td>  
                            <td width="2%"><input type="checkbox" name="chkevaluacion[]" value="ONGs" <?php if($chkevaluacion!=''){if(in_array("ONGs", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>ONGs</label></td>
                            <td width="2%"><input type="checkbox" name="chkevaluacion[]" value="Profesional RPA" <?php if($chkevaluacion!=''){if(in_array("Profesional RPA", $chkevaluacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Profesional RPA</label></td>                      
                        </tr>
                        <tr>                            
                            <td><input type="checkbox" name="chkevaluacion[]" value="Escuela" <?php if($chkevaluacion!=''){if(in_array("Escuela", $chkevaluacion)) echo "checked";}?>></td>
                            <td><label>Escuela</label></td>
                            <td><input type="checkbox" name="chkevaluacion[]" value="PSI 24 horas" <?php if($chkevaluacion!=''){if(in_array("PSI 24 horas", $chkevaluacion)) echo "checked";}?>></td>
                            <td><label>PSI 24 horas</label></td>                   
                            <td><input type="checkbox" name="chkevaluacion[]" value="Servicio de salud" <?php if($chkevaluacion!=''){if(in_array("Servicio de salud", $chkevaluacion)) echo "checked";}?>></td>
                            <td><label>Servicio de salud</label></td>  
                            <td><input type="checkbox" name="chkevaluacion[]" value="Tribunales de familia" <?php if($chkevaluacion!=''){if(in_array("Tribunales de familia", $chkevaluacion)) echo "checked";}?>></td>
                            <td><label>Tribunales de familia</label></td>
                            <td><input type="checkbox" name="chkevaluacion[]" value="Otra" <?php if($chkevaluacion!=''){if(in_array("Otra", $chkevaluacion)) echo "checked";}?>></td>
                            <td><label>Otra</label></td>
                            <td colspan="2"><input type="text" name="chkdetalleotro" size="30" maxlength="50" placeholder="Detallar Otro" value="<?php echo $chkdetalleotro;?>"></td>
                        </tr>
                        <tr>
							<td colspan="12"><b>Proporcionar detalles de cualquier difucultad particular en obtener la información</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><textarea cols="185" rows="3" name="detalle_dificultad" id="detalle_dificultad" onKeyUp="Contar('detalle_dificultad','MostContador','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('detalle_dificultad','MostContador','{CHAR} caracteres restantes.',1000);" onBlur="Contar('detalle_dificultad','MostContador','{CHAR} caracteres restantes.',1000);"><?php echo $detalle_dificultad;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador">1000 caracteres restantes</span></label>
                            </td>
                        </tr> 
                        <tr>
							<td colspan="12"><b>Especificar si una proporción significativa de información aún debe ser obtenida</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><textarea cols="185" rows="3" name="detalle_porobtener" id="detalle_porobtener" onKeyUp="Contar('detalle_porobtener','MostContador2','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('detalle_porobtener','MostContador2','{CHAR} caracteres restantes.',1000);" onBlur="Contar('detalle_porobtener','MostContador2','{CHAR} caracteres restantes.',1000);"><?php echo $detalle_porobtener;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador2">1000 caracteres restantes</span></label></td>
                        </tr>  
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td align="left" class="datos_sgs">
                        <table width="100%">
                        <caption style="color:#2571B7">2. Detalle de la causal de ingreso al PSI 24 horas (Refierase a la conducta de derivación)</caption>
                        <tr>
							<td colspan="12"><b>Resumen</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><textarea cols="185" rows="3" name="detalle_causal" id="detalle_causal" onKeyUp="Contar('detalle_causal','MostContador3','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('detalle_causal','MostContador3','{CHAR} caracteres restantes.',1000);" onBlur="Contar('detalle_causal','MostContador3','{CHAR} caracteres restantes.',1000);"><?php echo $detalle_causal;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador3">1000 caracteres restantes</span></label></td>
                        </tr>
                        </table>
                        <table width="100%" align="center">
                        <tr>
                        	<td width="10%" align="center"><label>Víctima específica</label></td>
                            <td width="8%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="victima_especifica" value="Si" <?php if($victima_especifica == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="victima_especifica" value="No" <?php if($victima_especifica == 'No') echo 'checked';?>>
                            </td>
                            <td width="10%" align="center"><label>Víctima vulnerable</label></td>
                            <td width="8%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="victima_vulnerable" value="Si" <?php if($victima_vulnerable == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="victima_vulnerable" value="No" <?php if($victima_vulnerable == 'No') echo 'checked';?>>
                            </td>
                            <td width="10%" align="center"><label>Víctima repetida</label></td>
                            <td width="8%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="victima_repetida" value="Si" <?php if($victima_repetida == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="victima_repetida" value="No" <?php if($victima_repetida == 'No') echo 'checked';?>>
                            </td>     
                            <td width="13%" align="center"><label>Víc. desconocida para él/ella</label></td>
                            <td width="7%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="victima_desconocida" value="Si" <?php if($victima_desconocida == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="victima_desconocida" value="No" <?php if($victima_desconocida == 'No') echo 'checked';?>>
                            </td> 
                            <td width="10%" align="center"><label>Motivación racial</label></td>
                            <td width="8%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="motivacion_racial" value="Si" <?php if($motivacion_racial == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="motivacion_racial" value="No" <?php if($motivacion_racial == 'No') echo 'checked';?>>
                            </td>                    
                        <tr>
                        <tr>
							<td colspan="10"><b>Detalle de la relación con la víctima.</b></td>
                        </tr>  
                        <tr>
                        	<td colspan="10"><textarea cols="185" rows="3" name="detalle_relacion" id="detalle_relacion" onKeyUp="Contar('detalle_relacion','MostContador4','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('detalle_relacion','MostContador4','{CHAR} caracteres restantes.',1000);" onBlur="Contar('detalle_relacion','MostContador4','{CHAR} caracteres restantes.',1000);"><?php echo $detalle_relacion;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador4">1000 caracteres restantes</span></label></td>
                        </tr>    
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td align="left" class="datos_sgs">
                        <table width="100%">
                        <caption style="color:#2571B7">3. Análisis de la infracción/ transgresión de la norma</caption>
                        <tr>
                        	<td colspan="2">3.1 <u>Acciones e intenciones</u></td>
                        </tr>    
                        <tr>
							<td colspan="2"><b>¿Cuál fue el delito?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="delito" id="delito" onKeyUp="Contar('delito','MostContador5','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('delito','MostContador5','{CHAR} caracteres restantes.',255);" onBlur="Contar('delito','MostContador5','{CHAR} caracteres restantes.',255);"><?php echo $delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador5">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Dónde, cuándo y con quién fue cometido?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="lugar_delito" id="lugar_delito" onKeyUp="Contar('lugar_delito','MostContador6','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('lugar_delito','MostContador6','{CHAR} caracteres restantes.',255);" onBlur="Contar('lugar_delito','MostContador6','{CHAR} caracteres restantes.',255);"><?php echo $lugar_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador6">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Qué métodos fueron usados?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="metodo_delito" id="metodo_delito" onKeyUp="Contar('metodo_delito','MostContador7','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('metodo_delito','MostContador7','{CHAR} caracteres restantes.',255);" onBlur="Contar('metodo_delito','MostContador7','{CHAR} caracteres restantes.',255);"><?php echo $metodo_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador7">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Qué grado de planificación involucró?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="planificacion_delito" id="planificacion_delito" onKeyUp="Contar('planificacion_delito','MostContador8','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('planificacion_delito','MostContador8','{CHAR} caracteres restantes.',255);" onBlur="Contar('planificacion_delito','MostContador8','{CHAR} caracteres restantes.',255);"><?php echo $planificacion_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador8">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Usó algún tipo de arma?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="arma_delito" id="arma_delito" onKeyUp="Contar('arma_delito','MostContador9','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('arma_delito','MostContador9','{CHAR} caracteres restantes.',255);" onBlur="Contar('arma_delito','MostContador9','{CHAR} caracteres restantes.',255);"><?php echo $arma_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador9">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuál fue el valor del dinero o propiedad robada?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="valor_delito" id="valor_delito" onKeyUp="Contar('valor_delito','MostContador10','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('valor_delito','MostContador10','{CHAR} caracteres restantes.',255);" onBlur="Contar('valor_delito','MostContador10','{CHAR} caracteres restantes.',255);"><?php echo $valor_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador10">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Usó alcohol y/o drogas al momento del delito?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="alcohol_delito" id="alcohol_delito" onKeyUp="Contar('alcohol_delito','MostContador11','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('alcohol_delito','MostContador11','{CHAR} caracteres restantes.',255);" onBlur="Contar('alcohol_delito','MostContador11','{CHAR} caracteres restantes.',255);"><?php echo $alcohol_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador11">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Fue un delito grupal?, si es así, ¿el NNA fue el líder o seguidor?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="grupal_delito" id="grupal_delito" onKeyUp="Contar('grupal_delito','MostContador12','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('grupal_delito','MostContador12','{CHAR} caracteres restantes.',255);" onBlur="Contar('grupal_delito','MostContador12','{CHAR} caracteres restantes.',255);"><?php echo $grupal_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador12">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las intenciones del NNA?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="intencion_delito" id="intencion_delito" onKeyUp="Contar('intencion_delito','MostContador13','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('intencion_delito','MostContador13','{CHAR} caracteres restantes.',255);" onBlur="Contar('intencion_delito','MostContador13','{CHAR} caracteres restantes.',255);"><?php echo $intencion_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador13">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las diferencias entre sus intenciones y sus acciones?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="diferencias_delito" id="diferencias_delito" onKeyUp="Contar('diferencias_delito','MostContador14','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('diferencias_delito','MostContador14','{CHAR} caracteres restantes.',255);" onBlur="Contar('diferencias_delito','MostContador14','{CHAR} caracteres restantes.',255);"><?php echo $diferencias_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador14">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Fue la víctima objetivo/ al azar/ preparada/ particularmente vulnerable?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="vulnerabilidad_delito" id="vulnerabilidad_delito" onKeyUp="Contar('vulnerabilidad_delito','MostContador15','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('vulnerabilidad_delito','MostContador15','{CHAR} caracteres restantes.',255);" onBlur="Contar('vulnerabilidad_delito','MostContador15','{CHAR} caracteres restantes.',255);"><?php echo $vulnerabilidad_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador15">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Hubo cualquier otro actor agravante o atenuante?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="agravante_delito" id="agravante_delito" onKeyUp="Contar('agravante_delito','MostContador16','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('agravante_delito','MostContador16','{CHAR} caracteres restantes.',255);" onBlur="Contar('agravante_delito','MostContador16','{CHAR} caracteres restantes.',255);"><?php echo $agravante_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador16">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
                        	<td colspan="2">3.2 <u>Resultados y consecuencias</u></td>
                        </tr>    
                        <tr>
							<td colspan="2"><b>¿Cuál es el impacto para la víctima en el corto y largo plazo?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="impacto_delito" id="impacto_delito" onKeyUp="Contar('impacto_delito','MostContador17','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('impacto_delito','MostContador17','{CHAR} caracteres restantes.',255);" onBlur="Contar('impacto_delito','MostContador17','{CHAR} caracteres restantes.',255);"><?php echo $impacto_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador17">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las consecuencias para el NNA (por ejemplo, reacción al arresto y detención, respuesta familiar)?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="consecuencia_delito" id="consecuencia_delito" onKeyUp="Contar('consecuencia_delito','MostContador18','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('consecuencia_delito','MostContador18','{CHAR} caracteres restantes.',255);" onBlur="Contar('consecuencia_delito','MostContador18','{CHAR} caracteres restantes.',255);"><?php echo $consecuencia_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador18">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
                        	<td colspan="2">3.3 <u>Razones y motivos</u></td>
                        </tr>    
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las circunstancias personales y sociales del NNA al momento del delito?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="circunstancia_delito" id="circunstancia_delito" onKeyUp="Contar('circunstancia_delito','MostContador19','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('circunstancia_delito','MostContador19','{CHAR} caracteres restantes.',255);" onBlur="Contar('circunstancia_delito','MostContador19','{CHAR} caracteres restantes.',255);"><?php echo $circunstancia_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador19">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las motivaciones del NNA?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="motivaciones_delito" id="motivaciones_delito" onKeyUp="Contar('motivaciones_delito','MostContador20','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('motivaciones_delito','MostContador20','{CHAR} caracteres restantes.',255);" onBlur="Contar('motivaciones_delito','MostContador20','{CHAR} caracteres restantes.',255);"><?php echo $motivaciones_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador20">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Cuáles fueron las actitudes del NNA?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="actitudes_delito" id="actitudes_delito" onKeyUp="Contar('actitudes_delito','MostContador21','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('actitudes_delito','MostContador21','{CHAR} caracteres restantes.',255);" onBlur="Contar('actitudes_delito','MostContador21','{CHAR} caracteres restantes.',255);"><?php echo $actitudes_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador21">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿El NNA tiene alguna actitud/creencia particular que podría haber influido en el delito?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="creencia_delito" id="creencia_delito" onKeyUp="Contar('creencia_delito','MostContador22','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('creencia_delito','MostContador22','{CHAR} caracteres restantes.',255);" onBlur="Contar('creencia_delito','MostContador22','{CHAR} caracteres restantes.',255);"><?php echo $creencia_delito;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador22">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
                        	<td colspan="2">3.4 <u>Patrones de comportamiento infractor/transgresor</u></td>
                        </tr>    
                        <tr>
							<td colspan="2"><b>¿Hay alguna similitud o diferencia con comportamientos previos?</b></td>
                        </tr>
                        <tr>
                        	<td width="10%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="similitud_previa" value="Si" <?php if($similitud_previa == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="similitud_previa" value="No" <?php if($similitud_previa == 'No') echo 'checked';?>>
                            </td>
                        	<td><textarea cols="165" rows="2" name="detalle_similitud" id="detalle_similitud" onKeyUp="Contar('detalle_similitud','MostContador23','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('detalle_similitud','MostContador23','{CHAR} caracteres restantes.',255);" onBlur="Contar('detalle_similitud','MostContador23','{CHAR} caracteres restantes.',255);"><?php echo $detalle_similitud;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador23">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Ha habido algún aumento/disminución en la gravedad y/o frecuencia?</b></td>
                        </tr>
                        <tr>
                        	<td width="10%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="aumento_gravedad" value="Si" <?php if($aumento_gravedad == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="aumento_gravedad" value="No" <?php if($aumento_gravedad == 'No') echo 'checked';?>>
                            </td>
                        	<td><textarea cols="165" rows="2" name="detalle_aumento" id="detalle_aumento" onKeyUp="Contar('detalle_aumento','MostContador24','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('detalle_aumento','MostContador24','{CHAR} caracteres restantes.',255);" onBlur="Contar('detalle_aumento','MostContador24','{CHAR} caracteres restantes.',255);"><?php echo $detalle_aumento;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador24">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿El NNA muestra una especialización/diversidad de infracciones?</b></td>
                        </tr>
                        <tr>
                        	<td width="10%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="especializacion" value="Si" <?php if($especializacion == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="especializacion" value="No" <?php if($especializacion == 'No') echo 'checked';?>>
                            </td>
                        	<td><textarea cols="165" rows="2" name="detalle_especializacion" id="detalle_especializacion" onKeyUp="Contar('detalle_especializacion','MostContador25','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('detalle_especializacion','MostContador25','{CHAR} caracteres restantes.',255);" onBlur="Contar('detalle_especializacion','MostContador25','{CHAR} caracteres restantes.',255);"><?php echo $detalle_especializacion;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador25">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿Hay alguna interrupción en los patrones transgresores?</b></td>
                        </tr>
                        <tr>
                        	<td width="10%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="interrupcion" value="Si" <?php if($interrupcion == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="interrupcion" value="No" <?php if($interrupcion == 'No') echo 'checked';?>>
                            </td>
                        	<td><textarea cols="165" rows="2" name="detalle_interrupcion" id="detalle_interrupcion" onKeyUp="Contar('detalle_interrupcion','MostContador26','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('detalle_interrupcion','MostContador26','{CHAR} caracteres restantes.',255);" onBlur="Contar('detalle_interrupcion','MostContador26','{CHAR} caracteres restantes.',255);"><?php echo $detalle_interrupcion;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador26">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>¿El NNA ha tenido intentos previos de desistir?</b></td>
                        </tr>
                        <tr>
                        	<td width="10%">
                            <label>Si</label>&nbsp;&nbsp;<input type="radio" name="intentos_desistir" value="Si" <?php if($intentos_desistir == 'Si') echo 'checked';?>>&nbsp;
                            <label>No</label>&nbsp;&nbsp;<input type="radio" name="intentos_desistir" value="No" <?php if($intentos_desistir == 'No') echo 'checked';?>>
                            </td>
                        	<td><textarea cols="165" rows="2" name="detalle_intentos" id="detalle_intentos" onKeyUp="Contar('detalle_intentos','MostContador27','{CHAR} caracteres restantes.',255);" onKeyPress="Contar('detalle_intentos','MostContador27','{CHAR} caracteres restantes.',255);" onBlur="Contar('detalle_intentos','MostContador27','{CHAR} caracteres restantes.',255);"><?php echo $detalle_intentos;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador27">255 caracteres restantes</span></label></td>
                        </tr>
                        <tr>
							<td colspan="2"><b>Análisis y evidencia de la infracción/transgresión de la norma</b></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><textarea cols="185" rows="2" name="detalle_transgresion" id="detalle_transgresion" onKeyUp="Contar('detalle_transgresion','MostContador28','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('detalle_transgresion','MostContador28','{CHAR} caracteres restantes.',1000);" onBlur="Contar('detalle_transgresion','MostContador28','{CHAR} caracteres restantes.',1000);"><?php echo $detalle_transgresion;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador28">1000 caracteres restantes</span></label></td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td align="left" class="datos_sgs">
                    	<table width="100%">
                        <caption style="color:#2571B7">4. Historia asociada a la infracción/transgresión de la norma</caption>
                        <tr>
                            <td width="12%"><label>Edad 1° detención o conducción (contacto policía)</label></td>
                            <td width="10%">
                            <input type="text" name="primera_detencion" size="2" maxlength="2" onKeyPress="return soloNumeros(event)" value="<?php echo $primera_detencion;?>"/>
                            </td>
                            <td width="12%"><label>Edad primera condena</label></td>
                            <td width="10%">
                            <input type="text" name="primera_condena" size="2" maxlength="2" onKeyPress="return soloNumeros(event)" value="<?php echo $primera_condena;?>"/>
                            </td>
                            <td width="12%"><label>N° condenas previas</label></td>
                            <td width="10%">
                            <input type="text" name="condenas_previas" size="2" maxlength="2" onKeyPress="return soloNumeros(event)" value="<?php echo $condenas_previas;?>"/>
                            </td>
                            <td width="12%"><label>Tiempo desde la última medida dispuesta por un juez (meses)</label></td>
                            <td width="10%">
                            <input type="text" name="tiempo_medida" size="2" maxlength="2" onKeyPress="return soloNumeros(event)" value="<?php echo $tiempo_medida;?>"/>
                            </td>
                        </tr>
                        </table>
                        <table width="100%">
                        <tr>
                        	<td colspan="12"><b>Indicar si el NNA ha recibido alguna de las siguientes medidas</b>:</td>
                        </tr>    
                        <tr>
                        	<td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Salidas alternativas" <?php if($chkmedidasnna!=''){if(in_array("Salidas alternativas", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Salidas alternativas</label></td>
                            <td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Medidas cautelares ambulatorias" <?php if($chkmedidasnna!=''){if(in_array("Medidas cautelares ambulatorias", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Medidas cautelares ambulatorias e internación provisoria</label></td>
                            <td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Servicios en beneficio de la comunidad" <?php if($chkmedidasnna!=''){if(in_array("Servicios en beneficio de la comunidad", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Servicio en beneficio de la comunidad y reparación del daño</label></td>     
                            <td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Libertad asistida" <?php if($chkmedidasnna!=''){if(in_array("Libertad asistida", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Libertad asistida</label></td>  
                            <td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Libertad asistida especial" <?php if($chkmedidasnna!=''){if(in_array("Libertad asistida especial", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Libertad asistida especial</label></td>
                            <td width="2%"><input type="checkbox" name="chkmedidasnna[]" value="Sancion en regimen semicerrado" <?php if($chkmedidasnna!=''){if(in_array("Sancion en regimen semicerrado", $chkmedidasnna)) echo "checked";}?>></td>
                            <td width="15%"><label>Sanción en régimen semicerrado</label></td>                      
                        </tr>
                        <tr>                            
                            <td><input type="checkbox" name="chkmedidasnna[]" value="Sancion en regimen cerrado" <?php if($chkmedidasnna!=''){if(in_array("Sancion en regimen cerrado", $chkmedidasnna)) echo "checked";}?>></td>
                            <td colspan="11"><label>Sanción en régimen cerrado</label></td>
                        </tr>
                        <tr>
							<td colspan="12"><b>¿Ha habido cualquier instancia de incumplimiento de las medidas previas?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12">
                            <select name="instancias_incumplimientos">
                            <option value="">Seleccione...</option>
                            <option value="1" <?php if($instancias_incumplimientos == 'Si') echo 'selected';?>>Si</option>
                            <option value="2" <?php if($instancias_incumplimientos == 'No') echo 'selected';?>>No</option>
                            <option value="3" <?php if($instancias_incumplimientos == 'No se sabe') echo 'selected';?>>No se sabe</option>
                            </select>
                            </td>
                        </tr> 
                        <tr>
							<td colspan="12"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onKeyUp="Contar('evidencia','MostContador29','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('evidencia','MostContador29','{CHAR} caracteres restantes.',1000);" onBlur="Contar('evidencia','MostContador29','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador29">1000 caracteres restantes</span></label></td>
                        </tr>  
                        </table>
                    </td>
                    </tr>
                    <tr>
                        <td align="left"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">
                                                <input type="submit" class="boton" value="Grabar Informaci&oacute;n" id="analisis">
                                                <input type="hidden" name="fecha_evaluacion" value="<?php print str_replace("","-",$fecha_evaluacion); ?>"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="button" class="boton" value="Volver" id="btn-volver" name="btn-vovler" onClick="history.back()"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                      </tr>
                    </table>    
                    </form>  
                    <br>
                    <br>  
              
   
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
	
	alert("Ocurrió un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	<?php }
	$_SESSION['mensaje']="";
}?>