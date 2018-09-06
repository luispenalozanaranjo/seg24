<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Motivacion{
	
	public	$conn;
	private	$res;

    function Motivacion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla motivacion de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla motivacion
 	 */
	public function entregaAssetMotivacion($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetMotivacion(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla motivacion.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetMotivacion($id,$caso,$comprendecomportamiento,$resolverproblemas,$comprendeconsecuencias,$identificaincentivos,
										  $muestraevidencia,$apoyofamiliar,$cooperacion,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetMotivacion(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$comprendecomportamiento,$resolverproblemas,$comprendeconsecuencias,$identificaincentivos,
							 $muestraevidencia,$apoyofamiliar,$cooperacion,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla motivacion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetMotivacion($id,$caso,$comprendecomportamiento,$resolverproblemas,$comprendeconsecuencias,$identificaincentivos,
										    $muestraevidencia,$apoyofamiliar,$cooperacion,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetMotivacion(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$comprendecomportamiento,$resolverproblemas,$comprendeconsecuencias,$identificaincentivos,
							 $muestraevidencia,$apoyofamiliar,$cooperacion,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un motivacion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionMotivacion($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionMotivacion(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>