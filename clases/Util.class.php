<?php
class Util
{
	public static function formatFecha($fecha)
	{
		$ano = substr($fecha,0, 4); // año
    	$g01 = substr($fecha,4, 1); // '-'
    	$mes = substr($fecha,5, 2); // mes
    	$g02 = substr($fecha,7, 1); // '-'
    	$dia = substr($fecha,8, 2); // dia
		if($dia>0 && $mes>0)
    	$fec = $dia.$g01.$mes.$g02.$ano;
		else
		$fec = "";
    	return $fec;
	}	
	
	public static function formatFecha2($fecha)
	{
		$ano = substr($fecha,6, 4); // año
    	$mes = substr($fecha,3, 2); // mes
    	$dia = substr($fecha,0, 2); // dia
    	$fec = $ano."".$mes."".$dia;
    	return $fec;
	}

	public static function formatFechaHora($fecha)
	{
		$ano = substr($fecha,0, 4); // año
    	$g01 = substr($fecha,4, 1); // '-'
    	$mes = substr($fecha,5, 2); // mes
    	$g02 = substr($fecha,7, 1); // '-'
    	$dia = substr($fecha,8, 2); // dia
		$hora= substr($fecha,10, 6); // dia
    	$fec = $dia.$g01.$mes.$g02.$ano."".$hora;
    	return $fec;
	}
	
	public static function formatFechaHora2($fecha)
	{
		$ano = substr($fecha,0, 4); // año
    	$g01 = substr($fecha,4, 1); // '-'
    	$mes = substr($fecha,5, 2); // mes
    	$g02 = substr($fecha,7, 1); // '-'
    	$dia = substr($fecha,8, 2); // dia
		$hora= substr($fecha,10, 6); // dia
    	$fec = $dia.$g01.$mes.$g02.$ano."".$hora;
    	return $hora;
	}
	
	public static function formatFechaSQL($fecha)
	{
		$ano = substr($fecha,0, 4); // año
    	$g01 = substr($fecha,4, 1); // '-'
    	$mes = substr($fecha,5, 2); // mes
    	$g02 = substr($fecha,7, 1); // '-'
    	$dia = substr($fecha,8, 2); // dia
    	$fec = $ano."".$mes."".$dia;
    	return $fec;
	}	
	
	public static function formatFechaSQL2($fecha)
	{
		$dia = substr($fecha,0, 2); // dia
    	$g01 = substr($fecha,2, 1); // '-'
    	$mes = substr($fecha,3, 2); // mes
    	$g02 = substr($fecha,5, 1); // '-'
    	$ano = substr($fecha,6, 4); // año
    	$fec = $ano."-".$mes."-".$dia;
    	return $fec;
	}
	
	public static function formatHora($fecha)
	{
		$hora= substr($fecha,11, 2); // dia
    	return $hora;
	}
	
	public static function formatMinuto($fecha)
	{
		$minuto= substr($fecha,14, 2); // dia
    	return $minuto;
	}	
	

	public static function conviertePorcentaje($num)
	{
		$salida="<b>".round(($num/100)*100,1)."</b>%";
		return $salida;	
	}
	
