<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Salud.class.php');
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
	$obj = new Salud(null);
	$rs = $obj->entregaAssetSalud($idcaso,$etapa);
	$obj->Close();
	
	$condiciones	=	'';
	$inmadurez	=	'';
	$acceso	=	'';
	$riesgo	=	'';
	$otro		=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$condiciones	=	$res['sf_condiciones'];
		$inmadurez	=	$res['sf_inmadurez'];
		$acceso	=	$res['sf_acceso'];
		$riesgo	=	$res['sf_riesgo'];
		$otro		=	$res['sf_otro'];
		$evidencia = $res['sf_evidencia'];
		$calificacion = $res['sf_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetSalud_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 7. Salud Física <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>   
                    <form name="form_saludfisica" id="form_saludfisica" method="post" action="insAssetSalud-ajax.php" onSubmit="return validarAssetSaludFisica()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left"><table width="100%">
                        <tr>
							<td colspan="8"><b>Indicar si alguna de las siguientes situaciones aplica al NNA</b></td>
                        </tr>
                        <tr>
                        	<td colspan="8"><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">Condiciones de salud que tengan un efecto significativo en el funcionamiento diario</td>
                                        <td align="center"><input type="radio" name="condiciones" value="Si" <?php if($condiciones == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="condiciones" value="No" <?php if($condiciones == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="condiciones" value="No se sabe" <?php if($condiciones == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Inmadurez física/ retraso en el desarrollo</td>
                                        <td align="center"><input type="radio" name="inmadurez" value="Si" <?php if($inmadurez == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="inmadurez" value="No" <?php if($inmadurez == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="inmadurez" value="No se sabe" <?php if($inmadurez == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de acceso a otros servicios de salud (por ejemplo dentista)</td>
                                        <td align="center"><input type="radio" name="acceso" value="Si" <?php if($acceso == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="acceso" value="No" <?php if($acceso == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="acceso" value="No se sabe" <?php if($acceso == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Salud puesta en riesgo por efecto de su propio comportamiento</td>
                                        <td align="center"><input type="radio" name="riesgo" value="Si" <?php if($riesgo == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="riesgo" value="No" <?php if($riesgo == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="riesgo" value="No se sabe" <?php if($riesgo == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Otros problemas</td>
                                        <td align="center"><input type="radio" name="otro" value="Si" <?php if($otro == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otro" value="No" <?php if($otro == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="otro" value="No se sabe" <?php if($otro == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                           	    </tbody>
	                   	    </table>                          </td>
                          </tr>
                        <tr>
							<td colspan="8"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="8"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
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