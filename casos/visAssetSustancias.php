<?php
session_start();
require_once('../clases/Casos.class.php');
require_once('../clases/Sustancias.class.php');
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
	$obj = new Sustancias(null);
	$rs = $obj->entregaAssetSustancias($idcaso,$etapa);
	$obj->Close();
	
	$tabaco	=	'';
	$tabaco_edad	=	'';
	$alcohol	=	'';
	$alcohol_edad	=	'';
	$solventes		=	'';
	$solventes_edad	=	'';
	$cannabis	=	'';
	$cannabis_edad	=	'';
	$pastabase	=	'';
	$pastabase_edad	=	'';
	$cocaina	=	'';
	$cocaina_edad	=	'';
	$anfetamina	=	'';
	$anfetamina_edad	=	'';
	$tranquilizante	=	'';
	$tranquilizante_edad	=	'';
	$extasis	=	'';
	$extasis_edad	=	'';
	$lcd	=	'';
	$lcd_edad	=	'';
	$inhalantes	=	'';
	$inhalantes_edad	=	'';
	$crack	=	'';
	$crack_edad	=	'';
	$heroina	=	'';
	$heroina_edad	=	'';
	$metadona	=	'';
	$metadona_edad	=	'';
	$esteroides	=	'';
	$esteroides_edad	=	'';
	$otros	=	'';
	$otros_edad	=	'';
	$nnariesgo	=	'';
	$usopositivo	=	'';
	$educacion	=	'';
	$infracciones	=	'';
	$otro	=	'';
	$evidencia	=	'';
	$calificacion	=	'';
	$opcion = 'insert';
	
	foreach( $rs as $res ){
		$tabaco	=	$res['su_tabaco'];
		$tabaco_edad	=	$res['su_tabaco_edad'];
		$alcohol	=	$res['su_alcohol'];
		$alcohol_edad	=	$res['su_alcohol_edad'];
		$solventes		=	$res['su_solventes'];
		$solventes_edad = $res['su_solventes_edad'];
		$cannabis = $res['su_cannabis'];
		$cannabis_edad = $res['su_cannabis_edad'];
		$pastabase = $res['su_pastabase'];
		$pastabase_edad = $res['su_pastabase_edad'];
		$cocaina = $res['su_cocaina'];
		$cocaina_edad = $res['su_cocaina_edad'];
		$anfetamina = $res['su_anfetamina'];
		$anfetamina_edad = $res['su_anfetamina_edad'];
		$tranquilizante = $res['su_tranquilizante'];
		$tranquilizante_edad = $res['su_tranquilizante_edad'];
		$extasis = $res['su_extasis'];
		$extasis_edad = $res['su_extasis_edad'];
		$lcd = $res['su_lcd'];
		$lcd_edad = $res['su_lcd_edad'];
		$inhalantes = $res['su_inhalantes'];
		$inhalantes_edad = $res['su_inhalantes_edad'];
		$crack = $res['su_crack'];
		$crack_edad = $res['su_crack_edad'];
		$heroina = $res['su_heroina'];
		$heroina_edad = $res['su_heroina_edad'];
		$metadona = $res['su_metadona'];
		$metadona_edad = $res['su_metadona_edad'];
		$esteroides = $res['su_esteroides'];
		$esteroides_edad = $res['su_esteroides_edad'];
		$otros = $res['su_otros'];
		$otros_edad = $res['su_otros_edad'];
		$nnariesgo = $res['su_nnariesgo'];
		$usopositivo = $res['su_usopositivo'];
		$educacion = $res['su_educacion'];
		$infracciones = $res['su_infracciones'];
		$otro = $res['su_otro'];
		$evidencia = $res['su_evidencia'];
		$calificacion = $res['su_calificacion'];
		$opcion = 'update';
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('assetSustancias_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

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
        				<h3> 6. Uso de Sustancias <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>   
                    <form name="form_sustancias" id="form_sustancias" method="post" action="insAssetSustancias-ajax.php" onSubmit="return validarAssetSustancias()">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <table width="100%" align="center">
                    <tr>                
                      <td align="left" class="datos_sgs"><table width="100%">
                        <tr>
                        <td><b>Seleccione las siguientes opciones dando detalles del uso de sustancias (basado en información disponible)</b></td>
                        </tr>
                        <tr>
                        	<td><table width="100%" class="tabla_chk_asset" border="1" bordercolor="#CCCCCC">
                            	<thead>
                                    <tr>
                                        <th width="50%">&nbsp;</th>
                                        <th width="10%">Alguna vez</th>
                                        <th width="10%">Recientemente</th>
                                        <th width="10%">No se sabe</th>
                                        <th width="10%">No consume</th>
                                        <th width="10%">Edad primer uso</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 	<tr>
                                    	<td align="left">Tabaco</td>
                                    	<td align="center"><input type="radio" name="tabaco" value="Alguna vez" <?php if($tabaco == 'Alguna vez') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="tabaco" value="Recientemente" <?php if($tabaco == 'Recientemente') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="tabaco" value="No se sabe" <?php if($tabaco == 'No se sabe') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="tabaco" value="No consume" <?php if($tabaco == 'No consume') echo 'checked';?>></td>
                                        <td align="center"><input type="text" size="1" maxlength="2" name="tabaco_edad" id="tabaco_edad" value="<?php if($tabaco_edad==''){ echo '0'; } else { echo $tabaco_edad;}?>" onkeypress="return soloNumeros(event)"></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Cannabis</td>
                                    	<td align="center"><input type="radio" name="cannabis" value="Alguna vez" <?php if($cannabis == 'Alguna vez') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="cannabis" value="Recientemente" <?php if($cannabis == 'Recientemente') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="cannabis" value="No se sabe" <?php if($cannabis == 'No se sabe') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="cannabis" value="No consume" <?php if($cannabis == 'No consume') echo 'checked';?>></td>
                                        <td align="center"><input type="text" size="1" maxlength="2" name="cannabis_edad" id="cannabis_edad" value="<?php if($cannabis_edad==''){ echo '0'; } else { echo $cannabis_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Anfetamina</td>
                                    	<td align="center"><input type="radio" name="anfetamina" value="Alguna vez" <?php if($anfetamina == 'Alguna vez') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="anfetamina" value="Recientemente" <?php if($anfetamina == 'Recientemente') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="anfetamina" value="No se sabe" <?php if($anfetamina == 'No se sabe') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="anfetamina" value="No consume" <?php if($anfetamina == 'No consume') echo 'checked';?>></td>
                                        <td align="center"><input type="text" size="1" maxlength="2" name="anfetamina_edad" id="anfetamina_edad" value="<?php if($anfetamina_edad==''){ echo '0'; } else { echo $anfetamina_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">LSD</td>
                                    	<td align="center"><input type="radio" name="lcd" value="Alguna vez" <?php if($lcd == 'Alguna vez') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="lcd" value="Recientemente" <?php if($lcd == 'Recientemente') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="lcd" value="No se sabe" <?php if($lcd == 'No se sabe') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="lcd" value="No consume" <?php if($lcd == 'No consume') echo 'checked';?>></td>
                                        <td align="center"><input type="text" size="1" maxlength="2" name="lcd_edad" id="lcd_edad" value="<?php if($lcd_edad==''){ echo '0'; } else { echo $lcd_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Heroína</td>
                                    	<td align="center"><input type="radio" name="heroina" value="Alguna vez" <?php if($heroina == 'Alguna vez') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="heroina" value="Recientemente" <?php if($heroina == 'Recientemente') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="heroina" value="No se sabe" <?php if($heroina == 'No se sabe') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="heroina" value="No consume" <?php if($heroina == 'No consume') echo 'checked';?>></td>
                                        <td align="center"><input type="text" size="1" maxlength="2" name="heroina_edad" id="heroina_edad" value="<?php if($heroina_edad==''){ echo '0'; } else { echo $heroina_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                                	</tr>
                               	   <tr>
                                 	  <td align="left">Alcohol</td>
                                 	  <td align="center"><input type="radio" name="alcohol" value="Alguna vez" <?php if($alcohol == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="alcohol" value="Recientemente" <?php if($alcohol == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="alcohol" value="No se sabe" <?php if($alcohol == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="alcohol" value="No consume" <?php if($alcohol == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="alcohol_edad" id="alcohol_edad" value="<?php if($alcohol_edad==''){ echo '0'; } else { echo $alcohol_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Pasta Base</td>
                                 	  <td align="center"><input type="radio" name="pastabase" value="Alguna vez" <?php if($pastabase == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="pastabase" value="Recientemente" <?php if($pastabase == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="pastabase" value="No se sabe" <?php if($pastabase == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="pastabase" value="No consume" <?php if($pastabase == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="pastabase_edad" id="pastabase_edad" value="<?php if($pastabase_edad==''){ echo '0'; } else { echo $pastabase_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Tranquilizante</td>
                                 	  <td align="center"><input type="radio" name="tranquilizante" value="Alguna vez" <?php if($tranquilizante == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="tranquilizante" value="Recientemente" <?php if($tranquilizante == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="tranquilizante" value="No se sabe" <?php if($tranquilizante == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="tranquilizante" value="No consume" <?php if($tranquilizante == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="tranquilizante_edad" id="tranquilizante_edad" value="<?php if($tranquilizante_edad==''){ echo '0'; } else { echo $tranquilizante_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Inhalantes</td>
                                 	  <td align="center"><input type="radio" name="inhalantes" value="Alguna vez" <?php if($inhalantes == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="inhalantes" value="Recientemente" <?php if($inhalantes == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="inhalantes" value="No se sabe" <?php if($inhalantes == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="inhalantes" value="No consume" <?php if($inhalantes == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="inhalantes_edad" id="inhalantes_edad" value="<?php if($inhalantes_edad==''){ echo '0'; } else { echo $inhalantes_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Metadona</td>
                                 	  <td align="center"><input type="radio" name="metadona" value="Alguna vez" <?php if($metadona == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="metadona" value="Recientemente" <?php if($metadona == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="metadona" value="No se sabe" <?php if($metadona == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="metadona" value="No consume" <?php if($metadona == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="metadona_edad" id="metadona_edad" value="<?php if($metadona_edad==''){ echo '0'; } else { echo $metadona_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Solventes</td>
                                 	  <td align="center"><input type="radio" name="solventes" value="Alguna vez" <?php if($solventes == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="solventes" value="Recientemente" <?php if($solventes == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="solventes" value="No se sabe" <?php if($solventes == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="solventes" value="No consume" <?php if($solventes == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="solventes_edad" id="solventes_edad" value="<?php if($solventes_edad==''){ echo '0'; } else { echo $solventes_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Cocaina</td>
                                 	  <td align="center"><input type="radio" name="cocaina" value="Alguna vez" <?php if($cocaina == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="cocaina" value="Recientemente" <?php if($cocaina == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="cocaina" value="No se sabe" <?php if($cocaina == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="cocaina" value="No consume" <?php if($cocaina == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="cocaina_edad" id="cocaina_edad" value="<?php if($cocaina_edad==''){ echo '0'; } else { echo $cocaina_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Éxtasis</td>
                                 	  <td align="center"><input type="radio" name="extasis" value="Alguna vez" <?php if($extasis == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="extasis" value="Recientemente" <?php if($extasis == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="extasis" value="No se sabe" <?php if($extasis == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="extasis" value="No consume" <?php if($extasis == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="extasis_edad" id="extasis_edad" value="<?php if($extasis_edad=='') { echo '0'; } else { echo $extasis_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Crack</td>
                                 	  <td align="center"><input type="radio" name="crack" value="Alguna vez" <?php if($crack == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="crack" value="Recientemente" <?php if($crack == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="crack" value="No se sabe" <?php if($crack == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="crack" value="No consume" <?php if($crack == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="crack_edad" id="crack_edad" value="<?php if($crack_edad=='') { echo '0'; } else { echo $crack_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Esteroides</td>
                                 	  <td align="center"><input type="radio" name="esteroides" value="Alguna vez" <?php if($esteroides == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="esteroides" value="Recientemente" <?php if($esteroides == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="esteroides" value="No se sabe" <?php if($esteroides == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="esteroides" value="No consume" <?php if($esteroides == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="esteroides_edad" id="esteroides_edad" value="<?php if($esteroides_edad=='') { echo '0'; } else { echo $esteroides_edad;}?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                                 	<tr>
                                 	  <td align="left">Otros</td>
                                 	  <td align="center"><input type="radio" name="otros" id="otro" value="Alguna vez" <?php if($otros == 'Alguna vez') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="otros" id="otro" value="Recientemente" <?php if($otros == 'Recientemente') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="otros" id="otro" value="No se sabe" <?php if($otros == 'No se sabe') echo 'checked';?>></td>
                                 	  <td align="center"><input type="radio" name="otros" id="otro" value="No consume" <?php if($otros == 'No consume') echo 'checked';?>></td>
                                 	  <td align="center"><input type="text" size="1" maxlength="2" name="otros_edad" id="otros_edad" value="<?php if($otros_edad==''){ echo '0'; } else { echo $otros_edad; }?>" onkeypress="return soloNumeros(event)"></td>
                               	  </tr>
                       	        </tbody>
	                   	    </table></td>
                        </tr>
                        </table>
                  
                        <table width="100%">
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
                                    	<td align="left">Prácticas que ponen al NNA en riesgo (por ej., se inyecta, comparte utencilios)</td>
                                        <td align="center"><input type="radio" name="nnariesgo" value="Si" <?php if($nnariesgo == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="nnariesgo" value="No" <?php if($nnariesgo == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="nnariesgo" value="No se sabe" <?php if($nnariesgo == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Ve el uso de sustancias como algo positivo y/o escencial en la vida</td>
                                        <td align="center"><input type="radio" name="usopositivo" value="Si" <?php if($usopositivo == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="usopositivo" value="No" <?php if($usopositivo == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="usopositivo" value="No se sabe" <?php if($usopositivo == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Notable efecto perjudicial en la educación, relaciones interpersonales, funcionamiento diario</td>
                                        <td align="center"><input type="radio" name="educacion" value="Si" <?php if($educacion== 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="educacion" value="No" <?php if($educacion == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="educacion" value="No se sabe" <?php if($educacion == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Comete infracciones para obtener dinero para consumir sustancias</td>
                                        <td align="center"><input type="radio" name="infracciones" value="Si" <?php if($infracciones == 'Si') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="infracciones" value="No" <?php if($infracciones == 'No') echo 'checked';?>></td>
                                        <td align="center"><input type="radio" name="infracciones" value="No se sabe" <?php if($infracciones == 'No se sabe') echo 'checked';?>></td>
                                	</tr>
                                 	<tr>
                                    	<td align="left">Otras relaciones con delitos (por ej., comete infracciones bajo la influencia de sustancias, porta/ provee drogas ilegales, obtiene sustancias por medio del engaño)</td>
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
                        	<td colspan="8"><textarea cols="185" rows="3" name="evidencia" id="evidencia" onkeyup="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('evidencia','MostContador1','{CHAR} caracteres restantes.',1000);"><?php echo $evidencia;?></textarea>
                            <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador1">1000 caracteres restantes</span></label>
                            </td>
                        </tr> 
                        </table>
                    </td>
                    </tr>
                    <tr>
                    <td align="left">
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