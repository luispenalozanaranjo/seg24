<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');
/**
 * Clase asociada a la tabla usuario.
 */
class Usuario{
	
	public $conn;

    function Usuario($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que permite validar y obtener los datos de un usuario en estado activo.
	 * Parámetros de entrada: rut y la clave del usuario
	 * Parámetros de salida: todos los datos del usuario
 	 */
	public function validaUsuario($rut,$clave){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaUsuario(?,?)");
		$stmt->execute(array($rut,$clave));
		return $stmt->fetchAll();//return $stmt->rowCount();		
			
	}
	/**
 	 * Método que permite obtener los datos de un usuario en estado activo.
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: todos los datos del usuario
 	 */
	public function entregaUsuarioActivo($rut){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaUsuarioActivo(?)");
		$stmt->execute(array($rut));
		return $stmt->fetchAll();//return $stmt->rowCount();		
			
	}
	/**
 	 * Método que agrega intentos de acceso al sistema.
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: no aplica
 	 */
	public function agregaIntento($rut){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaIntento(?)");
		$stmt->execute(array($rut));
		return $stmt->rowCount();		
			
	}
	/**
 	 * Método que entrega los intentos de acceso al sistema.
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: n° de intentos y etado de bloqueo de la cuenta del usuario
 	 */
	public function entregaIntento($rut){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaIntento(?)");
		$stmt->execute(array($rut));
		return $stmt->fetchAll();		
			
	}
	/**
 	 * Método que resetea la clave del usuario luego del bloqueo.
	 * Parámetros de entrada: rut y nueva clave del usuario
	 * Parámetros de salida: no aplica
 	 */
	public function reseteaClave($rut,$clave){
	
		$stmt = $this->conn->prepare("CALL SP_ReseteaClave(?,?)");
		$stmt->execute(array($rut,$clave));
		return $stmt->rowCount();		
			
	}
	/**
 	 * Método que resetea o vuelve a cero los intentos fallidos que ha realizado el usuario.
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: no aplica
 	 */
	public function reseteaIntento($rut){
	
		$stmt = $this->conn->prepare("CALL SP_ReseteaIntento(?)");
		$stmt->execute(array($rut));
		return $stmt->rowCount();		
			
	}
	/**
 	 * Método que modifica la clave de un usuario al ingresar por primera vez.
	 * Parámetros de entrada: rut y clave del usuario
	 * Parámetros de salida: no aplica
 	 */
	public function modificaClave($rut,$clave){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaClave(?,?)");
		$stmt->execute(array($rut,$clave));
		return $stmt->rowCount();		
			
	}
	/**
 	 * Método que muestra todos los registros de la tabla usuario.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla usuario
 	 */
	public function muestraUsuarios(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaUsuarios()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	/**
 	 * Método que muestra todos los registros de la tabla usuario por tipo de perfil.
	 * Parámetros de entrada:id perfil
	 * Parámetros de salida: todos los datos de la tabla usuario
 	 */
	public function muestraUsuariosPorPerfil($id){
	
		$stmt = $this->conn->prepare("CALL SP_VistaUsuariosPorPerfil(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();				
	}
	
	/**
 	 * Método que muestra todos los registros activos de la tabla usuario.
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos activos de la tabla usuario
 	 */
	public function muestraUsuariosGestores(){
	
		$stmt = $this->conn->prepare("CALL SP_VistaUsuariosGestores()");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	/**
 	 * Método que muestra todos los registros de la tabla usuario
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: todos los datos de la tabla usuario
 	 */
	public function muestraUsuariosLimit($ini,$fin){
	
		$stmt = $this->conn->prepare("CALL SP_VistaUsuariosLimit(?,?)");
		$stmt->execute(array($ini,$fin));
		return $stmt->fetchAll();				
	}
	
	/**
 	 * Método que muestra todos los registros de la tabla usuario
	 * Parámetros de entrada: inicio y fin (limit), filtro
	 * Parámetros de salida: todos los datos de la tabla usuario
 	 */
	public function muestraUsuariosLimitFiltro($ini,$fin,$filtro){
		//LEFT JOIN comuna c on c.co_idcomuna=u.co_idcomuna
		$stmt = $this->conn->prepare("
		SELECT us_rut, us_nombre, us_paterno, us_materno, us_email, us_estado, 
		p.pe_descripcion 
		FROM usuario u 
		INNER JOIN perfil p on p.pe_idperfil=u.pe_idperfil 
		WHERE us_rut <> '' ".$filtro." ORDER BY us_nombre ASC, us_paterno ASC, us_materno ASC LIMIT ".$ini.",".$fin."");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	/**
 	 * Método que muestra todos los registros de la tabla usuario
	 * Parámetros de entrada: filtro
	 * Parámetros de salida: todos los datos de la tabla usuario
 	 */
	public function muestraUsuariosFiltro($filtro){
	
		$stmt = $this->conn->prepare("
		SELECT us_rut, us_nombre, us_paterno, us_materno, us_email, us_estado, c.co_descripcion, 
		p.pe_descripcion 
		FROM usuario u 
		LEFT JOIN comuna c on c.co_idcomuna=u.co_idcomuna 
		INNER JOIN perfil p on p.pe_idperfil=u.pe_idperfil 
		WHERE us_rut <> '' ".$filtro." ORDER BY us_rut DESC");
		$stmt->execute();
		return $stmt->fetchAll();	
	}
	/**
 	 * Método que inserta un registro.
	 * Parámetros de entrada: rut, nombre, apellido paterno, apellido materno, clave, email, comuna, perfil y estado
	 * Parámetros de salida: no aplica
 	 */
	public function agregaUsuario($rut,$nom,$pat,$mat,$cla,$ema,$com,$per,$est){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaUsuario(?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rut,$nom,$pat,$mat,$cla,$ema,$com,$per,$est));
		return $stmt->rowCount();				
	}
	
	/**
 	 * Método que inserta un registro (sin la comuna).
	 * Parámetros de entrada: rut, nombre, apellido paterno, apellido materno, clave, email, perfil y estado
	 * Parámetros de salida: no aplica
 	 */
	public function agregaUsuarioV2($rut,$nom,$pat,$mat,$cla,$ema,$per,$est){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaUsuarioV2(?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rut,$nom,$pat,$mat,$cla,$ema,$per,$est));
		return $stmt->rowCount();				
	}
	
	/**
 	 * Método que permite obtener los datos de un usuario
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: todos los datos del usuario
 	 */
	public function entregaUsuario($rut){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaUsuario(?)");
		$stmt->execute(array($rut));
		return $stmt->fetchAll();//return $stmt->rowCount();		
			
	}
	/**
 	 * Método que modifica los datos de un usuario
	 * Parámetros de entrada: nombre, email, institución, perfil, estado y rut
	 * Parámetros de salida: no aplica
 	 */
	public function modificaUsuario($nom,$pat,$mat,$ema,$com,$per,$est,$rut){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaUsuario(?,?,?,?,?,?,?,?)");
		$stmt->execute(array($nom,$pat,$mat,$ema,$com,$per,$est,$rut));
		return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que modifica los datos de un usuario (sin la comuna)
	 * Parámetros de entrada: nombre, email, perfil, estado y rut
	 * Parámetros de salida: no aplica
 	 */
	public function modificaUsuarioV2($nom,$pat,$mat,$ema,$per,$est,$rut){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaUsuarioV2(?,?,?,?,?,?,?)");
		$stmt->execute(array($nom,$pat,$mat,$ema,$per,$est,$rut));
		return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que permite obtener obtener el total de registros de la tabla usuario
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: total de usuarios
 	 */
	public function cuentaUsuarios(){
	
		$stmt = $this->conn->prepare("CALL SP_CuentaUsuarios()");
		$stmt->execute();
		return $stmt->fetchColumn();//return $stmt->rowCount();				
	}
	
	/**
 	 * Método que permite obtener obtener el total de registros de la tabla usuario
	 * Parámetros de entrada: no aplica
	 * Parámetros de salida: total de usuarios
 	 */
	public function cuentaUsuariosFiltro($filtro){
	
		$stmt = $this->conn->prepare("
		SELECT count(*) FROM usuario u WHERE us_rut <> '' ".$filtro."");
		$stmt->execute();
		return $stmt->fetchColumn();//return $stmt->rowCount();				
	}
	/**
 	 * Método que permite obtener el nombre de un usuario
	 * Parámetros de entrada: rut
	 * Parámetros de salida: nombre usuario
 	 */
	public function entregaNombreUsuario($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaNombreUsuario(?)");
		$stmt->execute(array($id));

		if($row=$stmt->fetch()) {
			$salida=$row['us_nombre'];
		}
		echo $salida;
			
	}
	
	public function agregaUsuarioHasComuna($rut,$idcom){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaUsuarioHasComuna(?,?)");
		$stmt->execute(array($rut,$idcom));
		return $stmt->rowCount();		
			
	}
	
	public function entregaUsuarioHasComuna($rut){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaUsuarioHasComuna(?)");
		$stmt->execute(array($rut));
		return $stmt->fetchAll();//return $stmt->rowCount();		
			
	}
	
	public function entregaIDRegionUsuarioHasComuna($rut){
		$salida ='';
		$stmt = $this->conn->prepare("CALL SP_EntregaIDRegionUsuarioHasComuna(?)");
		$stmt->execute(array($rut));

		if($row=$stmt->fetch()) {
			$salida=$row['re_idregion'];
		}
		return $salida;
			
	}
	
	public function eliminaUsuarioHasComuna($rut){
	
		//$stmt = $this->conn->prepare("CALL SP_EliminaUsuarioHasComuna(?)");
		$stmt = $this->conn->prepare("DELETE FROM usuario_has_comuna WHERE us_rut='".$rut."'");
		$stmt->execute(array($rut));
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
	
	function Identity() {
    	$stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() AS ID");
		$stmt->execute();
		if($row = $stmt->fetch()) {
			$ret = $row['ID'];
		}
		return($ret);
    }
	
	public function Close(){
		
    	$this->conn = null;
		
	}
	
}
?>
