<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Educacion.class.php');
require_once('../clases/Util.class.php');

$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$idetapa =  filter_var($_GET['idetapa'], FILTER_SANITIZE_STRING);

$_SESSION['idcaso'] = $idcaso;
$_SESSION['idetapa'] = $idetapa;

if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$asset = "ASSET";
} else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
	$asset = "Reevaluaciòn ASSET";
}

$caso = new Casos(null);
$resultado = $caso->entregaCaso($idcaso);
if(count($resultado)>0){	
	$obj = new Educacion(null);
	$rs = $obj->entregaAssetEducacion($idcaso,$etapa);
	$obj->Close();
	
	$horasdedicadas	=	'';
	$horasefectivas	=	'';
	$inasistencia	=	'';
	$complementarios	=	'';
	$alfabetizacion		=	'';
	$necesidades	=	'';
	$aritmeticas	=	'';
	$certificado	=	'';
	$evidencia1	=	'';
	$actitudnegativa	=	'';
	$relacionpobre	=	'';
	$faltaadherencia	=	'';
	$actitudpadres	=	'';
	$victimabullying	=	'';
	$victimariobullying	=	'';
	$otro	=	'';
	$evidencia2	=	'';
	$calificacion	=	'';
	$chkeducacion	=	'';
	$chkdetalleotro	=	'';
	$chkinasistencia	=	'';
	$chkdetalleotrasinasistencias	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$horasdedicadas	=	$res['ed_horasdedicadas'];
		$horasefectivas	=	$res['ed_horasefectivas'];
		$inasistencia	=	$res['ed_inasistencia'];
		$complementarios	=	$res['ed_complementarios'];
		$alfabetizacion		=	$res['ed_alfabetizacion'];
		$necesidades = $res['ed_necesidades'];
		$aritmeticas = $res['ed_aritmeticas'];
		$certificado = $res['ed_certificado'];
		$evidencia1 = $res['ed_evidencia1'];
		$actitudnegativa = $res['ed_actitudnegativa'];
		$relacionpobre = $res['ed_relacionpobre'];
		$faltaadherencia = $res['ed_faltaadherencia'];
		$actitudpadres = $res['ed_actitudpadres'];
		$victimabullying = $res['ed_victimabullying'];
		$victimariobullying = $res['ed_victimariobullying'];
		$otro = $res['ed_otro'];
		$evidencia2 = $res['ed_evidencia2'];
		$calificacion = $res['ed_calificacion'];
		$chkeducacion = explode(",", $res['ed_chkeducacion']);
		$chkdetalleotro = $res['ed_chkdetalleotro'];
		$chkinasistencia = explode(",", $res['ed_chkinasistencia']);
		$chkdetalleotrasinasistencias = $res['ed_chkdetalleotrasinasistencias'];		
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetEducacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}
?>
<script>
$(document).ready(function() {
            $("#inasistencia").on("change", function() {
               var valor = $("#inasistencia").val();
			  
               if(valor==''){
				$("#Expulsion").attr('disabled', true);
				  $("#Asuntos").attr('disabled', true);
				  $("#Enfermedad").attr('disabled', true);
				  $("#Suspension").attr('disabled', true);
				  $("#Otras").attr('disabled', true);  
				  $("#Expulsion").prop('checked', false);
				  $("#Asuntos").prop('checked', false);
				  $("#Enfermedad").prop('checked', false);
				  $("#Suspension").prop('checked', false);
				  $("#Otras").prop('checked', false);
			   } else if (valor == "Si") {
                  $("#Expulsion").attr('disabled', false);
				  $("#Asuntos").attr('disabled', false);
				  $("#Enfermedad").attr('disabled', false);
				  $("#Suspension").attr('disabled', false);
				  $("#Otras").attr('disabled', false);
				  $("#Otras").prop('checked', false);
    
                $(":checkbox").click(function () {
                var value = $("input:checked").val();
                if(value=='Otras inasistencias'){
					$('#chkdetalleotrasinasistencias').attr('readonly', false);
                        
				} else {
					$('#chkdetalleotrasinasistencias').attr('readonly', true);
				}
            });
               } else if (valor == "No") {
                  // deshabilitamos
				  $("#Expulsion").attr('disabled', true);
				  $("#Asuntos").attr('disabled', true);
				  $("#Enfermedad").attr('disabled', true);
				  $("#Suspension").attr('disabled', true);
                  $("#Otras").attr('disabled', true);
				  
				   $("#Expulsion").prop('checked', false);
				  $("#Asuntos").prop('checked', false);
				  $("#Enfermedad").prop('checked', false);
				  $("#Suspension").prop('checked', false);
				  $("#Otras").prop('checked', false);
		
               }
            });
			
			
			$("#Otros").click(function () {
                var value = $("input:checked").val();
                if(value=='Otro'){
					$('#chkdetalleotro').attr('readonly', false);
                        
				} else {
					$('#chkdetalleotro').attr('readonly', true);
				}
            });
			
         });
