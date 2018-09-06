<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Percepcion.class.php');
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
	$obj = new Percepcion(null);
	$rs = $obj->entregaAssetPercepcion($idcaso,$etapa);
	$obj->Close();
	
	$identidad	=	'';
	$autoestima	=	'';
	$desconfianza	=	'';
	$discriminado	=	'';
	$discriminador		=	'';
	$criminal	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$identidad	=	$res['pe_identidad'];
		$autoestima	=	$res['pe_autoestima'];
		$desconfianza	=	$res['pe_desconfianza'];
		$discriminado	=	$res['pe_discriminado'];
		$discriminador		=	$res['pe_discriminador'];
		$criminal = $res['pe_criminal'];
		$evidencia = $res['pe_evidencia'];
		$calificacion = $res['pe_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetPercepcion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 9. Percepcion de sí mismo y de otros <?php print 'Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_percepcion" id="form_percepcion" method="post" action="insAssetPercepcion-ajax.php" onSubmit="return validarAssetPercepcion()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
							<td colspan="10"><b>Indicar si alguna de las siguientes situaciones aplica al joven</b></td>
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
                                    	<td align="left">Tiene problemas de identidad</td>
                                        <td align="center"><input type="radio" name="identidad" value="Si" <?php if($identidad == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="identidad" value="No" <?php if($identidad == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="identidad" value="No se sabe" <?php if($identidad == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Tiene una autoestima inapropiada (muy alta o muy baja)</td>
                                        <td align="center"><input type="radio" name="autoestima" value="Si" <?php if($autoestima == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="autoestima" value="No" <?php if($autoestima == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="autoestima" value="No se sabe" <?php if($autoestima == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Tiene una desconfianza general hacia otros</td>
                                        <td align="center"><input type="radio" name="desconfianza" value="Si" <?php if($desconfianza== 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="desconfianza" value="No" <?php if($desconfianza == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="desconfianza" value="No se sabe" <?php if($desconfianza == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	   <tr>
                                 	  <td align="left">Se ve a sí mismo como una víctima de discriminación o de un trato injusto (por ej., en el hogar, escuela)</td>
                                 	  <td align="center"><input type="radio" name="discriminado" value="Si" <?php if($discriminado== 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="discriminado" value="No" <?php if($discriminado == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="discriminado" value="No se sabe" <?php if($discriminado == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Muestra actitudes discriminatorias hacia otros (por ej., raza, etnia, religión, género, edad, clase)</td>
                                 	  <td align="center"><input type="radio" name="discriminador" value="Si" <?php if($discriminador== 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="discriminador" value="No" <?php if($discriminador == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="discriminador" value="No se sabe" <?php if($discriminador == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Se percibe a sí mismo con una identidad criminal</td>
                                 	  <td align="center"><input type="radio" name="criminal" value="Si" <?php if($criminal== 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="criminal" value="No" <?php if($criminal == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="criminal" value="No se sabe" <?php if($criminal == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                           	    </tbody>
	                   	    </table></td>
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