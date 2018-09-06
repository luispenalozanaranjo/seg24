<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');
/**
 * Clase asociada a la tabla casos.
 */
class Casos{
	
	public $conn;

    function Casos($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	
	/**
 	 * Método que muestra todos los registros de la tabla casos
	 * Parámetros de entrada: inicio y fin (limit), filtro
	 * Parámetros de salida: todos los datos de la tabla casos
 	 */
	public function muestraCasosLimitFiltro($ini,$fin,$filtro,$order){
	        
		$stmt = $this->conn->prepare("
		SELECT 
		CAST(ca.ca_idcaso AS UNSIGNED) as idcaso, ca_codigo, ci_rut, ca.ci_idciudadano, ci_nombre, ci_paterno, 
		ci_materno, us_nombre, us_paterno, us_materno, re_descripcion, co_descripcion, ca_etapa, 
		CASE WHEN LOCATE('(', vi_descripcion)=0 
        THEN vi_descripcion 
        ELSE 
        TRIM(SUBSTRING(vi_descripcion,1,((LOCATE('(', vi_descripcion))-1))) 
        end vi_descripcion, 
		de.de_iddelito, de_descripcion, ci_edadingreso, re_estado, sol.*,ca_finsercion, resu.rs_idresultado, insti.in_idinstitucion,
        CASE
			WHEN ca_etapa = 'Ingreso' THEN 1
            WHEN ca_etapa = 'Contactabilidad' THEN 2
            WHEN ca_etapa = 'Evaluacion' THEN 3
            WHEN ca_etapa = 'Derivacion' THEN 4
			WHEN ca_etapa = 'Reevaluacion' THEN 5
            WHEN ca_etapa = 'Cierre' THEN 6
            WHEN ca_etapa = 'Finalizado' THEN 7
        END AS ca_idetapa    
		FROM caso ca 
		INNER JOIN ciudadano ci on ci.ci_idciudadano = ca.ci_idciudadano
		INNER JOIN usuario u on u.us_rut = ca.us_rut
		INNER JOIN delito de on de.de_iddelito = ca.de_iddelito
		LEFT JOIN (visita vis LEFT JOIN resultado resu ON vis.rs_idresultado=resu.rs_idresultado) on vis.vi_idvisita=(SELECT MAX(visi.vi_idvisita) FROM visita visi WHERE visi.ca_idcaso = ca.ca_idcaso)
		LEFT JOIN comuna co on co.co_idcomuna = ci.co_idcomuna
		LEFT JOIN provincia pr on co.pv_idprovincia = pr.pv_idprovincia
		LEFT JOIN region re on pr.re_idregion = re.re_idregion
		LEFT JOIN via_ingreso vi on vi.vi_idvia = ca.vi_idvia
		LEFT JOIN revision rev ON rev.ca_idcaso = ca.ca_idcaso
		LEFT JOIN solicitud_cierre sol ON sol.ca_idcaso = ca.ca_idcaso
		LEFT JOIN (derivacion deri LEFT JOIN institucion insti ON deri.in_idinstitucion=insti.in_idinstitucion) on deri.ca_idcaso=ca.ca_idcaso
		WHERE ca.ca_idcaso > 0 ".$filtro." GROUP BY idcaso ".$order." LIMIT ".$ini.",".$fin."");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	
	/**
 	 * Método que muestra todos los registros de la tabla casos
	 * Parámetros de entrada: filtro
	 * Parámetros de salida: todos los datos de la tabla casos
 	 */
	public function muestraCasosFiltro($filtro){
	
		$stmt = $this->conn->prepare("
		SELECT 
		CAST(ca.ca_idcaso AS UNSIGNED) as idcaso, ca_codigo, vi_descripcion, ci_rut, ca.ci_idciudadano, ci_nombre, ci_paterno, 
		ci_materno, us_nombre, us_paterno, us_materno, re_descripcion, co_descripcion, ca_etapa, 
		de_descripcion, ci_edadingreso, ci_fnacimiento, na_descripcion, ci_domicilio, ci_numero, ci_poblacion, re_estado, count(re_estado) rechazados, sol.*, 
		CASE 
		WHEN dej.de_fderivacion='0000-00-00' THEN '' 
		     ELSE 
		dej.de_fderivacion END AS de_fderivacion, 
		an.an_fecinicio, an.an_fectermino, ca_fdenuncia, ca_finsercion, (ca_reingresovulnerado+ca_reingresoinimputable) reingresos, 
        CASE
			WHEN ca_etapa = 'Ingreso' THEN 1
            WHEN ca_etapa = 'Contactabilidad' THEN 2
            WHEN ca_etapa = 'Evaluacion' THEN 3
            WHEN ca_etapa = 'Derivacion' THEN 4
			WHEN ca_etapa = 'Reevaluacion' THEN 5
            WHEN ca_etapa = 'Cierre' THEN 6
            WHEN ca_etapa = 'Finalizado' THEN 7
        END AS ca_idetapa    
		FROM caso ca 
		INNER JOIN (ciudadano ci LEFT JOIN nacionalidad nac ON ci.na_idnacionalidad=nac.na_idnacionalidad) on ci.ci_idciudadano = ca.ci_idciudadano
		INNER JOIN usuario u on u.us_rut = ca.us_rut
		INNER JOIN delito de on de.de_iddelito = ca.de_iddelito
		LEFT JOIN comuna co on co.co_idcomuna = ci.co_idcomuna
		LEFT JOIN provincia pr on co.pv_idprovincia = pr.pv_idprovincia
		LEFT JOIN region re on pr.re_idregion = re.re_idregion
		LEFT JOIN via_ingreso vi on vi.vi_idvia = ca.vi_idvia
		LEFT JOIN revision rev ON rev.ca_idcaso = ca.ca_idcaso AND rev.ca_idcaso='Rechazada'
		LEFT JOIN solicitud_cierre sol ON sol.ca_idcaso = ca.ca_idcaso
        LEFT JOIN derivacion dej ON dej.ca_idcaso = ca.ca_idcaso
        LEFT JOIN analisis an ON an.ca_idcaso = ca.ca_idcaso
		WHERE ca.ca_idcaso > 0 ".$filtro." GROUP BY idcaso ORDER BY ca_finsercion DESC, ci_nombre ASC");
		$stmt->execute();
		return $stmt->fetchAll();
	}
	//DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ci_fnacimiento)), '%Y')+0 AS edad
	
	/**
 	 * Método que permite obtener obtener el total de registros de la tabla casos
	 * Parámetros de entrada: filtro
	 * Parámetros de salida: total de casos
 	 */
	public function cuentaCasosFiltro($filtro){
	
		$stmt = $this->conn->prepare("
		SELECT count(*)
		FROM caso ca 
		INNER JOIN ciudadano ci on ci.ci_idciudadano = ca.ci_idciudadano
		INNER JOIN usuario u on u.us_rut = ca.us_rut
		INNER JOIN delito de on de.de_iddelito = ca.de_iddelito
		LEFT JOIN comuna co on co.co_idcomuna = ci.co_idcomuna
		LEFT JOIN provincia pr on co.pv_idprovincia = pr.pv_idprovincia
		LEFT JOIN region re on pr.re_idregion = re.re_idregion
		LEFT JOIN via_ingreso vi on vi.vi_idvia = ca.vi_idvia
		WHERE ca_idcaso > 0 ".$filtro."");
		$stmt->execute();
		return $stmt->fetchColumn();//return $stmt->rowCount();				
	}
	
		
	/**
	 * Mètodo que se encarga de mostrar los casos por Contactabilidad
	 * Parámetros de entrada: Definir
	 * Parámetros de salida: no aplica
	 */
	 
	 	public function muestraContactabilidadLimitFiltro($ini,$fin,$filtro){
	        
		$stmt = $this->conn->prepare("
		SELECT CAST(v.ca_idcaso AS UNSIGNED) as idcaso, 
		CAST(v.ca_idcaso AS UNSIGNED) ca_idcaso, 
		DATE_FORMAT(v.vi_fecha, '%d-%m-%Y') vi_fecha, 
		concat(u.us_nombre,' ', u.us_paterno,' ',u.us_materno) us_nombre, 
		r.rs_descripcion, v.vi_rutresponsable, INITCAP(v.vi_nombreresponsable) vi_nombreresponsable, 
		CASE 
		    WHEN v.vi_fnacresponsable='0000-00-00' 
			THEN '' 
			ELSE 
			DATE_FORMAT(v.vi_fnacresponsable, '%d-%m-%Y') END vi_fnacresponsable, 
			v.vi_parentezco, 
			v.vi_telefono, 
			INITCAP(v.vi_sugerencias) vi_sugerencias,
        CASE
			WHEN ca_etapa = 'Ingreso' THEN 1
            WHEN ca_etapa = 'Contactabilidad' THEN 2
            WHEN ca_etapa = 'Evaluacion' THEN 3
            WHEN ca_etapa = 'Derivacion' THEN 4
			WHEN ca_etapa = 'Reevaluacion' THEN 5
            WHEN ca_etapa = 'Cierre' THEN 6
            WHEN ca_etapa = 'Finalizado' THEN 7
        END AS ca_idetapa, r.rs_idresultado, rev.re_estado
		FROM visita v 
		LEFT JOIN (caso c LEFT JOIN ciudadano ci ON c.ci_idciudadano=ci.ci_idciudadano) on c.ca_idcaso=v.ca_idcaso
		LEFT JOIN comuna co on co.co_idcomuna = ci.co_idcomuna
		LEFT JOIN provincia pr on co.pv_idprovincia = pr.pv_idprovincia
		LEFT JOIN region re on pr.re_idregion = re.re_idregion
		LEFT JOIN revision rev ON rev.ca_idcaso = c.ca_idcaso
		INNER JOIN resultado r ON r.rs_idresultado = v.rs_idresultado 
		INNER JOIN usuario u ON u.us_rut = v.us_rut
	    WHERE v.ca_idcaso > 0   AND c.ca_etapa='Contactabilidad' ".$filtro." ORDER BY v.ca_idcaso DESC LIMIT ".$ini.",".$fin."");
		$stmt->execute();
		return $stmt->fetchAll();				
	}
	 
		/**
 	 * Método que permite obtener obtener el total de registros de la tabla casos
	 * Parámetros de entrada: filtro
	 * Parámetros de salida: total de casos
 	 */
	public function cuentaContactabilidadFiltro($filtro){
	
		$stmt = $this->conn->prepare("
		SELECT count(*) FROM visita v 
		LEFT JOIN (caso c LEFT JOIN ciudadano ci ON c.ci_idciudadano=ci.ci_idciudadano) on c.ca_idcaso=v.ca_idcaso
		LEFT JOIN comuna co on co.co_idcomuna = ci.co_idcomuna
		LEFT JOIN provincia pr on co.pv_idprovincia = pr.pv_idprovincia
		LEFT JOIN region re on pr.re_idregion = re.re_idregion
		LEFT JOIN revision rev ON rev.ca_idcaso = c.ca_idcaso
		INNER JOIN resultado r ON r.rs_idresultado = v.rs_idresultado 
		INNER JOIN usuario u ON u.us_rut = v.us_rut
	    WHERE v.ca_idcaso > 0 AND c.ca_etapa='Contactabilidad' ".$filtro."");
		$stmt->execute();
		return $stmt->fetchColumn();//return $stmt->rowCount();				
	}
	 
	/**
 	 * Método que inserta un registro en la tabla ciudadano.
	 * Parámetros de entrada: rut, comuna, nombre, apellido paterno, apellido materno, fecha nacimiento, edad, sexo, educación, domicilio,
	 *						  numero, poblacion, nacionalidad.
	 * Parámetros de salida: no aplica
 	 */
	public function agregaCiudadano($rut,$comuna,$nombre,$paterno,$materno,$fnac,$sexo,$edad,$educacion,$domicilio,$numero,$poblacion,$nacionalidad){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaCiudadano(?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rut,$comuna,$nombre,$paterno,$materno,$fnac,$sexo,$edad,$educacion,$domicilio,$numero,$poblacion,$nacionalidad));
		return $stmt->rowCount();	// rowCount()			
	}
	
	/**
 	 * Método que actualiza un registro en la tabla ciudadano.
	 * Parámetros de entrada: comuna, nombre, apellido paterno, apellido materno, fecha nacimiento, edad, sexo, educación, domicilio,
	 *						  numero, poblacion, nacionalidad, rut.
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCiudadano($rut,$comuna,$nombre,$paterno,$materno,$fnac,$sexo,$edad,$educacion,$domicilio,$numero,$poblacion,$nacionalidad,$id){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCiudadano(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rut,$comuna,$nombre,$paterno,$materno,$fnac,$sexo,$edad,$educacion,$domicilio,$numero,$poblacion,$nacionalidad,$id));
		return $stmt->rowCount();
	}
	
		/**
 	 * Método que actualiza un registro en la tabla ciudadano.
	 * Parámetros de entrada: comuna, nombre, apellido paterno, apellido materno, fecha nacimiento, edad, sexo, educación, domicilio,
	 *						  numero, poblacion, nacionalidad, rut.
	 * Parámetros de salida: no aplica
 	 */
	public function modificaRutEvaluacion($rut,$idcaso,$eva){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaEvaluacionRut(?,?,?)");
		$stmt->execute(array($rut,$idcaso,$eva));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que inserta un registro en la tabla caso.
	 * Parámetros de entrada: rut, vía, ciudadano, delito, fecha denuncia, motivo, clase participante, n° reingreso vulnerado
	 *						  n° reingreso infractor, n° reingreso inimputable, unidad procedimiento, comuna procedimiento, juzgado, 
	 *						  n° parte, detenido o afectado en, n° ingresos 24Horas, registro civil, codigo
	 * Parámetros de salida: no aplica
 	 */
	public function agregaCaso($rutusu,$via,$idciudadano,$delito,$fdenuncia,$motivo,$clase,$vulnerado,$infractor,$inimputable,$unidad,
							   $comprocedimiento,$juzgado,$parte,$detenidoen,$ingreso24,$regcivil,$codigo){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaCaso(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rutusu,$via,$idciudadano,$delito,$fdenuncia,$motivo,$clase,$vulnerado,$infractor,$inimputable,$unidad,
							 $comprocedimiento,$juzgado,$parte,$detenidoen,$ingreso24,$regcivil,$codigo));
		return $stmt->rowCount();				
	}
	
	/**
 	 * Método que actualiza un registro en la tabla caso.
	 * Parámetros de entrada: rut, vía, ciudadano, delito, fecha denuncia, motivo, clase participante, n° reingreso vulnerado
	 *						  n° reingreso infractor, n° reingreso inimputable, unidad procedimiento, comuna procedimiento, juzgado, 
	 *						  n° parte, detenido o afectado en, n° ingresos 24Horas, registro civil, idcaso
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCaso($rutusu,$via,$idciudadano,$delito,$fdenuncia,$motivo,$clase,$vulnerado,$infractor,$inimputable,$unidad,
							   $comprocedimiento,$juzgado,$parte,$detenidoen,$ingreso24,$regcivil,$codigo,$idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCaso(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($rutusu,$via,$idciudadano,$delito,$fdenuncia,$motivo,$clase,$vulnerado,$infractor,$inimputable,$unidad,
							 $comprocedimiento,$juzgado,$parte,$detenidoen,$ingreso24,$regcivil,$codigo,$idcaso));
		return $stmt->rowCount();				
	}
	/**
 	 * Método que permite obtener los datos de un usuario
	 * Parámetros de entrada: rut del usuario
	 * Parámetros de salida: todos los datos del usuario
 	 */
	public function entregaCaso($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	public function entregaCasoV2($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetch();	//return $stmt->fetchAll();//return $stmt->rowCount();		
			
	}
	/**
 	 * Método que modifica los datos de un usuario
	 * Parámetros de entrada: nombre, email, institución, perfil, estado y rut
	 * Parámetros de salida: no aplica
 	 */
	public function modificaCasoCodigo($cod,$id){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCasoCodigo(?,?)");
		$stmt->execute(array($cod,$id));
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
	
	/**
 	 * Método que inserta un solicitud de cierre.
	 * Parámetros de entrada: caso, motivo, observacion
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaSolicitudCierre($idcaso,$motivo,$observacion){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaSolicitudCierre(?,?,?)");
		$stmt->execute(array($idcaso,$motivo,$observacion));
		return $stmt->rowCount();				
	}
	
	/**
 	 * Método que finaliza una solicitud de cierre
	 * Parámetros de entrada: caso, satisfactorio, comentario
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaSolicitudCierre($idcaso,$satisfactorio,$comentario){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaSolicitudCierre(?,?,?)");
		$stmt->execute(array($idcaso,$satisfactorio,$comentario));
		return $stmt->rowCount();
	}
	
	
	public function entregaGestorComunaCaso($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaGestorComunaCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	public function entregaGestorComunaCaso2($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaGestorComunaCaso2(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	   public function entregaGestorProfesionalCaso($id){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaGestorProfesionalCaso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	//concat(ci.ci_nombre,' ',ci.ci_paterno,' ',ci.ci_materno) like '%".$nom."%'
	public function comprobarCodigoCaso($rut,$nom){
		//$stmt = $this->conn->prepare("CALL SP_ComprobarCodigoCaso(?)");
		$stmt = $this->conn->prepare("SELECT CAST(ca.ca_idcaso AS UNSIGNED) as idcaso, ca_codigo, ci_rut, ca.ci_idciudadano, ci_nombre, ci_paterno
FROM caso ca INNER JOIN ciudadano ci on ci.ci_idciudadano = ca.ci_idciudadano WHERE ci.ci_rut like '%".$rut."%' and concat(ci.ci_nombre,' ',ci.ci_paterno,' ',ci.ci_materno) like '%".$nom."%'");
		$stmt->execute(array($rut,$nom));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	public function entregaAssetEvidencias($id,$etapa){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetEvidencias(?,?)");
		$stmt->execute(array($id,$etapa));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
		
	/**
 	 * Método que imprime la fecha de la creacion del caso
	 * Parámetros de entrada: fecha creacion caso
	 * Parámetros de salida: 1
 	 */
	public function validaFechaIngreso($id){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaIngreso(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que imprime la fecha de la contactabilidad del caso
	 * Parámetros de entrada: fecha contactabilidad caso
	 * Parámetros de salida: 1
 	 */
	public function validaFechaContactabilidad($id){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaContactabilidad(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que imprime la fecha de la contactabilidad del caso
	 * Parámetros de entrada: fecha contactabilidad caso
	 * Parámetros de salida: 1
 	 */
	public function validaFechaAnalisisInicio($id){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaAnalisisInicio(?)");
		$stmt->execute(array($id));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que imprime la fecha de termino del analisis o asset del caso
	 * Parámetros de entrada: fecha analisis caso
	 * Parámetros de salida: 1
 	 */
	public function validaFechaAnalisisTermino($id,$eva){
	
		$stmt = $this->conn->prepare("CALL SP_ValidaFechaAnalisisTermino(?,?)");
		$stmt->execute(array($id,$eva));
		return $stmt->fetchAll();	//return $stmt->rowCount();		
			
	}
	
	/**
 	 * Método que elimina un caso en cascada
	 * Parámetros de entrada: caso
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function eliminaCaso($idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_EliminaCaso(?)");
		$stmt->execute(array($idcaso));
		return $stmt->rowCount();
	}
	
		/**
 	 * Método que elimina un Consentimiento
	 * Parámetros de entrada: caso
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function eliminaConsentimientoCasoVisita($id,$idcaso){
	
		$stmt = $this->conn->prepare("CALL SP_EliminaVisitaConsentimiento(?,?)");
		$stmt->execute(array($id,$idcaso));
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
