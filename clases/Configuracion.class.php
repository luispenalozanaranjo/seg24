<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');
/**
 * Clase asociada a la tabla usuario.
 */
class Configuracion{
	
	public $conn;

    function Configuracion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que muestra todos los registros de la tabla configuracion.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla configuracion
 	 */
	public function muestraConfiguracion(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaConfiguracion()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}

	/**
 	 * Método que modifica los datos de la configuracion
	 * Parámetros de entrada: solicitud cierre derivación externa, alerta verde DE, alerta amarilla DE,
	 *						  alerta roja DE, solicitud cierre MST, alerta verde MST, alerta amarilla MST,
	 *						  alerta roja MST, puntaje derivacion, n° visitas mínimo, n° visitas máximo,
	 *						  gestor territorial
	 * Parámetros de salida: no aplica
 	 */
	public function modificaConfiguracion($cierreDE,$verdeDE,$amarilloDE,$rojoDE,$cierreMST,$verdeMST,
										  $amarilloMST,$rojoMST,$puntaje,$minvisitas,$maxvisitas,$gestor){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaConfiguracion($cierreDE,$verdeDE,$amarilloDE,$rojoDE,$cierreMST,$verdeMST,
										  $amarilloMST,$rojoMST,$puntaje,$minvisitas,$maxvisitas,$gestor,@salida)");
		$stmt->execute();
		if($row = $stmt->fetch()) {
			$ret = $row['Salida'];
		}
		return($ret);			
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
	
}
?>
