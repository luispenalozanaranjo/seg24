<!--<script>
$(document).ready(function(){
	
});
</script>
<script>
    $(function() {
        $('.ms1').multipleSelect({
            width: '34%'
        });

    });
</script>
-->
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/clases/Comuna.class.php');
$comuna = new Comuna(null);
$comunas = $comuna->muestraComunas(); //$comuna->muestraComunasRegion($_POST['id_region']);
$comuna->Close();

if(isset($_POST['rut'])){
	require_once('../clases/Usuario.class.php');
	$obj = new Usuario(null);
	$resul = $obj->entregaUsuarioHasComuna($_POST['rut']);
	if(count($resul)>0){
			foreach($resul as $resc){				
				$cmbcomunas[]=$resc['co_idcomuna'];
			}

	}else{
		$cmbcomunas[]='';
	}
	$obj->Close();

}else
$cmbcomunas[]='';

//class="ms1" size="8"   style="width:35%"
$html = '<select name="comuna[]" id="comuna"  size="8"   style="width:35%; height:40%;" multiple >';

//if(isset($_POST['id_region']))
//$html .= '<option value="">Seleccione...</option>';



foreach( $comunas as $com ){
		/*if( $com['co_idcomuna'] == $_POST['id_comuna'] )
		*/
		
		if (in_array($com['co_idcomuna'], $cmbcomunas))
		$sel = "selected";
		else
		$sel ="";
		
		/*
		if( $com['co_idcomuna'] == 1101 ||  $com['co_idcomuna'] == 1102)
		$sel = "selected";
		else 
		$sel ="";
		*/
		
        $html .= '<option value="'.$com['co_idcomuna'].'" '.$sel.'>'.$com['co_descripcion'].'</option>';
}




$html.= '</select>';
//$html.= print_r($cmbcomunas);
echo $html;
?>
