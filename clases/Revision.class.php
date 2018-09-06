<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Revision{
	
	public	$conn;
	private	$res;

    function Revision($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla revision de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipo de evaluación
	 * Parámetros de salida: todos los datos de tabla revision
 	 */
	public function entregaAssetRevision($codigo,$idevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetRevision(?,?)");
		$stmt->execute(array($codigo,$idevaluacion));
		return $stmt->fetchAll();
			
	}
	
	/**
 	 * Método que permite consultar por los datos de la tabla comentario de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipo de evaluación
	 * Parámetros de salida: todos los datos de tabla comentario
 	 */
	public function entregaAssetComentario($codigo,$idevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetComentario(?,?)");
		$stmt->execute(array($codigo,$idevaluacion));
		return $stmt->fetchAll();
			
	}
	
	/**
 	 * Método que modifica los datos de la tabla revision
	 * Parámetros de entrada: id evaluacion, id caso, estado
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaAssetRevision($id,$caso,$estado){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetRevision(?,?,?)");
		$stmt->execute(array($id,$caso,$estado));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que modifica los datos de la tabla comentario
	 * Parámetros de entrada: id evaluacion, id caso, rut usuario, comentario, estado
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetComentario($id,$caso,$rut,$comentario,$estado){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetComentario(?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$rut,$comentario,$estado));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que modifica la etapa de un caso
	 * Parámetros de entrada: codigo, etapa
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaCasoEtapa($cod,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCasoEtapa(?,?)");
		$stmt->execute(array($cod,$etapa));
		return $stmt->rowCount();
			
	}
	
	function Begin() {
    	$this->conn->beginTransaction();
    }
	
	function Commit() {
    	$this->conn->commit();
    }
	
	function Rollback() {
    	$this->conn->rollback();
    }
	
	public function Close(){
		
    	$this->conn = null;
		
	}
	
}
?>