<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Archivo{
	
	public $conn;

    function Archivo($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite entregar el archivo de un caso
	 * Parámetros de entrada: id caso
	 * Parámetros de salida: todos los datos de la tabla archivo
 	 */
	public function entregaArchivoCaso($id)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaArchivoCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();
	}
	
	/**
 	 * Método que permite entregar el archivo de consentimiento de un caso
	 * Parámetros de entrada: id caso
	 * Parámetros de salida: todos los datos de la tabla archivo
 	 */
	public function entregaArchivoConsentimiento($id)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaArchivoConsentimiento(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();
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
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>