<?php
session_start();
//echo $_SESSION['gestor_territorial'];
if (in_array(4, $_SESSION['glopermisos']['modulo']) && 
($_SESSION['glopermisos']['escritura'][3] == 1 || $_SESSION['glopermisos']['lectura'][3] == 1 )){
require_once('../clases/Casos.class.php');
require_once('../clases/Revision.class.php');
require_once('../clases/Evaluacion.class.php');
require_once('../clases/Util.class.php');

$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$idetapa =  filter_var($_GET['idetapa'], FILTER_SANITIZE_STRING);

$_SESSION['idcaso'] = $idcaso;
$_SESSION['idetapa'] = $idetapa;
$perfil = '';
$opcion = '';
$gestor_territorial = '';
$gestor_profesional = '';

if($_SESSION['idetapa']=='3'){
	$etapa = 1;
	$asset = "ASSET";
	$rechazo_asset = "Rechazar Evaluaciòn";
	$aceptacion_asset = "Aprobar Evaluaciòn";
} else if($_SESSION['idetapa']=='5'){
	$etapa = 2;
	$asset = "Reevaluaciòn ASSET";
	$rechazo_asset = "Rechazar Reevaluaciòn";
	$aceptacion_asset = "Aprobar Reevaluaciòn";
}

$caso = new Casos(null);
$resultado = $caso->entregaCaso($idcaso);
if(count($resultado)>0){	
	$estado_revision = '';

	$obj = new Revision(null);
	$revision = $obj->entregaAssetRevision($idcaso,$etapa);
	$comentario = $obj->entregaAssetComentario($idcaso,$etapa);
	
	foreach( $revision as $rev ){
		$estado_revision = $rev['re_estado'];
	}
	
	$obj->Close();
	
$obj = new Evaluacion(null);
$rs = $obj->entregaAssetEvaluacion($idcaso,$etapa);
foreach( $rs as $res ){		
	$totalnotas = $res['ev_notahogar']+$res['ev_notarelacion']+$res['ev_notaeducacion']+$res['ev_notabarrio']+$res['ev_notaestilo']+$res['ev_notasustancias']+$res['ev_notasalud']+$res['ev_notasalud2']+$res['ev_notapercepcion']+$res['ev_notacomportamiento']+$res['ev_notaactitud']+$res['ev_notamotivacion'];
}

$entrega_gestor_profesional = $caso->entregaGestorProfesionalCaso($idcaso);
foreach($entrega_gestor_profesional as $res){
	$gestor_profesional2=$res['us_rut'];
}

$resultado = $caso->entregaGestorComunaCaso($idcaso);
if(count($resultado)>0){	
	foreach($resultado as $res){
		//echo $perfil;
		if($res['pe_idperfil'] == 3)
		$gestor_territorial = $res['rutgestor'];
  
        if($res['pe_idperfil'] == 2)
		$gestor_profesional = $res['us_rut'];
	}
	
}else{
$gestor_territorial = $_SESSION['gestor_territorial'];
$gestor_profesional = $_SESSION['gestor_territorial'];
}
		
}
else{
	session_destroy();
	header('location: ../index.php');
}

//echo $gestor_profesional.'  '.$gestor_profesional2;
?>
<div id="content-wrapper">
<section>
<div class="contenedor">

      <h2></h2>
                	
                    <div class="caja">
        				<h3> Revisi&oacute;n <?php print '- Caso N&deg; '.$_SESSION['idcaso']; ?></h3>   
                    </div>  
                    <form name="form_revision" id="form_revision" method="post" action="insAssetRevision-ajax.php" onsubmit="return validarAssetRevision()">
                    <input type="hidden" name="auth_token" />
                    <input type="hidden" name="opcion" value="<?php echo $opcion;?>">
                    <input type="hidden" name="gestor_territorial" value="<?php echo $gestor_profesional;?>">
                    <input type="hidden" name="total_puntos" value="<?php echo $totalnotas; ?>">
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs" colspan="2"><?php 
							//$_SESSION['gestor_territorial'] == $_SESSION['glorut']
//echo $perfil. 'ddd'.$_SESSION['gestor_territorial'].'  '.$estado_revision." / ".$gestor_territorial."-".$_SESSION['glorut']." / ".$_SESSION['glogestorcentral'];
							if($estado_revision == 'Pendiente' && ($gestor_territorial == $_SESSION['glorut'] || $_SESSION['glogestorcentral'] == 'SI') ){?>
                            <table width="100%">
                            <tr>
                                <td><b>Comentarios</b></td>
                            </tr>
                            <tr>    
                                <td><textarea cols="150" rows="3" name="comentario" id="comentario" onkeyup="Contar('comentario','MostContador1','{CHAR} caracteres restantes.',1000);" onkeypress="Contar('comentario','MostContador1','{CHAR} caracteres restantes.',1000);" onblur="Contar('comentario','MostContador1','{CHAR} caracteres restantes.',1000);"></textarea>
                                <br><label style="margin-top:0;font-size:12px;color:#C00;width:auto"><span id="MostContador1">1000 caracteres restantes</span></label>
                                </td>
                            </tr>
                            <tr>
                            	<td align="left" class="datos_sgs" style="background-color:#FFF;border-bottom:0"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="<?php print $aceptacion_asset; ?>" name="Aprueba"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="<?php print $rechazo_asset; ?>" name="Rechaza"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                           	  </tr>
                          </table>
                            <?php }?>
                   	  </td>
                    </tr>
                    <tr>
                        <!--<td align="left" class="datos_sgs"><input type="submit" class="btn btn-warning" value="Grabar Comentario"></td>-->
                        
                    </tr>
                    </table>    
                    </form>  
                    <br>

                    <div id="contenedor-grilla-base">
                    <table align="center"  id="grilla-base" style="table-layout:fixed; width:100%;">
                    <tr class="cabecera">
                    	<th width="10%">Fecha</th>
                        <th width="10%">Usuario</th>
                        <th width="10%">Comentario</th>
                        <th width="10%">Estado</th>
                    </tr>
                    <?php
					foreach( $comentario as $com ){
					?>
                    <tr>
                    	<td width="10%" nowrap align="center" valign="top"><?php echo Util::formatFechaHora($com['cm_fecha']);?></td>
                        <td width="20%" valign="top"><?php echo $com['nombre'];?></td>
                        <td width="60%"><?php print substr($com['cm_comentario'],0,77)."<br>";
print substr($com['cm_comentario'],77,77)."<br>";
print substr($com['cm_comentario'],154,77)."<br>";
print substr($com['cm_comentario'],231,77)."<br>";
print substr($com['cm_comentario'],308,77)."<br>";
print substr($com['cm_comentario'],385,77)."<br>";
print substr($com['cm_comentario'],462,77)."<br>";
print substr($com['cm_comentario'],539,77)."<br>";
print substr($com['cm_comentario'],616,77)."<br>";
print substr($com['cm_comentario'],693,77)."<br>";
print substr($com['cm_comentario'],770,61)."<br>";
print substr($com['cm_comentario'],831,38)."<br>";
print substr($com['cm_comentario'],869,38)."<br>";
print substr($com['cm_comentario'],907,38)."<br>";
print substr($com['cm_comentario'],945,38)."<br>";
print substr($com['cm_comentario'],983,17)."<br>";?></td>
                        <td width="10%" align="center" valign="top"><?php echo $com['cm_estado'];?></td>
                    </tr>
                                     
                    <?php }?>
                    </table>
                    <table width="100%">
                                    	<tbody><tr>
                                          <td width="1%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="1%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                          <td width="30%"><input onclick="window.location.href='visCasos.php'" type="button" value="Volver" class="boton" /></td>
                                          <td width="2%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="1%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="1%">&nbsp;</td>
                                        </tr>
                                    </tbody></table>
                    </div>
                     
    
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
	alert("OcurriÃ³ un error al ingresar el registro");
	</script>
	<?php }else if($_SESSION['mensaje']==1){?>
	<script>
	alert("Registro ingresado exitosamente");
	</script>
	
	<?php }
}?>
<?php }
else{
	session_destroy();
	header('location: ../index.php');
}
?>