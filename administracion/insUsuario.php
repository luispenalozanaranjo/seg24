<?php
session_start();
//include('../accesos.php');
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
require_once('../clases/Region.class.php');
require_once('../clases/Perfil.class.php');
require_once('../clases/Util.class.php');

$perfil = new Perfil(null);
$perfiles = $perfil->muestraPerfilesActivos();
$perfil->Close();

$region = new Region(null);
$regiones = $region->muestraRegiones();
$region->Close();

$navegador = Util::detectaNavegador();
$cadena = md5('insusuario_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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
<!--<script src="../js/jquery-ui.js"></script>-->
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script>
$(document).ready(function(){
	//$('#divregion').hide();
	$('#divcomunas').hide();
	
	 $.post("comunasMul-ajax.php", { id_region: 0 }, function(data){
            $("#cmbcomunas").html(data);
            });
			
	/*
	$("#region").change(function () {
    	$("#region option:selected").each(function () {
        	id_region = $(this).val();
            $.post("comunasMul-ajax.php", { id_region: id_region }, function(data){
            $("#cmbcomunas").html(data);
            });            
		});
	});
	*/
	
	
	$("#perfil").change(function(){
		if($(this).val() != 1){
			
			//$('#divregion').show();
			$('#divcomunas').show();
			
			$( "#region" ).rules( "add", {
			  required: true,
			  messages: {
				required: "Requerido",
			  }
			});
			
			if($(this).val() == 3){
				$( "#comuna" ).rules( "add", {
				  required: true,
				  minlength :   1 ,
				  maxlength :   7 ,
				  messages: {
					required: "Por favor, seleccione al menos 1 comuna.",
					minlength :   $ . format ( 'Por favor, seleccione al menos {0} comuna.' ) ,
					maxlength :   $ . format ( 'Por favor, seleccione como maximo {0} comunas.' ) ,
				  }
				});
			}else{
				
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
	/*
	$("#region").change(function(){
		$( "#comuna" ).rules( "add", {
			  required: true,
			  minlength :   2 ,
			  messages: {
				required: "Requerido",
			  }
			});
		
	});
	*/
	
	$.validator.addMethod("rut", function(value, element) { 
        return this.optional(element) || validaRut(value); 
	}, "Debe ingresar un RUT válido.");
	
	$("#form").validate({
			rules: {
				rut: { required: true},
				nombre: { required: true},
				paterno: { required: true},
				materno: { required: true},
				clave: { required: true},
				email: { required: true},
				perfil: { required: true}/*,
				region: { required: true},
				comuna: { required: true}*/
				
			},
			messages: {
				rut: "Debe ingresar un RUT válido",
				nombre: "Requerido",
				paterno: "Requerido",
				materno: "Requerido",
				clave: "Requerido",
				email: "Requerido",
				perfil: "Requerido"/*,
				region: "Requerido",
				comuna: "Requerido"*/
				
			},
			submitHandler: function(form){
				//document.form.action = 'insUsuario-ajax.php';
				//document.form.submit();
				$.ajax({
					type: 'post',
					url: 'insUsuario-ajax.php',
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

<link rel="stylesheet" href="../js/multiple-select.css" />
<script src="../js/jquery.multiple.select.js"></script>
<script>
    $(function() {
        $('.ms1').multipleSelect({
            width: '100%'
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
        				<h3>NUEVO</h3>
                     
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
                            <td><input type="text" name="rut" size="15" maxlength="10" class="required rut" style="
    width: 146px;
"/> <label>(sin puntos ni gui&oacute;n)</label></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Nombre *</label></td>
                            <td><input type="text" name="nombre" size="50" maxlength="50" class="required nombre" style="
    width: 391px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Apellido Paterno *</label></td>
                            <td><input type="text" name="paterno" size="50" maxlength="50" class="required paterno" style="
    width: 391px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Apellido Materno *</label></td>
                            <td><input type="text" name="materno" size="50" maxlength="50" class="required materno" style="
    width: 391px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Clave *</label></td>
                            <td><input type="password" name="clave" size="20" maxlength="20" class="required clave" style="
    width: 181px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Email *</label></td>
                            <td><input type="text" name="email" size="50" maxlength="50" class="required email" style="
    width: 391px;
"/></td>
                        </tr>
                         <tr>
                            <td class="alt"><label>Estado *</label></td>
                            <td><label>Activo</label>&nbsp;<input type="radio" id="estado" name="estado" value="1" checked />&nbsp;&nbsp;<label>Inactivo</label>&nbsp;<input type="radio" id="estado" name="estado" value="2" /> </td>
                        </tr>
                          <tr>
                            <td width="25%" class="alt"><label>Perfil *</label></td>
                            <td>
                            <select name="perfil" id="perfil" style="
    width: 130px;
">
                            <option value="">Seleccione...</option>
                            <?php 
                            foreach( $perfiles as $per ){
                            ?>
                            <option value="<?php echo $per['pe_idperfil'];?>"><?php echo $per['pe_descripcion'];?></option>
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
                            ?-->
                            <!--option value="<!--?php echo $reg['re_idregion'];?>"><!--?php echo $reg['re_descripcion'];?></option>
                            <!--?php 
                            }
                            ?>
                            </select>
                            </td>
                        </tr-->
                        <tr id="divcomunas">
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
                                            <td width="15%">
                                             <input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="button" class="boton" value="Volver" id="btn-volver" name="btn-vovler" onclick="history.back()"></td>
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