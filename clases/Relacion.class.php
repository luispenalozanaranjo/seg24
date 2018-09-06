<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Relacion{
	
	public	$conn;
	private	$res;

    function Relacion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla relacion de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipo de evaluación
	 * Parámetros de salida: todos los datos de tabla relacion
 	 */
	public function entregaAssetRelacion($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetRelacion(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla relacion.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetRelacion($id,$caso,$involucrado,$experiencia,$alcohol,$testigo,$drogas,$duelo,$comunicacion,$cuidado,$supervision,
										$otros,$evidencia,$calificacion,$chkmiembros){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetRelacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$involucrado,$experiencia,$alcohol,$testigo,$drogas,$duelo,$comunicacion,$cuidado,$supervision,
							 $otros,$evidencia,$calificacion,$chkmiembros));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla relacion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetRelacion($id,$caso,$involucrado,$experiencia,$alcohol,$testigo,$drogas,$duelo,$comunicacion,$cuidado,$supervision,
										  $otros,$evidencia,$calificacion,$chkmiembros){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetRelacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$involucrado,$experiencia,$alcohol,$testigo,$drogas,$duelo,$comunicacion,$cuidado,$supervision,
							 $otros,$evidencia,$calificacion,$chkmiembros));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que actualiza la calificación en la tabla evaluacion
	 * Parámetros de entrada: id evaluación, id caso, nota
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaCalificacionRelacion($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionRelacion(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>