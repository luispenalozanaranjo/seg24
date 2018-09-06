<?php
session_start();
if (in_array(2, $_SESSION['glopermisos']['modulo']) && 
($_SESSION['glopermisos']['escritura'][1] == 1 || $_SESSION['glopermisos']['lectura'][1] == 1 )){
require_once('../clases/Casos.class.php');
require_once('../clases/Region.class.php');
require_once('../clases/Delito.class.php');
require_once('../clases/ViaIngreso.class.php');
require_once('../clases/Nacionalidad.class.php');
require_once('../clases/Util.class.php');

$idcaso	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);

$rut	=	'';
$rut_encargado = '';
$idciudadano = '';
$nombre	=	'';
$paterno	=	'';
$materno	=	'';
$region_u	=	'';
$comuna_u	=	'';
$fnacimiento	=	'';
$nacionalidad_u	=	'';
$sexo	=	'';
$educacion	=	'';
$domicilio	=	'';
$numero	=	'';
$poblacion	=	'';
$fdenuncia	=	'';
$motivo	=	'';
$delito_u	=	'';
$clasep	=	'';			      
$rcivil	=	'';
$rvulnerado	=	'';
$rinfractor	=	'';
$rinimputable	=	'';
$unidad	=	'';
$comunapro	=	'';
$juzgado	=	'';
$parte	=	'';
$detenidoen	=	'';
$ingreso24	=	'';
$via_u	=	'';
$codigo = '';

