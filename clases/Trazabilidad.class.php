<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

//require_once('../db/DataManager.class.php');
/**
 * Clase asociada a la tabla usuario.
 */
class Trazabilidad{
	
	public $conn;

    function Trazabilidad($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * M�todo que permite consultar por la Fecha de Evaluacion y el Responsable de esta misma
	 * Par�metros de entrada: id del caso
 	 */
	public function entregaFechaInicioAssetResponsable($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetFechaInicioResponsable(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}
	
		/**
 	 * M�todo que permite consultar por la Etapa de un Caso
	 * Par�metros de entrada: id del caso
 	 */
	public function entregaEtapaCaso($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_ValidarTrazabilidad(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}
	
			/**
 	 * M�todo que permite consultar CANTIDAD PERTENECIENTE A UN CASO
	 * Par�metros de entrada: id del caso
 	 */
	public function cantidadEntregaCaso($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_CantidadTrazabilidadCaso(?)");
		$stmt->execute(array($idcaso));
		return $stmt->fetchAll();	//return $stmt->fetchAll();//return $stmt->rowCount();	
			
	}

	/**
 	 * Método que inserta un registro en la tabla trazabilidad
	 * Parámetros de entrada: estado,caso 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaTrazabilidad($estado,$caso,$rut,$nom){
		$stmt = $this->conn->prepare("CALL SP_AgregaTrazabilidad(?,?,?,?)");
		$stmt->execute(array($estado,$caso,$rut,$nom));
		return $stmt->rowCount();		
				
	}
	
	/**
 	 * Método que permite obtener los datos de trazabilidad
	 * Parámetros de entrada: rut del etapa
	 * Parámetros de salida: todos los datos del trazabilidad
 	 */
	public function entregaTrazabilidad($estado,$caso){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaTrazabilidad(?,?)");
		$stmt->execute(array($estado,$caso));
		return $stmt->fetchAll();
	}
	
	
	
	public function Close(){
		
    	$this->conn = null;
		
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
}
?>
