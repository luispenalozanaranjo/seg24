<?php
session_start();
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
require_once('../clases/Perfil.class.php');	
$perfil = new Perfil(null);
$listaPerfil = $perfil->muestraPerfiles();
//echo "perfiles:".count($listaPerfil);
$perfil->Close();	
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
<!--<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
<script src="../js/jquery-1.8.0.min.js"></script>
<script src="../js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script src="../js/jquery-ui.js"></script>

<!--<script src="../js/jquery.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){
	load(1);
	  
});

function load(page){
    $.ajax({
		url:'visUsuario-ajax.php?action=ajax&page='+page,
		success:function(data){
			$("#resultado").html(data).fadeIn('slow');
		}
	})
}

function limpiar(){
	$.ajax({
		url:'visUsuario-limpia.php',
		success:function(data){
			redireccionar('visUsuario.php');
		}
	})
}

function cargar(){
	$.ajax({
		type: 'post',
		url: 'visUsuario-carga.php',
		data: $("#form").serialize(),
		dataType: 'json',
		success: function(msg){
	
			if (msg.success) {
				redireccionar('visUsuario.php');
			}
						
			else {
				alert('Ocurrió un error al filtrar la información');
			}
		}
	});
}
</script>
</head>
<body>
    		
<div id="content-wrapper">
            <?php include('../header.php');?>
                <?php require_once('../menu.php');?>
               
                	<?php require_once('../sub_menu_administracion.php');?>
                <section>
                               <div class="contenedor">
                <div class="caja caja-sin-borde caja-sin-margen">
                    <form name="form" id="form" method="post">
                    <table width="100%" class="alert-error">
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="expUsuario.php" target="_blank"><img src="../images/excel.png" align='middle'></a>&nbsp;<a href="insUsuario.php"><img src="../images/add.png" align='middle'></a></td>

                    </tr>
                    <tr>
                        <td width="5%" align="left"><label>Nombre</label></td>
                        <td width="20%" align="left"><input type="text" id="nombre" name="nombre" maxlength="50" size="30" <?php if(isset($_SESSION['filtroUS_nombre'])!='')echo "value='".$_SESSION['filtroUS_nombre']."'";?>></td>
                        <td width="5%" align="left"><label>Estado</label></td>
                        <td width="10%" align="left">
                        <select name="estado" id="estado">
                        <option value="">Seleccione...</option>
                        <option value="Activo" <?php if(isset($_SESSION['filtroUS_estado'])!='' && $_SESSION['filtroUS_estado']=='Activo')echo "selected";?>>Activo</option>
                        <option value="Inactivo" <?php if(isset($_SESSION['filtroUS_estado'])!='' && $_SESSION['filtroUS_estado']=='Inactivo')echo "selected";?>>Inactivo</option>
                        </select>
                        </td>
                        <td width="5%" align="left"><label>Perfil</label></td>
                        <td width="10%" align="left">
                        <select name="perfil" id="perfil">
                        <option value="">Seleccione...</option>
                        <?php if(count($listaPerfil)>0){
								foreach ($listaPerfil as $res){
						?>			
								<option value="<?php echo $res['pe_idperfil'];?>" <?php if(isset($_SESSION['filtroUS_perfil'])!='' && $_SESSION['filtroUS_perfil']==$res['pe_idperfil'])echo "selected";?>><?php echo $res['pe_descripcion'];?></option>
						<?php	}
								
						}							
						?>
                        
  
                        </select>
                        </td>
                    </tr>
                    <tr>
                      <td colspan="6"><table width="100%">
                                    	<tbody><tr>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="button" class="boton" value="Buscar" onClick="cargar()"> </td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%"><input type="button" class="boton" value="Limpiar" onClick="limpiar()"></td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                            <td width="15%">&nbsp;</td>
                                        </tr>
                                    </tbody></table></td>
                      </tr>
                    </table>
                    </form>
                    <!-- resultado ajax -->
                    <div id="resultado"></div>
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
	
<!-- end .content -->
<!-- end .container -->
</div>
</body>
</html>
<?php }else{
	session_destroy();
	header('location: ../index.php');
}
?>