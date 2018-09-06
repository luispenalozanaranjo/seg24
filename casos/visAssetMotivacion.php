<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Motivacion.class.php');
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
	$obj = new Motivacion(null);
	$rs = $obj->entregaAssetMotivacion($idcaso,$etapa);
	$obj->Close();
	
	$comprendecomportamiento	=	'';
	$resolverproblemas	=	'';
	$comprendeconsecuencias	=	'';
	$identificaincentivos	=	'';
	$muestraevidencia		=	'';
	$apoyofamiliar	=	'';
	$cooperacion	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$comprendecomportamiento	=	$res['mo_comprendecomportamiento'];
		$resolverproblemas	=	$res['mo_resolverproblemas'];
		$comprendeconsecuencias	=	$res['mo_comprendeconsecuencias'];
		$identificaincentivos	=	$res['mo_identificaincentivos'];
		$muestraevidencia		=	$res['mo_muestraevidencia'];
		$apoyofamiliar = $res['mo_apoyofamiliar'];
		$cooperacion = $res['mo_cooperacion'];
		$evidencia = $res['mo_evidencia'];
		$calificacion = $res['mo_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetMotivacion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 12. Motivación al Cambio  <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_motivacion" id="form_motivacion" method="post" action="insAssetMotivacion-ajax.php" onSubmit="return validarAssetMotivacion()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
							<td><b>Indicar si el NNA muestra alguna de las siguientes actitudes</b></td>
                        </tr>
                        <tr>
                        	<td><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
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
                                    	<td align="left">Tiene una comprensión adecuada de los aspectos problemáticos de su propio comportamiento</td>
                                        <td align="center"><input type="radio" name="comprendecomportamiento" value="Si" <?php if($comprendecomportamiento == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprendecomportamiento" value="No" <?php if($comprendecomportamiento == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprendecomportamiento" value="No se Sabe" <?php if($comprendecomportamiento == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Muestra evidencias concretas de querer resolver los problemas de su vida</td>
                                        <td align="center"><input type="radio" name="resolverproblemas" value="Si" <?php if($resolverproblemas == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="resolverproblemas" value="No" <?php if($resolverproblemas == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="resolverproblemas" value="No se sabe" <?php if($resolverproblemas == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Comprende las consecuencias para el mismo de futuras infracciones</td>
                                        <td align="center"><input type="radio" name="comprendeconsecuencias" value="Si" <?php if($comprendeconsecuencias == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprendeconsecuencias" value="No" <?php if($comprendeconsecuencias == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="comprendeconsecuencias" value="No se sabe" <?php if($comprendeconsecuencias == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Ha identificado claramente las razones o incentivos para evitar futuras infracciones</td>
                                        <td align="center"><input type="radio" name="identificaincentivos" value="Si" <?php if($identificaincentivos == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="identificaincentivos" value="No" <?php if($identificaincentivos == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="identificaincentivos" value="No se sabe" <?php if($identificaincentivos == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Muestra evidencias concretas de querer dejar de incurrir en comportamientos infractores</td>
                                        <td align="center"><input type="radio" name="muestraevidencia" value="Si" <?php if($muestraevidencia == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="muestraevidencia" value="No" <?php if($muestraevidencia == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="muestraevidencia" value="No se sabe" <?php if($muestraevidencia == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                               	   <tr>
                                 	  <td align="left">Recibirá apoyo de la familia, amigos y otras personas durante la intervención</td>
                                 	  <td align="center"><input type="radio" name="apoyofamiliar" value="Si" <?php if($apoyofamiliar == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="apoyofamiliar" value="No" <?php if($apoyofamiliar == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="apoyofamiliar" value="No se sabe" <?php if($apoyofamiliar == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Está dispuesto a cooperar con los demás (familia, otras agencias) para lograr un cambio</td>
                                 	  <td align="center"><input type="radio" name="cooperacion" value="Si" <?php if($cooperacion == 'Si') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="cooperacion" value="No" <?php if($cooperacion == 'No') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="cooperacion" value="No se sabe" <?php if($cooperacion == 'No se sabe') echo 'checked';?>></td>
                               	  </tr>
                               	</tbody>
	                   	    </table></td>
                          </tr>
                        <tr>
							<td><b>Evidencia (explicar la razón de la respuesta "No se sabe")</b></td>
                        </tr>
                        <tr>
                        	<td><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
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