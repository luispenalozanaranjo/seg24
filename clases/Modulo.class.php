<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Modulo{
	
	public	$conn;
	private	$res;
	private	$nombre;
	private	$estado;

    function Modulo($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que muestra todos los registros de la tabla Modulo con estado activo.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla Modulo
 	 */
	public function muestraModulosActivos(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaModulosActivos()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>
