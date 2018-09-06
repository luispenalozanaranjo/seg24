<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/SaludMental.class.php');
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
	$obj = new SaludMental(null);
	$rs = $obj->entregaAssetSaludMental($idcaso,$etapa);
	$obj->Close();
	
	$acontecimientos	=	'';
	$circunstancias	=	'';
	$preocupaciones	=	'';
	$evidencia1	=	'';
	$diagnostico		=	'';
	$derivacion	=	'';
	$evidencia2	=	'';
	$afectado	=	'';
	$provocadano	=	'';
	$suicidio	=	'';
	$evidencia3	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$acontecimientos	=	$res['sm_acontecimientos'];
		$circunstancias	=	$res['sm_circunstancias'];
		$preocupaciones	=	$res['sm_preocupaciones'];
		$evidencia1	=	$res['sm_evidencia1'];
		$diagnostico	=	$res['sm_diagnostico'];
		$derivacion = $res['sm_derivacion'];
		$evidencia2 = $res['sm_evidencia2'];
		$afectado = $res['sm_afectado'];
		$provocadano = $res['sm_provocadano'];
		$suicidio = $res['sm_suicidio'];
		$evidencia3 = $res['sm_evidencia3'];
		$calificacion = $res['sm_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetSaludMental_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}
?>
<div id="content-wrapper">
<section>
<div class="contenedor">
  <h2></h2>
                	
                    <div class="caja">
        				<h3> 8. Salud Mental y Emocional <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_saludmental" id="form_saludmental" method="post" action="insAssetSaludMental-ajax.php" onSubmit="return validarAssetSaludMental()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
							<td><b>¿Está el funcionamiento diario del NNA significativamente afectado por emociones o pensamientos como resultado de lo siguiente?</b></td>
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
                                    	<td align="left">Resolución de importantes acontecimientos del pasado (por ejemplo rabia, tristeza, dolor, amargura)</td>
                                        <td align="center"><input type="radio" name="acontecimientos" value="Si" <?php if($acontecimientos == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="acontecimientos" value="No" <?php if($acontecimientos == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="acontecimientos" value="No se sabe" <?php if($acontecimientos == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Circunstancias actuales (por ejemplo frustración, estrés, tristeza, preocupación, ansiedad)</td>
                                        <td align="center"><input type="radio" name="circunstancias" value="Si" <?php if($circunstancias == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="circunstancias" value="No" <?php if($circunstancias == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="circunstancias" value="No se sabe" <?php if($circunstancias == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Preocupaciones sobre el futuro (por ejemplo preocupación, ansiedad, miedo, incertidumbre)</td>
                                        <td align="center"><input type="radio" name="preocupaciones" value="Si" <?php if($preocupaciones== 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="preocupaciones" value="No" <?php if($preocupaciones == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="preocupaciones" value="No se sabe" <?php if($preocupaciones == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	</tbody>
	                   	    </table></td>
                          </tr> 
                        <tr>
							<td><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td><textarea cols="185" rows="3" name="evidencia1" id="evidencia1" onkeyup="Contar('evidencia1','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia1','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia1','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia1;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador1">1000 caracteres restantes</span></label>
                            </td>
                        </tr> 
                        <tr>
							<td>&nbsp;</td>
                        </tr>
                        <tr>
                        	<td colspan="2"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">¿Ha habido algún diagnóstico formal de enfermedad mental?</td>
                                        <td align="center"><input type="radio" name="diagnostico" value="Si" <?php if($diagnostico == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="diagnostico" value="No" <?php if($diagnostico == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="diagnostico" value="No se sabe" <?php if($diagnostico == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Ha habido algún otro contacto o derivación a servicios de salud mental?</td>
                                        <td align="center"><input type="radio" name="derivacion" value="Si" <?php if($derivacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="derivacion" value="No" <?php if($derivacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="derivacion" value="No se sabe" <?php if($derivacion == 'No se sabe') echo 'checked';?>></td>
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
                        <tr>
							<td><b>¿Hay indicaciones de que alguna de las siguientes situaciones se presentan en el NNA?</b></td>
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
                                    	<td align="left">Se encuentra afectado por otras dificultades emocionales o psicológicas</td>
                                        <td align="center"><input type="radio" name="afectado" value="Si" <?php if($afectado == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="afectado" value="No" <?php if($afectado == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="afectado" value="No se sabe" <?php if($afectado == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Se ha provocado daño deliberadamente?</td>
                                        <td align="center"><input type="radio" name="provocadano" value="Si" <?php if($provocadano == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="provocadano" value="No" <?php if($provocadano == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="provocadano" value="No se sabe" <?php if($provocadano == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">¿Ha tenido intentos de suicidio previos?</td>
                                        <td align="center"><input type="radio" name="suicidio" value="Si" <?php if($suicidio== 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="suicidio" value="No" <?php if($suicidio == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="suicidio" value="No se sabe" <?php if($suicidio == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	</tbody>
	                   	    </table></td>
                          </tr> 
                        <tr>
							<td><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td><textarea cols="185" rows="3" name="evidencia3" id="evidencia3" onkeyup="Contar('evidencia3','MostContador3','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia3','MostContador3','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia3','MostContador3','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia3;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador3">1000 caracteres restantes</span></label>
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
                        <td><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onClick="window.location.href='visCasos.php'" type="button" Value="Volver &raquo;" class="boton"></td>
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