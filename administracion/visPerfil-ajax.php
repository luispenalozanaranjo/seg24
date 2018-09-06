<?php
session_start();
//include("../accesos.php");
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
	

$action = (isset($_GET['action'])&& $_GET['action'] !=NULL)?$_GET['action']:'';
$action = 'ajax';
if($action == 'ajax'){
	
	require_once('../clases/Perfil.class.php');		
	require_once('../clases/Paginador.class.php');
			
	$obj = new Perfil(null);
	$numrows=$obj->cuentaPerfiles();
	
	//pagination variables
	$page = (isset($_GET['page']) && !empty($_GET['page']))?$_GET['page']:1;
	if (isset($page))
	$pageno = $page;
	else
	$pageno = 15;

	$rows_per_page = 15;//numero de filas por página
	$lastpage = ceil($numrows/$rows_per_page);//se obtiene la última página
	$pageno = (int)$pageno;
	if ($pageno > $lastpage)
	$pageno = $lastpage;
	if ($pageno < 1)
	$pageno = 1;

	$start = ($pageno - 1) * $rows_per_page;	
	$limit = $rows_per_page;

	//main query to fetch the data
	$resultado = $obj->muestraPerfilesLimit($start,$limit);

	//loop through fetched data
	$salida = '';
	?>
        <link rel='stylesheet' type='text/css' href='../css/global.css' />
		<link rel='stylesheet' type='text/css' href='../css/grilla-base.css'>
		<link rel='stylesheet' type='text/css' href='../css/jquery-ui.css'>
    <div id="contenedor-grilla-base">
    <table align="center" id="grilla-base" style="
    width: 1130px;
">
    <tr class="cabecera">
        <th>ID</th>
        <th>Nombre</th>
        <th>Estado</th>
        <?php if($_SESSION['gloidperfil'] == 1){?>
        <th>Opci&oacute;n</th>
        <?php }?>
    </tr>
    <?php
	foreach( $resultado as $res ){
	?>
	<tr onmouseover="this.style.backgroundColor='#FFFFCC'" onmouseout="this.style.backgroundColor='#FCFCFC'" bgcolor="#FCFCFC" class="texto">
		<td width="10%" align="center"><?php echo $res['pe_idperfil'];?></td>
        <td width="20%"><?php echo $res['pe_descripcion'];?></td>
        <td width="10%" align="center"><?php echo $res['pe_estado'];?></td>
        <?php if($_SESSION['gloidperfil'] == 1){?>
		<td width="10%" align="center">
        <a title="Editar" href="modPerfil.php?id=<?php echo $res['pe_idperfil'];?>"><img src="../images/edit32_med.png" width="18" /></a>
        </td>
        <?php }?>
	</tr>
	<?php	
	}
	$obj->Close();
	$paginador = new Paginador($pageno,$lastpage,$numrows);
	?>
	<tr class="tr_foot">
		<td colspan="7" align="center" width="100%">
		<div style="float:left;width:auto;margin-top:8px;">&nbsp;&nbsp;<label><?php echo $numrows;?> registro(s)</label></div>
		<div style="float:right;width:auto;margin-top:8px;"><label><?php echo $paginador->cantidadPaginas();?></label>&nbsp;&nbsp;</div>
		<div align="center" class="position_paginador"><label><?php echo $paginador->muestraPaginador();?></label></div>
		</td>	
	</tr>
	</table>
    </div>
	<?php
}

}else{
	session_destroy();
	header('location: ../index.php');
}
?>