	public static function formateo_rut($rut_param){
    
    //validaciones varias
    //....
/*	if($rut_param != ''){
    $parte1 = substr($rut_param, 0,2); //12
    $parte2 = substr($rut_param, 2,3); //345
    $parte3 = substr($rut_param, 5,3); //456
    $parte4 = substr($rut_param, 8);   //todo despues del caracter 8 

    return $parte1.".".$parte2.".".$parte3."-".$parte4;}
	else
	return '-';*/
	
	if($rut_param != ''){
	
	return number_format( substr ( $rut_param, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut_param, strlen($rut_param) -1 , 1 );
	}
	else
	return '-';

	}
	
	public static function formatoNumerico($num)
	{
	$valor=number_format($num,0,"",".");
	return $valor;
	}
	
	public static function entregaMes($id)
	{
		if($id==1)
		$mes="Ene";
		else if($id==2)
		$mes="Feb";
		else if($id==3)
		$mes="Mar";
		else if($id==4)
		$mes="Abr";
		else if($id==5)
		$mes="May";
		else if($id==6)
		$mes="Jun";
		else if($id==7)
		$mes="Jul";
		else if($id==8)
		$mes="Ago";
		else if($id==9)
		$mes="Sep";
		else if($id==10)
		$mes="Oct";
		else if($id==11)
		$mes="Nov";
		else if($id==12)
		$mes="Dic";
		
		return $mes;
	}
	
	public static function entregaMesCompleto($id)
	{
		if($id==1)
		$mes="Enero";
		else if($id==2)
		$mes="Febrero";
		else if($id==3)
		$mes="Marzo";
		else if($id==4)
		$mes="Abril";
		else if($id==5)
		$mes="Mayo";
		else if($id==6)
		$mes="Junio";
		else if($id==7)
		$mes="Julio";
		else if($id==8)
		$mes="Agosto";
		else if($id==9)
		$mes="Septiembre";
		else if($id==10)
		$mes="Octubre";
		else if($id==11)
		$mes="Noviembre";
		else if($id==12)
		$mes="Diciembre";
		
		return $mes;
	}
	
	public static function extensionArchivo($filename){
    	return substr(strrchr($filename, '.'), 1);
	}
	
	public static function generaToken($form){
		$token = md5(uniqid(microtime(), true));
		$token_time = time();
		$_SESSION['csrf'][$form.'_token'] = array('token'=>$token, 'time'=>$token_time);
		return $token;
	}
	
	public static function verificaToken($form, $token, $tiempo=0) {
 		//verifica token
		if(!isset($_SESSION['csrf'][$form.'_token'])) {
       		return false;
   		}
 		//compara token
		if ($_SESSION['csrf'][$form.'_token']['token'] !== $token) {
       		return false;
   		}

   		if($tiempo > 0){
			$tiempo = ($tiempo * 144); 
       		$token_age = time() - $_SESSION['csrf'][$form.'_token']['time'];
       		if($token_age >= $tiempo){
      			return false;
       		}
   		}
 		return true;
	}
	
	public static function detectaNavegador() {
		$navegador=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
		$sisoperativo=array("WIN","MAC","LINUX");

		$info['navegador'] = "N/A";
		$info['sisoperativo']= "N/A";

		//búsqueda del navegador
		foreach($navegador as $parent){
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
			$version = preg_replace('/[^0-9,.]/','',$version);
			if ($s){
				$info['navegador'] = $parent;
				$info['version'] = $version;
			}
		}
		//búsqueda del S.O.
		foreach($sisoperativo as $val){
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
				$info['sisoperativo'] = $val;
		}

		return $info;
	}
	
	public static function validaRUT($rut)
	{
		if(strpos($rut,"-")==false)
		{
			$RUT[0] = substr($rut, 0, -1);
			$RUT[1] = substr($rut, -1);
		}
		else
		{
			$RUT = explode("-", trim($rut));
		}
		
		$elRut = str_replace(".", "", trim($RUT[0]));
		$factor = 2;
		$suma = 0;
		
		for($i = strlen($elRut)-1; $i >= 0; $i--):
			$factor = $factor > 7 ? 2 : $factor;
			$suma += $elRut{$i}*$factor++;
		endfor;
		
		$dv = 11 - ($suma % 11); 
		if($dv == 11)
		{
			$dv=0;
		}
		else if($dv == 10)
		{
			$dv="k";
		}
		
		if($dv == trim(strtolower($RUT[1])))
		{
			return 'SI';
		}
		else
		{
			return 'NO';
		}
	}
	
	public static function calculaEdad($fecha)
	{	
		if($fecha!=''){
		list($Y,$m,$d) = explode("-",$fecha);
		return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
		}else{
		return '';	
		}
	}
	
	public static  function agregarCero($valor){
	 $res = str_pad($valor, 1, '0', STR_PAD_LEFT);
	 return $res;
	}
}
?>