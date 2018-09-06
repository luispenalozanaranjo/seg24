<?php
session_start();
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		
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
<script type="text/javascript">
$(document).ready(function(){
	load(1);
});

function load(page){
    $.ajax({
		url:'visPerfil-ajax.php?action=ajax&page='+page,
		success:function(data){
			$("#resultado").html(data).fadeIn('slow');
		}
	})
}
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
                    <table width="100%" class="alert-error">
                    <tr>
                        
                        <td align="right"><a href="expPerfil.php" target="_blank"><img src="../images/excel.png" align='middle'></a>&nbsp;<a href="insPerfil.php"><img src="../images/add.png" align='middle'></a></td>
                    </tr>
                    </table>
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

<!-- end .container --></div>
</body>
</html>
<?php }else{
	session_destroy();
	header('location: ../index.php');
}
?>