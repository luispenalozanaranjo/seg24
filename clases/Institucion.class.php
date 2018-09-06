<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Institucion{
	
	public	$conn;
	private	$res;

    function Institucion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que muestra todos los registros de la tabla institucion con estado activo.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla institucion
 	 */
	public function muestraInstitucionesActivas(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaInstitucionesActivas()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
		/**
 	 * Método que muestra todos los registros de la tabla institucion con estado activo en otro programa de baja intensidad.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla institucion
 	 */
	
		public function muestraInstitucionesActivasBajaIntensidad(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaInstitucionesActivasProgramaBajaIntensidad()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	public function muestraInstitucionesActivasBajaIntensidad1(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaInstitucionesActivasProgramaBajaIntensidad1()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	
		
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>