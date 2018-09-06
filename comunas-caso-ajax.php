<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/clases/Comuna.class.php');
$comuna = new Comuna(null);
$comunas = $comuna->muestraComunasRegion($_POST['id_region']);
$comuna->Close();

$html = "";
$html .= '<option value="">Seleccione...</option>';
foreach( $comunas as $com ){
		if( $com['co_idcomuna'] == $_POST['id_comuna'] )
		$sel = "selected";
		else 
		$sel ="";
        $html .= '<option value="'.$com['co_idcomuna'].'~'.$com['co_sigla'].'" '.$sel.'>'.$com['co_descripcion'].'</option>';
}
echo $html;
?>
