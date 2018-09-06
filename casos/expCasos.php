<?php
session_start();
set_time_limit(0);

header('Pragma: public');  
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past     
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');  
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1  
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1  
header('Pragma: no-cache');  
header('Expires: 0');  
header('Content-Transfer-Encoding: none');  
header('Content-Type: application/vnd.ms-excel; charset=utf-8'); // This should work for IE & Opera  
header('Content-type: application/x-msexcel; charset=utf-8'); // This should work for the rest  
header("content-disposition: attachment;filename=Casos_".date('d-m-Y').".xls");

require_once('../clases/Casos.class.php');
require_once('../clases/Util.class.php');

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
$resultado = $obj->muestraCasosFiltro($filtro);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<table align="center" border="1">
<tr>
    <th bgcolor="#d9d9d9">ID Caso</th>
    <th bgcolor="#d9d9d9">C&oacute;digo</th>
    <th bgcolor="#d9d9d9">V&iacute;a Ingreso</th>
    <th bgcolor="#d9d9d9">RUT</th>
    <th bgcolor="#d9d9d9">Nombre</th>
    <th bgcolor="#d9d9d9">Edad</th>
    <th bgcolor="#d9d9d9">Fecha de Nacimiento</th>
    <th bgcolor="#d9d9d9">Nacionalidad</th>
    <th bgcolor="#d9d9d9">Domicilio</th>
    <th bgcolor="#d9d9d9">Numero de Casa</th>
    <th bgcolor="#d9d9d9">Dpto/Poblacion</th>   
    <th bgcolor="#d9d9d9">Tipo Delito</th>
    <th bgcolor="#d9d9d9">Fecha de denuncia o derivacion</th>
    <th bgcolor="#d9d9d9">Nº de reingresos</th>
    <th bgcolor="#d9d9d9">Profesional</th>
    <th bgcolor="#d9d9d9">Regi&oacute;n</th>
    <th bgcolor="#d9d9d9">Comuna</th>
    <th bgcolor="#d9d9d9">Etapa</th>
    <th bgcolor="#d9d9d9">Fecha  Inicio Evaluacion</th>
    <th bgcolor="#d9d9d9">Fecha Termino Evaluacion</th>
    <th bgcolor="#d9d9d9">Nº de veces rechazado</th>
    <th bgcolor="#d9d9d9">Fecha Derivacion</th>
    <th bgcolor="#d9d9d9">Fecha Ingreso  a programa derivado </th>
</tr>
<?php
foreach( $resultado as $res ){
?>
<tr>
    <td align="left"><?php echo $res['idcaso'];?></td>
    <td align="left"><?php echo $res['ca_codigo'];?></td>
    <td align="left"><?php echo $res['vi_descripcion'];?></td>
    <td align="left"><?php echo Util::formateo_rut($res['ci_rut']);?></td>
    <td align="left"><?php echo $res['ci_nombre'].' '.$res['ci_paterno'].' '.$res['ci_materno'];?></td>
    <td align="left"><?php echo $res['ci_edadingreso'];?></td>
    <td align="left"><?php echo $res['ci_fnacimiento'];?></td>
    <td align="left"><?php echo $res['na_descripcion'];?></td>
    <td align="left"><?php echo $res['ci_domicilio'];?></td>
    <td align="left"><?php echo $res['ci_numero'];?></td>
    <td align="left"><?php echo $res['ci_poblacion'];?></td>
    <td align="left"><?php echo $res['de_descripcion'];?></td>
    <td align="left"><?php echo $res['ca_fdenuncia']; ?></td>
    <td align="left"><?php echo $res['reingresos']; ?></td>
    <td align="left"><?php echo $res['us_nombre'].' '.$res['us_paterno'];?></td>
    <td align="left"><?php echo $res['re_descripcion'];?></td>
    <td align="left"><?php echo $res['co_descripcion'];?></td>
    <td align="left"><?php echo $res['ca_etapa'];?></td>
    <td align="left"><?php echo $res['an_fecinicio'];?></td>
    <td align="left"><?php echo $res['an_fectermino'];?></td>
    <td align="left"><?php echo $res['rechazados']; ?></td>
    <td align="left"><?php echo $res['de_fderivacion']; ?></td>
    <td align="left"><?php echo UTIL::formatFecha($res['ca_finsercion']); ?></td>
</tr>
<?php	
}
?>
</table>
</body>
</html>
