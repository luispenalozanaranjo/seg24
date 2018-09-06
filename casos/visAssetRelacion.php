<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Relacion.class.php');
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
	$obj = new Relacion(null);
	$rs = $obj->entregaAssetRelacion($idcaso,$etapa);
	$obj->Close();
	
	$involucrado	=	'';
	$experiencia	=	'';
	$alcohol	=	'';
	$testigo	=	'';
	$drogas		=	'';
	$duelo	=	'';
	$comunicacion	=	'';
	$cuidado	=	'';
	$supervision	=	'';
	$otros	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$chkmiembros	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$involucrado	=	$res['re_involucrado'];
		$experiencia	=	$res['re_experiencia'];
		$alcohol	=	$res['re_alcohol'];
		$testigo	=	$res['re_testigo'];
		$drogas		=	$res['re_drogas'];
		$duelo = $res['re_duelo'];
		$comunicacion = $res['re_comunicacion'];
		$cuidado = $res['re_cuidado'];
		$supervision = $res['re_supervision'];
		$otros = $res['re_otros'];
		$evidencia = $res['re_evidencia'];
		$calificacion = $res['re_calificacion'];
		$chkmiembros = explode(",", $res['re_chkmiembros']);
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetRelacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3>2. Relaciones Familiares y Personales <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>
                     
                    </div>            
                    <form name="form_relacion" id="form_relacion" method="post" action="insAssetRelacion-ajax.php" onsubmit="return validarAssetRelaciones()">
                   <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
                        	<td colspan="12"><b>¿Con cuáles de los miembros de la familia o cuidadores ha estado en contacto  el NNA la mayor parte del tiempo en los últimos 6 meses?</b></td>
                        </tr>
                        <tr>
                        	<td width="2%"><input type="checkbox" name="chkmiembros[]" value="Madre" <?php if($chkmiembros!=''){if(in_array("Madre", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Madre</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Padre" <?php if($chkmiembros!=''){if(in_array("Padre", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Padre</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Padres adoptivos" <?php if($chkmiembros!=''){if(in_array("Padres adoptivos", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Padres adoptivos</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Padrastro/ Madrastra" <?php if($chkmiembros!=''){if(in_array("Padrastro/ Madrastra", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Padrastro/ Madrastra</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Hermano(s)" <?php if($chkmiembros!=''){if(in_array("Hermano(s)", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Hermano(s)</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Abuelo(s)" <?php if($chkmiembros!=''){if(in_array("Abuelo(s)", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Abuelo(s)</label></td>
                         </tr>
                         <tr>   
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Pareja" <?php if($chkmiembros!=''){if(in_array("Pareja", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Pareja</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Propio(s) hijo(s)" <?php if($chkmiembros!=''){if(in_array("Propio(s) hijo(s)", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Propio(s) hijo(s)</label></td>   
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Otros familiares" <?php if($chkmiembros!=''){if(in_array("Otros familiares", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Otros familiares</label></td>
                            <td width="2%"><input type="checkbox" name="chkmiembros[]" value="Otros adultos significativos" <?php if($chkmiembros!=''){if(in_array("Otros adultos significativos", $chkmiembros)) echo "checked";}?>></td>
                            <td width="15%"><label>Otros adultos significativos</label></td>                        
                            <td><input type="checkbox" name="chkmiembros[]" value="Otros(s)" <?php if($chkmiembros!=''){if(in_array("Otros(s)", $chkmiembros)) echo "checked";}?>></td>
                            <td colspan="3"><label>Otros(s)</label></td> 
                        </tr>
                        </table>
                        <table>
                        <tr>
							<td colspan="10"><b>Indicar si alguna de las siguientes situaciones se aplica al NNA</b></td>
                        </tr>
                        <tr>
                        	<td colspan="10"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">Han estado involucrados en actividades delictuales</td>
                                        <td align="center"><input type="radio" name="involucrado" value="Si" <?php if($involucrado == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="involucrado" value="No" <?php if($involucrado == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="involucrado" value="No se Sabe" <?php if($involucrado == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Presenta un consumo severo de alcohol</td>
                                        <td align="center"><input type="radio" name="alcohol" value="Si" <?php if($alcohol == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="alcohol" value="No" <?php if($alcohol == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="alcohol" value="No se Sabe" <?php if($alcohol == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Presentan un consumo severo de drogas o solventes</td>
                                        <td align="center"><input type="radio" name="drogas" value="Si" <?php if($drogas == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="drogas" value="No" <?php if($drogas == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="drogas" value="No se Sabe" <?php if($drogas == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Adultos significativos fracasan en la comunicación o expresión de cuidado/interés</td>
                                        <td align="center"><input type="radio" name="comunicacion" value="Si" <?php if($comunicacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comunicacion" value="No" <?php if($comunicacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comunicacion" value="No se Sabe" <?php if($comunicacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Supervisión o establecimiento de límites inconsistente</td>
                                        <td align="center"><input type="radio" name="supervision" value="Si" <?php if($supervision == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="supervision" value="No" <?php if($supervision == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="supervision" value="No se Sabe" <?php if($supervision == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Experiencia de abuso (por ej., física, sexual, emocional, negligencia)</td>
                                        <td align="center"><input type="radio" name="experiencia" value="Si" <?php if($experiencia == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="experiencia" value="No" <?php if($experiencia == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="experiencia" value="No se Sabe" <?php if($experiencia == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Testigo de violencia en el contexto familiar</td>
                                        <td align="center"><input type="radio" name="testigo" value="Si" <?php if($testigo == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="testigo" value="No" <?php if($testigo == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="testigo" value="No se Sabe" <?php if($testigo == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                   <tr>
                                      <td align="left">Duelo o pérdida significativa</td>
                                      <td align="center"><input type="radio" name="duelo" value="Si" <?php if($duelo == 'Si') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="duelo" value="No" <?php if($duelo == 'No') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="duelo" value="No se sabe" <?php if($duelo == 'No se sabe') echo 'checked';?>></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Dificultades en el cuidado de su(s) propio(s) hijo(s)</td>
                                      <td align="center"><input type="radio" name="cuidado" value="Si" <?php if($cuidado == 'Si') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="cuidado" value="No" <?php if($cuidado == 'No') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="cuidado" value="No se sabe" <?php if($cuidado == 'No se sabe') echo 'checked';?>></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Otros problemas (por ej., padres con problemas de salud física/mental, pérdida de contacto, divorcio violento de los padres, etc.)</td>
                                      <td align="center"><input type="radio" name="otros" value="Si" <?php if($otros== 'Si') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="otros" value="No" <?php if($otros == 'No') echo 'checked';?>></td>
                                      <td align="center"><input type="radio" name="otros" value="No se sabe" <?php if($otros == 'No se sabe') echo 'checked';?>></td>
                                    </tr>
                       		    </tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td colspan="10"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="10"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador">1000 caracteres restantes</span></label>
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
                                            <td width="15%"><input type="submit" class="boton" value="Grabar Información" /></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onclick="window.location.href='visCasos.php'" type="submit" value="Volver" class="boton" /></td>
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