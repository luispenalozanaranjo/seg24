<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Responsable{
	
	public $conn;

    function Responsable($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite mostrar al responsable de un caso
	 * Parámetros de entrada: id caso
	 * Parámetros de salida: todos los datos de la tabla responsable
 	 */
	public function entregaResponsablesCaso($id)
	{
		$stmt = $this->conn->prepare("CALL SP_EntregaResponsablesCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();		
		
	}
	
	/**
 	 * Método que permite ingresar un nuevo responsable
	 * Parámetros de entrada: id caso, rut, nombre, fecha nacimiento, parentezco, teléfono, fecha firma
	 * Parámetros de salida: total de registros
 	 */
	public function ingresaResponsablesCaso($id,$rut,$nombre,$fnacimiento,$parentezco,$telefono,$ffirma){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaResponsablesCaso(?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$rut,$nombre,$fnacimiento,$parentezco,$telefono,$ffirma));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que permite ingresar un nuevo responsable
	 * Parámetros de entrada: id caso, rut, nombre, fecha nacimiento, parentezco, teléfono, fecha firma
	 * Parámetros de salida: total de registros
 	 */
	public function modificaResponsablesCaso($id,$rut,$nombre,$fnacimiento,$parentezco,$telefono,$ffirma){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaResponsablesCaso(?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$rut,$nombre,$fnacimiento,$parentezco,$telefono,$ffirma));
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
 	 * Método que permite modificar un archivo
	 * Parámetros de entrada: id caso, nombre archivo, peso, extensión, ubicación, mime, tipo
	 * Parámetros de salida: filas afectadas
 	 */
	public function modificaArchivoCaso($id,$nombre,$peso,$extension,$ubicacion,$mime,$tipo){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaArchivoCaso(?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$nombre,$peso,$extension,$ubicacion,$mime,$tipo));
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