<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');


class Analisis{
	
	public	$conn;
	private	$res;
	private	$nombre;
	private	$estado;

    function Analisis($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	
	
	/**
 	 * Método que permite consultar por el análisis de un caso en particular
	 * Parámetros de entrada: código del caso
	 * Parámetros de salida: todos los datos de tabla analisis
 	 */
	public function entregaAssetAnalisis($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetAnalisis(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que permite consultar por la ultima fecha de evaluacion del caso
	 * Parámetros de entrada: id del caso
 	 */
	public function verificarAssetFechaAnalisis($idcaso,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_VistaVerificarFechaAsset(?,?)");
		$stmt->execute(array($idcaso,$etapa));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}
		/**
 	 * Método que permite consultar por la ultima fecha de evaluacion del caso
	 * Parámetros de entrada: id del caso
 	 */
	public function verificarAssetFechaAnalisis2($idcaso,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_VistaVerificarFechaAsset2(?,?)");
		$stmt->execute(array($idcaso,$etapa));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			 
	}
	/**
 	 * Método que inserta un registro en la tabla analisis.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetAnalisis($etapa,$caso,$finicio,$ftermino,$ddificultad,$dporobtener,$dcausal,$vespecifica,$vvulnerable,
										$vrepetida,$vdesconocida,$mracial,$drelacion,$delito,$lugdelito,$metdelito,$pladelito,$armdelito,
										$valdelito,$alcdelito,$grudelito,$intdelito,$difdelito,$vuldelito,$agrdelito,$impdelito,$condelito,
										$cirdelito,$motdelito,$actdelito,$credelito,$sprevia,$dsimilitud,$agravedad,$daumento,$especializacion,
										$despecializacion,$interrupcion,$dinterrupcion,$idesistir,$dintentos,$dtransgresion,$pdetencion,
										$pcondena,$cprevias,$tmedida,$iincumplimientos,$evidencia,$chkevaluacion,$chkmedidasnna,$chkdetalleotro){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetAnalisis(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($etapa,$caso,$finicio,$ftermino,$ddificultad,$dporobtener,$dcausal,$vespecifica,$vvulnerable,
							$vrepetida,$vdesconocida,$mracial,$drelacion,$delito,$lugdelito,$metdelito,$pladelito,$armdelito,
							$valdelito,$alcdelito,$grudelito,$intdelito,$difdelito,$vuldelito,$agrdelito,$impdelito,$condelito,
							$cirdelito,$motdelito,$actdelito,$credelito,$sprevia,$dsimilitud,$agravedad,$daumento,$especializacion,
							$despecializacion,$interrupcion,$dinterrupcion,$idesistir,$dintentos,$dtransgresion,$pdetencion,
							$pcondena,$cprevias,$tmedida,$iincumplimientos,$evidencia,$chkevaluacion,$chkmedidasnna,$chkdetalleotro));		
							
		return $stmt->rowCount();//rowCount
	}

	/**
 	 * Método que elimina los permisos de un Analisis
	 * Parámetros de entrada: id del Analisis
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetAnalisis($id,$caso,$finicio,$ftermino,$ddificultad,$dporobtener,$dcausal,$vespecifica,$vvulnerable,
										$vrepetida,$vdesconocida,$mracial,$drelacion,$delito,$lugdelito,$metdelito,$pladelito,$armdelito,
										$valdelito,$alcdelito,$grudelito,$intdelito,$difdelito,$vuldelito,$agrdelito,$impdelito,$condelito,
										$cirdelito,$motdelito,$actdelito,$credelito,$sprevia,$dsimilitud,$agravedad,$daumento,$especializacion,
										$despecializacion,$interrupcion,$dinterrupcion,$idesistir,$dintentos,$dtransgresion,$pdetencion,
										$pcondena,$cprevias,$tmedida,$iincumplimientos,$evidencia,$chkevaluacion,$chkmedidasnna,$chkdetalleotro){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetAnalisis(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
									 ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$finicio,$ftermino,$ddificultad,$dporobtener,$dcausal,$vespecifica,$vvulnerable,
							$vrepetida,$vdesconocida,$mracial,$drelacion,$delito,$lugdelito,$metdelito,$pladelito,$armdelito,
							$valdelito,$alcdelito,$grudelito,$intdelito,$difdelito,$vuldelito,$agrdelito,$impdelito,$condelito,
							$cirdelito,$motdelito,$actdelito,$credelito,$sprevia,$dsimilitud,$agravedad,$daumento,$especializacion,
							$despecializacion,$interrupcion,$dinterrupcion,$idesistir,$dintentos,$dtransgresion,$pdetencion,
							$pcondena,$cprevias,$tmedida,$iincumplimientos,$evidencia,$chkevaluacion,$chkmedidasnna,$chkdetalleotro));
		return $stmt->rowCount();		
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>