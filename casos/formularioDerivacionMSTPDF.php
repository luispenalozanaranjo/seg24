<?php
session_start();

require_once('../js/tcpdf/config/lang/eng.php');
require_once('../js/tcpdf/tcpdf.php');
	
//$_GET['id'] = 30;	
if(isset($_GET['id']) && $_GET['id']!='')
{
	$idcaso		=	filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

if (in_array(2, $_SESSION['glopermisos']['modulo']) && ($_SESSION['glopermisos']['escritura'][1] == 1 || $_SESSION['glopermisos']['lectura'][1] == 1 )){
require_once('../clases/Casos.class.php');
require_once('../clases/Comuna.class.php');
require_once('../clases/Delito.class.php');
require_once('../clases/Nacionalidad.class.php');
require_once('../clases/Util.class.php');
require_once('../clases/Trazabilidad.class.php');	
require_once('../clases/Evaluacion.class.php');
require_once('../clases/Visita.class.php');
require_once('../clases/Derivacion.class.php');
require_once('../clases/Analisis.class.php');

$rut	=	'';
$idciudadano = '';
$nombre	=	'';
$paterno	=	'';
$materno	=	'';
$region_u	=	'';
$comuna_u	=	'';
$fnacimiento	=	'';
$nacionalidad_u	=	'';
$sexo	=	'';
$educacion	=	'';
$domicilio	=	'';
$numero	=	'';
$poblacion	=	'';
$fdenuncia	=	'';
$motivo	=	'';
$delito_u	=	'';
$delito_unum	=	'';
$clasep	=	'';			      
$rcivil	=	'';
$rvulnerado	=	'';
$rinfractor	=	'';
$rinimputable	=	'';
$unidad	=	'';
$comunapro	=	'';
$juzgado	=	'';
$parte	=	'';
$detenidoen	=	'';
$ingreso24	=	'';
$via_u	=	'';
$codigo = '';


$caso = new Casos(null);
$resultado = $caso->entregaCaso($idcaso);
if(count($resultado)>0){
	foreach( $resultado as $res ){
		$rutnino	=	$res['ci_rut'];
		$idciudadano = $res['ci_idciudadano'];
		$nombre		=	$res['ci_nombre'];
		$paterno	=	$res['ci_paterno'];
		$materno	=	$res['ci_materno'];
		$region_u	=	$res['re_idregion'];
		$comuna_u	=	$res['co_idcomuna'];
		$fnacimiento	=	Util::formatFecha($res['ci_fnacimiento']);
		$edad = Util::calculaEdad($res['ci_fnacimiento']);
		$nacionalidad_u	=	$res['na_idnacionalidad'];
		$sexo	=	$res['ci_sexo'];
		$educacion	=	$res['ci_educacion'];
		$domicilio	=	$res['ci_domicilio'];
		$numero	=	$res['ci_numero'];
		$poblacion	=	$res['ci_poblacion'];
		$fdenuncia	=	Util::formatFecha($res['ca_fdenuncia']);
		$motivo	=	$res['ca_motivo'];
		$delito_u	=	$res['de_iddelito'];
		$delito_unum	=	$res['de_num'];
		$clasep	=	$res['ca_claseparticipante'];					      
		$rcivil	=	$res['ca_regcivil'];
		$rvulnerado	=	$res['ca_reingresovulnerado'];
		$rinfractor	=	$res['ca_reingresoinfractor'];
		$rinimputable	=	$res['ca_reingresoinimputable'];
		$unidad	=	$res['ca_unidadprocedimiento'];
		$comunapro	=	$res['ca_comunaprocedimiento'];
		$juzgado	=	$res['ca_juzgado'];
		$parte	=	$res['ca_parte'];
		$detenidoen	=	$res['ca_detenidoen'];
		$ingreso24	=	$res['ca_ingresos24'];
		$via_u	=	$res['vi_idvia'];
		$via_descripcion	=	$res['vi_descripcion'];
		$codigo = $res['ca_codigo'];
	}
	
	$delito = new Delito(null);
	$delitos = $delito->muestraDelitosActivos();
	$delito->Close();
		
	$comuna = new Comuna(null);
	$nomcomuna = $comuna->entregaComuna($comuna_u);;
	$comuna->Close();
	
	$nacionalidad = new Nacionalidad(null);
	$nacionalidades = $nacionalidad->muestraNacionalidad();
	$nacionalidad->Close();
	
	$trazabilidad = new Trazabilidad(null);
	$result_traz = $trazabilidad->entregaTrazabilidad('Ingreso',$idcaso);
	$trazabilidad->Close();
	$nomederivador ='';
	$fechaderivacion ='';
	$mailderivador = '';
	if(count($result_traz)>0){
		foreach($result_traz as $res){
			$nomederivador = $res['us_nombre'];	
			$fechaderivacion	=	Util::formatFecha($res['tr_fecha']);
			$mailderivador	=	$res['us_email'];
		}
	}
	
	//$idresul = '2';
	$obj = new Visita(null);
	//$resulVis = $obj->entregaVisitasCasoEvaluado($idresul,$idcaso);
	$resulVis = $obj->entregaVisitasCasoEvaluadoPDF($idcaso);
	$obj->Close();
	$nomres	= '';
	$rut = '';
	$fechavis = '';
	$hora= '';
	$minuto ='';
	$fechanac = '';
	$fono	= '';
	$paren	= '';
	$edad_resp = '';
	$sugerencias = '';
	if(count($resulVis)>0){
		foreach($resulVis as $rvis){
			$nomres	= $rvis['vi_nombreresponsable'];
			$rut	= $rvis['vi_rutresponsable'];
			$fono	= $rvis['vi_telefono'];
			$paren	= $rvis['vi_parentezco'];
			$idresul= $rvis['rs_idresultado'];
			$sugerencias= $rvis['vi_sugerencias'];
			if($rvis['vi_fnacresponsable']!='0000-00-00'){
			$fechanac = Util::formatFecha($rvis['vi_fnacresponsable']);
			$edad_resp = Util::calculaEdad($rvis['vi_fnacresponsable']);
			}else{
			$fechanac = '';
			$edad_resp = '';
			}
			
			if($rvis['vi_fechainsercion']!='0000-00-00'){
			$fechavis = Util::formatFecha($rvis['vi_fechainsercion']);
			}else{
			$fechavis = '';
			}
			
			if($rvis['vi_fecha']!='0000-00-00'){
			$hora_vis = Util::formatFechaHora2($rvis['vi_fecha']);
			$hora_vis = explode(":", trim($hora_vis));
			$hora= $hora_vis[0];
			$minuto = $hora_vis[1];
			}else{
			$hora= '';
			$minuto ='';
			}
		}	
	}	
	
		
	$obj = new Evaluacion(null);
	$rs_evaluacion = $obj->entregaAssetEvaluacion($idcaso,1);
	$obj->Close();
	$infoadicional	=	'';
	$notahogar	=	'';
	$notarelacion	=	'';
	$notaeducacion		=	'';
	$notabarrio	=	'';
	$notaestilo	=	'';
	$notasustancias	=	'';
	$notasalud	=	'';
	$notasalud2	=	'';
	$notapercepcion	=	'';
	$notacomportamiento	=	'';
	$notaactitud	=	'';
	$notamotivacion	=	'';
	$estado = '';
	$opcion = 'insert';
	
	if(count($rs_evaluacion)>0){
		foreach( $rs_evaluacion as $res ){
			$infoadicional =	$res['ev_infoadicional'];
			$notahogar = $res['ev_notahogar'];
			$notarelacion = $res['ev_notarelacion'];
			$notaeducacion =	$res['ev_notaeducacion'];
			$notabarrio = $res['ev_notabarrio'];
			$notaestilo = $res['ev_notaestilo'];
			$notasustancias = $res['ev_notasustancias'];
			$notasalud = $res['ev_notasalud'];
			$notasalud2 = $res['ev_notasalud2'];
			$notapercepcion = $res['ev_notapercepcion'];
			$notacomportamiento = $res['ev_notacomportamiento'];
			$notaactitud = $res['ev_notaactitud'];
			$notamotivacion = $res['ev_notamotivacion'];
			$estado = $res['re_estado'];
			$opcion = 'update';
			
			$totalnotas = $notahogar+$notarelacion+$notaeducacion+$notabarrio+$notaestilo+$notasustancias+$notasalud+$notasalud2+$notapercepcion+$notacomportamiento+$notaactitud+$notamotivacion;
		}
	}
	
$derivacion = new Derivacion(null);
$salida = $derivacion->entregaDerivacion($idcaso);
$derivacion->Close();
$criteriosexclusion='';$fecderivacion='';$fecmst='';
if(count($salida)>0){
		foreach($salida as $res){
			
			$tipo = $res['de_tipo'];			
			if($tipo == 'Derivacion MST'){
				$obsmst = $res['de_observacion'];
				$fecingresoMST = Util::formatFecha($res['de_fingresoprograma']);
				$idinstitucionMST = $res['in_idinstitucion'];
			}
			$fecderivacion = Util::formatFecha($res['de_fingresoprograma']);			
			$fecmst = Util::formatFecha($res['de_fingresomst']);
			$opcriterio = $res['de_criterios'];
			$criterio = explode(",", $res['de_chkcriterios']);
			$institucion = $res['in_descripcion'];
			$motivoderivacion = $res['de_motivos'];
			
			if(count($criterio)>0){
				if(in_array("NNA independiente", $criterio))
                    $criteriosexclusion.='<tr><td align="justify">El niño, niña o adolescente vive de manera independiente o el cuidador principal u otra figura adulta no ha podido ser localizada pese a todos los esfuerzos.</td></tr>';
				if(in_array("NNA ideacion suicida", $criterio)) 
				$criteriosexclusion.='<tr><td align="justify">El niño, niña o adolescente se encuentra con ideación suicida y/o homicida activa. Ha tenido un intento suicida y/o homicida reciente.</td></tr>';
				
				if(in_array("NNA problema psiquiatrico", $criterio))
				$criteriosexclusion.='<tr><td align="justify">El niño, niña o adolescente presenta un problema psiquiátrico grave descompensado que constituye el principal problema a atender en una derivación.</td></tr>';
				
				if(in_array("NNA delito sexual", $criterio))
				$criteriosexclusion.='<tr><td align="justify">El niño, niña o adolescente ha cometido un delito sexual en ausencia de otro comportamiento delictual.</td></tr>';
				
				if(in_array("NNA retraso desarrollo", $criterio))
				$criteriosexclusion.='<tr><td align="justify">El niño, niña o adolescente presenta un retraso generalizado del desarrollo (trastorno autista, Asperger, Rett, entre otros).</td></tr>';
				
			}
		}			
}

$analisis = new Analisis(null);
$res_analisis = $analisis->entregaAssetAnalisis($idcaso,1);
$analisis->Close();
$chkmedidasnna = '';
	if(count($res_analisis)>0){
		foreach($res_analisis as $res){
			//$chkmedidasnna = explode(",", $res['an_chkmedidasnna']);
			$chkmedidasnna = $res['an_chkmedidasnna'];
			}
	}
	
	$navegador = Util::detectaNavegador();
	$cadena = md5('modcasos_'.$navegador['navegador'].''.$navegador['version'].''.$_SESSION['glorut']);

}
else{
	session_destroy();
	header('location: ../index.php');
}


/*************INICIO CLASE PDF*********************************/

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
		//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'../../../images/logo.png';
		$this->Image($image_file, 17, 10, 22, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-20);
		// Set font
		$this->SetFont('times', '', 10);
		//$footer = '';
		$footer = '
		<table align="center">
		<tr>
		<td align="right" width="100%">'.date('d').' de '.Util::entregaMesCompleto(date('m')).' de '.date('Y').'</td>
		</tr>	
		</table>
		';
		//firma
		$this->WriteHtmlCell(0, 0, '', '',$footer, 0, 1, 0, true, 'C');
//		$this->Cell(50, 50, 'FIRMA2 ', 0, false, 'R', 0, '', 0, false, 'C', 'C');
		
		//$this->SetY(-15);
		$this->SetY(-15);
		// Set font
		//$this->SetFont('times', '', 9);
		// Page number
		$this->Cell(188, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
/*$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 061');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');*/

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10, 35, 10);//PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 30);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
$pdf->SetFont('times','',11);
// set border width
$pdf->SetLineWidth(1);
$pdf->AddPage();
$pdf->Ln(-25);
$html ='
<table width="650" border="0" cellspacing="0" cellpadding="0"  bordercolor="#000000" background="">
<tr><td>		
	<table width="650" border="0" cellspacing="0" cellpadding="10"  bordercolor="#000000" background="">
	<tr>
	  <td >
		<table width="600"  border="0" align="center">
			<tr>
				<td align="center">
			    <b><h4>Formulario de Derivaci&oacute;n de Casos Equipo de Detecci&oacute;n'.'<br>'.'Temprana a Terapia Mutisist&eacute;mica</h4></b></td>
			  </tr>
			<tr>
				<td  align="left"><b>I.- Datos de Identificaci&oacute;n del Caso</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>	
			<tr>
				<td>
					<table width="600" border="1" height="400" align="center" cellpadding="1">
						<tr><td align="left" colspan="2"><b>Datos del Ni&ntilde;o, Ni&ntilde;a o Adolecente</b></td></tr>
						<tr><td align="left" width="30%">Nombre</td><td align="left"  width="70%">&nbsp;'.$nombre.' '.$paterno.' '.$materno.'</td></tr>
						<tr><td align="left">Fecha de nacimiento</td><td align="left">&nbsp;'.$fnacimiento.'</td></tr>
						<tr><td align="left">RUT</td><td align="left">&nbsp;'.Util::formateo_rut($rutnino).'</td></tr>
						<tr><td align="left">C&oacute;digo</td><td align="left">&nbsp;'.$codigo.'</td></tr>
						<tr><td align="left">Direcci&oacute;n</td><td align="left">&nbsp;'.$domicilio.', '.$numero.' '.$poblacion.', '.$nomcomuna.'</td></tr>
						<tr><td align="left">Escolaridad</td><td align="left">&nbsp;'.$educacion.'</td></tr>
						<tr><td align="left">Tipo delito PSI24</td><td align="left">&nbsp;'.$delito_unum.'</td></tr>
						<tr><td align="left">Medida o sansi&oacute;n RPA: ¿Cu&aacute;l,d&oacute;nde?</td><td align="left">&nbsp;'.$chkmedidasnna.'</td></tr>
						<tr><td align="left" colspan="2"><b>Datos del Adulto Responsable</b></td></tr>
						<tr><td align="left">Nombre</td><td align="left">&nbsp;'.$nomres.' ('.$paren.')</td></tr>
						<tr><td align="left">Edad</td><td align="left">&nbsp;'.$edad_resp.'</td></tr>
						<tr><td align="left">Direcci&oacute;n</td><td align="left">&nbsp;'.$domicilio.', '.$numero.', '.$nomcomuna.'</td></tr>
						<tr><td align="left">Dpto/ Población</td><td align="left">&nbsp;'.$poblacion.'</td></tr>
						<tr><td align="left">Tel&eacute;fono</td><td align="left">&nbsp;'.$fono.'</td></tr>
						<tr><td align="left" colspan="2"><b>Datos del profesional que deriva</b></td></tr>
						<tr><td align="left">Nombre</td><td align="left">&nbsp;'.$nomederivador.'</td></tr>
						<tr><td align="left">Datos de Contacto</td><td align="left">&nbsp;'.$mailderivador.'</td></tr>
						<tr><td align="left">Fecha Derivaci&oacute;n</td><td align="left">&nbsp;'.$fecderivacion.'</td></tr>
						<tr><td align="left" colspan="2"><b>Sugerencias para contactar familia</b></td></tr>
						<tr><td align="left">Horarios de trabajo, datos pr&aacute;cticos</td><td align="left">&nbsp;'.$sugerencias.'</td></tr>
					</table>
				</td>
			</tr>	
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td  align="left"><b>II.- Criterios de exclusi&oacute;n del Caso:</b><i>previa derivaci&oacute;n, se debe constatar que el caso NO PRESENTE ninguno de los siguientes criterios de exclusi&oacute;n.</i></td>				
			</tr>
			<tr>
				<td>
					<table width="600" border="1" align="center" cellpadding="2">
						<tr><td align="left"><b>Criterios de exclusi&oacute;n</b></td></tr>
						<tr><td align="left">'.$criteriosexclusion.'</td></tr>
					</table>
				</td>
			</tr>	
		</table>
	</td>
	</tr>
	
	</table>
</td></tr>
</table>				




<table width="650" border="0" cellspacing="0" cellpadding="0"  bordercolor="#000000" background="">
<tr><td>		
	<table width="650" border="0" cellspacing="0" cellpadding="10"  bordercolor="#000000" background="">
	<tr>
	  <td >
		<table width="600"  border="0" align="center">
			<tr>
				<td colspan="3" align="left" width="40%">III.- Resultado</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="300" border="1" align="center" style="background-color:#D8D8D8;" cellpadding="2">
						<tr><td align="center" width="80%">ESCALAS ASSET</td><td align="center">PUNTAJES</td></tr>
						<tr><td align="left" width="80%">Condiciones del hogar</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notahogar.'</td></tr>
						<tr><td align="left" width="80%">Rel. familiares y personales</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notarelacion.'</td></tr>
						<tr><td align="left" width="80%">Educ., capacitaci&oacute;n y empleo</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notaeducacion.'</td></tr>
						<tr><td align="left" width="80%">Contexto comunitario</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notabarrio.'</td></tr>
						<tr><td align="left" width="80%">Estilo de vida</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notaestilo.'</td></tr>
						<tr><td align="left" width="80%">Uso de sustancias</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notasustancias.'</td></tr>
						<tr><td align="left" width="80%">Salud f&iacute;sica</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notasalud.'</td></tr>
						<tr><td align="left" width="80%">Salud mental y emocional</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notasalud2.'</td></tr>
						<tr><td align="left" width="80%">Percep. de s&iacute; mismo y de otros</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notapercepcion.'</td></tr>
						<tr><td align="left" width="80%">Pensamiento y comportamiento</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notacomportamiento.'</td></tr>
						<tr><td align="left" width="80%">Act. hacia la comisi&oacute;n de delitos</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notaactitud.'</td></tr>
						<tr><td align="left" width="80%">Motivaci&oacute;n al cambio</td>
						<td align="center" style="background-color:#FFFFFF;">'.$notamotivacion.'</td></tr>
						<tr><td align="center" width="80%">PUNTAJE TOTAL</td><td align="center">'.$totalnotas.'</td></tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="left" width="40%">VI.- Conclusi&oacute;n</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="600" border="1" align="center" cellpadding="10">
						<tr style="line-height: 100%;">
							<td align="justify">'.nl2br($infoadicional).'</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr>
				<td width="100%" align="center"><br><br>________________________</td>
			</tr>	
			<tr>
				<td width="100%" align="center">'.$nomederivador.'</td>
			</tr>
		</table>
	</td>
	</tr>
	
	</table>
</td></tr>
</table>			
';

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->Ln(0);
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('FormularioDerivacionMSTCaso_'.$idcaso.'.pdf', 'I');

/*************************FIN CLASE PSF**************************/
}

}else{
	session_destroy();
	header('location: ../index.php');
}
?>
