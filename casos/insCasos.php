<?php
session_start();
if (in_array(2, $_SESSION['glopermisos']['modulo']) && $_SESSION['glopermisos']['escritura'][1] == 1){
//include('../accesos.php');
require_once('../clases/Region.class.php');
require_once('../clases/Delito.class.php');
require_once('../clases/ViaIngreso.class.php');
require_once('../clases/Nacionalidad.class.php');
require_once('../clases/Util.class.php');

$delito = new Delito(null);
$delitos = $delito->muestraDelitosActivos();
$delito->Close();

$via = new ViaIngreso(null);
$vias = $via->muestraViaIngresoActivos();
$via_opt1=$via->MostrarAssetMedidasSancionesSename();
$via_opt2=$via->MostrarAssetOtrasSename();
$via_opt3=$via->MostrarProgramaProteccionSENAME();


$vias3 = $via->muestraViaIngresoActivosPPSENAME();
$vias1 = $via->muestraViaIngresoActivosPPOtros();
$vias2 = $via->muestraViaIngresoActivosMedidasSanciones();
$via->Close();

$region = new Region(null);
$regiones = $region->muestraRegiones();
$region->Close();

$nacionalidad = new Nacionalidad(null);
$nacionalidades = $nacionalidad->muestraNacionalidad();
$nacionalidad->Close();

$navegador = Util::detectaNavegador();
$cadena = md5('inscasos_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

foreach ($via_opt1 as $res){
	$opt1=$res['vi_descripcion'];
}

foreach($via_opt2 as $res){
	$opt2=$res['vi_descripcion'];
}

foreach($via_opt3 as $res){
	$opt3=$res['vi_descripcion'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="../images/gob.jpg" rel="icon" type="image/x-icon" />
<title><?php echo $_SESSION['glosistema'];?></title>
		<link rel="stylesheet" type="text/css" href="../css/global.css" />
		<link rel="stylesheet" type="text/css" href="../css/grilla.css">
		<link rel="stylesheet" type="text/css" href="../css/grilla-base.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link href="../js/datepicker/css/redmond/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
setTimeout ("redireccionar('../index.php')", <?php echo $_SESSION['gloexpiracion'];?>); //tiempo expresado en milisegundos
</script>
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="../js/jquery.blockUI.js"></script>
<!--datepicker--> 
<script src="../js/jquery-ui.js"></script>
<script src="../js/datepicker/js/jquery.ui.datepicker-es.js"></script>
<!--datepicker--> 
<script>
$(document).ready(function(){
	$(document).ajaxStop($.unblockUI); 
	
	$('#rut').blur(function() { 
	//alert('validar rut');
	
		$.validator.addMethod("rut", function(value, element) { 
			return this.optional(element) || validaRut(value); 
		}, "Debe ingresar un RUT válido.");
		
	});
	
	$("#datepicker0").datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		buttonImage: '../images/icon/iconCalendario.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		yearRange: '-10:-0',
		maxDate: "-0D"
	});
	
	$("#datepicker1").datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		buttonImage: '../images/icon/iconCalendario.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		//yearRange: '-40:-0',
		minDate: "-6574D",
		maxDate: "-2922D"
		//yearRange: '-80:+30'
		
		
	});
	
	$("#region").change(function () {
    	$("#region option:selected").each(function () {
        	id_region = $(this).val();
            $.post("../comunas-caso-ajax.php", { id_region: id_region }, function(data){
            $("#comuna").html(data);
			//$("#comunapro").html(data);
            });            
		});
	});
	
	$("#regionpro").change(function () {
    	$("#regionpro option:selected").each(function () {
        	id_region = $(this).val();
            $.post("../comunas-caso-ajax.php", { id_region: id_region }, function(data){
           // $("#comuna").html(data);
			$("#comunapro").html(data);
            });            
		});
	});
	
	$("#form").validate({
			rules: {
				nombre: { required: true},
				paterno: { required: true},
				materno: { required: true},
				region: { required: true},
				comuna: { required: true},
				fnacimiento: { required: true},
				nacionalidad: { required: true},
				sexo: { required: true},
				educacion: { required: true},
				domicilio: { required: true},
				numero: { required: true},
				fdenuncia: { required: true},
				delito: { required: true},
				viaingreso: { required: true}
			},
			messages: {
				nombre: "Requerido",
				paterno: "Requerido",
				materno: "Requerido",
				region: "Requerido",
				comuna: "Requerido",
				fnacimiento: "Requerido",
				nacionalidad: "Requerido",
				sexo: "Requerido",
				educacion: "Requerido",
				domicilio: "Requerido",
				numero: "Requerido",
				fdenuncia: "Requerido",
				delito: "Requerido",
				viaingreso: "Requerido"
			},
			submitHandler: function(form){
				//document.form.action = 'insCasos-ajax.php';
				//document.form.submit();
				$.ajax({
					type: 'post',
					url: 'insCasos-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							//redireccionar('visCasos.php');
							redireccionar('modCasos.php?id='+msg.mensaje);
						}		
						else {
							if(msg.mensaje == 'ERROR')
							redireccionar('index.php');
							else
							alert(msg.mensaje);
						}
					}
				});
				
			}
	});
});

