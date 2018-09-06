<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Perfil{
	
	public	$conn;
	private	$res;
	private	$nombre;
	private	$estado;

    function Perfil($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite ejecutar el SP que entrega datos del perfil.
	 * Parámetros de entrada: código del perfil.
	 * Parámetros de salida: todos los datos del perfil.
 	 */
	public function entregaPerfil($codigo){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaPerfil(?)");
		$stmt->execute(array($codigo));
		return $stmt->fetchAll();		
	}
	/**
 	 * Método que muestra todos los registros de la tabla perfil.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla perfil
 	 */
	public function muestraPerfiles(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaPerfiles()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	/**
 	 * Método que muestra todos los registros de la tabla perfil con estado activo.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla perfil
 	 */
	public function muestraPerfilesActivos(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaPerfilesActivos()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	/**
 	 * Método que permite obtener obtener el total de registros de la tabla perf
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: total de perfiles
 	 */
	public function cuentaPerfiles(){
	
		$stmt = $this->conn->prepare("CALL SP_CuentaPerfiles()");
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	/**
 	 * Método que muestra todos los registros de la tabla perfil
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla perfil
 	 */
	public function muestraPerfilesLimit($ini,$fin){
	
		$stmt = $this->conn->prepare("CALL SP_VistaPerfilesLimit(?,?)");
		$stmt->execute(array($ini,$fin));
		return $stmt->fetchAll();				
	}
	/**
 	 * Método que inserta un registro.
	 * Parámetros de entrada: descripción y estado
	 * Parámetros de salida: no aplica
 	 */
	public function agregaPerfil($nom,$est){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaPerfil(?,?)");
		$stmt->execute(array($nom,$est));
		return $stmt->rowCount();				
	}
	/**
 	 * Método que inserta perfil y permisos en la tabla permiso_has_perfil.
	 * Parámetros de entrada: id del permiso, id del perfil
	 * Parámetros de salida: no aplica
 	 */
	public function agregaPermisoPerfil($perfil,$modulo,$escritura,$lectura,$aprobacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaPermisoPerfil(?,?,?,?,?)");
		$stmt->execute(array($perfil,$modulo,$escritura,$lectura,$aprobacion));
		return $stmt->rowCount();				
	}
	/**
 	 * Método que muestra los permisos asociados a un perfil
	 * Parámetros de entrada: id del perfil
	 * Parámetros de salida: id del permiso
 	 */
	public function muestraPermisoPerfil($perfil){
	
		$stmt = $this->conn->prepare("CALL SP_VistaPermisoPerfil(?)");
		$stmt->execute(array($perfil));
		return $stmt->fetchAll();				
	}
	/**
 	 * Método que elimina los permisos de un perfil
	 * Parámetros de entrada: id del perfil
	 * Parámetros de salida: no aplica
 	 */
	public function eliminaPermisoPerfil($perfil){
	
		$stmt = $this->conn->prepare("CALL SP_EliminaPermisoPerfil(?)");
		$stmt->execute(array($perfil));
		return $stmt->rowCount();				
	}
	/**
 	 * Método que elimina los permisos de un perfil
	 * Parámetros de entrada: id del perfil
	 * Parámetros de salida: no aplica
 	 */
	public function modificaPerfil($nom,$est,$id){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaPerfil(?,?,?)");
		$stmt->execute(array($nom,$est,$id));
		return $stmt->rowCount();				
	}
	
	/**
 	 * Método que permite ejecutar el SP que entrega los módulos de un perfil.
	 * Parámetros de entrada: código del perfil.
	 * Parámetros de salida: todos los datos del perfil.
 	 */
	public function entregaPerfilModulo($codigo){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaPerfilModulo(?)");
		$stmt->execute(array($codigo));
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
	
	function Identity() {
    	$stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() AS ID");
		$stmt->execute();
		if($row = $stmt->fetch()) {
			$ret = $row['ID'];
		}
		return($ret);
    }
}
?>
