<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link href="../images/gob.jpg" rel="icon" type="image/x-icon" />
<title><?php echo $_SESSION['glosistema'];?></title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.min.js"></script>
</head>
<body>

<div class="container">
<div class="content">
	<div id="header"><?php echo $_SESSION['glologo'];?></div>
	
    <div id="mainContent">
    		
            <div id="contenido">
            <?php include('header.php');?>
            
            <div align="center">
                <?php require_once('menu.php');?>
                <table width="100%" style="font-weight:bold;">
                <tr>
                	<td align="left" class="tabla_submenu">
                    <?php require_once('sub_menu_administracion.php');?>
                    </td>
                    <td align="right" valign="top" width="30%"><a href="expActivo.php" target="_blank"><img src="../images/excel.png"></a>
                    &nbsp;<a href="insActivo.php"><img src="../images/add.png"></a>
                    </td>
                </tr>
                </table>    
                <form name="form" id="form" method="post">
                <table width="100%" class="alert-error">
                <tr>
                	<td width="10%" align="left">N&deg; Inventario</td>
                    <td width="15%" align="left"><input type="text" id="numero" name="numero" onKeyPress="return soloNumeros(event)" maxlength="10" size="10" <?php if(isset($_SESSION['filtroAC_numero'])!='')echo "value='".$_SESSION['filtroAC_numero']."'";?>></td>
                    <td width="8%" align="left">Serie</td>
                    <td width="20%" align="left">
                    <input type="text" size="18" maxlength="50" name="serie" id="serie" <?php if(isset($_SESSION['filtroAC_serie'])!='')echo "value='".$_SESSION['filtroAC_serie']."'";?>><!--
                    <select name="modelo" id="modelo" class="required tipo"/>
                    <option value="">Seleccione...</option>
                    </select>-->
                    </td>
                    <td width="8%" align="left">Responsables</td>
                    <td width="15%" align="left">
                    <input type="text" name="responsable" id="responsable" size="25" maxlength="50" <?php if(isset($_SESSION['filtroAC_responsable'])!='')echo "value='".$_SESSION['filtroAC_responsable']."'";?>/>
                    <!--<option value="">Seleccione...</option>
                    <?php /*foreach($responsables as $resp){
					$sel = '';	
					if(isset($_SESSION['filtroAC_responsable']) && $_SESSION['filtroAC_responsable']!=''){
						if($resp['pe_nombre'] == $_SESSION['filtroAC_responsable'])
						$sel = 'selected';
					}	
					?>
                    <option value="<?php echo $resp['pe_nombre'];?>" <?php echo $sel;?>><?php echo $resp['pe_nombre'];?></option>
                    <?php }*/?>
                    </select>-->
                    </td>
                    <td align="right"><input type="button" value="Buscar" onClick="cargar()">&nbsp;<input type="button" value="Limpiar" onClick="limpiar()"></td>
                </tr>
                <tr>
                	<td align="left">Equipo</td>
                    <td colspan="3" align="left">
                    <input type="text" size="50" maxlength="100" name="equipo" id="equipo" <?php if(isset($_SESSION['filtroAC_equipo'])!='')echo "value='".$_SESSION['filtroAC_equipo']."'";?>>
                    </td>
                    <td align="left">Due&ntilde;o</td>
                    <td colspan="2" align="left">
                    <select name="dueno" id="dueno" class="required tipo"/>
                    <option value="">Seleccione...</option>
                    </select>
                    </td>
                </tr>
                </table>
                </form>
                <!-- resultado ajax -->
            	<div id="resultado"></div>
                
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