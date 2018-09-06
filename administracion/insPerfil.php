<?php
session_start();
//include('../accesos.php');
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
		
require_once('../clases/Modulo.class.php');
require_once('../clases/Util.class.php');

$modulo = new Modulo(null);
$modulos = $modulo->muestraModulosActivos();
$modulo->Close();

$navegador = Util::detectaNavegador();
$cadena = md5('insperfil_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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
<script>
$(document).ready(function(){
	
	$("#form").validate({
			rules: {
				nombre: { required: true}
			},
			messages: {
				nombre: "Requerido"
			},
			submitHandler: function(form){
				
				$.ajax({
					type: 'post',
					url: 'insPerfil-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							redireccionar('visPerfil.php');
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
                	<?php require_once('../sub_menu_administracion.php'); ?>
 <section>
  <div class="contenedor">
    <h2>PERFILES</h2>
                	
                    <div class="caja">
        				<h3>Nuevo</h3>
                     
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
                            <td width="25%" class="alt"><label>Nombre *</label></td>
                            <td><input type="text" name="nombre" size="50" maxlength="50" class="required nombre" style="
    width: 391px;
"/></td>
                        </tr>
                        <tr>
                            <td class="alt"><label>Estado *</label></td>
                            <td><label>Activo</label>&nbsp;<input type="radio" id="estado" name="estado" value="1" checked />&nbsp;&nbsp;<label>Inactivo</label>&nbsp;<input type="radio" id="estado" name="estado" value="2" /> </td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Permisos *</label></td>
                            <td>
                            <div id="contenedor-grilla-base">
                            	<table width="100%" id="grilla-base" style="
    width: 1080px;
">
                                <tr class="cabecera">
                                	<th width="121">M&oacute;dulo</th>
                                    <th width="211">Lectura</th>
                                    <th width="109">Escritura</th>
                                    <th width="80">Aprobaci&oacute;n</th>
                                </tr>
                            	<?php 
								foreach( $modulos as $mod ){
								?>
                                <tr>
                                	<td width="121" align="left"><label><?php echo $mod['mo_descripcion']; ?></label></td>
                                    <td width="211" align="left"><input type="checkbox" name="lectura_<?php echo $mod['mo_idmodulo'];?>"></td>
                                    <td width="109" align="left"><input type="checkbox" name="escritura_<?php echo $mod['mo_idmodulo'];?>"></td>
                                    <?php if($mod['mo_idmodulo']>2){?>
                                    <td align="left"><input type="checkbox" name="aprobacion_<?php echo $mod['mo_idmodulo'];?>"></td>
                                    <?php }else{?>
                                    <td width="595" align="left">&nbsp;</td>
                                    <?php }?>
                                </tr>                            
								<?php 
                                }
                                ?>
                            	</table>
                              </div>
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
                                            <td width="15%"><span class="datos_sgs" style="background-color:#FFF;border-bottom:0"><input type="submit" class="boton" value="Grabar Informaci&oacute;n"></span></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input onclick="window.location.href='visUsuario.php'" type="button" value="Volver" class="boton" /></td>
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
</div>
</body>
</html>
<?php }else{
	session_destroy();
	header('location: ../index.php');
}
?>