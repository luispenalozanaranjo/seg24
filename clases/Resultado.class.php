<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Resultado{
	
	public $conn;

    function Resultado($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de resultados
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla resultado
 	 */
	public function muestraResultadosActivos()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaResultadosActivos()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>