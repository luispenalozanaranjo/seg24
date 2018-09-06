<?php
session_start();
//include('../accesos.php');
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
$cadena = md5('inscaso_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="../images/gob.jpg" rel="icon" type="image/x-icon" />
<title><?php echo $_SESSION['glosistema'];?></title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
setTimeout ("redireccionar('../index.php')", <?php echo $_SESSION['gloexpiracion'];?>); //tiempo expresado en milisegundos
</script>
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script>
$(document).ready(function(){
	
	$("#region").change(function () {
    	$("#region option:selected").each(function () {
        	id_region = $(this).val();
            $.post("../comunas-ajax.php", { id_region: id_region }, function(data){
            $("#comuna").html(data);
            });            
		});
	});
	
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
				perfil: { required: true}
			},
			messages: {
				rut: "Debe ingresar un RUT válido",
				nombre: "Requerido",
				paterno: "Requerido",
				materno: "Requerido",
				clave: "Requerido",
				email: "Requerido",
				perfil: "Requerido"
			},
			submitHandler: function(form){
				
				$.ajax({
					type: 'post',
					url: 'insCaso-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							redireccionar('visCaso.php');
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
<div class="container">
<div class="content">
	<div id="header"><?php echo $_SESSION['glologo'];?></div>
	
    <div id="mainContent">
    		
            <div id="contenido">
            <?php include('../header.php');?>
            <div align="center">
                <?php require_once('../menu.php');?>
                <form name="form" id="form" method="post">
                <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                <table width="100%" align="center">
                <tr>                
                    <td align="left" class="datos_sgs">
                    <table width="100%">
                    <tr>
                        <th class="orange">USUARIOS &raquo; Nuevo</th>
                        <th class="semilla" width="7%"><a href="visUsuario.php">Volver &raquo;</a></th>
                    </tr>    
                    <tr style="border-top:1px solid #C1DAD7;">
                        <td colspan="2">(*) Datos obligatorios.</td>
                    </tr>
                    </table>
              
                    <table width="100%">
                    <tr>
                        <td width="25%" class="alt">RUT *</td>
                        <td><input type="text" name="rut" size="15" maxlength="10" class="required rut"/> (sin puntos ni gui&oacute;n)</td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Nombre *</td>
                        <td><input type="text" name="nombre" size="50" maxlength="50" class="required nombre"/></td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Apellido Paterno *</td>
                        <td><input type="text" name="paterno" size="50" maxlength="50" class="required paterno"/></td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Apellido Materno *</td>
                        <td><input type="text" name="materno" size="50" maxlength="50" class="required materno"/></td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Clave *</td>
                        <td><input type="password" name="clave" size="20" maxlength="20" class="required clave"/></td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Email *</td>
                        <td><input type="text" name="email" size="50" maxlength="50" class="required email"/></td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Regi&oacute;n</td>
                        <td>
                        <select name="region" class="region" id="region">
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
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Comuna</td>
                        <td>
                        <select name="comuna" id="comuna" class="comuna">
                        <option value="">Seleccione...</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" class="alt">Perfil *</td>
                        <td>
                        <select name="perfil" id="perfil">
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
                    <tr>
                        <td class="alt">Estado *</td>
                        <td>Activo&nbsp;<input type="radio" id="estado" name="estado" value="1" checked />&nbsp;&nbsp;Inactivo&nbsp;<input type="radio" id="estado" name="estado" value="2" /> </td>
                    </tr>
                    </table>
                </td>
                </tr>
                <tr>
                    <td align="left" class="datos_sgs"><input type="submit" class="btn btn-warning" value="Grabar Informaci&oacute;n"></td>
                </tr>
                </table>    
                </form>  
                <br><br>
            </div>
        </div>
	</div>      
<br><br>  
<div class="clearfloat"></div>
<div id="footer">
	<?php include('../footer.php');?>
</div>
<!-- footer -->
	
<!-- end .content --></div>
<!-- end .container --></div>
</body>
</html>