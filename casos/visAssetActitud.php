<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Actitud.class.php');
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
	$obj = new Actitud(null);
	$rs = $obj->entregaAssetActitud($idcaso,$etapa);
	$obj->Close();
	
	$negacion	=	'';
	$reticente	=	'';
	$comprensionvictima	=	'';
	$faltaremordimiento	=	'';
	$comprensionimpacto		=	'';
	$infraccionaceptable	=	'';
	$objetivoaceptable	=	'';
	$infraccioninevitable	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$negacion	=	$res['ac_negacion'];
		$reticente	=	$res['ac_reticente'];
		$comprensionvictima	=	$res['ac_comprensionvictima'];
		$faltaremordimiento	=	$res['ac_faltaremordimiento'];
		$comprensionimpacto		=	$res['ac_comprensionimpacto'];
		$infraccionaceptable = $res['ac_infraccionaceptable'];
		$objetivoaceptable = $res['ac_objetivoaceptable'];
		$infraccioninevitable = $res['ac_infraccioninevitable'];
		$evidencia = $res['ac_evidencia'];
		$calificacion = $res['ac_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetActitud_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 11. Actitudes hacia la infracción/ transgresión a la norma <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_actitud" id="form_actitud" method="post" action="insAssetActitud-ajax.php" onSubmit="return validarAssetActitud()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
							<td colspan="6"><b>Indicar si el NNA muestra alguna de las siguientes actitudes</b></td>
                        </tr>
                        <tr>
                        	<td colspan="6"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">Negación de la seriedad de su comportamiento</td>
                                        <td align="center"><input type="radio" name="negacion" value="Si" <?php if($negacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="negacion" value="No" <?php if($negacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="negacion" value="No se sabe" <?php if($negacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Reticente a aceptar cualquier responsabilidad por su participación en la infracción</td>
                                        <td align="center"><input type="radio" name="reticente" value="Si" <?php if($reticente == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="reticente" value="No" <?php if($reticente == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="reticente" value="No se sabe" <?php if($reticente == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de comprensión sobre los efectos de su comportamiento en la(s) víctima(s)</td>
                                        <td align="center"><input type="radio" name="comprensionvictima" value="Si" <?php if($comprensionvictima == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprensionvictima" value="No" <?php if($comprensionvictima == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprensionvictima" value="No se sabe" <?php if($comprensionvictima == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de remordimiento</td>
                                        <td align="center"><input type="radio" name="faltaremordimiento" value="Si" <?php if($faltaremordimiento == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaremordimiento" value="No" <?php if($faltaremordimiento == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaremordimiento" value="No se sabe" <?php if($faltaremordimiento == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de comprensión respecto al impacto de su comportamiento en la familia/ cuidadores</td>
                                        <td align="center"><input type="radio" name="comprensionimpacto" value="Si" <?php if($comprensionimpacto == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprensionimpacto" value="No" <?php if($comprensionimpacto == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprensionimpacto" value="No se sabe" <?php if($comprensionimpacto == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	   <tr>
                                 	  <td align="left">La creencia de que ciertos tipos de infracción son aceptables</td>
                                 	  <td align="center"><input type="radio" name="infraccionaceptable" value="Si" <?php if($infraccionaceptable == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="infraccionaceptable" value="No" <?php if($infraccionaceptable == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="infraccionaceptable" value="No se sabe" <?php if($infraccionaceptable == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">La creencia de que ciertos grupos son objetivos alcanzables para cometer infracciones</td>
                                 	  <td align="center"><input type="radio" name="objetivoaceptable" value="Si" <?php if($objetivoaceptable == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="objetivoaceptable" value="No" <?php if($objetivoaceptable == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="objetivoaceptable" value="No se sabe" <?php if($objetivoaceptable == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">El o la adolescente cree que cometer futuras <br>
infracciones es inevitable</td>
                                 	  <td align="center"><input type="radio" name="infraccioninevitable" value="Si" <?php if($infraccioninevitable == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="infraccioninevitable" value="No" <?php if($infraccioninevitable == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="infraccioninevitable" value="No se sabe" <?php if($infraccioninevitable == 'No se sabe') echo 'checked';?>></td>
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
                                            <td width="15%"><input type="submit" class="btn boton" value="Grabar Información" /></td>
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