</script>
<div id="content-wrapper">
<section>
  <div class="contenedor">
    <h2></h2>
                	
                    <div class="caja">
        				<h3>3. Educación, Capacitación y Empleo (ECE) <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>   
                    <form name="form_educacion" id="form" method="post" action="insAssetEducacion-ajax.php" onsubmit="return validarAssetEducacion()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
                        	<td colspan="12"><b>¿Cuál de las siguientes situaciones describe mejor la situación de educación, capacitación o trabajo actual?</b></td>
                        </tr>
                        <tr>
                        	<td width="2%"><input type="checkbox" name="chkeducacion[]" value="Basica" <?php if($chkeducacion!=''){if(in_array("Basica", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Básica</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Media cientifico humanista" <?php if($chkeducacion!=''){if(in_array("Media cientifico humanista", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Media cientifico humanista</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Media tecnico profesional" <?php if($chkeducacion!=''){if(in_array("Media tecnico profesional", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Media tecnico profesional</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Educacion para adultos" <?php if($chkeducacion!=''){if(in_array("Educacion para adultos", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Educacion para adultos</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Programa reinsercion educativa" <?php if($chkeducacion!=''){if(in_array("Programa reinsercion educativa", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Programa reinserción educativa</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Examenes libres" <?php if($chkeducacion!=''){if(in_array("Examenes libres", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Exámenes libres</label></td>
                         </tr>
                         <tr>   
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Trabajo jornada completa" <?php if($chkeducacion!=''){if(in_array("Trabajo jornada completa", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Trabajo jornada completa</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Trabajo media jornada" <?php if($chkeducacion!=''){if(in_array("Trabajo media jornada", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Trabajo media jornada</label></td>   
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Trabajo temporal" <?php if($chkeducacion!=''){if(in_array("Trabajo temporal", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Trabajo temporal</label></td>
                            <td width="2%"><input type="checkbox" name="chkeducacion[]" value="Curso de capacitacion" <?php if($chkeducacion!=''){if(in_array("Curso de capacitacion", $chkeducacion)) echo "checked";}?>></td>
                            <td width="15%"><label>Curso de capacitacion</label></td>                        
                            <td><input type="checkbox" name="chkeducacion[]" value="Sin ocupacion o estudios" <?php if($chkeducacion!=''){if(in_array("Sin ocupacion o estudios", $chkeducacion)) echo "checked";}?>></td>
                            <td><label>Sin ocupación o estudios</label></td> 
                            <td><input type="checkbox" name="chkeducacion[]" id="Otros" value="Otro" <?php if($chkeducacion!=''){if(in_array("Otro", $chkeducacion)) echo "checked";}?> onChange="assetEducacion1(this)"></td>
                            <td><label>Otro</label></td> 
                        </tr>
                        <tr>
                        	<td colspan="12" align="right"><input type="text" id="chkdetalleotro" name="chkdetalleotro" size="22" maxlength="50" placeholder="Detalle Otro" readonly value="<?php echo $chkdetalleotro;?>" style="
    width: 180px;
"></td>
                        </tr>
                        <tr>
                        	<td colspan="4"><label>¿Cuántas horas ECE debería dedicar a la semana?</label>&nbsp;&nbsp;<input type="text" name="horasdedicadas" size="2" maxlength="2" value="<?php if($horasdedicadas=='') { echo '0'; } else { echo $horasdedicadas;}?>" onkeypress="return soloNumeros(event)"style="width: 55px;"></td>
                            <td colspan="8"><label>¿Cuántas horas efectivas le dedica a ECE en la semana?</label>&nbsp;&nbsp;<input type="text" name="horasefectivas" size="2" maxlength="2" value="<?php if($horasefectivas=='') { echo '0';} else { echo $horasefectivas; }?>" onkeypress="return soloNumeros(event)" style="width: 55px;"></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><label>¿Hay evidencia de inasistencia?</label>&nbsp;&nbsp;
                            <select name="inasistencia" id="inasistencia" style="width:150px;">
                            <option value="">Seleccione...</option>
                            <option value="Si" <?php if(isset($inasistencia) && $inasistencia == 'Si'){echo 'selected';}?>>Si</option>
                            <option value="No" <?php if(isset($inasistencia) && $inasistencia == 'No'){echo 'selected';}?>>No</option>
                            </select>
                            </td>
                        </tr>
                        <tr>   
                            <td width="2%"><input type="checkbox" name="chkinasistencia[]" id="Expulsion" value="Expulsion/desvinculacion" <?php if($chkinasistencia!=''){if(in_array("Expulsion/desvinculacion", $chkinasistencia)) echo "checked";}?> disabled></td>
                            <td width="15%"><label>Expulsión/desvinculación</label></td>
                            <td width="2%"><input type="checkbox" name="chkinasistencia[]" id="Asuntos" value="Asuntos familiares" <?php if($chkinasistencia!=''){if(in_array("Asuntos familiares", $chkinasistencia)) echo "checked";}?> disabled></td>
                             <td width="15%"><label>Suspensión temporal</label></td>
                            <td width="2%"><input type="checkbox" name="chkinasistencia[]" value="Enfermedad" id="Enfermedad" <?php if($chkinasistencia!=''){if(in_array("Enfermedad", $chkinasistencia)) echo "checked";}?> disabled></td>
                            <td width="15%"><label>Asuntos familiares</label></td>   
                            <td width="2%"><input type="checkbox" name="chkinasistencia[]" id="Suspension" value="Suspension temporal" <?php if($chkinasistencia!=''){if(in_array("Suspension temporal", $chkinasistencia)) echo "checked";}?> disabled></td>
                           
                            <td width="15%"><label>Enfermedad</label></td>                        
                            <td><input type="checkbox" name="chkinasistencia[]" value="Otras inasistencias" id="Otras" <?php if($chkinasistencia!=''){if(in_array("Otras inasistencias", $chkinasistencia)) echo "checked";}?> disabled></td>
                            <td><label>Otras inasistencias</label></td> 
                            <td colspan="2" align="right"><input type="text" name="chkdetalleotrasinasistencias" id="chkdetalleotrasinasistencias" readonly size="22" maxlength="50" placeholder="Detalle otras inasistencias" value="<?php echo $chkdetalleotrasinasistencias;?>" style="
    width: 180px;
"></td>
                        </tr>
                        </table>
                        <table width="100%">
                        <tr>
							<td><b>Logros educativos</b></td>
                        </tr>
                        <tr>
                        	<td colspan="5"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
                            	<thead>
                                    <tr>
                                        <th width="70%">&nbsp;</th>
                                        <th width="10%">Si</th>
                                        <th width="10%">No</th>
                                        <th width="10%">No Sabe</th>
                                  </tr>
                              </thead>
                                 <tbody>
                                 	<tr>
                                    	<td align="left">¿Tiene algún estudio complementario a la escuela?</td>
                                        <td align="center"><input type="radio" name="complementarios" value="Si" <?php if($complementarios == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="complementarios" value="No" <?php if($complementarios == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="complementarios" value="No se sabe" <?php if($complementarios == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Tiene necesidades educativas especiales (NNE) identificadas?</td>
                                        <td align="center"><input type="radio" name="necesidades" value="Si" <?php if($necesidades == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="necesidades" value="No" <?php if($necesidades == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="necesidades" value="No se sabe" <?php if($necesidades == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Si la respuesta es Si, ¿tiene algo que lo acredite?</td>
                                        <td align="center"><input type="radio" name="certificado" value="Si" <?php if($certificado == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="certificado" value="No" <?php if($certificado == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="certificado" value="No se sabe" <?php if($certificado == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Tiene dificultades de alfabetización?</td>
                                        <td align="center"><input type="radio" name="alfabetizacion" value="Si" <?php if($alfabetizacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="alfabetizacion" value="No" <?php if($alfabetizacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="alfabetizacion" value="No se sabe" <?php if($alfabetizacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Tiene dificultades aritméticas?</td>
                                        <td align="center"><input type="radio" name="aritmeticas" value="Si" <?php if($aritmeticas == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="aritmeticas" value="No" <?php if($aritmeticas == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="aritmeticas" value="No se sabe" <?php if($aritmeticas == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                </tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td><textarea cols="185" rows="3" name="evidencia1" id="evidencia1" onkeyup="Contar('evidencia1','MostContador','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia1','MostContador','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia1','MostContador','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia1;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador">1000 caracteres restantes</span></label>
                            </td>
                        </tr>
                        <tr>
							<td><b>Otros factores relacionados con la participación en ECE</b></td>
                        </tr>
                        <tr>
                        	<td><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
                            	<thead>
                                    <tr>
                                        <th width="70%">&nbsp;</th>
                                        <th width="10%">Si</th>
                                        <th width="10%">No</th>
                                        <th width="10%">No Sabe</th>
                                  </tr>
                              </thead>
                                 <tbody>
                                 	<tr>
                                    	<td align="left">Actitudes negativas hacia la educación/ capacitación/ empleo</td>
                                        <td align="center"><input type="radio" name="actitudnegativa" value="Si" <?php if($actitudnegativa == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actitudnegativa" value="No" <?php if($actitudnegativa == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actitudnegativa" value="No se sabe" <?php if($actitudnegativa == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de adherencia a la actual prestación de ECE (por ej., quiere desertar)</td>
                                        <td align="center"><input type="radio" name="faltaadherencia" value="Si" <?php if($faltaadherencia == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaadherencia" value="No" <?php if($faltaadherencia == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaadherencia" value="No se sabe" <?php if($faltaadherencia == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Víctima de violencia/ bullying</td>
                                        <td align="center"><input type="radio" name="victimabullying" value="Si" <?php if($victimabullying == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="victimabullying" value="No" <?php if($victimabullying == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="victimabullying" value="No se sabe" <?php if($victimabullying == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Víctimario de violencia/ bullying</td>
                                        <td align="center"><input type="radio" name="victimariobullying" value="Si" <?php if($victimariobullying == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="victimariobullying" value="No" <?php if($victimariobullying == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="victimariobullying" value="No se sabe" <?php if($victimariobullying == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Pobres relaciones con la mayoría de los profesores / tutores/ empleadores/ colegios</td>
                                        <td align="center"><input type="radio" name="relacionpobre" value="Si" <?php if($relacionpobre == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="relacionpobre" value="No" <?php if($relacionpobre == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="relacionpobre" value="No se sabe" <?php if($relacionpobre == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Actitudes negativas de los padres/ cuidadores hacia la educación/ capacitación o empleo</td>
                                        <td align="center"><input type="radio" name="actitudpadres" value="Si" <?php if($actitudpadres == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actitudpadres" value="No" <?php if($actitudpadres == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actitudpadres" value="No se sabe" <?php if($actitudpadres == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Otros problemas (por ejemplo, cambios frecuentes de escuela, la escuela es poco desafiante/ aburrida, no tiene dinero para comprar libros/ herramientas/ equipos)</td>
                                        <td align="center"><input type="radio" name="otro" value="Si" <?php if($otro == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otro" value="No" <?php if($otro == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otro" value="No se sabe" <?php if($otro == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                       		    </tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td><textarea cols="185" rows="3" name="evidencia2" id="evidencia2" onkeyup="Contar('evidencia2','MostContador2','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia2','MostContador2','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia2','MostContador2','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia2;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador2">1000 caracteres restantes</span></label>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td align="left" class="datos_sgs">
                        <table width="100%">
                        <tr>
							<td width="30%"><label>Puntúa el grado en el cual las condiciones del hogar del NNA se asocian a la probabilidad de que éste incurra en futuras infracciones<br>(0 = no asociado, 4 = muy asociado)</label></td>
                            <td align="left">
                            <input type="radio" name="calificacion" value="0" <?php if($calificacion == 0) echo 'checked';?>>&nbsp;<label>0</label>&nbsp;&nbsp;
                            <input type="radio" name="calificacion" value="1" <?php if($calificacion == 1) echo 'checked';?>>&nbsp;<label>1</label>&nbsp;&nbsp;
                            <input type="radio" name="calificacion" value="2" <?php if($calificacion == 2) echo 'checked';?>>&nbsp;<label>2</label>&nbsp;&nbsp;
                            <input type="radio" name="calificacion" value="3" <?php if($calificacion == 3) echo 'checked';?>>&nbsp;<label>3</label>&nbsp;&nbsp;
                            <input type="radio" name="calificacion" value="4" <?php if($calificacion == 4) echo 'checked';?>>&nbsp;<label>4</label>&nbsp;&nbsp;
                            </td>
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
                                            <td width="15%"><span class="datos_sgs">
                                              <input type="submit" class="boton" value="Grabar Información" />
                                            </span></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onClick="window.location.href='visCasos.php'" type="button" Value="Volver" class="boton"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                      </tr>
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
	alert("Ocurrió un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	<?php }
	$_SESSION['mensaje']="";
}?>