function comprobarCaso(){
	
	if($("#rut").val()!='' && $("#nombre").val()=='' && $("#paterno").val()=='' && $("#materno").val()==''){
		//alert('rut');
		
		$.ajax({
				type: 'post',
				url: 'comprobarCodigoCaso-ajax.php',
				data: $("#form").serialize(),
				dataType: 'json',
				beforeSend: function(){
					$.blockUI({ message: '<h2><img src=\'../images/gif-load.gif\' size=\'20\' align=\'absmiddle\' />&nbsp;Espere un momento...</h2>' }); 
				},
				success: function(msg){			
					if (msg.success) {
						$("#codigo").val(msg.mensaje)
						alert("El ciudadano ya se encuentra ingresado y el codigo del caso es: "+msg.mensaje);
						//alert(msg.mensaje);
						//redireccionar('visAdmisibilidad.php');
					}else {
						if(msg.borrar == '1')
							$("#rut").val('');
						else 
							alert(msg.mensaje);							
					}
				}
		});	
	}
	if($("#rut").val()=='' && $("#nombre").val()!='' && $("#paterno").val()!='' && $("#materno").val()!=''){
		alert($("#nombre").val());
		$.ajax({
				type: 'post',
				url: 'comprobarCodigoCaso-ajax.php',
				data: $("#form").serialize(),
				dataType: 'json',
				beforeSend: function(){
					$.blockUI({ message: '<h2><img src=\'../images/gif-load.gif\' size=\'20\' align=\'absmiddle\' />&nbsp;Espere un momento...</h2>' }); 
				},
				success: function(msg){			
					if (msg.success) {
						$("#codigo").val(msg.mensaje)
						alert("El ciudadano ya se encuentra ingresado y el codigo del caso es: "+msg.mensaje);
						//alert(msg.mensaje);
						//redireccionar('visAdmisibilidad.php');
					}else {
						if(msg.borrar == '1')
							$("#rut").val('');
						else 
							alert(msg.mensaje);							
					}
				}
		});	
	}
}
</script>
</head>
<body>
<div id="content-wrapper">
            <?php include('../header.php');?>
                <?php require_once('../menu.php');?>
                <section>
