<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Barrio{
	
	public	$conn;
	private	$res;

    function Barrio($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla barrio de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla barrio
 	 */
	public function entregaAssetBarrio($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetBarrio(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla barrio.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetBarrio($id,$caso,$evidenciatrafico,$tensionetnica,$localidadaislada,$faltainstalaciones,
									  $otro,$evidencia,$calificacion,$descripcion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetBarrio(?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$evidenciatrafico,$tensionetnica,$localidadaislada,$faltainstalaciones,
						     $otro,$evidencia,$calificacion,$descripcion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla barrio
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetBarrio($id,$caso,$evidenciatrafico,$tensionetnica,$localidadaislada,$faltainstalaciones,
									    $otro,$evidencia,$calificacion,$descripcion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetBarrio(?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$evidenciatrafico,$tensionetnica,$localidadaislada,$faltainstalaciones,
							 $otro,$evidencia,$calificacion,$descripcion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un barrio
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionBarrio($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionBarrio(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>