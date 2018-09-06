<style>
.seleccion {
    text-decoration: underline;
	background-color: #848484;
}
ul {
    list-style-type: none;
}
a {
    text-decoration: none;
}	

</style>	
<?php
$var = explode("/",$_SERVER['PHP_SELF']);
$pag = $var[count($var)-1];

$class_analisis 	= '';
$class_hogar 	= '';
$class_relacion 	= '';
$class_educacion 	= '';
$class_barrio 	= '';
$class_estilo 	= '';
$class_sustancias 	= '';
$class_salud 	= '';
$class_salud2 	= '';
$class_percepcion 	= '';
$class_comportamiento 	= '';
$class_actitud 	= '';
$class_motivacion 	= '';
$class_resumen 	= '';
$class_revision = '';
$estado = '';

if ($pag == 'visAssetAnalisis.php')
$class_analisis = 'class="seleccion"';
if ($pag == 'visAssetHogar.php')
$class_hogar = 'class="seleccion"';
if ($pag == 'visAssetRelacion.php')
$class_relacion = 'class="seleccion"';
if ($pag == 'visAssetEducacion.php')
$class_educacion = 'class="seleccion"';
if ($pag == 'visAssetBarrio.php')
$class_barrio = 'class="seleccion"';
if ($pag == 'visAssetEstilo.php')
$class_estilo = 'class="seleccion"';
if ($pag == 'visAssetSustancias.php')
$class_sustancias = 'class="seleccion"';
if ($pag == 'visAssetSalud.php')
$class_salud = 'class="seleccion"';
if ($pag == 'visAssetSaludMental.php')
$class_salud2 = 'class="seleccion"';
if ($pag == 'visAssetPercepcion.php')
$class_percepcion = 'class="seleccion"';
if ($pag == 'visAssetComportamiento.php')
$class_comportamiento = 'class="seleccion"';
if ($pag == 'visAssetActitud.php')
$class_actitud = 'class="seleccion"';
if ($pag == 'visAssetMotivacion.php')
$class_motivacion = 'class="seleccion"';
if ($pag == 'visAssetEvaluacion.php')
$class_resumen = 'class="seleccion"';
if ($pag == 'visAssetRevision.php')
$class_revision = 'class="seleccion"';

/*
Se habilita el menú "Revisión", al momento de tener todas las calificaciones
*/
//session_start();
//print_r($_SESSION['estado']); 
//if($_SESSION['estado']=='')
//$_SESSION['estado']=1;

if($_SESSION['idetapa']=='3'){
	$_SESSION['estado'] = 1;
} else if($_SESSION['idetapa']=='5'){
	$_SESSION['estado'] = 2;
}

require_once('clases/Evaluacion.class.php');
$obj = new Evaluacion(null);
$rs = $obj->entregaAssetEvaluacion($idcaso,$_SESSION['estado']);
foreach( $rs as $res )
$estado = $res['re_estado'];


$obj->Close();
?>
<?php print '<br />'.'<div style="color:#FF6600">'.'<b>'.'Caso N&deg; '.$_SESSION['idcaso'].'</b>'.'</div>'.'<br />'; ?>

<?php echo '
		<nav>
			<div class="contenedor" id="contenedorMenu">
				<ul class="menu">
					<li>
						<a href="visAssetAnalisis.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_analisis.' >An&aacute;lisis</a>
					</li>
					<li>
                        <a href="visAssetHogar.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '. $class_hogar.' >Hogar</a>
                    </li>
					<li>
						<a href="visAssetRelacion.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_relacion.' >Relaciones</a>
					</li>
					<li>
						<a href="visAssetEducacion.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'"  '.$class_educacion.' >Educaci&oacute;n</a>
					</li>
				    <li>
						<a href="visAssetBarrio.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'"  '.$class_barrio.' >Barrio</a>
					</li>
					 <li>
						<a href="visAssetEstilo.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_estilo.'>Estilo de Vida</a>
					</li>
					 <li>
						<a href="visAssetSustancias.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_sustancias.'>Sustancias</a>
					</li>
					 <li>
						<a href="visAssetSalud.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_salud.'>Salud Física</a>
					</li>
					 <li>
						<a href="visAssetSaludMental.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_salud2.'>Salud Mental</a>
					</li>';
					
					
		print	'</ul>
			</div>
		</nav>'; ?>
        
        <?php print '<nav>
			<div class="contenedor" id="contenedorMenu">
				<ul class="menu"><li>
						<a href="visAssetPercepcion.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_percepcion.'>Percepci&oacute;n</a>
					</li>
					<li>
					<a href="visAssetComportamiento.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_comportamiento.'>Comportamiento</a>
					</li>
					 <li>
						<a href="visAssetActitud.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_actitud.'>Actitud</a>
					</li>
					<li>
						<a href="visAssetMotivacion.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_motivacion.'>Motivaci&oacute;n</a>
					</li>
					
					<li>
						<a href="visAssetEvaluacion.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_resumen.'>Resumen '.$estado.'</a>
					</li>';
					if($estado!=''){
            print '<li style="border:groove;border-color:#3bafe2"><a href="visAssetRevision.php?id='.$_SESSION['idcaso'].'&idetapa='.$_SESSION['idetapa'].'" '.$class_revision.'><b>Revision</b></a></li>';
            }
			print '</ul>
			</div>
		</nav>'; ?>
    <script>
$('#contenedorMenu li a').on('click', function(){
    $('li a.seleccion').removeClass('seleccion');
    $(this).addClass('seleccion');
});
</script>