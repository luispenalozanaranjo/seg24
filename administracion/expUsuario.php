<?php
session_start();
set_time_limit(0);
date_default_timezone_set('America/Santiago');

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Usuarios_".date('d-m-Y').".xls");

require_once('../clases/Usuario.class.php');
require_once('../clases/Util.class.php');

$filtro = '';

if(isset($_SESSION['filtroUS_nombre']) && $_SESSION['filtroUS_nombre']!='')
$filtro.=" AND concat(us_nombre,' ',us_paterno,' ',us_materno) like '%".$_SESSION['filtroUS_nombre']."%'";

if(isset($_SESSION['filtroUS_estado']) && $_SESSION['filtroUS_estado']!='')
$filtro.=" AND us_estado = '".$_SESSION['filtroUS_estado']."'";

$obj = new Usuario(null);
$resultado = $obj->muestraUsuariosFiltro($filtro);


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table align="center" border="1">
<tr>
    <th bgcolor="#d9d9d9">RUT</th>
    <th bgcolor="#d9d9d9">Nombre Completo</th>
    <th bgcolor="#d9d9d9">Email</th>
    <th bgcolor="#d9d9d9">Perfil</th>
    <th bgcolor="#d9d9d9">Comuna</th>   
    <th bgcolor="#d9d9d9">Estado</th>
</tr>
<?php
foreach( $resultado as $res ){
		$cmbcomunas='';
		$resul = $obj->entregaUsuarioHasComuna($res['us_rut']);
		//print_r($resul);
		if(count($resul)>0){
			$i=0;
			foreach($resul as $resc){
				$i++;
				if($i>1)
				$cmbcomunas.=', ';
				
				$cmbcomunas.=$resc['co_descripcion'];
			}
			
		}else{
		$cmbcomunas='';
		}
	
?>
<tr>
    <td align="left"><?php echo $res['us_rut'];?></td>
    <td align="left"><?php echo $res['us_nombre'].' '.$res['us_paterno'].' '.$res['us_materno'];?></td>
    <td align="left"><?php echo $res['us_email'];?></td>
    <td align="left"><?php echo $res['pe_descripcion'];?></td>
    <td align="left"><?php echo $cmbcomunas;?></td>
    <td align="left"><?php echo $res['us_estado'];?></td>
</tr>
<?php	
}
?>
</table>
</body>
</html>