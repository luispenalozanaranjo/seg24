<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Estilo{
	
	public	$conn;
	private	$res;

    function Estilo($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla estilo de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla estilo
 	 */
	public function entregaAssetEstilo($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetEstilo(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla estilo.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetEstilo($id,$caso,$faltaamistad,$actividadriesgo,$asocpredominante,$dineroinsuficiente,
									  $faltaasociacion,$tiempolibre,$otro,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetEstilo(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$faltaamistad,$actividadriesgo,$asocpredominante,$dineroinsuficiente,
						     $faltaasociacion,$tiempolibre,$otro,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla estilo
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetEstilo($id,$caso,$faltaamistad,$actividadriesgo,$asocpredominante,$dineroinsuficiente,
									    $faltaasociacion,$tiempolibre,$otro,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetEstilo(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$faltaamistad,$actividadriesgo,$asocpredominante,$dineroinsuficiente,
							 $faltaasociacion,$tiempolibre,$otro,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un estilo
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionEstilo($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionEstilo(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>