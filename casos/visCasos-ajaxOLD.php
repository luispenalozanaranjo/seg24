<?php
session_start();
/*print '<pre>';
print_r($_SESSION['glopermisos']['modulo']);
print '</pre>';

print '<pre>';
print_r($_SESSION['glopermisos']['escritura']);
print '</pre>';

print '<pre>';
print_r($_SESSION['glopermisos']['lectura']);
print '</pre>';
*/

		echo "<link rel='stylesheet' type='text/css' href='../css/global.css' />
		<link rel='stylesheet' type='text/css' href='../css/grilla.css'>
		<link rel='stylesheet' type='text/css' href='../css/grilla-base.css'>
		<link rel='stylesheet' type='text/css' href='../css/jquery-ui.css'>";

if (in_array(2, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][1] == 1 || $_SESSION['glopermisos']['lectura'][1] == 1 )){
//include("../accesos.php");

$action = (isset($_GET['action'])&& $_GET['action'] !=NULL)?$_GET['action']:'';
$action = 'ajax';
if($action == 'ajax'){
	
	require_once('../clases/Casos.class.php');	
	require_once('../clases/Util.class.php');		
	require_once('../clases/Paginador.class.php');
	
	$filtro = '';
	
	if(isset($_SESSION['filtroCA_codigo']) && $_SESSION['filtroCA_codigo']!='')
	$filtro.=" AND ca_codigo LIKE '%".$_SESSION['filtroCA_codigo']."%'";
	
	if(isset($_SESSION['filtroCA_delito']) && $_SESSION['filtroCA_delito']!='')
	$filtro.=" AND ca.de_iddelito = ".$_SESSION['filtroCA_delito']."";
	
	if(isset($_SESSION['filtroCA_profesional']) && $_SESSION['filtroCA_profesional']!='')
	$filtro.=" AND concat(us_nombre,' ',us_paterno,' ',us_materno) like '%".$_SESSION['filtroCA_profesional']."%'";
	
	if(isset($_SESSION['filtroCA_etapa']) && $_SESSION['filtroCA_etapa']!='')
	$filtro.=" AND ca_etapa = '".$_SESSION['filtroCA_etapa']."'";
	
	if(isset($_SESSION['filtroCA_rut']) && $_SESSION['filtroCA_rut']!='')
	$filtro.=" AND ca.ci_rut = '".$_SESSION['filtroCA_rut']."'";
	
	if(isset($_SESSION['filtroCA_nombre']) && $_SESSION['filtroCA_nombre']!='')
	$filtro.=" AND concat(ci_nombre,' ',ci_paterno,' ',ci_materno) like '%".$_SESSION['filtroCA_nombre']."%'";
	
	if(isset($_SESSION['filtroCA_viaingreso']) && $_SESSION['filtroCA_viaingreso']!='')
	$filtro.=" AND ca.vi_idvia = ".$_SESSION['filtroCA_viaingreso']."";

	
	if(isset($_SESSION['filtroCA_region']) && $_SESSION['filtroCA_region']!='')
	$filtro.=" AND re.re_idregion = ".$_SESSION['filtroCA_region']."";
	
	if(isset($_SESSION['filtroCA_comuna']) && $_SESSION['filtroCA_comuna']!='')
	$filtro.=" AND co.co_idcomuna = ".$_SESSION['filtroCA_comuna']."";
	
	if(isset($_SESSION['filtroCA_numcaso']) && $_SESSION['filtroCA_numcaso']>0)
	$filtro.=" AND ca.ca_idcaso = ".$_SESSION['filtroCA_numcaso']."";
	
/*	if(isset($_SESSION['filtroCA_fnacimiento']) && $_SESSION['filtroCA_fnacimiento']!='')
	$filtro.=" AND ci.ci_fnacimiento = '".Util::formatFecha2($_SESSION['filtroCA_fnacimiento'])."'";*/
	
	if(isset($_SESSION['filtroCA_direccion']) && $_SESSION['filtroCA_direccion']!='')
	$filtro.=" AND CONCAT(ci.ci_domicilio, ' ', CONVERT(ci.ci_numero, CHAR(50)), ' ',ci.ci_poblacion) LIKE '%".$_SESSION['filtroCA_direccion']."%'";
	
	if(isset($_SESSION['filtroCA_finsercion_inicio']) && $_SESSION['filtroCA_finsercion_inicio']!='' && $_SESSION['filtroCA_finsercion_fin']=='')
	$filtro.=" AND DATE_FORMAT(DATE(ca_finsercion),'%Y%m%d') BETWEEN '".Util::formatFecha2($_SESSION['filtroCA_finsercion_inicio'])."' AND '%'";
	
	
	if(isset($_SESSION['filtroCA_finsercion_fin']) && $_SESSION['filtroCA_finsercion_fin']!='' && $_SESSION['filtroCA_finsercion_inicio']=='')
	$filtro.=" AND DATE_FORMAT(DATE(ca_finsercion),'%Y%m%d') BETWEEN '%' AND '".Util::formatFecha2($_SESSION['filtroCA_finsercion_fin'])."'";
	
	
	if((isset($_SESSION['filtroCA_finsercion_inicio']) && $_SESSION['filtroCA_finsercion_inicio']!='') && (isset($_SESSION['filtroCA_finsercion_fin']) && $_SESSION['filtroCA_finsercion_fin']!=''))
	$filtro.=" AND DATE_FORMAT(DATE(ca_finsercion),'%Y%m%d') BETWEEN '".Util::formatFecha2($_SESSION['filtroCA_finsercion_inicio'])."' AND '".Util::formatFecha2($_SESSION['filtroCA_finsercion_fin'])."'";
	
	

	if($_SESSION['gloidperfil']!=1  && $_SESSION['glogestorcentral'] == 'NO'){
		$cmbcomunas=$_SESSION['glocmbcomunas'];
		$arr='';
		$i=0;		
		for ($i=0;$i<count($cmbcomunas);$i++)    
		{  
		if($i>0)
		$arr.=',';
		//echo "<br> Comuna " . $i . ": " . $cmbcomunas[$i];   
		$arr.=$cmbcomunas[$i];
		} 
		$arr2=" AND co.co_idcomuna in (".$arr.")";
		$filtro.=" AND co.co_idcomuna in (".$arr.")";
	}
			
	$obj = new Casos(null);
	$numrows=$obj->cuentaCasosFiltro($filtro);
	
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
	$resultado = $obj->muestraCasosLimitFiltro($start,$limit,$filtro);

	//loop through fetched data
	$salida = '';
	
	$paginador = new Paginador($pageno,$lastpage,$numrows);
	?>
<link rel='stylesheet' type='text/css' href='../css/global.css' />

		<link rel='stylesheet' type='text/css' href='../css/grilla-base.css'>
		<link rel='stylesheet' type='text/css' href='../css/jquery-ui.css'>

                      <div id="contenedor-grilla-base" class="scroll">
                  
    <table align="center" id="grilla-base">
    <tr>
      <th colspan="4" align="left"><label>Total de Registros <?php echo $numrows;?></label></th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th colspan="2">&nbsp;</th>
      <th colspan="4" class="position_cant_pagina"><label><?php echo $paginador->muestraPaginador();?></label></th>
      </tr>
    <tr class="cabecera">
        <th>ID Caso</th>
        <th>C&oacute;digo<?php //echo $filtro;?></th>
        <th>Fecha<br />Creaci&oacute;n</th>
        <th>RUT</th>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Tipo Delito</th>
        <th>Profesional</th>
        <!--<th>Regi&oacute;n</th>-->
        <th>Comuna</th>
        <th>V&iacute;a<br>Ingreso</th>
        <th>Etapa</th>
        <th>Opci&oacute;n</th>
    </tr>
    <?php
	foreach( $resultado as $res ){
		if($res['re_estado'] == 'Rechazada')
		$img = '../images/icon/noAsset.png';
		else
		$img = '../images/icon/Asset.png';
	?>
	<tr onmouseover="this.style.backgroundColor='#FFFFCC'" onmouseout="this.style.backgroundColor='#FCFCFC'" bgcolor="#FCFCFC" class="texto">
		<td width="4%" align="center"><?php echo $res['idcaso'];?></td>
        <td width="8%" align="center"><?php echo $res['ca_codigo'];?></td>
        <td width="6%" align="center"><?php echo Util::formatFecha($res['ca_finsercion']);?></td>
        <td width="7%" align="center"><?php echo Util::formateo_rut($res['ci_rut']);?></td>
        <td width="10%" align="center"><?php echo $res['ci_nombre'].' '.$res['ci_paterno'].' '.$res['ci_materno'];?></td>
        <td width="3%" align="center"><?php echo $res['ci_edadingreso'];?></td>
        <td width="15%" align="center"><?php echo $res['de_descripcion'];?></td>
        <td width="10%" align="center"><?php echo $res['us_nombre'].' '.$res['us_paterno'];?></td>
        <!--<td width="13%" align="center"><?php echo $res['re_descripcion'];?></td>-->
        <td width="10%" align="center"><?php echo $res['co_descripcion'];?></td>
        <td width="10%" align="center"><?php echo $res['vi_descripcion'];?></td>
        <td width="10%" align="center"><?php echo $res['ca_etapa'];?></td>
		<td width="10%" align="center" nowrap="nowrap">
       
        <?php
		if (in_array(1, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][0] > 0 || $_SESSION['glopermisos']['lectura'][0] > 0)){
		?><a title="Eliminar Caso <?php echo $res['ca_codigo'];?>" onclick="solicitudEliminacion(<?php echo $res['idcaso'];?>)"><img src="../images/icon/iconEliminar.png" width="18" align="middle" /></a> 
		<?php } ?>

        <a title="Ver Caso" href="modCasos.php?id=<?php echo $res['idcaso'];?>"><img src="../images/icon/iconLapiz.png" width="18" align="middle" /></a>
        <?php if($res['ca_idetapa']>1 && in_array(3, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][2] == 1 || $_SESSION['glopermisos']['lectura'][2] == 1)){?>
        <a title="Contactabilidad" href="visContactabilidad.php?id=<?php echo $res['idcaso'];?>"><img src="../images/icon/iconUsuario.png" width="18" align="middle" /></a>
        <?php }
		if($res['ca_idetapa']>2 && in_array(4, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][3] == 1 || $_SESSION['glopermisos']['lectura'][3] == 1)){?>
        <a title="Asset" href="visAssetAnalisis.php?id=<?php echo $res['idcaso'];?>&idetapa=<?php echo '3'; ?>"><img src="<?php echo $img;?>" width="18" align="middle" /></a>
        <?php }
		if($res['ca_idetapa']>3 && in_array(5, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][4] == 1 || $_SESSION['glopermisos']['lectura'][4] == 1)){
		?>
        <a title="Derivación" href="visDerivacion.php?id=<?php echo $res['idcaso'];?>"><img src="../images/icon/iconCalendario.png" width="18" align="middle" /></a>
		<?php }
		if($res['ca_idetapa']>4 && in_array(6, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][5] == 1 || $_SESSION['glopermisos']['lectura'][5] == 1)){
		?><a title="Reevaluacion" href="visAssetAnalisis.php?id=<?php echo $res['idcaso'];?>&idetapa=<?php echo '5'; ?>"><img src="../images/icon/Asset2.png" width="18" align="middle" /></a>
        <?php }
		if($res['ca_idetapa']==6 && in_array(8, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][6] == 1 || $_SESSION['glopermisos']['lectura'][6] == 1) && ($_SESSION['gloidperfil']==2 ) && $res['sol_solicitado'] != 'Si'){
		?><a title="Cierre" onclick="solicitudCierre(1,<?php echo $res['idcaso'];?>)"><img src="../images/icon/iconSolCierre.png" align="middle" /></a> 
        <?php }
		if($res['ca_idetapa']==6 && in_array(8, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][6] == 1 || $_SESSION['glopermisos']['lectura'][6] == 1) && ($_SESSION['gloidperfil']==3 ) && $res['sol_solicitado'] == 'Si'){
		?><a title="Cierre" onclick="solicitudCierre(2,<?php echo $res['idcaso'];?>)"><img src="../images/icon/iconCierre.png" align="middle" /></a> 
        <?php }?>         </td>
	</tr>
	<?php	
	}
	$obj->Close();

	?>
	<tr>
	  <td colspan="12">&nbsp;</td>
	  </tr>
	<tr>
		<td colspan="4">
		<label>Total de Registros <?php echo $numrows;?></label>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  <td colspan="2" class="position_paginador"></td>
	  <td colspan="4" class="position_cant_pagina"><label><?php echo $paginador->muestraPaginador();?></label></td>
	  </tr>
	</table></div>
	<?php
}

}else{
	session_destroy();
	header('location: ../index.php');
}

?>