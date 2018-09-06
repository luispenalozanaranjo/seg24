<?php
session_start();
$_SESSION = array(); 

if(isset($_COOKIE[session_name()])) { 
	setcookie(session_name(), '', time() - 42000, '/'); 
} 
else{
	//setcookie('contador', $_COOKIE['contador'] + 1, time() + 365 * 24 * 60 * 60); 
}

header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_destroy();

//.$_SERVER['HTTP_HOST'].''
$CSRFKey = "Seg#24H.2k15";
$token = sha1('index_'.$_SERVER['REMOTE_ADDR'].''.$_SERVER['HTTP_USER_AGENT'].''.$CSRFKey.''.date('Ymd'));

//echo 'index_'.$_SERVER['REMOTE_ADDR'].''.$_SERVER['HTTP_USER_AGENT'].''.$_SERVER['HTTP_HOST'].''.$CSRFKey.''.date('Ymd');
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
<script>
$(document).ready(function(){
	$("input").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			$.fn.valUser();
		}
	});
});	

$.fn.valUser = function() {
	
			var d = document.form;
			//alert((d.contador.value==1 && d.code.value==''));
			if(d.rut_user.value=='') {
				alert('Ingrese Login para que pueda Acceder al Sistema');
				d.rut_user.focus();
			}
			else if(!validaRut(d.rut_user.value)) { 
				alert('Ingrese un RUT v'+"\u00e1"+'lido para que pueda Acceder al Sistema');
				d.rut_user.focus();
			}
			else if(d.clave.value=='') { 
				alert('Ingrese su Clave para que pueda Acceder al Sistema');
				d.clave.focus();
			}
			else{
				$.ajax({
					type: 'post',
					url: 'sec/login.php',
					data: $("#form").serialize(),
					dataType: 'json',
				
					success: function(msg){
			
						if (msg.success) {
							redireccionar(msg.salida);
						}
									
						else {
							alert(msg.salida);
							redireccionar("index.php");
						}
					}
				});
			}
}	

$.fn.valMat = function() {
	
			var d = document.form;
			//alert((d.contador.value==1 && d.code.value==''));
		    if(d.contador.value==1 && d.code.value==''){
				alert("Debe ingresar el RESULTADO del problema matematico");
				d.code.focus();
			}
			else{
				$.ajax({
					type: 'post',
					url: 'sec/login.php',
					data: $("#form").serialize(),
					dataType: 'json',
				
					success: function(msg){
			
						if (msg.success) {
							redireccionar(msg.salida);
						}
									
						else {
							alert(msg.salida);
							redireccionar("index.php");
						}
					}
				});
			}
}	
</script>
</head>
<body onload="document.form.rut.focus();">
<section>
       <div id="barra-login">
          <div id="login-wrapper">
             <div id="contenedor-logo"></div>
             <div id="contenedor-login">
                   <p id="titulo"><strong>Acceso SEG24Horas</strong></p>
                   <p id="subtitulo"><strong>Sistema de Evaluaci&oacute;n y Gesti&oacute;n</strong></p>
            
            <form id="form" name="form" method="post">

            <table width="100%" align="center">
       
            <tr>
              <td colspan="4" align="left"><h2><strong>Acceso Usuarios</strong></h2>
            <input type="hidden" name="auth_token" value="<?php echo $token;?>" /></td>
              </tr>
            <tr>
                <td width="42%" align="left" class="contenedor-campo left"><label>Login:</label>                  
                <div class="contenedor-campo left"><input type="text" name="rut" id="rut_user" size="15" maxlength="10"/></div></td>
                <td width="6%" align="left" class="contenedor-campo left">&nbsp;</td>
                <td colspan="2" align="left" class="contenedor-campo left"><label>Clave:</label>
                <div class="contenedor-campo left">
                <input type="password" name="clave" id="clave" size="15" maxlength="20"/></div></td>
            </tr>    
            <tr>
                <td><a href="recordar.php" style="text-decoration:none"><label style="cursor:pointer">&iquest;Olvid&oacute; su clave?</label></a></td>
                <td>&nbsp;</td>
                <td width="42%"><input type="button" class="boton" value="Ingresar" onClick="$.fn.valUser()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="10%">&nbsp;</td>    
            </tr>
            <tr>
            	<td colspan="4"></td>
            </tr>
            <tr>
              <td colspan="4" align="center">            
           </td>
            </tr>    	
            </table>

            </form>
            </div>
                <p><br>
                </p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>

                
                <!-- menu -->
         
  		</div>
        <div class="footer">
                    <table width="100%">
                        <tr>
                            <td width="5%">&nbsp;</td>
                            <td width="90%">
                                ACCESO RESTRINGIDO: Toda cuenta de usuario para acceder a &eacute;ste sistema de informaci&oacute;n es de car&aacute;cter confidencial, personal e intransferible, por lo tanto, las acciones realizadas en este equipo ser&aacute;n registradas para efectos de revisi&oacute;n y auditor&iacute;a, siendo &eacute;stas de exclusiva responsabilidad del usuario propietario.
                            </td>
                            <td width="5%">&nbsp;</td>
                        </tr>
                    </table>
                </div>
        </div>
	
 </section>


</body>
</html>
