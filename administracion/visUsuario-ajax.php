<script>
/*
$(document).ready(function()
{
  $('a[title]').qtip({
      position: {
         corner: {
            target: 'topLeft',
            tooltip: 'bottomRight'
         }
      },
      style: {
         name: 'red',
         padding: '7px 13px',
         textAlign: 'justify',
         width: {
            max: 610,
            min: 0
         },
		 color:'darkblue',
         tip: true
      }
   });
 });  
 */
</script> 
<?php
session_start();
//include("../accesos.php");
if (in_array(1, $_SESSION['glopermisos']['modulo']) && 
	($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){

$action = (isset($_GET['action'])&& $_GET['action'] !=NULL)?$_GET['action']:'';
$action = 'ajax';
if($action == 'ajax'){
	
	require_once('../clases/Usuario.class.php');		
	require_once('../clases/Paginador.class.php');
	
	$filtro = '';
	
	if(isset($_SESSION['filtroUS_nombre']) && $_SESSION['filtroUS_nombre']!='')
	$filtro.=" AND concat(us_nombre,' ',us_paterno,' ',us_materno) like '%".$_SESSION['filtroUS_nombre']."%'";
	
	if(isset($_SESSION['filtroUS_estado']) && $_SESSION['filtroUS_estado']!='')
	$filtro.=" AND us_estado = '".$_SESSION['filtroUS_estado']."'";
	
	if(isset($_SESSION['filtroUS_perfil']) && $_SESSION['filtroUS_perfil']!='')
	$filtro.=" AND u.pe_idperfil = '".$_SESSION['filtroUS_perfil']."'";
			
	$obj = new Usuario(null);
	$numrows=$obj->cuentaUsuariosFiltro($filtro);
	
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
	$resultado = $obj->muestraUsuariosLimitFiltro($start,$limit,$filtro);

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
    <tr class="cabecera-user">
        <th>RUT</th>
        <th>Nombre Completo</th>
        <th>Email</th>
        <th>Perfil</th>
        <th>Comuna</th>
        <th>Estado</th>
        <?php if($_SESSION['gloidperfil'] == 1){?>
        <th>Opci&oacute;n</th>
        <?php }?>
    </tr>
    <?php
	foreach( $resultado as $res ){
		
		$cmbcomunas='';
		$cmbcomunas2='';
		$resul = $obj->entregaUsuarioHasComuna($res['us_rut']);
		//print_r($resul);
		if(count($resul)>0){
			$i=0;
			foreach($resul as $resc){
				$i++;
				if($i>1){
				$cmbcomunas.='<br>';
				$cmbcomunas2.=', ';
				}
				
				$cmbcomunas.=$resc['co_descripcion'];
				$cmbcomunas2.=$resc['co_descripcion'];
			}
			
			if($i>1)
			$nomcomuna = $i." Comunas";
			else
			$nomcomuna = $i." Comuna";
			
		}else{
		$cmbcomunas='';
		$cmbcomunas2='';
		$i=0;
		}
	?>
	<tr onmouseover="this.style.backgroundColor='#FFFFCC'" onmouseout="this.style.backgroundColor='#FCFCFC'" bgcolor="#FCFCFC" class="texto">
		<td width="10%" align="center"><?php echo $res['us_rut'];?></td>
        <td width="20%"><?php echo $res['us_nombre'].' '.$res['us_paterno'].' '.$res['us_materno'];?></td>
        <td width="20%" align="center"><?php echo $res['us_email'];?></td>
        <td width="15%" align="center"><?php echo $res['pe_descripcion'];?></td>
        <td width="15%" align="center">
        <?php if($i>0){?>
        <a href='#' title='<?php echo $cmbcomunas;?>'><?php echo $nomcomuna;?></a>
        <?php }else{ echo "Sin Comuna";} ?>
        </td>
        <td width="10%" align="center"><?php echo $res['us_estado'];?></td>
        <?php if($_SESSION['gloidperfil'] == 1){?>
		<td width="10%" align="center">
        <a title="Editar" href="modUsuario.php?id=<?php echo $res['us_rut'];?>"><img src="../images/edit32_med.png" width="18" /></a>
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