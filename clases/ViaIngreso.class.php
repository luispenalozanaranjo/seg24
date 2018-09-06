<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class ViaIngreso{
	
	public $conn;

    function ViaIngreso($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar un listado de vías de ingreso
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla vías de ingreso
 	 */
	public function muestraViaIngresoActivos()
	{
		$stmt = $this->conn->prepare("CALL SP_VistaViaIngresoActivos()");
		$stmt->execute();
		return $stmt->fetchAll();		
		
	}
	
	public function entregaViaIngreso($id){
		$ret = '';
		$stmt = $this->conn->prepare("CALL SP_EntregaViaIngreso(?)");
		$stmt->execute(array($id));
		if($row = $stmt->fetch()) {
			$ret = $row['vi_descripcion'];
		}
		return($ret);
	}
	
	/*****************Entrego Descripcion Sename***************************/
	
		public function MostrarProgramaProteccionSENAME(){
	
		$stmt = $this->conn->prepare("CALL SP_ViaProgramaProteccionSENAME");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
	public function MostrarAssetOtrasSename(){
	
		$stmt = $this->conn->prepare("CALL SP_ViaIngresoOtras");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
   public function MostrarAssetMedidasSancionesSename(){
	
		$stmt = $this->conn->prepare("CALL SP_MedidasSancionesLeyResponsabilidadPenalAdolescenteSENAME");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
	
	
		public function muestraViaIngresoActivosMedidasSanciones(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaViaIngresoActivosPPMedidasSanciones");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
	  public function muestraViaIngresoActivosPPSENAME(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaViaIngresoActivosPPSENAME");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
		  public function muestraViaIngresoActivosPPOtros(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaViaIngresoActivosPPOtros");
		$stmt->execute(array());
		return $stmt->fetchAll();
	}
	
/**********************************************/	
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>