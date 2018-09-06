<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Hogar.class.php');
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
	$obj = new Hogar(null);
	$rs = $obj->entregaAssetHogar($idcaso,$etapa);
	$obj->Close();
	
	$condicion	=	'';
	$sin_domicilio	=	'';
	$incumplimiento	=	'';
	$hogar_deprivado	=	'';
	$vive_delincuentes		=	'';
	$situacion_calle	=	'';
	$desorganizado	=	'';
	$otros	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$chkviviendanna	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$condicion	=	$res['ho_condicion'];
		$sin_domicilio	=	$res['ho_sin_domicilio'];
		$incumplimiento	=	$res['ho_incumplimiento'];
		$hogar_deprivado	=	$res['ho_hogar_deprivado'];
		$vive_delincuentes		=	$res['ho_vive_delincuentes'];
		$situacion_calle = $res['ho_situacion_calle'];
		$desorganizado = $res['ho_desorganizado'];
		$otros = $res['ho_otros'];
		$evidencia = $res['ho_evidencia'];
		$calificacion = $res['ho_calificacion'];
		$chkviviendanna = explode(",", $res['ho_chkviviendanna']);
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetHogar_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}
?>
<div id="content-wrapper">  		
<section>
<div class="contenedor">  
  <div class="menuExtra">
       				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <h2></h2>
                	
                    <div class="caja">
        				<h3> 1. Condiciones del Hogar <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>
                    </div>

                    <form name="form_hogar" id="form_hogar" method="post" action="insAssetHogar-ajax.php" onsubmit="return validarAssetHogar()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
                        	<td colspan="12"><b>¿Con quién ha vivido el NNA la mayor parte del tiempo en los últimos seis meses?</b></td>
                        </tr>
                        <tr>
                        	<td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Madre" <?php if($chkviviendanna!=''){if(in_array("Madre", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Madre</label></td>
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Padre" <?php if($chkviviendanna!=''){if(in_array("Padre", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Padre</label></td>
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Padrastro/ Madrastra" <?php if($chkviviendanna!=''){if(in_array("Padrastro/ Madrastra", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Padrastro/ Madrastra</label></td>
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Hermano(s)" <?php if($chkviviendanna!=''){if(in_array("Hermano(s)", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Hermano(s)</label></td>
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Abuelo(s)" <?php if($chkviviendanna!=''){if(in_array("Abuelo(s)", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Abuelo(s)</label></td>
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Otros familiares" <?php if($chkviviendanna!=''){if(in_array("Otros familiares", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Otros familiares</label></td>
                        </tr>    
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Independiente" <?php if($chkviviendanna!=''){if(in_array("Independiente", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Sólo, de manera independiente</label></td>     
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Pareja" <?php if($chkviviendanna!=''){if(in_array("Pareja", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Pareja</label></td>
                            
                            <td width="2%"><input type="checkbox" name="chkviviendanna[]" value="Con el propio hijo(s)" <?php if($chkviviendanna!=''){if(in_array("Con el propio hijo(s)", $chkviviendanna)) echo "checked";}?>></td>
                            <td width="15%"><label>Con el propio hijo(s)</label></td>                                            
                            <td><input type="checkbox" name="chkviviendanna[]" value="Amigos" <?php if($chkviviendanna!=''){if(in_array("Amigos", $chkviviendanna)) echo "checked";}?>></td>
                            <td><label>Amigos</label></td>
                            <td><input type="checkbox" name="chkviviendanna[]" value="Residente de hogar o institucion" <?php if($chkviviendanna!=''){if(in_array("Residente de hogar o institucion", $chkviviendanna)) echo "checked";}?>></td>
                            <td><label>Residente de hogar o institución</label></td>                   
                            <td><input type="checkbox" name="chkviviendanna[]" value="Otros(s)" <?php if($chkviviendanna!=''){if(in_array("Otros(s)", $chkviviendanna)) echo "checked";}?>></td>
                            <td><label>Otros(s)</label></td>  
                        </tr>
                        <tr>
							<td colspan="12"><b>Si su actual condición es diferente, especificar a continuación:</b></td>
                        </tr>
                        <tr>
                        	<td colspan="12"><textarea cols="185" rows="3" name="condicion" id="condicion" onkeyup="Contar('condicion','MostContador','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('condicion','MostContador','{CHAR} caracteres restantes.',1000);" onblur="Contar('condicion','MostContador','{CHAR} caracteres restantes.',1000);"><?php echo $condicion;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador">1000 caracteres restantes</span></label>
                            </td>
                        </tr> 
                        </table>
                        <table>
                        <tr>
							<td colspan="10"><b>Indicar si alguna de las siguientes situaciones se aplica al NNA</b></td>
                        </tr>
                        <tr>
                        	<td colspan="10">
                            <table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">Sin domicilio fijo</td>
                                        <td align="center"><input type="radio" name="sin_domicilio" value="Si" <?php if($sin_domicilio == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="sin_domicilio" value="No" <?php if($sin_domicilio == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="sin_domicilio" value="No Sabe" <?php if($sin_domicilio == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Inadecuado no cumple con sus necesidades (por ej., hacinamiento, falta de servicios básicos)</td>
                                        <td align="center"><input type="radio" name="incumplimiento" value="Si" <?php if($incumplimiento == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="incumplimiento" value="No" <?php if($incumplimiento == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="incumplimiento" value="No Sabe" <?php if($incumplimiento == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Hogar deprivado (por ej., dependiente de beneficios, derecho o alimentación escolar)</td>
                                        <td align="center"><input type="radio" name="hogar_deprivado" value="Si" <?php if($hogar_deprivado == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="hogar_deprivado" value="No" <?php if($hogar_deprivado == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="hogar_deprivado" value="No Sabe" <?php if($hogar_deprivado == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Vive con delincuentes conocidos</td>
                                        <td align="center"><input type="radio" name="vive_delincuentes" value="Si" <?php if($vive_delincuentes == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="vive_delincuentes" value="No" <?php if($vive_delincuentes == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="vive_delincuentes" value="No Sabe" <?php if($vive_delincuentes == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Fugado o en situación de calle (por ej., alguna vez reportado como persona desaparecida)</td>
                                        <td align="center"><input type="radio" name="situacion_calle" value="Si" <?php if($situacion_calle == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="situacion_calle" value="No" <?php if($situacion_calle == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="situacion_calle" value="No Sabe" <?php if($situacion_calle == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Desorganizado/ caótico (por ej., diferentes personas que van y vienen)</td>
                                        <td align="center"><input type="radio" name="desorganizado" value="Si" <?php if($desorganizado == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="desorganizado" value="No" <?php if($desorganizado == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="desorganizado" value="No Sabe" <?php if($desorganizado == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Otros problemas (por ej., incertidumbre sobre la duración de la estadía)</td>
                                        <td align="center"><input type="radio" name="otros" value="Si" <?php if($otros == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otros" value="No" <?php if($otros == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otros" value="No Sabe" <?php if($otros == 'No sabe') echo 'checked';?>></td>
                                	</tr>
                          		</tbody>
	                   	    </table>
                            </td>
                          </tr>
                        <tr>
							<td colspan="10"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="10"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador1">1000 caracteres restantes</span></label>
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
                        <td align="left">
                        <table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">
                                                <input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
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
