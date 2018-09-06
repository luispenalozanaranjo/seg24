<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Visita{
	
	public $conn;

    function Visita($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de visitas de un caso
	 * Parámetros de entrada: id caso
	 * Parámetros de salida: todos los datos de la tabla visita
 	 */
	public function muestraVisitasCaso($id)
	{
		$stmt = $this->conn->prepare("CALL SP_VistaVisitasCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();		
		
	}
	
	/**
 	 * Método que permite contar la cantidad total de registros
	 * Parámetros de entrada: id caso
	 * Parámetros de salida: total de registros
 	 */
	public function cuentaVisitasCaso($id){
	
		$stmt = $this->conn->prepare("CALL SP_CuentaVisitasCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchColumn();//return $stmt->rowCount();
	}
	
	/**
 	 * Método que inserta un registro en la tabla visita.
	 * Parámetros de entrada: fecha visita, id resultado, rut usuario
	 * Parámetros de salida: no aplica
 	 */
	public function agregaVisitaCaso($idcaso,$fecha,$resultado,$profesional,$rutres,$nombreres,$fnac,$parentezco,$telefono,$archivo,$sugerencias){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaVisitaCaso(?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($idcaso,$fecha,$resultado,$profesional,$rutres,$nombreres,$fnac,$parentezco,$telefono,$archivo,$sugerencias));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que permite ingresar un nuevo archivo
	 * Parámetros de entrada: id caso, nombre archivo, peso, extensión, ubicación, mime, tipo
	 * Parámetros de salida: filas afectadas
 	 */
	public function ingresaArchivoCaso($id,$nombre,$peso,$extension,$ubicacion,$mime,$tipo){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaArchivoCaso(?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$nombre,$peso,$extension,$ubicacion,$mime,$tipo));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que modifica la etapa de un caso
	 * Parámetros de entrada: codigo, etapa
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaCasoEtapa($cod,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCasoEtapa(?,?)");
		$stmt->execute(array($cod,$etapa));
		return $stmt->rowCount();
			
	}
	
	public function entregaVisitasCaso($idcaso,$idvisita)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaVisitasCaso(?,?)");
		$stmt->execute(array($idcaso,$idvisita));
		return $stmt->fetchAll();		
		
	}
	
	public function modificaVisitaCaso($idcaso,$idvisita,$fecha,$resultado,$profesional,$rutres,$nombreres,$fnac,$parentezco,$telefono,$archivo,$sugerencias){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaVisitaCaso(?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($idcaso,$idvisita,$fecha,$resultado,$profesional,$rutres,$nombreres,$fnac,$parentezco,$telefono,$archivo,$sugerencias));
		return $stmt->rowCount();//rowCount
	}
	
	public function entregaVisitasCasoEvaluado($idresul,$idcaso)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaVisitasCasoEvaluado(?,?)");
		$stmt->execute(array($idresul,$idcaso));
		return $stmt->fetchAll();		
		
	}
	
	public function entregaVisitasCasoEvaluadoPDF($idcaso)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaVisitasCasoEvaluadoPDF(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();		
		
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