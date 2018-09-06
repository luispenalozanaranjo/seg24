<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class SaludMental{
	
	public	$conn;
	private	$res;

    function SaludMental($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla salud_mental de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla salud_mental
 	 */
	public function entregaAssetSaludMental($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetSaludMental(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla salud_mental.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetSaludMental($id,$caso,$acontecimientos,$circunstancias,$preocupaciones,$evidencia1,$diagnostico,$derivacion,
										   $evidencia2,$afectado,$provocadano,$suicidio,$evidencia3,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetSaludMental(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$acontecimientos,$circunstancias,$preocupaciones,$evidencia1,$diagnostico,$derivacion,
							 $evidencia2,$afectado,$provocadano,$suicidio,$evidencia3,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla salud_mental
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetSaludMental($id,$caso,$acontecimientos,$circunstancias,$preocupaciones,$evidencia1,$diagnostico,$derivacion,
										    $evidencia2,$afectado,$provocadano,$suicidio,$evidencia3,$calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetSaludMental(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$acontecimientos,$circunstancias,$preocupaciones,$evidencia1,$diagnostico,$derivacion,
							 $evidencia2,$afectado,$provocadano,$suicidio,$evidencia3,$calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un salud_mental
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionSaludMental($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionSaludMental(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>