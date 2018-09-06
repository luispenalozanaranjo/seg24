<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Nacionalidad{
	
	public $conn;

    function Nacionalidad($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de vías de ingreso
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla vías de ingreso
 	 */
	public function muestraNacionalidad()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaNacionalidad()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>