<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Sustancias{
	
	public	$conn;
	private	$res;

    function Sustancias($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla sustancias de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipod e evaluación
	 * Parámetros de salida: todos los datos de tabla sustancias
 	 */
	public function entregaAssetSustancias($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetSustancias(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla sustancias.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetSustancias($id,$caso,$tabaco,$tabaco_edad,$alcohol,$alcohol_edad,$solventes,$solventes_edad,$cannabis,
										  $cannabis_edad,$pastabase,$pastabase_edad,$cocaina,$cocaina_edad,$anfetamina,$anfetamina_edad,
										  $tranquilizante,$tranquilizante_edad,$extasis,$extasis_edad,$lcd,$lcd_edad,$inhalantes,
										  $inhalantes_edad,$crack,$crack_edad,$heroina,$heroina_edad,$metadona,$metadona_edad,$esteroides,
										  $esteroides_edad,$otros,$otros_edad,$nnariesgo,$usopositivo,$educacion,$infracciones,$otro,$evidencia,
										  $calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetSustancias(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$tabaco,$tabaco_edad,$alcohol,$alcohol_edad,$solventes,$solventes_edad,$cannabis,$cannabis_edad,
							 $pastabase,$pastabase_edad,$cocaina,$cocaina_edad,$anfetamina,$anfetamina_edad,$tranquilizante,$tranquilizante_edad,
							 $extasis,$extasis_edad,$lcd,$lcd_edad,$inhalantes,$inhalantes_edad,$crack,$crack_edad,$heroina,$heroina_edad,
							 $metadona,$metadona_edad,$esteroides,$esteroides_edad,$otros,$otros_edad,$nnariesgo,$usopositivo,$educacion,
							 $infracciones,$otro,$evidencia,$calificacion));
		return $stmt->rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla sustancias
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetSustancias($id,$caso,$tabaco,$tabaco_edad,$alcohol,$alcohol_edad,$solventes,$solventes_edad,$cannabis,
										  $cannabis_edad,$pastabase,$pastabase_edad,$cocaina,$cocaina_edad,$anfetamina,$anfetamina_edad,
										  $tranquilizante,$tranquilizante_edad,$extasis,$extasis_edad,$lcd,$lcd_edad,$inhalantes,
										  $inhalantes_edad,$crack,$crack_edad,$heroina,$heroina_edad,$metadona,$metadona_edad,$esteroides,
										  $esteroides_edad,$otros,$otros_edad,$nnariesgo,$usopositivo,$educacion,$infracciones,$otro,$evidencia,
										  $calificacion){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetSustancias(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$tabaco,$tabaco_edad,$alcohol,$alcohol_edad,$solventes,$solventes_edad,$cannabis,
							 $cannabis_edad,$pastabase,$pastabase_edad,$cocaina,$cocaina_edad,$anfetamina,$anfetamina_edad,
							 $tranquilizante,$tranquilizante_edad,$extasis,$extasis_edad,$lcd,$lcd_edad,$inhalantes,
							 $inhalantes_edad,$crack,$crack_edad,$heroina,$heroina_edad,$metadona,$metadona_edad,$esteroides,
							 $esteroides_edad,$otros,$otros_edad,$nnariesgo,$usopositivo,$educacion,$infracciones,$otro,$evidencia,
							 $calificacion));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que elimina los permisos de un sustancias
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCalificacionSustancias($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionSustancias(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>