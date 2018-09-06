<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Derivacion{
	
	public	$conn;
	private	$res;

    function Derivacion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla derivacion
	 * Parámetros de entrada: código del caso
	 * Parámetros de salida: todos los datos de tabla derivacion
 	 */
	public function entregaDerivacion($codigo){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaDerivacion(?)");
		$stmt->execute(array($codigo));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que permite consultar por la ultima fecha de evaluacion del caso
	 * Parámetros de entrada: id del caso
 	 */
	public function validaFechaDerivacion($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaDerivacion(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}
	
		/**
 	 * Método que permite consultar por la ultima fecha de evaluacion del caso
	 * Parámetros de entrada: id del caso
 	 */
	public function validaFechaReevaluacionTermino($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaReevaluacionTermino(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}
	/**
 	 * Método que inserta un registro en la tabla derivacion.
	 * Parámetros de entrada: caso, tipo derivacion, observacion, fecha derivacion, fecha ingreso al programa, fecha ingreso mst, criterios, 
	 						  opcion criterios, institución, motivo
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaDerivacion($caso,$tipo,$obs,$fderivacion,$fingresoprograma,$fingresomst,$criterios,$opcriterios,$institucion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaDerivacion(?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($caso,$tipo,$obs,$fderivacion,$fingresoprograma,$fingresomst,$criterios,$opcriterios,$institucion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que modifica un registro en la tabla derivacion.
	 * Parámetros de entrada: caso, tipo derivacion, observacion, fecha derivacion, fecha ingreso al programa, fecha ingreso mst, criterios, 
	 						  opcion criterios, institución, motivo
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaDerivacion($caso,$tipo,$obs,$fderivacion,$fingresoprograma,$fingresomst,$criterios,$opcriterios,$institucion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaDerivacion(?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($caso,$tipo,$obs,$fderivacion,$fingresoprograma,$fingresomst,$criterios,$opcriterios,$institucion));
		return $stmt->rowCount();//rowCount
	}
	
	/**
 	 * Método que modifica la etapa de un caso
	 * Parámetros de entrada: codigo, etapa
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaCasoEtapa($cod,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCasoEtapa(?,?)");
		$stmt->execute(array($cod,$etapa));
		return $stmt->rowCount();//rowCount
			
	}
	public function modificaCasoEtapa2($cod,$etapa){
	
		$stmt = $this->conn->prepare("UPDATE caso c SET c.ca_etapa = '".$etapa."' WHERE c.ca_idcaso = '".$cod."'");
		$stmt->execute(array($cod,$etapa));
		return $stmt->rowCount();//rowCount
			
	}
	
	/**
 	 * Método que modifica la etapa a reevaluacion
	 * Parámetros de entrada: etapa, codigo
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaDerivacionReevaluacion($etapa,$cod){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaDerivacionReevaluacion(?,?)");
		$stmt->execute(array($etapa,$cod));
		return $stmt->rowCount();
			
	}
	
	public function agregaRevisionDerivacionReevaluacion($cod,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_agregaRevisionDerivacionReevaluacion(?,?)");
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