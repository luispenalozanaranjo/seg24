<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Hogar{
	
	public	$conn;
	private	$res;

    function Hogar($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla hogar de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla hogar
 	 */
	public function entregaAssetHogar($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetHogar(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla hogar.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetHogar($id,$caso,$condicion,$sin_domicilio,$incumplimiento,$hogar_deprivado,$vive_delincuentes,$situacion_calle,
									 $desorganizado,$otros,$evidencia,$calificacion,$chkviviendanna){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetHogar(?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$condicion,$sin_domicilio,$incumplimiento,$hogar_deprivado,$vive_delincuentes,$situacion_calle,
						     $desorganizado,$otros,$evidencia,$calificacion,$chkviviendanna));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla hogar
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetHogar($id,$caso,$condicion,$sin_domicilio,$incumplimiento,$hogar_deprivado,$vive_delincuentes,$situacion_calle,
									 $desorganizado,$otros,$evidencia,$calificacion,$chkviviendanna){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetHogar(?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$condicion,$sin_domicilio,$incumplimiento,$hogar_deprivado,$vive_delincuentes,$situacion_calle,
							 $desorganizado,$otros,$evidencia,$calificacion,$chkviviendanna));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un hogar
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionHogar($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionHogar(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
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