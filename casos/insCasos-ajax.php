<?php
session_start();

$data = array("success" => false, "mensaje" => 'Ocurrió un error al ingresar el registro');
$token	=	"";
$valida	=	"";

if( isset($_POST['nombre']) && $_POST['nombre']!='' && isset($_POST['paterno']) && $_POST['paterno']!=''
	&& isset($_POST['materno']) && $_POST['materno']!='' && isset($_POST['region']) && $_POST['region']!='' 
	&& isset($_POST['comuna']) && $_POST['comuna']!='' && isset($_POST['fnacimiento']) && $_POST['fnacimiento']!='' 
	&& isset($_POST['domicilio']) && $_POST['domicilio']!='' && isset($_POST['numero']) && $_POST['numero']!='' ){
		
	require_once('../clases/Casos.class.php');
	require_once('../clases/Auditoria.class.php');
	require_once('../clases/Util.class.php');
	
	
	//valida token
	$token	= $_POST['auth_token'];
	$navegador = Util::detectaNavegador();
	$cadena = md5('inscasos_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);
	$valida	= Util::verificaToken($cadena, $token, 300);
	//valida el RUT
	if(isset($_POST['rut']) && $_POST['rut']!='')
	$valrut = Util::validaRUT($_POST['rut']);
	else
	$valrut = 'Si';

	if(!$valida){
	 	session_destroy();
		$data = array("success" => false, "mensaje" => 'ERROR');
	}
	else if($valrut == 'NO'){
		$data = array("success" => false, "mensaje" => 'RUT no válido');
	} 
	else {	
	
		 //if($valrut == 'NO')
		//$data = array("success" => false, "mensaje" => 'RUT no válido');
				
		//sanitiza variables
		$region	=	filter_var($_POST['region'], FILTER_SANITIZE_NUMBER_INT);
		$comuna	=	filter_var($_POST['comuna'], FILTER_SANITIZE_STRING);
		$nombre	=	ucwords(strtolower(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING)));
		$paterno	=	ucwords(strtolower(filter_var($_POST['paterno'], FILTER_SANITIZE_STRING)));
		$materno	=	ucwords(strtolower(filter_var($_POST['materno'], FILTER_SANITIZE_STRING)));
		$fnacimiento	=	filter_var($_POST['fnacimiento'], FILTER_SANITIZE_STRING);
		$domicilio	=	ucwords(strtolower(filter_var($_POST['domicilio'], FILTER_SANITIZE_STRING)));
		$numero	=	filter_var($_POST['numero'], FILTER_SANITIZE_NUMBER_INT);
		$rut	=	filter_var($_POST['rut'], FILTER_SANITIZE_STRING);		
		$caracter = array(".", "-");
		
		if(isset($_POST['rut']))
		$rut = str_replace($caracter, "", $rut);
		else
		$rut = '';
		
		if(isset($_POST['nacionalidad']) && $_POST['nacionalidad']!='')
		$nacionalidad	=	filter_var($_POST['nacionalidad'], FILTER_SANITIZE_STRING);
		else
		$nacionalidad = NULL;
		
		if(isset($_POST['sexo']))
		$sexo	=	filter_var($_POST['sexo'], FILTER_SANITIZE_STRING);
		else
		$sexo = '';
		
		if(isset($_POST['educacion']))
		$educacion	=	filter_var($_POST['educacion'], FILTER_SANITIZE_STRING);
		else
		$educacion = '';
		
		if(isset($_POST['poblacion']))
		$poblacion	=	ucwords(strtolower(filter_var($_POST['poblacion'], FILTER_SANITIZE_STRING)));
		else
		$poblacion = '';
		
		if(isset($_POST['fdenuncia']))
		$fdenuncia	=	Util::formatFechaSQL2(filter_var($_POST['fdenuncia'], FILTER_SANITIZE_STRING));
		else
		$fdenuncia = '';
		
		if(isset($_POST['motivo']))
		$motivo	=	ucwords(strtolower(filter_var($_POST['motivo'], FILTER_SANITIZE_STRING)));
		else
		$motivo = '';
		
		if(isset($_POST['delito']))
		$delito	=	filter_var($_POST['delito'], FILTER_SANITIZE_STRING);
		else
		$delito = '';
		
		if(isset($_POST['clase']))
		$clase	=	filter_var($_POST['clase'], FILTER_SANITIZE_STRING);
		else
		$clase = '';
		
		if(isset($_POST['rcivil']))
		$rcivil	=	filter_var($_POST['rcivil'], FILTER_SANITIZE_STRING);
		else
		$rcivil = '';
		
		if(isset($_POST['rvulnerado']))
		$rvulnerado	=	filter_var($_POST['rvulnerado'], FILTER_SANITIZE_NUMBER_INT);
		else
		$rvulnerado = '';
		
		if(isset($_POST['rinfractor']))
		$rinfractor	=	filter_var($_POST['rinfractor'], FILTER_SANITIZE_NUMBER_INT);
		else
		$rinfractor = '';
		
		if(isset($_POST['rinimputable']))
		$rinimputable	=	filter_var($_POST['rinimputable'], FILTER_SANITIZE_NUMBER_INT);
		else
		$rinimputable = '';
		
		if(isset($_POST['unidad']))
		$unidad	=	ucwords(strtolower(filter_var($_POST['unidad'], FILTER_SANITIZE_STRING)));
		else
		$unidad = '';
		
		if(isset($_POST['comunapro']))
		$comunapro	=	filter_var($_POST['comunapro'], FILTER_SANITIZE_STRING);
		else
		$comunapro = '';
		
		if(isset($_POST['juzgado']))
		$juzgado	=	ucwords(strtolower(filter_var($_POST['juzgado'], FILTER_SANITIZE_STRING)));
		else
		$juzgado = '';
		
		if(isset($_POST['parte']))
		$parte	=	filter_var($_POST['parte'], FILTER_SANITIZE_NUMBER_INT);
		else
		$parte = '';
		
		if(isset($_POST['detenido']))
		$detenido	=	filter_var($_POST['detenido'], FILTER_SANITIZE_STRING);
		else
		$detenido = '';
		
		if(isset($_POST['r24horas']))
		$r24horas	=	filter_var($_POST['r24horas'], FILTER_SANITIZE_NUMBER_INT);
		else
		$r24horas = '';
		
		if(isset($_POST['viaingreso']))
		$viaingreso	=	filter_var($_POST['viaingreso'], FILTER_SANITIZE_STRING);
		else
		$viaingreso = '';
		
		if(isset($_POST['codigo']))
		$codigo	=	filter_var($_POST['codigo'], FILTER_SANITIZE_STRING);
		else
		$codigo = '';
		
		$fnacimiento	=	Util::formatFechaSQL2($fnacimiento);
		$edad	= Util::calculaEdad($fnacimiento);
		
		$com = substr($comuna,0,strpos($comuna,'~'));

		$obj = new Casos(null);
		$obj->Begin();
		$resultado = $obj->agregaCiudadano($rut,$com,$nombre,$paterno,$materno,$fnacimiento,$edad,$sexo,$educacion,$domicilio,$numero,$poblacion,$nacionalidad);
		$idciudadano = $obj->Identity();
		
		//print_r($resultado);
		//print_r("idciudadano: ".$idciudadano);

		if( $resultado > 0 ){
			
			$resultado2 = $obj->agregaCaso($_SESSION['glorut'],$viaingreso,$idciudadano,$delito,$fdenuncia,$motivo,$clase,
			$rvulnerado,$rinfractor,$rinimputable,$unidad,$comunapro,$juzgado,$parte,$detenido,$r24horas,$rcivil,$codigo);
			$idcaso = $obj->Identity();
			$resultado_eva = $obj->modificaRutEvaluacion($_SESSION['glorut'],$idcaso,1);
			
			if($codigo==''){
				//se formatea en CodComuna+Año-idcaso. Ej: STGO2015-0001
				$idcaso = str_pad((int) $idcaso,6,"0",STR_PAD_LEFT);
				$codigo = substr($comuna,strpos($comuna,'~')+1,strlen($comuna)).date('Y').'-'.$idcaso;
				$resultado3 = $obj->modificaCasoCodigo($codigo,$idcaso);
			}
			
			
			
			if($resultado2 > 0){
				$data = array("success" => true,"mensaje"=>$idcaso);
				$obj->Commit();
				
				/********************INICIO INGRESO TRAZABILIDAD*****************************/
				$nomuser = $_SESSION['glonombre'].' '.$_SESSION['glopaterno'].' '.$_SESSION['glomaterno'];
				require_once('../clases/Trazabilidad.class.php');
				$trazabilidad = new Trazabilidad(null);
				$resultado4 = $trazabilidad->agregaTrazabilidad('Ingreso',$idcaso,$_SESSION['glorut'],$nomuser);
				$resultado5 = $trazabilidad->agregaTrazabilidad('Contactabilidad',$idcaso,$_SESSION['glorut'],$nomuser);
				$trazabilidad->Close();
				/********************FIN INGRESO TRAZABILIDAD*****************************/
			
				/***************************/
				$auditoria_accion = "Insert";
				$auditoria_tabla = "ciudadano";
				$auditoria_sql = "agregaCiudadano(".$rut.",".$comuna.",".$nombre.",".$paterno.",".$materno.",".$fnacimiento.",".$sexo.",".$educacion.",".$domicilio.",".$numero.",".$poblacion.",".$nacionalidad.")";
				$auditoria_meta = "Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				$auditoria->Close();
				
				$auditoria_accion = "Insert";
				$auditoria_tabla = "caso";
				$auditoria_sql = "agregaCaso(".$_SESSION['glorut'].",".$viaingreso.",".$idciudadano.",".$delito.",".$fdenuncia.",".$motivo.",
				".$clase.",".$rvulnerado.",".$rinfractor.",".$rinimputable.",".$unidad.",".$comunapro.",".$juzgado.",".$parte.",".$detenido.",
				".$r24horas.",".$rcivil.",".$codigo.")";
				$auditoria_meta = "Post";
				$auditoria = new Auditoria(null);
				$auditoria->agregaAuditoria($_SESSION['glorut'],$auditoria_accion,$auditoria_tabla,$auditoria_sql,$auditoria_meta);
				$auditoria->Close();
				/***************************/
			}
			else{
			$obj->Rollback();
			}
		}
		else
		$obj->Rollback();
		
		$obj->Close();
	}
	
	echo json_encode($data);
}	
else{
	session_destroy();
	header('location: ../index.php');
}
?>