<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Comportamiento.class.php');
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
	$obj = new Comportamiento(null);
	$rs = $obj->entregaAssetComportamiento($idcaso,$etapa);
	$obj->Close();
	
	$faltacomprension	=	'';
	$impulsividad	=	'';
	$emociones	=	'';
	$faltaasertividad	=	'';
	$temperamental		=	'';
	$habilidades	=	'';
	$propiedad	=	'';
	$sexual	=	'';
	$agresion	=	'';
	$manipulacion	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$faltacomprension	=	$res['com_faltacomprension'];
		$impulsividad	=	$res['com_impulsividad'];
		$emociones	=	$res['com_emociones'];
		$faltaasertividad	=	$res['com_faltaasertividad'];
		$temperamental		=	$res['com_temperamental'];
		$habilidades = $res['com_habilidades'];
		$propiedad = $res['com_propiedad'];
		$sexual = $res['com_sexual'];
		$agresion = $res['com_agresion'];
		$manipulacion = $res['com_manipulacion'];
		$evidencia = $res['com_evidencia'];
		$calificacion = $res['com_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetComportamiento_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 10. Pensamiento y Comportamiento <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_comportamiento" id="form_comportamiento" method="post" action="insAssetComportamiento-ajax.php" onSubmit="return validarAssetComportamiento()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
							<td colspan="6"><b>¿Las acciones del NNA tienen alguna de estas características?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="6"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
                            	<thead>
                                    <tr>
                                        <th width="70%">&nbsp;</th>
                                        <th width="10%">Si</th>
                                        <th width="10%">No</th>
                                        <th width="10%">No se sabe</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 	<tr>
                                    	<td align="left">Falta de comprensión de consecuencias</td>
                                        <td align="center"><input type="radio" name="faltacomprension" value="Si" <?php if($faltacomprension == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltacomprension" value="No" <?php if($faltacomprension == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltacomprension" value="No se sabe" <?php if($faltacomprension == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Impulsividad</td>
                                        <td align="center"><input type="radio" name="impulsividad" value="Si" <?php if($impulsividad == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="impulsividad" value="No" <?php if($impulsividad == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="impulsividad" value="No se sabe" <?php if($impulsividad == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Necesidad de emociones (se aburre fácilmente)</td>
                                        <td align="center"><input type="radio" name="emociones" value="Si" <?php if($emociones == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="emociones" value="No" <?php if($emociones == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="emociones" value="No se Sabe" <?php if($emociones == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Cede fácilmente ante presiones de los otros (falta de asertividad)</td>
                                        <td align="center"><input type="radio" name="faltaasertividad" value="Si" <?php if($faltaasertividad == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaasertividad" value="No" <?php if($faltaasertividad == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaasertividad" value="No se Sabe" <?php if($faltaasertividad == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Temperamental</td>
                                        <td align="center"><input type="radio" name="temperamental" value="Si" <?php if($temperamental == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="temperamental" value="No" <?php if($temperamental == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="temperamental" value="No se Sabe" <?php if($temperamental == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                    <tr>
                                    	<td align="left">Habilidades sociales y comunicacionales inapropiadas</td>
                                        <td align="center"><input type="radio" name="habilidades" value="Si" <?php if($habilidades == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="habilidades" value="No" <?php if($habilidades == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="habilidades" value="No se Sabe" <?php if($habilidades == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                </tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td colspan="6"><b>¿Muestra el NNA alguno de los siguientes tipos de comportamiento?</b></td>
                        </tr>
                        <tr>
                        	<td colspan="6"> 
                            <table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
                            	<thead>
                                    <tr>
                                        <th width="70%">&nbsp;</th>
                                        <th width="10%">Si</th>
                                        <th width="10%">No</th>
                                        <th width="10%">No se sabe</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 	<tr>
                                    	<td align="left">Destrucción de la propiedad</td>
                                        <td align="center"><input type="radio" name="propiedad" value="Si" <?php if($propiedad == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="propiedad" value="No" <?php if($propiedad == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="propiedad" value="No se sabe" <?php if($propiedad == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Agresión hacia otros (física o verbal)</td>
                                        <td align="center"><input type="radio" name="agresion" value="Si" <?php if($agresion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="agresion" value="No" <?php if($agresion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="agresion" value="No se sabe" <?php if($agresion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Comportamiento sexual inapropiado</td>
                                        <td align="center"><input type="radio" name="sexual" value="Si" <?php if($sexual == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="sexual" value="No" <?php if($sexual == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="sexual" value="No se Sabe" <?php if($sexual == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Intentos de manipular/ controlar a otros</td>
                                        <td align="center"><input type="radio" name="manipulacion" value="Si" <?php if($manipulacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="manipulacion" value="No" <?php if($manipulacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="manipulacion" value="No se Sabe" <?php if($manipulacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	</tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td colspan="6"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="6"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
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