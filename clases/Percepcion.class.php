<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Percepcion{
	
	public	$conn;
	private	$res;

    function Percepcion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla percepcion de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla percepcion
 	 */
	public function entregaAssetPercepcion($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetPercepcion(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla percepcion.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetPercepcion($id,$caso,$identidad,$autoestima,$desconfianza,$discriminado,$discriminador,$criminal,
										  $evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetPercepcion(?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$identidad,$autoestima,$desconfianza,$discriminado,$discriminador,$criminal,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla percepcion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetPercepcion($id,$caso,$identidad,$autoestima,$desconfianza,$discriminado,$discriminador,$criminal,
										    $evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetPercepcion(?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$identidad,$autoestima,$desconfianza,$discriminado,$discriminador,$criminal,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un percepcion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionPercepcion($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionPercepcion(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>