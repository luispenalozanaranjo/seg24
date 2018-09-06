<?php
session_start();

require_once('../js/tcpdf/config/lang/eng.php');
require_once('../js/tcpdf/tcpdf.php');
	
$_GET['id'] = 32;	
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
require_once('../clases/Analisis.class.php');
require_once('../clases/Evaluacion.class.php');

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
		$rut	=	$res['ci_rut'];
		$idciudadano = $res['ci_idciudadano'];
		$nombre	=	$res['ci_nombre'];
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
	$result_traz = $trazabilidad->entregaTrazabilidad('Evaluacion',$idcaso);
	$trazabilidad->Close();
	$nomevaluador ='';
	$fechaevaluacion ='';
	if(count($result_traz)>0){
		foreach($result_traz as $res){
			$nomevaluador = $res['us_nombre'];	
			$fechaevaluacion	=	Util::formatFecha($res['tr_fecha']);
		}
	}
	
	$analisis = new Analisis(null);
	$res_analisis = $analisis->entregaAssetAnalisis($idcaso,1);
	$analisis->Close();
	$chkevaluacion = '';
	$chkmedidasnna = '';
	$chkdetalleotro = '';
	$nna = 'no';$opm = 'no';$pvn = 'no';$far = 'no';$ongs = 'no';$prpa = 'no';$escu = 'no';$p24h = 'no';$sersa = 'no';$tdf = 'no';$otra = 'no';
	if(count($res_analisis)>0){
		foreach($res_analisis as $res){
			$chkevaluacion = explode(",", $res['an_chkevaluacion']);
			$chkmedidasnna = explode(",", $res['an_chkmedidasnna']);
			$chkdetalleotro = $res['an_chkdetalleotro'];
			
			if($chkevaluacion!=''){
				if(in_array("NNA evaluado", $chkevaluacion)) $nna = 'si';
				if(in_array("Otros programas municipales", $chkevaluacion)) $opm = 'si';
				if(in_array("Profesional Vida Nueva", $chkevaluacion)) $pvn = 'si';
				if(in_array("Familiar o adulto responsable", $chkevaluacion)) $far = 'si';
				if(in_array("ONGs", $chkevaluacion)) $ongs = 'si';
				if(in_array("Profesional RPA", $chkevaluacion)) $prpa = 'si';
				if(in_array("Escuela", $chkevaluacion)) $escu = 'si';
				if(in_array("PSI 24 horas", $chkevaluacion)) $p24h = 'si';
				if(in_array("Servicio de salud", $chkevaluacion)) $sersa = 'si';
				if(in_array("Tribunales de familia", $chkevaluacion)) $tdf = 'si';
				if(in_array("Otra", $chkevaluacion)) $otra = 'si';
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
		$this->SetY(15);
		// Set font
		$this->SetFont('times', '', 10);
		
		$footer = '';
		/*$footer = '
		<table align="center">
		<tr>
			<td width="30%" align="center">________________________</td>
			<td width="40%">&nbsp;</td>
			<td width="30%" align="center">________________________</td>
		</tr>	
		<tr>
			<td align="center">Entregado por<br>'.$_SESSION['nomentrega'].'</td>
			<td>&nbsp;</td>
			<td align="center">Recibido por<br>'.$_SESSION['nomrecibe'].'</td>
		</tr>
		</table>
		';*/
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
$pdf->SetMargins(10, 10, 10);//PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
$pdf->SetFont('times','',11);
$pdf->AddPage();
$pdf->Ln(0);
$html ='
<table width="650" border="0" cellspacing="0" cellpadding="0"  bordercolor="#000000" background="">
<tr><td>		
	<table width="650" border="0" cellspacing="0" cellpadding="10"  bordercolor="#000000" background="">
	<tr><td >
		<table width="600"  border="0" align="center">
			<tr>
				<td align="center" colspan="3">
				<b><h4>Informe de Evaluación de Riesgo Delictual</h4></b>
				</td>
			</tr>
			<tr>
				<td align="left" width="40%">I.- Identificaci&oacute;n</td>
				<td align="right" width="40%">C&oacute;digo interno:</td>
				<td align="left" >&nbsp;<b>'.$codigo.'</b></td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="600" border="0" align="center">
						<tr><td align="left">Nombre</td><td width="40">:</td><td align="left">'.$nombre.' '.$paterno.' '.$materno.'</td></tr>
						<tr><td align="left">Fecha de nacimiento</td><td>:</td><td align="left">'.$fnacimiento.'</td></tr>
						<tr><td align="left">Edad</td><td>:</td><td align="left">'.$edad.'</td></tr>
						<tr><td align="left">Escolaridad</td><td>:</td><td align="left">'.$educacion.'</td></tr>
						<tr><td align="left">Domicilio</td><td>:</td><td align="left">'.$domicilio.', '.$numero.'</td></tr>
						<tr><td align="left">Comuna</td><td>:</td><td align="left">'.$nomcomuna.'</td></tr>
						<tr><td align="left">Evaluado por</td><td>:</td><td align="left">'.$nomevaluador.'</td></tr>
						<tr><td align="left">Fecha Evaluacion</td><td>:</td><td align="left">'.$fechaevaluacion.'</td></tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="left" width="40%">II.- Motivo de evaluaci&oacute;n</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="600" border="1" align="center" cellpadding="10">
						<tr>
							<td align="justify">Caso ingresa con fecha '.$fdenuncia.' desde '.$via_descripcion.'. El objeto de la evaluación es determinar la presencia de factores de riesgo delictual y sugerir derivación a servicios de intervención psicosocial pertinentes.'.$motivo.'</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="left" width="40%">III.- Metodolog&iacute;a de evaluaci&oacute;n</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="600" border="1" align="center" cellpadding="10">
						<tr>
							<td align="justify">La evaluación de factores de riesgo fue realizada mediante la aplicación del protocolo ASSET adaptado. Las fuentes de información que se consultaron para completar el protocolo se indican a continuación.</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="left" width="40%">IV.- Fuentes de informacion</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td colspan="3">
					<table width="600" border="0" align="center">
						<tr><td align="right" width="35%">NNA evaluado</td>
						<td align="center"></td></tr>
						<tr><td align="right">Otros programas municipales</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$opm.'.png"  width="16"></td></tr>
						<tr><td align="right">Profesional 24 Horas</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$pvn.'.png"  width="16"></td></tr>
						<tr><td align="right">Familiar o adulto responsable</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$far.'.png"  width="16"></td></tr>
						<tr><td align="right">ONGs</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$ongs.'.png"  width="16"></td></tr>
						<tr><td align="right">Profesional RPA</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$prpa.'.png"  width="16"></td></tr>
						<tr><td align="right">Escuela</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$escu.'.png"  width="16"></td></tr>
						<tr><td align="right">PSI 24 horas</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$p24h.'.png"  width="16"></td></tr>
						<tr><td align="right">Servicio de salud</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$sersa.'.png"  width="16"></td></tr>
						<tr><td align="right">Tribunales de familia</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$tdf.'.png"  width="16"></td></tr>
						';
						if($chkdetalleotro!=''){
						$html.='
						<tr><td align="right">'.$chkdetalleotro.'</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_si.png"  width="16"></td></tr>
						';	
						}
						
$html.='				<tr><td align="right">Otra</td>
						<td align="left">&nbsp;&nbsp;<img src="../images/checkbox_'.$otra.'.png"  width="16"></td></tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			
		</table>
	</td>
	</tr>
	
	</table>
</td></tr>
</table>	
';

$pdf->writeHTML($html, true, false, false, false, '');
// reset pointer to the last page
$pdf->lastPage();
$pdf->SetFont('times','',11);
$pdf->AddPage();
$pdf->Ln(10);
$html ='
<table width="650" border="0" cellspacing="0" cellpadding="0"  bordercolor="#000000" background="">
<tr><td>		
	<table width="650" border="0" cellspacing="0" cellpadding="10"  bordercolor="#000000" background="">
	<tr><td >
		<table width="600"  border="0" align="center">
			
			<tr>
				<td colspan="3" align="left" width="40%">V.- Resultado</td>
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
						<tr>
							<td align="justify">'.$infoadicional.'</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td width="100%" align="center"><br><br><br><br><br><br><br><br>________________________</td>
			</tr>	
			<tr>
				<td width="100%" align="center">'.$nomevaluador.'</td>
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
$pdf->Output('FormularioDerivacionCaso_'.$idcaso.'.pdf', 'I');

/*************************FIN CLASE PSF**************************/
}

}else{
	session_destroy();
	header('location: ../index.php');
}
?>