$caso = new Casos(null);
$resultado = $caso->entregaCaso($idcaso);
$resultado_fcreacion_caso = $caso->validaFechaIngreso($idcaso);
foreach ($resultado_fcreacion_caso as $fcreacion) {
	$fcreacion_caso = $fcreacion['ca_finsercion'];
}
if(count($resultado)>0){
	foreach( $resultado as $res ){
		$rut	=	$res['ci_rut'];
		$rut_encargado = $res['us_rut'];
		$idciudadano = $res['ci_idciudadano'];
		$nombre	=	$res['ci_nombre'];
		$paterno	=	$res['ci_paterno'];
		$materno	=	$res['ci_materno'];
		$region_u	=	$res['re_idregion'];
		$comuna_u	=	$res['co_idcomuna'];
		$fnacimiento	=	Util::formatFecha($res['ci_fnacimiento']);
		$nacionalidad_u	=	$res['na_idnacionalidad'];
		$sexo	=	$res['ci_sexo'];
		$educacion	=	$res['ci_educacion'];
		$domicilio	=	$res['ci_domicilio'];
		$numero	=	$res['ci_numero'];
		$poblacion	=	$res['ci_poblacion'];
		$fdenuncia	=	Util::formatFecha($res['ca_fdenuncia']);
		$motivo	=	$res['ca_motivo'];
		$delito_u	=	$res['de_iddelito'];
		$clasep	=	$res['ca_claseparticipante'];					      
		$rcivil	=	$res['ca_regcivil'];
		$rvulnerado	=	$res['ca_reingresovulnerado'];
		$rinfractor	=	$res['ca_reingresoinfractor'];
		$rinimputable	=	$res['ca_reingresoinimputable'];
		$unidad	=	$res['ca_unidadprocedimiento'];
		$comunapro	=	$res['ca_comunaprocedimiento'];
		$juzgado	=	$res['ca_juzgado'];
		$parte	=	$res['ca_parte'];
		$detenidoen	=	$res['ca_detenidoen'];
		$ingreso24	=	$res['ca_ingresos24'];
		$via_u	=	$res['vi_idvia'];
		$codigo = $res['ca_codigo'];
		$etapa = $res['ca_etapa'];
	}
	
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
	$region_pro = $region->entregaRegionComuna($idcaso);
	//echo $comunapro;
	$region->Close();
	
	$nacionalidad = new Nacionalidad(null);
	$nacionalidades = $nacionalidad->muestraNacionalidad();
	$nacionalidad->Close();
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('modcasos_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

//echo $comuna_u;

foreach ($via_opt1 as $res){
	$opt1=$res['vi_descripcion'];
}

foreach($via_opt2 as $res){
	$opt2=$res['vi_descripcion'];
}

foreach($via_opt3 as $res){
	$opt3=$res['vi_descripcion'];
}
}
else{
	session_destroy();
	header('location: ../index.php');
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
		/*maxDate: "-0D"*/
		maxDate: "<?php echo date('d-m-Y') ?>",
        minDate: "<?php if($fcreacion_caso==''){ echo "-100Y";} else { echo $fcreacion_caso; }?>"
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
	
	
	$.post("../comunas-caso-ajax.php", { id_region: <?php echo $region_u;?>, id_comuna: <?php echo $comuna_u;?> }, function(data){
            $("#comuna").html(data);
    });  
	
	<?php if($region_pro>0 && $comunapro>0){ ?>
	$.post("../comunas-caso-ajax.php", { id_region: <?php echo $region_pro;?>, id_comuna: <?php echo $comunapro;?> }, function(data){
            $("#comunapro").html(data);
    });  
	<?php } ?>
	
	
	
	$('#rut').keypress(function() { 
	//alert('validar rut');
	
		$.validator.addMethod("rut", function(value, element) { 
			return this.optional(element) || validaRut(value); 
		}, "Debe ingresar un RUT válido.");
		
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
				numero: { required: true}
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
				numero: "Requerido"
			},
			submitHandler: function(form){
				//alert("ok");
				//document.form.action = 'modCasos-ajax.php';
				//document.form.submit();
				$.ajax({
					type: 'post',
					url: 'modCasos-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							redireccionar('visCasos.php');
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
</script>
</head>
<body>
 <div id="content-wrapper">	  
            <?php include('../header.php');?>

                <?php require_once('../menu.php');?>
<section>
<div class="contenedor">
 <div class="menuExtra">
       				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <h2>Casos</h2>
                	
                    <div class="caja">
        				<h3> Edici&oacute;n -  <?php print 'Caso N&deg; '.$idcaso; ?></h3>   
                    </div>  
  <!--<div class="contenedor">-->
<!--<div class="caja caja-sin-borde caja-sin-margen">  -->
                <form name="form" id="form" method="post">
                <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                <input type="hidden" name="idciudadano" value="<?php echo $idciudadano;?>">
                <table width="100%" align="center">
                <tr>                
                  <td align="left" class="datos_sgs"><table width="99%">
                    <caption style="color:#2571B7">
                    Informaci&oacute;n Personal 
                    </caption>
                    <tr>
                    	<td class="alt"><label><b>C&oacute;digo Caso</b></label></td>
                        <td><input type="text" name="codigo" size="20" maxlength="50" value="<?php echo $codigo;?>"/></td>
                        <td>&nbsp;</td>
                      <td><label>Auto Asignarme</label></td>
                        <td>
                      <input type="checkbox" name="auto_asignar" value="Si">&nbsp;<label><?php echo $_SESSION['glonombre'].' '.$_SESSION['glopaterno']; ?></label></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                    	<td width="9%"><label>ID</label></td>
                        <td width="12%">
                        <input type="text" name="idcaso" size="10" readonly value="<?php echo $idcaso;?>">
                        </td>
                        <td width="4%" class="alt">&nbsp;</td>
                        <td width="7%" class="alt"><label>Regi&oacute;n *</label></td>
                        <td colspan="4">
                        <select name="region" id="region" class="required region">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $regiones as $reg ){
							if($region_u == $reg['re_idregion'])
							$sel = 'selected';
							else 
							$sel = '';
                        ?>
                        <option value="<?php echo $reg['re_idregion'];?>" <?php echo $sel;?>><?php echo $reg['re_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td width="1%">&nbsp;</td>
                        <td width="7%"><label>Comuna *</label></td>
                        <td width="21%">
                        <select name="comuna" id="comuna" class="required comuna">
                        <option value="">Seleccione...</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                    	<td width="9%"><label>RUT</label></td>
                        <td width="12%"><input type="text" name="rut" id="rut" size="10" maxlength="10" value="<?php echo $rut;?>" class="rut"/></td>
                        <td width="4%">&nbsp;</td>
                    	<td width="7%"><label>Nombre *</label></td>
                        <td width="21%"><input type="text" name="nombre" size="20" maxlength="50" class="required nombre" value="<?php echo $nombre;?>"/></td>
                        <td width="1%">&nbsp;</td>
                        <td width="5%"><label>Apellido Paterno *</label></td>
                        <td width="12%"><input type="text" name="paterno" size="20" maxlength="50" class="required paterno" value="<?php echo $paterno;?>"/></td>
                        <td width="1%">&nbsp;</td>
                        <td width="7%"><label>Apellido Materno *</label></td>
                        <td width="21%"><input type="text" name="materno" size="20" maxlength="50" class="required materno" value="<?php echo $materno;?>"/></td>
                    </tr>
                    <tr>
                    	<td width="9%"><label>Fec. Nacimiento *</label></td>
                        <td width="12%"><input type="text" name="fnacimiento" id="datepicker1" size="8" maxlength="10" class="required fnacimiento" readonly value="<?php echo $fnacimiento;?>"/></td>
                        <td>&nbsp;</td>
                        <td><label>Nacionalidad *</label> 
                      </td>
                        <td>
                        <select name="nacionalidad" id="nacionalidad" class="required nacionalidad">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $nacionalidades as $nac ){
							if($nacionalidad_u == $nac['na_idnacionalidad'])
							$sel = 'selected';
							else
							$sel = '';
                        ?>
                        <option value="<?php echo $nac['na_idnacionalidad'];?>" <?php echo $sel;?>><?php echo $nac['na_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td>&nbsp;</td>
                        <td><label>Sexo *</label></td>
                        <td>
                        <select name="sexo" class="required sexo">
                        <option value="">Seleccione...</option>
                        <option value="Masculino" <?php if($sexo == 'Masculino')echo 'selected';?>>Masculino</option>
                        <option value="Femenino" <?php if($sexo == 'Femenino') echo 'selected';?>>Femenino</option>
                        </select>
                        </td>
                        <td>&nbsp;</td>
                        <td><label>Nivel Educacional *</label></td>
                        <td colspan="3">
                        <select name="educacion" class="required educacion">
                        <option value="">Seleccione...</option>
                        <option value="Basica" <?php if($educacion == 'Basica') echo 'selected';?>>B&aacute;sica</option>
                        <option value="Media humanistico cientifica" <?php if($educacion == 'Media humanistico cientifica') echo 'selected';?>>Media human&iacute;stico cient&iacute;fica</option>
                        <option value="Media tecnico profesional" <?php if($educacion == 'Media tecnico profesional') echo 'selected';?>>Media t&eacute;cnico profesional</option>
                        <option value="Escuela especial" <?php if($educacion == 'Escuela especial') echo 'selected';?>>Escuela especial</option>
                        <option value="Educacion para adultos" <?php if($educacion == 'Educacion para adultos') echo 'selected';?>>Educaci&oacute;n para adultos</option>
                        <option value="Programa reinsercion educativa" <?php if($educacion == 'Programa reinsercion educativa') echo 'selected';?>>Programa reinserci&oacute;n educativa</option>
                        <option value="Examenes libres" <?php if($educacion == 'Examenes libres') echo 'selected';?>>Ex&aacute;menes libres</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                    	<td><label>Domicilio *</label></td>
                        <td colspan="4"><input type="text" name="domicilio" size="50" maxlength="50" class="required domicilio" value="<?php echo $domicilio;?>"/></td>
                        <td>&nbsp;</td>
                        <td><label>N&uacute;mero *</label></td>
                        <td><input type="text" name="numero" size="5" maxlength="10" onKeyPress="return soloNumeros(event)" class="required numero" value="<?php echo $numero;?>"/></td>
                        <td>&nbsp;</td>
                        <td><label>Dpto/ Poblaci&oacute;n</label></td>
                        <td><input type="text" name="poblacion" size="20" maxlength="50" value="<?php echo $poblacion;?>"/></td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                <td align="left" class="datos_sgs">
                	<table width="99%">
                    <caption style="color:#2571B7">Informaci&oacute;n del Delito</caption>
                    <tr>
                        <td width="8%" class="alt"><label>Fec. Denuncia o derivaci&oacute;n</label></td>
                        <td width="15%"><input type="text" name="fdenuncia" id="datepicker0" size="8" maxlength="10" readonly value="<?php echo $fdenuncia;?>"/></td>
                        <td width="8%" class="alt"><label>Motivo</label></td>
                        <td colspan="3"><input type="text" name="motivo" size="65" maxlength="100" value="<?php echo $motivo;?>">
                        </td>
                        <td width="8%" class="alt"><label>Delito</label></td>
                        <td width="15%">
                        <select name="delito" id="delito" style="width:180px">
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $delitos as $del ){

							
							if($del['de_num']<=9)
							$num = Util::agregarCero($del['de_num']);
							else
							$num = $del['de_num'];
							
							if($delito_u == $num)
							$sel = 'selected';
							else
							$sel = '';
                        ?>
                        <option value="<?php echo $num;?>" <?php echo $sel;?>><?php echo $num." ".$del['de_descripcion'];?></option>
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
                        <option value="Afectado" <?php if($clasep == 'Afectado')echo 'selected';?>>Afectado</option>
                        <option value="Conducido" <?php if($clasep == 'Conducido')echo 'selected';?>>Conducido</option>
                        <option value="Denunciado" <?php if($clasep == 'Denunciado')echo 'selected';?>>Denunciado</option>
                        <option value="Detenido" <?php if($clasep == 'Detenido')echo 'selected';?>>Detenido</option>
                        </select>
                        </td>
                        <td width="8%" class="alt"><label>Registro Civil</label></td>
                        <td width="15%">
                        <select name="rcivil" id="rcivil">
                        <option value="">Seleccione...</option>
                        <option value="Si" <?php if($rcivil == 'Si')echo 'selected';?>>Si</option>
                        <option value="No" <?php if($rcivil == 'No')echo 'selected';?>>No</option>
                        </select>
                        </td>
                        <td width="8%" class="alt"><label>N&deg; reingreso vulnerado</label></td>
                        <td width="15%"><input type="text" name="rvulnerado" size="2" maxlength="3" onKeyPress="return soloNumeros(event)" value="<?php echo $rvulnerado;?>"/></td>
                        <td width="8%" class="alt"><label>N&deg; reingreso infractor</label></td>
                        <td width="15%"><input type="text" name="rinfractor" size="2" maxlength="3" onKeyPress="return soloNumeros(event)" value="<?php echo $rinfractor;?>"/></td>
                    </tr>
                    <tr>
                    	<td class="alt"><label>N&deg; reingreso inimputable</label></td>
                        <td><input type="text" name="rinimputable" size="2" maxlength="3" onKeyPress="return soloNumeros(event)" value="<?php echo $rinimputable;?>"/></td>
                        <td class="alt"><label>Unidad procedimiento</label></td>
                        <td><input type="text" name="unidad" size="20" maxlength="50" value="<?php echo $unidad;?>"/></td>
                        <td width="8%" class="alt"><label>Regi&oacute;n procedimiento</label></td>
                        <td colspan="1">
                        <select name="regionpro" id="regionpro" >
                        <option value="">Seleccione...</option>
                        <?php 
                        foreach( $regiones as $reg ){
							if($region_pro == $reg['re_idregion'])
							$sel = 'selected';
							else 
							$sel = '';
                        ?>
                        <option value="<?php echo $reg['re_idregion'];?>" <?php echo $sel;?>><?php echo $reg['re_descripcion'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        </td>
                        <td class="alt"><label>Comuna procedimiento</label></td>
                        <td><select name="comunapro" id="comunapro">
                        <option value="">Seleccione...</option>
                        </select></td>
                        
                    </tr>
                    <tr>
                    	<td class="alt"><label>Juzgado</label></td>
                        <td><input type="text" name="juzgado" size="20" maxlength="50" value="<?php echo $juzgado;?>"/></td>
                    	<td class="alt"><label>N&deg; parte</label></td>
                        <td><input type="text" name="parte" size="5" maxlength="10" onKeyPress="return soloNumeros(event)" value="<?php echo $parte;?>"/></td>
                        <td class="alt"><label>Detenido o afectado en</label></td>
                        <td><input type="text" name="detenido" size="20" maxlength="50" value="<?php echo $detenidoen;?>"/></td>
                        <td class="alt"><label>N&deg; ingresos 24 Horas</label></td>
                        <td><input type="text" name="r24horas" size="2" maxlength="3" onKeyPress="return soloNumeros(event)" value="<?php echo $ingreso24;?>"/></td>
                    </tr>
                    <tr>    
                        <td class="alt"><label>V&iacute;a Ingreso</label></td>
                        <td>
                        <select name="viaingreso" id="viaingreso" style="width:180px">
      <option value="">Seleccione...</option>
        <optgroup label="<?php print $opt1; ?>">
         <?php 
                        foreach( $vias2 as $via ){
							if($via_u == $via['vi_idvia'])
							$sel = 'selected';
							else
							$sel = '';
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>" <?php echo $sel;?>><?php echo $via['vi_descripcion'];?></option>
                        <?php 
                        }
                        ?>
          </optgroup>
        <optgroup label="<?php print $opt3; ?>">
        <?php 
                        foreach( $vias3 as $via ){
							if($via_u == $via['vi_idvia'])
							$sel = 'selected';
							else
							$sel = '';
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>" <?php echo $sel;?>><?php echo $via['vi_descripcion'];?></option>
                        <?php 
                        }
                        ?>
          </optgroup>
                  <optgroup label="<?php print $opt2;?>">
            <?php 
                        foreach( $vias1 as $via ){
							if($via_u == $via['vi_idvia'])
							$sel = 'selected';
							else
							$sel = '';
                        ?>
                        <option value="<?php echo $via['vi_idvia'];?>" <?php echo $sel;?>><?php echo $via['vi_descripcion'];?></option>
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
                <?php if (in_array(2, $_SESSION['glopermisos']['modulo'])  
						&& $_SESSION['glopermisos']['escritura'][1] == 1){?>
                <tr>
                    <td align="left" class="datos_sgs">
<table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">
                                                <input type="submit" class="boton" value="Grabar Informaci&oacute;n">
                    <input type="hidden" name="rut_encargado" value="<?php print $rut_encargado; ?>">
                                            </td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">
                                                <input onClick="window.location.href='visCasos.php'" type="button" Value="Volver &raquo;" class="boton">  
                                            </td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table>
					</td>
                  </tr>
                <?php }?>
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