<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/clases/Comuna.class.php');
$comuna = new Comuna(null);
$comunas = $comuna->muestraComunasRegion($_POST['id_region']);
$comuna->Close();

$html = "";
foreach( $comunas as $com ){
		if( $com['co_idcomuna'] == $_POST['comuna'] )
		$sel = "selected";
		else 
		$sel ="";
        $html .= '<option value="'.$com['co_idcomuna'].'" '.$sel.'>'.$com['co_descripcion'].'</option>';
}
echo $html;
?>
