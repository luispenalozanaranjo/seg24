<?php
session_start();
require_once('../clases/Util.class.php');

$navegador = Util::detectaNavegador();
$cadena = md5('repclave_'.$navegador['navegador'].''.$navegador['version'].''.date("YmdHi"));
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="images/gob.jpg" rel="icon" type="image/x-icon" />
<title>SEG24Horas</title>
<link rel="stylesheet" type="text/css" href="../css/login.css" />
<link rel="stylesheet" type="text/css" href="../css/global.css" />
<script src="../js/script.js"></script>
<script src="../js/jquery.min.js"></script>
<script>
$(document).ready(function(){
function valUser()
{
	$.ajax({
		type: 'post',
		url: 'modifica_clave.php',
		data: $("#form").serialize(),
		dataType: 'json',
	
		success: function(msg){

			if (msg.success) {
				document.form.reset();
				alert('Su nueva clave ha sido modificada exitosamente');
				location.reload(true);
			}
						
			else {
				alert(msg.salida);
			}
		}
	});
}
$('.boton').click(function() { 
			var d = document.form;
			var RegExPattern = /(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d){8,10}.+$)/;/*/(?!^[0-9]*$)(?!^[a-z]*$)(?!^[A-Z]*$)^([a-zA-Z0-9]{8,10})$/;*/
			
			//validación de email
			if(d.actual.value=='') {
				alert('Debe ingresar su clave actual');
				d.actual.focus();
			}
			else if(d.clave1.value==''){
				alert("Debe ingresar su nueva clave");
				d.clave1.focus();
			}
			else if(d.clave2.value==''){
				alert("Debe reingresar su nueva clave");
				d.clave2.focus();
			}
			else if ((!d.clave1.value.match(RegExPattern))) {
        		alert('Password incorrecta'); 
				d.clave1.focus();
			}
			else if(d.clave1.value!=d.clave2.value){
				alert("Las claves no coinciden");
				d.clave2.focus();
			}
			else{
				valUser(); 
			}
        }); 
});
</script>
</head>
<body>

<section>
            <div id="barra-login">
                <div id="login-wrapper">
                    <div id="contenedor-logo"></div>
	
    <div id="contenedor-login">
		<p id="subtitulo">Recuperaci&oacute;n de clave</p>
        <div align="center">
        
        	<div id="menu" style="width:480px; background-color: #EDF1F5;">
		<?php 
		if( $_SESSION['gloacceso'] == 'Si' ){
		?>
		<h2>Cambio de clave</h2>
        <div align="center">
        
        	<div id="menu" style="width:480px; background-color: #EDF1F5;">
            <br>
            <form id="form" name="form" method="post">
            <input type="hidden" name="auth_token" value="<?php echo Util::generaToken($cadena);?>" />
            <table width="100%" align="center">
            <tr>
                <td width="40%" colspan="2" align="right"><label>Ingrese su clave actual:</label></td>
                <td colspan="2" align="left">&nbsp;&nbsp;<input type="password" name="actual" id="actual" size="20" maxlength="20"/></td>
            </tr>    
            <tr>
            	<td colspan="2" align="right"><label>Ingrese su nueva clave:</label></td>
            	<td colspan="2" align="left">&nbsp;&nbsp;<input type="password" name="clave1" id="clave1" size="20" maxlength="20" /></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><label>Reingrese su nueva clave:</label></td>
            	<td colspan="2" align="left">&nbsp;&nbsp;<input type="password" name="clave2" id="clave2" size="20" maxlength="20" /></td>
            </tr>
            <tr>
                <td align="center">&nbsp;</td>
                <td colspan="2" align="center"><input type="button" class="boton" value="Cambiar clave"></td>
                <td align="center">&nbsp;</td>
            </tr>   	
            </table>
            </form>
            </div>
  			<!-- menu -->
  		</div>
        <?php 
		} elseif( $_SESSION['gloidperfil']>0 ) {
			if (in_array(7, $_SESSION['glopermisos']['modulo']))
			header('Location: ../administracion/visUsuario.php');
			else
			header('Location: ../casos/visCasos.php');
		} else {
			header('Location: ../index.php');
		}
		?>
            </div>
            <br><br>

  			<!-- menu -->
  		</div>
	</div>   
                       <div class="footer">
                    <table width="200%">
                        <tr>
                            <td><img src="../images/warning.png" align="absmiddle">Su nueva clave debe estar compuesta por una palabra entre 8 y 10 caracteres, por lo menos una letra en may&uacute;scula, una letra en min&uacute;scula y un n&uacute;mero.
                            </td></td>
                        </tr>
                    </table>
                </div>   
<br><br>  
<div class="clearfloat"></div>
<!-- footer -->
	
  		</div>
         
        </div>

        </div>
	
 </section>
</body>
</html>