<?php
session_start();
//include('../accesos.php');
require_once('../clases/Modulo.class.php');
require_once('../clases/Perfil.class.php');
require_once('../clases/Util.class.php');

$id	=	filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$obj = new Perfil(null);
$resultado = $obj->entregaPerfil($id);
if(count($resultado)>0){
	$permisos = $obj->muestraPermisoPerfil($id);
	foreach( $resultado as $res ){
		$nombre	=	$res['pe_descripcion'];
		$estado	=	$res['pe_estado'];
	}
	$obj->Close();
}
else{
	session_destroy();
	header('location: ../index.php');

}

$modulo = new Modulo(null);
$modulos = $modulo->muestraModulosActivos();
$modulo->Close();

$navegador = Util::detectaNavegador();
$cadena = md5('modperfil_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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
					url: 'modPerfil-ajax.php',
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
                	<?php /*require_once('../sub_menu_administracion.php');*/?>
 <section>
   <div class="contenedor">
 <div class="menuExtra">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="window.location.href='visPerfil.php'" type="submit" Value="Volver &raquo;" class="boton_volver"></div>
                    <h2>PERFILES</h2>
                	
                    <div class="caja">
        				<h3>Edici&oacute;n</h3>
                     
                    </div> 
                    <form name="form" id="form" method="post">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <table width="100%" align="center">
                    <tr>                
                        <td colspan="2" align="left" class="datos_sgs">
                        <table width="100%">
                        <tr style="border-top:1px solid #C1DAD7;">
                            <td width="7%" colspan="2"><label>(*) Datos obligatorios.</label></td>
                        </tr>
                        </table>
                  
                        <table width="100%">
                        <tr>
                            <td width="25%" class="alt"><label>ID</label></td>
                            <td><input type="text" name="id" size="1" readonly value="<?php echo $id;?>" class="alert-active" style="
    width: 48px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Nombre *</label></td>
                            <td><input type="text" name="nombre" size="50" maxlength="50" class="required nombre" value="<?php echo $nombre;?>" style="
    width: 391px;
"/></td>
                        </tr>
                        <tr>
                            <td class="alt"><label>Estado *</label></td>
                            <td><label>Activo</label>&nbsp;<input type="radio" id="estado" name="estado" value="1" <?php if($estado == 'Activo') echo "checked";?> />&nbsp;&nbsp;<label>Inactivo</label>&nbsp;<input type="radio" id="estado" name="estado" value="2" <?php if($estado == 'Inactivo') echo "checked";?> /> </td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Permisos *</label></td>
                            <td>
                            <div id="contenedor-grilla-base">
                            	<table width="100%" id="grilla-base">
                                <tr class="cabecera">
                                	<th width="143px">M&oacute;dulo</th>
                                    <th width="68px">Lectura</th>
                                    <th width="68px">Escritura</th>
                                    <th width="68px">Aprobaci&oacute;n</th>
                                </tr>
                            	<?php 
								foreach( $modulos as $mod ){
									foreach($permisos as $per){
										if($mod['mo_idmodulo'] == $per['mo_idmodulo']){
											if($per['escritura']==1)
											$chk_escritura = "checked";
											else
											$chk_escritura = "";
											
											if($per['lectura']==1)
											$chk_lectura = "checked";
											else
											$chk_lectura = "";
											
											if($per['aprobacion']==1)
											$chk_aprobacion = "checked";
											else
											$chk_aprobacion = "";											
										}
									}
								?>
                                <tr>
                                	<td width="20%" align="left"><label><?php echo $mod['mo_descripcion']; ?></label></td>
                                    <td width="10%" align="left"><input type="checkbox" name="lectura_<?php echo $mod['mo_idmodulo'];?>" <?php echo $chk_lectura;?>></td>
                                    <td width="10%" align="left"><input type="checkbox" name="escritura_<?php echo $mod['mo_idmodulo'];?>" <?php echo $chk_escritura;?>></td>
                                    <?php if($mod['mo_idmodulo']>2){?>
                                    <td align="left"><input type="checkbox" name="aprobacion_<?php echo $mod['mo_idmodulo'];?>" <?php echo $chk_aprobacion;?>></td>
                                    <?php }else{?>
                                    <td align="left">&nbsp;</td>
                                    <?php }?>
                                </tr>                            
								<?php 
                                }
                                ?>
                            	</table></div>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                        <td width="55%" align="left" class="datos_sgs"><input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
                        <td width="45%" align="left" class="datos_sgs">&nbsp;</td>
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