<?php
session_start();
//include('../accesos.php');
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
require_once('../clases/Usuario.class.php');
require_once('../clases/Region.class.php');
require_once('../clases/Perfil.class.php');
require_once('../clases/Util.class.php');

$navegador = Util::detectaNavegador();
$cadena = md5('modusuario_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

$rut	=	filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$valrut = Util::validaRUT($rut);

//valida el RUT
if($valrut == 'SI'){
	$obj = new Usuario(null);
	$resultado = $obj->entregaUsuario($rut);
	//valida que el usuario exista en el sistema

	if(count($resultado)>0){
		foreach( $resultado as $res ){
			$nombre	=	$res['us_nombre'];
			$paterno	=	$res['us_paterno'];
			$materno	=	$res['us_materno'];
			$email	=	$res['us_email'];
			$region_u	=	$res['re_idregion'];
			$comuna_u	=	$res['co_idcomuna'];
			$perfil_u	=	$res['pe_idperfil'];
			$estado	=	$res['us_estado'];
		}
		
		if($region_u==''){
			$region_u = "''";
			$comuna_u = "''";
		}
		if($perfil_u!=1){
		$idregion = $obj->entregaIDRegionUsuarioHasComuna($rut);
		$resul = $obj->entregaUsuarioHasComuna($rut);
		$cobcomuna = $resul;
		}else
		$idregion='';
		
		$perfil = new Perfil(null);
		$perfiles = $perfil->muestraPerfilesActivos();
		$perfil->Close();
		
		$region = new Region(null);
		$regiones = $region->muestraRegiones();
		$region->Close();
	}
	else{
		session_destroy();
		header('location: ../index.php');

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
<script type="text/javascript">
setTimeout ("redireccionar('../index.php')", <?php echo $_SESSION['gloexpiracion'];?>); //tiempo expresado en milisegundos
</script>
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<!--<script src="../js/jquery.Rut.js"></script>-->
<script>
$(document).ready(function(){
	
		/*$.validator.addMethod("rut", function(value, element) { 
        return this.optional(element) || validaRut(value); 
	}, "Debe ingresar un RUT válido.");
	*/
	 
	<?php if($perfil_u!=1){
			
			if($perfil_u==3)
			$num=8;
			else
			$num=1;		
	?>
			/*$(function() {
				$( "#region" ).rules( "add", {
					  required: true,
					  messages: {
						required: "Requerido",
					  }
				});
			});*/	
			
		<?php if($idregion>0){?>
			$.post("comunasMul-ajax.php", { id_region: <?php echo $idregion;?> , rut: '<?php echo $rut;?>'}, function(data){
            $("#cmbcomunas").html(data);
			
			<?php if($perfil_u==3){ ?>
			$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   8 ,				  
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comuna.' ) ,
				  }
				});
			<?php }else{ ?>	
			$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   1 ,				  
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comuna.' ) ,
				  }
				});
			<?php } ?>	
			
            });		
	
		<?php } ?>	
		
	<?php }else{ ?>
			//$('#divregion').hide();
			$('#divcomunas').hide();
            
	<?php }?>
	
		
	/*$("#region").change(function () {
    	$("#region option:selected").each(function () {
        	id_region = $(this).val();
			//alert(id_region);
           $.post("comunasMul-ajax.php", { id_region: id_region }, function(data){
            $("#cmbcomunas").html(data);
			//$("#perfil").val('');
			<!?php if($perfil_u==3){ ?>
			$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   8 ,				  
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comuna.' ) ,
				  }
				});
			<!--?php }else{ ?-->	
			$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   1 ,				  
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comuna.' ) ,
				  }
				});
			<!--?php } ?-->	
			//alert('alert');
            }); 			      
		});
		
	});
	*/
	
	$("#perfil").change(function(){
		if($(this).val() != 1){
			
			//$('#divregion').show();
			$('#divcomunas').show();
			
			/*$( "#region" ).rules( "add", {
			  required: true,
			  messages: {
				required: "Requerido",
			  }
			});*/
			if($(this).val() == 3){
				$.post("comunasMul-ajax.php", { id_region: 0 }, function(data){
					$("#cmbcomunas").html(data);
				});
				$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   8 ,
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comunas.' ) ,
				  }
				});
			}else{
				$.post("comunasMul-ajax.php", { id_region: 0 }, function(data){
					$("#cmbcomunas").html(data);
				});
				$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   1 ,				  
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comuna.' ) ,
				  }
				});
			}// fin if($(this).val() == 2){
			
		}else{//if($(this).val() != 1){
			//$('#divregion').hide();
			$('#divcomunas').hide();
		}
		
		
	});
	
	
					
	$("#form").validate({
			rules: {
				nombre: { required: true},
				paterno: { required: true},
				materno: { required: true},
				email: { required: true},
				perfil: { required: true}
			},
			messages: {
				nombre: "Requerido",
				paterno: "Requerido",
				materno: "Requerido",
				email: "Requerido",
				perfil: "Requerido"
			},
			submitHandler: function(form){
				//document.form.action = 'modUsuario-ajax.php';
				//document.form.submit();
				
				$.ajax({
					type: 'post',
					url: 'modUsuario-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							redireccionar('visUsuario.php');
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

                	<?php /*require_once('../sub_menu_administracion.php');*/?>
<section>
  <div class="contenedor">
    <h2>USUARIOS</h2>
                	
                    <div class="caja">
        				<h3>Edici&oacute;n</h3>
                     
                    </div>  
                    <form name="form" id="form" method="post">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs">
                        <table width="100%">
                        <tr style="border-top:1px solid #C1DAD7;">
                            <td width="7%" colspan="2"><label>(*) Datos obligatorios.</label></td>
                        </tr>
                        </table>
                  
                        <table width="100%">
                        <tr>
                            <td width="25%" class="alt"><label>RUT *</label></td>
                            <td><input type="text" name="rut" size="15" maxlength="10" readonly value="<?php echo $rut;?>" class="alert-active" /></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Nombre *</label></td>
                            <td><input type="text" name="nombre" size="50" maxlength="50" class="required nombre" value="<?php echo $nombre;?>"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Apellido Paterno *</label></td>
                            <td><input type="text" name="paterno" size="50" maxlength="50" class="required paterno" value="<?php echo $paterno;?>"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Apellido Materno *</label></td>
                            <td><input type="text" name="materno" size="50" maxlength="50" class="required materno" value="<?php echo $materno;?>"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Email *</label></td>
                            <td><input type="text" name="email" size="50" maxlength="50" class="required email" value="<?php echo $email;?>"/></td>
                        </tr>
                        
                        <tr>
                            <td class="alt"><label>Estado *</label></td>
                            <td><label>Activo</label>&nbsp;<input type="radio" id="estado" name="estado" value="1" <?php if($estado == 'Activo'){ echo 'checked';}?>/>&nbsp;&nbsp;<label>Inactivo</label>&nbsp;<input type="radio" id="estado" name="estado" value="2" <?php if($estado == 'Inactivo'){ echo 'checked';}?> /> </td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Perfil *</label></td>
                            <td>
                            <select name="perfil" id="perfil">
                            <option value="">Seleccione...</option>
                            <?php 
                            foreach( $perfiles as $per ){
								if($perfil_u == $per['pe_idperfil'])
								$sel = "selected";
								else 
								$sel = "";
                            ?>
                            <option value="<?php echo $per['pe_idperfil'];?>" <?php echo $sel;?>><?php echo $per['pe_descripcion'];?></option>
                            <?php 
                            }
                            ?>
                            </select>
                            </td>
                        </tr>
                        <!--tr id="divregion">
                            <td width="25%" class="alt">Regi&oacute;n</td>
                            <td>
                            <select name="region" id="region">
                            <option value="">Seleccione...</option>
                            <!--?php 
                            foreach( $regiones as $reg ){
								if($idregion == $reg['re_idregion'])
								$sel = "selected";
								else
								$sel = "";
                            ?-->
                            <!--option value="<!--?php echo $reg['re_idregion'];?>" <!--?php echo $sel;?>><!--?php echo $reg['re_descripcion'];?></option>
                            <!--?php 
                            }
                            ?>
                            </select>
                            </td>
                        </tr-->
                        <tr  id="divcomunas">
                            <td width="25%" class="alt"><label>Comuna</label></td>
                            <td>
                            <!--<select name="comuna" id="comuna" multiple class="ms1" >
                            <option value="">Seleccione...</option>
                            </select>
                            -->
                            <div id="cmbcomunas"></div>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                        <td align="left"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onClick="window.location.href='visUsuario.php'" type="button" Value="Volver &raquo;" class="boton"></td>
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

</div>
 </section>   
<br><br>  
<div class="clearfloat"></div>
<div id="footer">
	<?php include('../footer.php');?>
</div>
<!-- footer -->
</div>
</body>
</html>
<?php }else{
	session_destroy();
	header('location: ../index.php');
}
?>