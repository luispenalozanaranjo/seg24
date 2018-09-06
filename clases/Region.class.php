<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

/**
 * Clase asociada a la tabla region.
 */
class Region{
	
	public $conn;

    function Region($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de regiones
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla region
 	 */
	public function muestraRegiones()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaRegiones()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	/**
 	 * Método que entrega una region en particular
	 * Parámetros de entrada: rut de la región
	 * Parámetros de salida: nombre de la región
 	 */
	public function entregaRegion($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaRegion(?)");
		$stmt->execute(array($id));

		if($row=$stmt->fetch()) {
			$salida=$row['re_descripcion'];
		}
		echo $salida;
			
	}
	
	public function entregaRegionComuna($id){
		
		$salida='';
		$stmt = $this->conn->prepare("CALL SP_EntregaRegionComuna(?)");
		$stmt->execute(array($id));

		if($row=$stmt->fetch()) {
			$salida=$row['re_idregion'];
		}
		return $salida;
			
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>