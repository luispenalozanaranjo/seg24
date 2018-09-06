<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Actitud{
	
	public	$conn;
	private	$res;

    function Actitud($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla actitud de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla actitud
 	 */
	public function entregaAssetActitud($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetActitud(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla actitud.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetActitud($id,$caso,$negacion,$reticente,$comprensionvictima,$faltaremordimiento,$comprensionimpacto,
									   $infraccionaceptable,$objetivoaceptable,$infraccioninevitable,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetActitud(?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$negacion,$reticente,$comprensionvictima,$faltaremordimiento,$comprensionimpacto,
						     $infraccionaceptable,$objetivoaceptable,$infraccioninevitable,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla actitud
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetActitud($id,$caso,$negacion,$reticente,$comprensionvictima,$faltaremordimiento,$comprensionimpacto,
									     $infraccionaceptable,$objetivoaceptable,$infraccioninevitable,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetActitud(?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$negacion,$reticente,$comprensionvictima,$faltaremordimiento,$comprensionimpacto,
							 $infraccionaceptable,$objetivoaceptable,$infraccioninevitable,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un actitud
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionActitud($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionActitud(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>