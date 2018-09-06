<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Evaluacion{
	
	public	$conn;
	private	$res;

    function Evaluacion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla evaluacion de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipo de evaluación
	 * Parámetros de salida: todos los datos de tabla evaluacion
 	 */
	public function entregaAssetEvaluacion($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetEvaluacion(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}
	
			/**
 	 * Método que agrega en tabla el comentario final
	 * Parámetros de entrada: id evaluacion, id caso, rut usuario, comentario
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetComentarioEvaluacion($caso,$rut){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetComentarioEvaluacion(?,?)");
		$stmt->execute(array($caso,$rut));
		return $stmt->rowCount();
	}
	
				/**
 	 * Método que agrega en tabla el comentario final
	 * Parámetros de entrada: id evaluacion, id caso, rut usuario, comentario
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function notaTotalEvaluacion($caso){
	
		$stmt = $this->conn->prepare("CALL SP_NotaTotalEvaluacion(?)");
		$stmt->execute(array($caso));
		return $stmt->fetchAll();
	}
	
	
	/**
 	 * Método que modifica los datos de la tabla evaluacion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetEvaluacion($codigo,$tipevaluacion,$infoadicional){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetEvaluacion(?,?,?)");
		$stmt->execute(array($codigo,$tipevaluacion,$infoadicional));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que cambia el estado de la revisión (tabla revision)
	 * Parámetros de entrada: id caso, id evaluación, estado
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaEstadoRevision($codigo,$tipevaluacion,$estado){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaEstadoRevision(?,?,?)");
		$stmt->execute(array($codigo,$tipevaluacion,$estado));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que muestra los estados de la revision de cada escala del Asset en Evaluacion y/o Reevaluacion
	 * Parámetros de entrada: id caso, id etapa
	 * Parámetros de salida: 30 n° filas afectadas
 	 */
	public function validaArgumentosEscalasAsset($idcaso,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaArgumentosEscalasAsset(?,?)");
		$stmt->execute(array($idcaso,$etapa));
		return $stmt->fetchAll();
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