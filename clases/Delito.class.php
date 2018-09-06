<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Delito{
	
	public $conn;

    function Delito($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de delitos
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla delito
 	 */
	public function muestraDelitosActivos()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaDelitosActivos()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>