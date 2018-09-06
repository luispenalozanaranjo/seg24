<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Comportamiento{
	
	public	$conn;
	private	$res;

    function Comportamiento($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla comportamiento de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla Comportamiento
 	 */
	public function entregaAssetComportamiento($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetComportamiento(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla comportamiento.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetComportamiento($id,$caso,$faltacomprension,$impulsividad,$emociones,$faltaasertividad,$temperamental,
											  $habilidades,$propiedad,$sexual,$agresion,$manipulacion,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetComportamiento(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$faltacomprension,$impulsividad,$emociones,$faltaasertividad,$temperamental,
							 $habilidades,$propiedad,$sexual,$agresion,$manipulacion,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla comportamiento
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetComportamiento($id,$caso,$faltacomprension,$impulsividad,$emociones,$faltaasertividad,$temperamental,
											    $habilidades,$propiedad,$sexual,$agresion,$manipulacion,$evidencia,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetComportamiento(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$faltacomprension,$impulsividad,$emociones,$faltaasertividad,$temperamental,
							 $habilidades,$propiedad,$sexual,$agresion,$manipulacion,$evidencia,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un comportamiento
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionComportamiento($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionComportamiento(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>