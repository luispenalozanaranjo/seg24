<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/db/DataManager.class.php');

class Educacion{
	
	public	$conn;
	private	$res;

    function Educacion($c) {
    	
    	if(isset($c)) {
    		$this->conn = $c;
    	} else {
    		$this->conn = DataManager::getInstance();
    	}

    }
	/**
 	 * Método que permite consultar por los datos de la tabla educacion de un caso y evaluación en particular
	 * Parámetros de entrada: código del caso, tipo de evaluación
	 * Parámetros de salida: todos los datos de tabla educacion
 	 */
	public function entregaAssetEducacion($codigo,$tipevaluacion){
	
		$stmt = $this->conn->prepare("CALL SP_EntregaAssetEducacion(?,?)");
		$stmt->execute(array($codigo,$tipevaluacion));
		return $stmt->fetchAll();
			
	}

	/**
 	 * Método que inserta un registro en la tabla educacion.
	 * Parámetros de entrada: 
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function agregaAssetEducacion($id,$caso,$horasdedicadas,$horasefectivas,$inasistencia,$complementarios,$alfabetizacion,
										 $necesidades,$aritmeticas,$certificado,$evidencia1,$actitudnegativa,$relacionpobre,
										 $faltaadherencia,$actitudpadres,$victimabullying,$victimariobullying,$otro,$evidencia2,
										 $calificacion,$chkeducacion,$chkdetalleotro,$chkinasistencia,$chkdetalleotrasinasistencias){
	
		$stmt = $this->conn->prepare("CALL SP_AgregaAssetEducacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$horasdedicadas,$horasefectivas,$inasistencia,$complementarios,$alfabetizacion,
							 $necesidades,$aritmeticas,$certificado,$evidencia1,$actitudnegativa,$relacionpobre,
							 $faltaadherencia,$actitudpadres,$victimabullying,$victimariobullying,$otro,$evidencia2,
							 $calificacion,$chkeducacion,$chkdetalleotro,$chkinasistencia,$chkdetalleotrasinasistencias));
		return $stmt->errorInfo();//rowCount();
	}

	/**
 	 * Método que modifica los datos de la tabla educacion
	 * Parámetros de entrada: 
	 * Parámetros de salida: no aplica
 	 */
	public function modificaAssetEducacion($id,$caso,$horasdedicadas,$horasefectivas,$inasistencia,$complementarios,$alfabetizacion,
										   $necesidades,$aritmeticas,$certificado,$evidencia1,$actitudnegativa,$relacionpobre,
										   $faltaadherencia,$actitudpadres,$victimabullying,$victimariobullying,$otro,$evidencia2,
										   $calificacion,$chkeducacion,$chkdetalleotro,$chkinasistencia,$chkdetalleotrasinasistencias){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaAssetEducacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($id,$caso,$horasdedicadas,$horasefectivas,$inasistencia,$complementarios,$alfabetizacion,
							 $necesidades,$aritmeticas,$certificado,$evidencia1,$actitudnegativa,$relacionpobre,
							 $faltaadherencia,$actitudpadres,$victimabullying,$victimariobullying,$otro,$evidencia2,
							 $calificacion,$chkeducacion,$chkdetalleotro,$chkinasistencia,$chkdetalleotrasinasistencias));
		return $stmt->rowCount();
	}
	
	/**
 	 * Método que actualiza la calificación en la tabla evaluacion
	 * Parámetros de entrada: id evaluación, id caso, nota
	 * Parámetros de salida: n° filas afectadas
 	 */
	public function modificaCalificacionEducacion($id,$caso,$nota){
	
		$stmt = $this->conn->prepare("CALL SP_ModificaCalificacionEducacion(?,?,?)");
		$stmt->execute(array($id,$caso,$nota));
		return $stmt->rowCount();
	}
	
	public function Close(){
		
    	$this->conn = null;
		
	}
}
?>