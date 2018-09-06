<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Comuna{
	
	public $conn;

    function Comuna($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de comunas
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla comuna
 	 */
	public function muestraComunas()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaComunas()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	/**
 	 * Método que permite mostrar un listado de comunas de una región en particular
	 * Parámetros de entrada: id de la región
	 * Parámetros de salida: todos los datos de la tabla comuna
 	 */
	public function muestraComunasRegion($idregion)
	{
		$stmt = $this->conn->prepare("CALL SP_VistaComunasRegion(?)");
		$stmt->execute(array($idregion));
		return $stmt->fetchAll();		
		
	}
	/**
 	 * Método que entrega una comuna en particular
	 * Parámetros de entrada: id de la comuna
	 * Parámetros de salida: nombre de la comuna
 	 */
	public function entregaComuna($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaComuna(?)");
		$stmt->execute(array($id));

		if($row=$stmt->fetch()) {
			$salida=$row['co_descripcion'];
		}
		return $salida;
			
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>