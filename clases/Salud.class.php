<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Salud{
	
	public	$conn;
	private	$res;

    function Salud($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla salud de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla salud
 	 */
	public function entregaAssetSalud($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetSalud(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla salud.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetSalud($id,$caso,$condiciones,$inmadurez,$acceso,$riesgo,$otro,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetSalud(?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$condiciones,$inmadurez,$acceso,$riesgo,$otro,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla salud
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetSalud($id,$caso,$condiciones,$inmadurez,$acceso,$riesgo,$otro,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetSalud(?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$condiciones,$inmadurez,$acceso,$riesgo,$otro,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un salud
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionSalud($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionSalud(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>