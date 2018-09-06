<?php 
session_start();
// content="text/plain; charset=utf-8"
// $Id: horizbarex4.php,v 1.4 2002/11/17 23:59:27 aditus Exp $
require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_bar.php');
 
$datay=array(0,0,0,1,2,0,0,0,2,1,1,0);
 
// Size of graph
$width=500;
$height=500;
 
// Set the basic parameters of the graph
$graph = new Graph($width,$height);
$graph->SetScale('textlin');
 
$top = 60;
$bottom = 100;
$left = 80;
$right = 30;
$graph->Set90AndMargin($left,$right,$top,$bottom);
 
// Nice shadow
$graph->SetShadow();
 
// Setup labels
$lbl = array("Motivacion al cambio","Act. hacia la comision de delitos","Pensamiento y comportamiento","Percep. de si mismo y de otros","Salud mental y emocional","Salud fisica","Uso de sustancias","Estilo de vida","Contexto comunitario","Educ., capacitacion y empleo","Rel. familiares y personales","Condiciones del hogar");
$graph->xaxis->SetTickLabels($lbl);
 
// Label align for X-axis
$graph->xaxis->SetLabelAlign('right','center','right');
 
// Label align for Y-axis
$graph->yaxis->SetLabelAlign('center','bottom');
 
// Titles
$graph->title->Set('Perfil de Riesgo');
 
// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor('orange');
$bplot->SetWidth(0.5);
$bplot->SetYMin(4);
 
$graph->Add($bplot);

 
		// Output the chart
		$uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/archivos/';
		if ( !file_exists($uploads_dir) ){
			mkdir($uploads_dir);	
			chmod($uploads_dir, 0777);
		}
		else{
			chmod($uploads_dir, 0777);
		}
		
//$graph->Stroke($uploads_dir.'/'.$_SESSION['idcaso'].'.png');
$graph->Stroke();
?>