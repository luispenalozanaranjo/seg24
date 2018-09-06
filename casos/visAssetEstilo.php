<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Estilo.class.php');
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
	$obj = new Estilo(null);
	$rs = $obj->entregaAssetEstilo($idcaso,$etapa);
	$obj->Close();
	
	$faltaamistad	=	'';
	$actividadriesgo	=	'';
	$asocpredominante	=	'';
	$dineroinsuficiente	=	'';
	$faltaasociacion		=	'';
	$tiempolibre	=	'';
	$otro	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$faltaamistad	=	$res['es_faltaamistad'];
		$actividadriesgo	=	$res['es_actividadriesgo'];
		$asocpredominante	=	$res['es_asocpredominante'];
		$dineroinsuficiente	=	$res['es_dineroinsuficiente'];
		$faltaasociacion		=	$res['es_faltaasociacion'];
		$tiempolibre = $res['es_tiempolibre'];
		$otro = $res['es_otro'];
		$evidencia = $res['es_evidencia'];
		$calificacion = $res['es_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetEstilo_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}
?>
</head>
<body>
<div id="content-wrapper">
<section>
<div class="contenedor">
  <h2></h2>
                	
                    <div class="caja">
        				<h3> 5. Estilo de Vida <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_estilo" id="form_estilo" method="post" action="insAssetEstilo-ajax.php" onSubmit="return validarAssetEstiloDeVida()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%" border="0">
                        <tr>
							<td colspan="8"><b>Indicar si las siguientes son características del estilo de vida del NNA</b></td>
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
                                    	<td align="left">Falta de amistades de edad apropiada al NNA</td>
                                        <td align="center"><input type="radio" name="faltaamistad" value="Si" <?php if($faltaamistad == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaamistad" value="No" <?php if($faltaamistad == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaamistad" value="No se sabe" <?php if($faltaamistad == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Asociación predominante con pares pro-infracción/ transgresión de normas</td>
                                        <td align="center"><input type="radio" name="asocpredominante" value="Si" <?php if($asocpredominante == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="asocpredominante" value="No" <?php if($asocpredominante == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="asocpredominante" value="No se sabe" <?php if($asocpredominante == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Falta de asociación con pares no infractores/ transgresores de norma</td>
                                        <td align="center"><input type="radio" name="faltaasociacion" value="Si" <?php if($faltaasociacion == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaasociacion" value="No" <?php if($faltaasociacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="faltaasociacion" value="No se sabe" <?php if($faltaasociacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">No tiene mucho que hacer en su tiempo libre</td>
                                        <td align="center"><input type="radio" name="tiempolibre" value="Si" <?php if($tiempolibre == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="tiempolibre" value="No" <?php if($tiempolibre == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="tiempolibre" value="No se sabe" <?php if($tiempolibre == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Participación en actividades de riesgo</td>
                                        <td align="center"><input type="radio" name="actividadriesgo" value="Si" <?php if($actividadriesgo == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actividadriesgo" value="No" <?php if($actividadriesgo == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="actividadriesgo" value="No se sabe" <?php if($actividadriesgo == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	   <tr>
                                 	  <td align="left">Insuficiente dinero obtenido de manera legítima</td>
                                 	  <td align="center"><input type="radio" name="dineroinsuficiente" value="Si" <?php if($dineroinsuficiente == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="dineroinsuficiente" value="No" <?php if($dineroinsuficiente == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="dineroinsuficiente" value="No se sabe" <?php if($dineroinsuficiente == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Otros problemas (por ej., apuestas, permanece afuera hasta tarde en la noche, aislamiento social)</td>
                                 	  <td align="center"><input type="radio" name="otro" value="Si" <?php if($otro == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="otro" value="No" <?php if($otro == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="otro" value="No se sabe" <?php if($otro == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                </tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td colspan="8"><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td colspan="8"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onKeyUp="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onKeyPress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onBlur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
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
                                            <td width="15%"><input type="submit" class="boton" value="Grabar Información" /></td>
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