<?php
session_start();
//include('../accesos.php');
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
		
require_once('../clases/Configuracion.class.php');
require_once('../clases/Usuario.class.php');
require_once('../clases/Util.class.php');

$usuario = new Usuario(null);
$usuarios = $usuario->muestraUsuariosGestores();
$usuario->Close();

$obj = new Configuracion(null);
$resultado = $obj->muestraConfiguracion();
if(count($resultado)>0){
		foreach( $resultado as $res ){
			if($res['idconfiguracion'] == 1)
			$cierre_DE = $res['valor'];
			if($res['idconfiguracion'] == 2)
			$verde_DE = $res['valor'];
			if($res['idconfiguracion'] == 3)
			$amarillo_DE = $res['valor'];
			if($res['idconfiguracion'] == 4)
			$rojo_DE = $res['valor'];
			if($res['idconfiguracion'] == 5)
			$cierre_MST = $res['valor'];
			if($res['idconfiguracion'] == 6)
			$verde_MST = $res['valor'];
			if($res['idconfiguracion'] == 7)
			$amarillo_MST = $res['valor'];
			if($res['idconfiguracion'] == 8)
			$rojo_MST = $res['valor'];
			if($res['idconfiguracion'] == 9)
			$puntaje = $res['valor'];
			if($res['idconfiguracion'] == 10)
			$minimo = $res['valor'];
			if($res['idconfiguracion'] == 11)
			$maximo = $res['valor'];
			if($res['idconfiguracion'] == 12)
			$gestor = $res['valor'];
		}
}
else{
	session_destroy();
	header('location: ../index.php');
}

$navegador = Util::detectaNavegador();
$cadena = md5('insconfiguracion_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
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
				cierre_de: { required: true},
				cierre_mst: { required: true},
				verde_de: { required: true},
				verde_mst: { required: true},
				amarillo_de: { required: true},
				amarillo_mst: { required: true},
				rojo_de: { required: true},
				rojo_mst: { required: true},
				minimo: { required: true},
				maximo: { required: true},
				gestor: { required: true},
				puntaje: { required: true}
			},
			messages: {
				cierre_de: "Requerido",
				cierre_mst: "Requerido",
				verde_de: "Requerido",
				verde_mst: "Requerido",
				amarillo_de: "Requerido",
				amarillo_mst: "Requerido",
				rojo_de: "Requerido",
				rojo_mst: "Requerido",
				minimo: "Requerido",
				maximo: "Requerido",
				gestor: "Requerido",
				puntaje: "Requerido"
			},
			submitHandler: function(form){
				
				$.ajax({
					type: 'post',
					url: 'visConfiguracion-ajax.php',
					data: $("#form").serialize(),
					dataType: 'json',
					success: function(msg){
						if (msg.success) {
							alert('Registro ingresado exitosamente');
							redireccionar('visConfiguracion.php');
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
<div class="caja caja-sin-borde caja-sin-margen">
              
                    <form name="form" id="form" method="post">
                    <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
                    <table width="100%" align="center">
                    <tr>                
                        <td align="left" class="datos_sgs">
                        <table width="100%"> 
                        <tr style="border-top:1px solid #C1DAD7;">
                            <td colspan="2"><label>(*) Datos obligatorios.</label></td>
                        </tr>
                        </table>
                  <div id="contenedor-grilla-base">
                        <table width="100%" id="grilla-base">
                        <tr class="cabecera">
                        	<th colspan="2" align="left" style="
    width: 532px;
">Derivaci&oacute;n Externa</th>
                            <th colspan="2"  align="left" style="
    width: 532px;
">Derivaci&oacute;n MST</th>
                        </tr>    
                        <tr>
                            <td width="25%" class="alt"><label>Solicitud Cierre *</label></td>
                            <td width="25%"><input type="text" name="cierre_de" size="1" maxlength="3" class="required cierre_de" value="<?php echo $cierre_DE;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                            <td width="25%" class="alt"><label>Solicitud Cierre *</label></td>
                            <td width="25%"><input type="text" name="cierre_mst" size="1" maxlength="3" class="required cierre_mst" value="<?php echo $cierre_MST;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Alerta Verde *</label></td>
                            <td><input type="text" name="verde_de" size="1" maxlength="3" class="required verde_de" value="<?php echo $verde_DE;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                            <td width="25%" class="alt"><label>Alerta Verde *</label></td>
                            <td><input type="text" name="verde_mst" size="1" maxlength="3" class="required verde_mst" value="<?php echo $verde_MST;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Alerta Amarilla *</label></td>
                            <td><input type="text" name="amarilla_de" size="1" maxlength="3" class="required amarilla_de" value="<?php echo $amarillo_DE;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                            <td width="25%" class="alt"><label>Alerta Amarilla *</label></td>
                            <td><input type="text" name="amarilla_mst" size="1" maxlength="3" class="required amarilla_mst" value="<?php echo $amarillo_MST;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Alerta Roja *</label></td>
                            <td><input type="text" name="roja_de" size="1" maxlength="3" class="required roja_de" value="<?php echo $rojo_DE;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                            <td width="25%" class="alt"><label>Alerta Roja *</label></td>
                            <td><input type="text" name="roja_mst" size="1" maxlength="3" class="required roja_mst" value="<?php echo $rojo_MST;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/> <label>d&iacute;as</label></td>
                        </tr>
                        </table>
                        </div>
                        <br>
                        <div id="contenedor-grilla-base">
                        <table width="100%" id="grilla-base">
                        <tr class="contenido">
                            <td width="25%" style="
    width: 290px;
"><label>N&deg; m&iacute;nimo visitas  *</label></td>
                            <td width="25%" style="
    width: 240px;
"><input type="text" name="minimo" size="1" maxlength="3" class="required minimo" value="<?php echo $minimo;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/></td>
                            <td width="25%" style="
    width: 290px;
"><label>N&deg; m&aacute;ximo visitas *</label></td>
                            <td width="25%"  style="
    width: 48px;
"><input type="text" name="maximo" size="1" maxlength="3" class="required maximo" value="<?php echo $maximo;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/></td>
                        </tr>
                        <tr>
                            <td width="25%" class="alt"><label>Gestor Territorial (Nivel Central) *</label></td>
                            <td>
                            <select name="gestor" id="gestor" style="
    width: 213px;
">
                            <option value="">Seleccione...</option>
                            <?php 
                            foreach( $usuarios as $usu ){
								if($gestor == $usu['us_rut'])
								$sel = "selected";
								else
								$sel = "";
                            ?>
                            <option value="<?php echo $usu['us_rut'];?>" <?php echo $sel;?>><?php echo $usu['us_nombre'].' '.$usu['us_paterno'].' '.$usu['us_materno'];?></option>
                            <?php 
                            }
                            ?>
                            </select>
                            </td>
                            <td width="25%" class="alt"><label>Puntaje derivaci&oacute;n *</label></td>
                            <td><input type="text" name="puntaje" size="1" maxlength="3" class="required puntaje" value="<?php echo $puntaje;?>" onKeyPress="return soloNumeros(event)" style="
    width: 48px;
"/></td>
                        </tr>
                        </table></div>
                    </td>
                    </tr>
                    <tr>
                        <td align="left"><table width="100%">
                                    	<tbody><tr>
                                          <td width="2%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="2%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                          <td width="15%"><input type="submit" class="boton" value="Grabar Informaci&oacute;n"></td>
                                          <td width="2%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="2%">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                          <td width="2%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                      </tr>
                    </table>    
                    </form>  
                    <br><br>
                </div>
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