<div class="contenedor">
 
                    <h2>Casos</h2>
                	
                    <div class="caja">
        				<h3>NUEVO</h3>   
                    </div>  
                <form name="form" id="form" method="post">
                <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                <table width="100%" align="center">
                <tr>                
                  <td align="left" class="datos_sgs"><table width="100%">
                    <caption style="color:#2571B7">Informaci&oacute;n Personal</caption>    
                    <tr>
                        <td width="7%" class="alt"><label>Regi&oacute;n *</label></td>
                        <td colspan="4">
                        <input type="hidden" name="codigo" id="codigo" size="20" maxlength="30" style="text-transform: uppercase" /><select name="region" id="region" class="required region">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $regiones as $reg ){
                        ?>
                        <option value="<?php echo $reg['re_idregion'];?>"><?php echo $reg['re_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td width="1%" class="alt">&nbsp;</td>
                        <td width="6%" class="alt"><label>Comuna *</label></td>
                        <td width="13%">
                        <select name="comuna" id="comuna" class="required comuna">
                        <option value="">Seleccione...</option>
                        </select>
                        </td>
                        <td width="1%" class="alt">&nbsp;</td>
                        <td width="6%" class="alt"><label>RUT</label></td>
                        <td width="19%"><input type="text" name="rut" id="rut"  size="10" maxlength="10" class="rut" onBlur="comprobarCaso();"/></td>
                    </tr>
                    <tr>
                    	<td width="7%" class="alt"><label>Nombre *</label></td>
                        <td width="18%"><input type="text" name="nombre" id="nombre" size="20" maxlength="50" class="required nombre" /><!--onBlur="comprobarCaso();"--></td>
                        <td width="3%" class="alt">&nbsp;</td>
                        <td width="5%" class="alt"><label>Apellido Paterno *</label></td>
                        <td width="21%"><input type="text" name="paterno" id="paterno" size="20" maxlength="50" class="required paterno" /><!--onBlur="comprobarCaso();"--></td>
                        <td width="1%" class="alt">&nbsp;</td>
                        <td width="6%" class="alt"><label>Apellido Materno *</label></td>
                        <td width="13%"><input type="text" name="materno" id="materno" size="20" maxlength="50" class="required materno" /><!--onBlur="comprobarCaso();"--></td>
                        <td width="1%" class="alt">&nbsp;</td>
                        <td width="6%" class="alt"><label>Fec. * Nacimiento </label></td>
                        <td width="19%"><input type="text" name="fnacimiento" id="datepicker1" size="8" maxlength="10" class="required fnacimiento" readonly/></td>
                    </tr>
                    <tr>
                    	
                        <td class="alt"><label>Nacionalidad *</label></td>
                        <td>
                        <select name="nacionalidad" id="nacionalidad" class="required nacionalidad">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $nacionalidades as $nac ){
                        ?>
                        <option value="<?php echo $nac['na_idnacionalidad'];?>"><?php echo $nac['na_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>Sexo *</label></td>
                        <td>
                        <select name="sexo" class="required sexo">
                        <option value="">Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        </select>
                        </td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>Nivel Educacional *</label></td>
                        <td colspan="4"><select name="educacion" class="required educacion">
                          <option value="">Seleccione...</option>
                          <option value="Basica">B&aacute;sica</option>
                          <option value="Media humanistico cientifica">Media human&iacute;stico cient&iacute;fica</option>
                          <option value="Media tecnico profesional">Media t&eacute;cnico profesional</option>
                          <option value="Escuela especial">Escuela especial</option>
                          <option value="Educacion para adultos">Educaci&oacute;n para adultos</option>
                          <option value="Programa reinsercion educativa">Programa reinserci&oacute;n educativa</option>
                          <option value="Examenes libres">Ex&aacute;menes libres</option>
                        </select></td>
                    </tr>
                    <tr>
                    	<td class="alt"><label>Domicilio *</label></td>
                        <td colspan="4"><input type="text" name="domicilio" size="50" maxlength="50" class="required domicilio"/></td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>N&uacute;mero *</label></td>
                        <td><input type="text" name="numero" size="5" maxlength="10" onKeyPress="return soloNumeros(event)" class="required numero"/></td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="alt"><label>Dpto/ Poblaci&oacute;n</label></td>
                      <td colspan="4"><input type="text" name="poblacion" size="20" maxlength="50"/></td>
                      <td class="alt">&nbsp;</td>
                      <td class="alt">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td class="alt">&nbsp;</td>
                      <td class="alt">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td align="left" class="datos_sgs">
                	<table width="100%">
                    <caption style="color:#2571B7">Informaci&oacute;n del Delito</caption>
                    <tr>
                        <td width="8%" class="alt"><label>Fec. Denuncia o derivaci&oacute;n *</label></td>
                        <td width="15%"><input type="text" name="fdenuncia" id="datepicker0" size="8" maxlength="10" readonly class="required fdenuncia"/></td>
                        <td width="8%" class="alt">&nbsp;</td>
                        <td width="8%" class="alt"><label>Motivo</label></td>
                        <td colspan="4"><input type="text" name="motivo" size="65" maxlength="100">
                        </td>
                        <td width="8%" class="alt">&nbsp;</td>
                        <td width="8%" class="alt"><label>Delito *</label></td>
                        <td width="15%">
                        <select name="delito" id="delito" style="width:180px" class="required delito">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $delitos as $del ){
							if($del['de_num']<=9)
							$num = "0".$del['de_num'];
							else
							$num = $del['de_num'];
                        ?>
                        <option value="<?php echo $num;?>"><?php echo $num." ".$del['de_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                    	<td width="8%" class="alt"><label>Clase participante</label></td>
                        <td width="15%">
                        <select name="clase" id="clase">
                        <option value="">Seleccione...</option>
                        <option value="Afectado">Afectado</option>
                        <option value="Conducido">Conducido</option>
                        <option value="Denunciado">Denunciado</option>
                        <option value="Detenido">Detenido</option>
                        </select>
                        </td>
                        <td width="8%" class="alt">&nbsp;</td>
                        <td width="8%" class="alt"><label>Registro Civil</label></td>
                        <td width="15%">
                        <select name="rcivil" id="rcivil">
                        <option value="">Seleccione...</option>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                        </select>
                        </td>
                        <td width="8%" class="alt">&nbsp;</td>
                        <td width="8%" class="alt"><label>N&deg; reingreso vulnerado</label></td>
                        <td width="15%"><input type="text" name="rvulnerado" size="2" maxlength="3" onKeyPress="return soloNumeros(event)"/></td>
                        <td width="8%" class="alt">&nbsp;</td>
                        <td width="8%" class="alt"><label>N&deg; reingreso infractor</label></td>
                        <td width="15%"><input type="text" name="rinfractor" size="2" maxlength="3" onKeyPress="return soloNumeros(event)"/></td>
                    </tr>
                    <tr>
                    	<td class="alt"><label>N&deg; reingreso inimputable</label></td>
                        <td><input type="text" name="rinimputable" size="2" maxlength="3" onKeyPress="return soloNumeros(event)"/></td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>Unidad procedimiento</label></td>
                        <td><input type="text" name="unidad" size="20" maxlength="50"/></td>
                        <td width="8%" class="alt">&nbsp;</td>
                         <td width="8%" class="alt"><label>Regi&oacute;n procedimiento</label></td>
                        <td colspan="1">
                        <select name="regionpro" id="regionpro" >
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $regiones as $reg ){
                        ?>
                        <option value="<?php echo $reg['re_idregion'];?>"><?php echo $reg['re_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>Comuna procedimiento</label></td>
                        <td><select name="comunapro" id="comunapro">
                        <option value="">Seleccione...</option>
                        </select></td>
                        
                    </tr>
                    <tr>
                    	<td class="alt"><label>Juzgado</label></td>
                        <td><input type="text" name="juzgado" size="20" maxlength="50"/></td>
                        <td class="alt">&nbsp;</td>
                    	<td class="alt"><label>N&deg; parte</label></td>
                        <td><input type="text" name="parte" size="5" maxlength="10" onKeyPress="return soloNumeros(event)"/></td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>Detenido o afectado en</label></td>
                        <td><input type="text" name="detenido" size="20" maxlength="50"/></td>
                        <td class="alt">&nbsp;</td>
                        <td class="alt"><label>N&deg; ingresos 24 Horas</label></td>
                        <td><input type="text" name="r24horas" size="2" maxlength="3" onKeyPress="return soloNumeros(event)"/></td>
                   </tr>
                   <tr>     
                        <td class="alt"><label>V&iacute;a Ingreso *</label></td>
                        <td colspan="10">
                        <select name="viaingreso" id="viaingreso" style="width:180px" class="required viaingreso">
      <option value="">Seleccione...</option>
        <optgroup label="<?php print $opt1; ?>">
        <?php 
                        foreach( $vias2 as $via ){
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>"><?php echo $via['vi_descripcion'];?></option>
                        <?php 
                        }
                        ?>
          </optgroup>
        <optgroup label="<?php print $opt3; ?>">
       <?php 
                        foreach( $vias3 as $via ){
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>"><?php echo $via['vi_descripcion'];?></option>
                        <?php 
                        }
                        ?>
          </optgroup>
                  <optgroup label="<?php print $opt2;?>">
           <?php 
                        foreach( $vias1 as $via ){
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>"><?php echo $via['vi_descripcion'];?></option>
                        <?php 
                        }
                        ?>
          </optgroup>
      </select>
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
                                            <td width="15%"><input onClick="window.location.href='visCasos.php'" type="submit" Value="Volver &raquo;" class="boton"></td>
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
</body>
</html>
<?php }
else{
	session_destroy();
	header('location: ../index.php');
}
?>