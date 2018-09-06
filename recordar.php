<?php
session_start();
$_SESSION = array(); 

if(isset($_COOKIE[session_name()])) { 
	setcookie(session_name(), '', time() - 42000, '/'); 
} 

setcookie('contador', $_COOKIE['contador'] + 1, time() + 365 * 24 * 60 * 60); 

header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_destroy();

$CSRFKey = "Seg#24H.2k15";
$token = sha1('recupera_'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_HOST'].$CSRFKey.date('Ymd'));
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="images/gob.jpg" rel="icon" type="image/x-icon" />
<title>SEG24Horas</title>
		<link rel="stylesheet" type="text/css" href="css/login.css" />
        <link rel="stylesheet" type="text/css" href="css/global.css" />
<script src="js/script.js"></script>
<script src="js/jquery.min.js"></script>
<!--<script src="js/jquery.blockUI.js"></script>-->
<script>
$(document).ready(function(){
//$(document).ajaxStop($.unblockUI); 
function valUser(rut,codigo)
{
	$.ajax({
		type: 'post',
		url: 'sec/envia_clave.php',
		data: $("#form").serialize(),
		//data: 'rut='+rut+'&code='+codigo,
		dataType: 'json',
	
		success: function(msg){

			if (msg.success) {
				document.form.reset();
				alert('Su nueva clave ha sido enviada a su correo electr'+'\u00f3'+'nico');
				redireccionar("index.php");
			}
						
			else {
				alert(msg.salida);
				redireccionar("recordar.php");
			}
		}
	});
}
$('.boton').click(function() { 
			var d = document.form;
			//validación de email
			var filtro=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
			if(d.rut.value=='') {
				alert('Ingrese su RUT');
				d.rut.focus();
			}
			else if(!validaRut(d.rut.value)) { 
				alert('Ingrese un RUT v'+"\u00e1"+'lido');
				d.rut.focus();
			}
			else if(d.code.value==''){
				alert("Debe ingresar el RESULTADO del problema matematico");
				d.code.focus();
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
           
            <form id="form" name="form" method="post">
            <input type="hidden" name="auth_token" value="<?php echo $token;?>" />
            <table width="100%" align="center">
            <tr>
                <td width="35%" align="right"><label>Ingrese su RUT:<br>(con d&iacute;gito verificador)</label></td>
                <td align="left" class="contenedor-campo left">&nbsp;&nbsp;<input type="text" name="rut" id="rut_user" size="10" maxlength="10"/></td>
            </tr>    
            <tr>
            	<td width="35%" align="right"><label>Resuelva el problema matem&aacute;tico:</label></td>
            	<td align="left" valign="bottom">&nbsp;&nbsp;
                <img src="securimage/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>" id="image" align="absmiddle" /><br>
                &nbsp;&nbsp;&nbsp;<a href="#" onclick="document.getElementById('image').src = 'securimage/securimage_show.php?sid=' + Math.random(); return false">Refrescar Imagen</a>
                <br><br>&nbsp;&nbsp;<input type="text" class="medio" name="code" id="code" maxlength="6" />
                </td>
            </tr>
            <tr>
                <td align="center" class="datos_sgs" colspan="2"><input type="button" class="boton" value="Recuperar"></td>
            </tr>   	
            </table>
            <br>
            </form>
            </div>
            <br><br><br>
  			<!-- menu -->
  		</div>
	</div>        
<br><br>  
<div class="clearfloat"></div>
<!-- footer -->
	
  		</div>
        </div>
	
 </section>
</body>
</html>
