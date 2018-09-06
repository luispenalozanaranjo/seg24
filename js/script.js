//Contador de caracteres.
function getObject(obj) {
var theObj;
if(document.all) {
if(typeof obj=="string") {
return document.all(obj);
} else {
return obj.style;
}
}
if(document.getElementById) {
if(typeof obj=="string") {
return document.getElementById(obj);
} else {
return obj.style;
}
}
return null;
}

function Contar(entrada,salida,texto,caracteres) {
var entradaObj=getObject(entrada);
var salidaObj=getObject(salida);
var longitud=caracteres - entradaObj.value.length;
if(longitud <= 0) {
longitud=0;
texto='<span class="disable"> '+texto+' </span>'; entradaObj.value=entradaObj.value.substr(0,caracteres);
}
salidaObj.innerHTML = texto.replace("{CHAR}",longitud);
}
//Contador de caracteres.

function validaRut(campo){
	if ( campo.length == 0 ){ return false; }
	if ( campo.length < 8 ){ return false; }
 
	campo = campo.replace('-','')
	campo = campo.replace(/\./g,'')
 
	var suma = 0;
	var caracteres = "1234567890kK";
	var contador = 0;    
	for (var i=0; i < campo.length; i++){
		u = campo.substring(i, i + 1);
		if (caracteres.indexOf(u) != -1)
		contador ++;
	}
	if ( contador==0 ) { return false }
	
	var rut = campo.substring(0,campo.length-1)
	var drut = campo.substring( campo.length-1 )
	var dvr = '0';
	var mul = 2;
	
	for (i= rut.length -1 ; i >= 0; i--) {
		suma = suma + rut.charAt(i) * mul
                if (mul == 7) 	mul = 2
		        else	mul++
	}
	res = suma % 11
	if (res==1)		dvr = 'k'
                else if (res==0) dvr = '0'
	else {
		dvi = 11-res
		dvr = dvi + ""
	}
	if ( dvr != drut.toLowerCase() ) { return false; }
	else { return true; }
}

function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
{
valUser();
return false;
}
else
return true;
}

function redireccionar(pagina)
{
	location.href=pagina;
}

function soloNumeros(e){
key=(document.all) ? e.keyCode : e.which;
if ((key < 48 || key > 57) && key != 8){//8 = backspace
return false;
}
return true;
}//fin funcion

/*function validaAssetResumen(){
	var assetInfoAdicional = document.forms["form"]["infoadicional"].value;
	
	if(assetInfoAdicional==''){
		alert("Debe LLenar el Resumen de los Otros Campos!!");
		return false;
	}
}
*/

function cambio_posicion_fecha(str) {
    var dia = str.substring(0, 2);
    var mes = str.substring(3, 5);
    var ano = str.substring(6, 10);
	var fecha = ano+'-'+mes+'-'+dia;
    return fecha;
}

function validarAssetFechas(){
	
	var assetFechaInicio = cambio_posicion_fecha(document.forms["form_analisis"]["fecinicio"].value);  
	var assetFechaFin = cambio_posicion_fecha(document.forms["form_analisis"]["fectermino"].value);  
	var assetFechaVal2 = document.forms["form_analisis"]["fecha_evaluacion"].value;  
	
	  var assetFechaVal1 = assetFechaVal2.replace("-", "");
	var assetFechaVal = assetFechaVal1.replace("-","");
	
	var assetFechaInicioValidar = assetFechaInicio.replace("-", "");
	var assetFechaInicioValidar2 = assetFechaInicioValidar.replace("-","");
	
	var assetFechaFinValidar = assetFechaFin.replace("-", "");
	var assetFechaFinValidar2 = assetFechaFinValidar.replace("-", "");
	

	if(assetFechaInicioValidar2==''){
		alert("Debe ingresar la fecha de inicio del analisis!!");
		return false;
	} else
		if(assetFechaFinValidar2==''){
		alert("Debe ingresar la fecha fin del analisis!!");
		return false;
	} else
	if((assetFechaInicioValidar2 < assetFechaVal)&&(assetFechaInicioValidar2!='')){
		alert("La fecha de Inicio del Asset es Menor a la fecha de creacion la cual es "+document.forms["form"]["fecha_evaluacion"].value+". Favor seleccionar una fecha Mayor!!");
		return false;
	} else if((assetFechaFinValidar2 < assetFechaVal)&&(assetFechaFinValidar2!='')){
		alert("La fecha de Fin del Asset es Menor a la fecha de creacion la cual es "+document.forms["form"]["fecha_evaluacion"].value+".  Favor seleccionar una fecha Mayor!!");
		return false;
	} else if((assetFechaFinValidar2 < assetFechaInicioValidar2)&&((assetFechaInicioValidar2!='')&&(assetFechaFinValidar2!=''))){
		alert("La fecha de Fin del Asset es Menor a la Fecha de creacion seleccionada. Favor seleccionar una fecha Mayor!!");
		return false;
	}
}


function validarAssetAnalisis() {
    var assetVictimaEspecifica = document.forms["form"]["victima_especifica"].value;
	var assetVictimaVulnerable = document.forms["form"]["victima_vulnerable"].value;
	var assetVictimaRepetida = document.forms["form"]["victima_repetida"].value;
	var assetVictimaDesconocida = document.forms["form"]["victima_desconocida"].value;
	var assetMotivoRacial= document.forms["form"]["motivacion_racial"].value;
    var assetSimilitudPrevia = document.forms["form"]["similitud_previa"].value;
	var assetAumentoGravedad = document.forms["form"]["aumento_gravedad"].value; 
	var assetEspecializacion = document.forms["form"]["especializacion"].value;    
	var assetInterrupcion = document.forms["form"]["interrupcion"].value; 
	var assetIntentosDesistir = document.forms["form"]["intentos_desistir"].value; 
	var assetFechaInicio = cambio_posicion_fecha(document.forms["form"]["fecinicio"].value);  
	var assetFechaFin = cambio_posicion_fecha(document.forms["form"]["fectermino"].value);  
	var assetFechaVal2 = document.forms["form"]["fecha_evaluacion"].value;  

        var assetFechaVal1 = assetFechaVal2.replace("-", "");
	var assetFechaVal = assetFechaVal1.replace("-","");
	
	var assetFechaInicioValidar = assetFechaInicio.replace("-", "");
	var assetFechaInicioValidar2 = assetFechaInicioValidar.replace("-","");
	
	var assetFechaFinValidar = assetFechaFin.replace("-", "");
	var assetFechaFinValidar2 = assetFechaFinValidar.replace("-", "");
	
        
/*	if(assetFechaInicioValidar==''){
		alert("Debe Ingresar la Fecha de Inicio del Formulario de Analisis!!!!");
		return false;
	}else if(assetFechaFinValidar==''){
		alert("Debe Ingresar la Fecha Fin del Formulario de Analisis!!!!");
		return false;
	}else*/
	if((assetFechaInicioValidar2 < assetFechaVal)&&(assetFechaInicioValidar2!='')){
		alert("La fecha de Inicio del Asset es Menor a la fecha de creacion la cual es "+document.forms["form"]["fecha_evaluacion"].value+". Favor seleccionar una fecha Mayor!!");
		return false;
	} else if((assetFechaFinValidar2 < assetFechaVal)&&(assetFechaFinValidar2!='')){
		alert("La fecha de Fin del Asset es Menor a la fecha de creacion la cual es "+document.forms["form"]["fecha_evaluacion"].value+".  Favor seleccionar una fecha Mayor!!");
		return false;
	} else if((assetFechaFinValidar2 < assetFechaInicioValidar2)&&((assetFechaInicioValidar2!='')&&(assetFechaFinValidar2!=''))){
		alert("La fecha de Fin del Asset es Menor a la Fecha de creacion seleccionada. Favor seleccionar una fecha Mayor!!");
		return false;
	} else if(assetVictimaEspecifica==''){
		alert("Debes Seleccionar al menos una opcion para la Víctima específica");
		return false;
	} else if(assetVictimaVulnerable==''){
		alert("Debes Seleccionar al menos una opcion para la Víctima vulnerable");
		return false;
	} else if(assetVictimaRepetida==''){
		alert("Debes Seleccionar al menos una opcion para la Víctima repetida");
		return false;
	} else if(assetVictimaDesconocida==''){
		alert("Debes Seleccionar al menos una opcion para la Víc. desconocida para él/ella");
		return false;
	} else if(assetMotivoRacial==''){
		alert("Debes Seleccionar al menos una opcion para la Motivacion Racial");
		return false;
	} else if(assetSimilitudPrevia==''){
		alert("Debes Seleccionar al menos una opcion para alguna similitud o diferencia con comportamientos previos");
		return false;
	}  else if(assetAumentoGravedad==''){
		alert("Debes Seleccionar al menos una opcion para el aumento/disminución en la gravedad y/o frecuencia");
		return false;
	} else if(assetEspecializacion==''){
		alert("Debes Seleccionar al menos una opcion para la muestra una especialización/diversidad de infracciones");
		return false;
	} else if(assetInterrupcion==''){
		alert("Debes Seleccionar al menos una opcion para alguna interrupción en los patrones transgresores");
		return false;
	} else if(assetIntentosDesistir==''){
		alert("Debes Seleccionar al menos una opcion para los intentos previos de desistir");
		return false;
	}   
}

function validarAssetHogar()
{

    var a=document.form_hogar['chkviviendanna[]'];
	var sel=false;
	//alert("Tamaño del array"+a.length);
	var p=0;
	for(i=0;i<a.length;i++){
		if(a[i].checked){
			//alert(a[i].value);
			p=1;
		}
	}
	
	if (p==0){
		alert('Seleccione por lo menos una Opcion para con quién ha vivido el NNA la mayor parte del tiempo en los últimos seis meses');
		return false;
	}  
	else if(document.forms["form_hogar"]["condicion"].value==''){
		
	alert("Debe Ingresar su actual condición");
	document.forms["form_hogar"]["condicion"].focus();
	return false;	
	
	}else if(document.forms["form_hogar"]["sin_domicilio"].value==''){
		
	alert("Debe Seleccionar su Domicilio Fijo");
	return false;	
	
	}else if(document.forms["form_hogar"]["incumplimiento"].value==''){
		
	alert("Debe Seleccionar una Opcion para Inadecuado no cumple con sus necesidades");
	return false;	
	
	}else if(document.forms["form_hogar"]["hogar_deprivado"].value==''){
		
	alert("Debe Seleccionar una Opcion para Hogar deprivado");
	return false;	
	
	}else if(document.forms["form_hogar"]["vive_delincuentes"].value==''){
		
	alert("Debe Seleccionar una Opcion que indica que Vive con delincuentes conocidos");
	return false;	
	
	}else if(document.forms["form_hogar"]["situacion_calle"].value==''){
		
	alert("Debe Seleccionar una Opcion que indica que este Fugado o en situación de calle");
	return false;	
	
	}else if(document.forms["form_hogar"]["desorganizado"].value==''){
		
	alert("Debe Seleccionar una Opcion para Desorganizado/ caótico");
	return false;	
	
	}else if(document.forms["form_hogar"]["otros"].value==''){
		
	alert("Debe Seleccionar una Opcion para Otros problemas");
	return false;	
	
	}else if(document.forms["form_hogar"]["evidencia"].value==''){
		
	alert("Debe Seleccionar una Evidencia");
	document.forms["form_hogar"]["evidencia"].focus();
	return false;	
	
	}
		
}


function validarAssetRelaciones()
{
    var a=document.form_relacion['chkmiembros[]'];
	var sel=false;
	//alert("Tamaño del array"+a.length);
	var p=0;
	for(i=0;i<a.length;i++){
		if(a[i].checked){
			//alert(a[i].value);
			p=1;
		}
	}
	
	if (p==0){
		alert('Seleccione por lo menos una Opcion para los miembros de la familia o cuidadores ha estado en contacto el NNA la mayor parte del tiempo en los últimos 6 meses');
		return false;
	}  
	else if(document.forms["form_relacion"]["involucrado"].value==''){
		
	alert("Debe Seleccionar alguna opción para involucrados en actividades delictuales");
	return false;	
	
	}else if(document.forms["form_relacion"]["alcohol"].value==''){
		
	alert("Debe Seleccionar alguna opción que Presenta un consumo severo de alcohol");
	return false;	
	
	}else if(document.forms["form_relacion"]["drogas"].value==''){
		
	alert("Debe Seleccionar una opción para que Presenten un consumo severo de drogas o solventes");
	return false;	
	
	}else if(document.forms["form_relacion"]["comunicacion"].value==''){
		
	alert("Debe Seleccionar una opción para Adultos significativos fracasan en la comunicación o expresión de cuidado/interés");
	return false;	
	
	}else if(document.forms["form_relacion"]["supervision"].value==''){
		
	alert("Debe Seleccionar una opción para	Supervisión o establecimiento de límites inconsistente");
	return false;	
	
	}else if(document.forms["form_relacion"]["experiencia"].value==''){
		
	alert("Debe Seleccionar una opción para Experiencia de abuso");
	return false;	
	
	}else if(document.forms["form_relacion"]["testigo"].value==''){
		
	alert("Debe Seleccionar una opción para Testigo de violencia en el contexto familiar");
	return false;	
	
	}else if(document.forms["form_relacion"]["duelo"].value==''){
		
	alert("Debe Seleccionar una opción para Duelo o pérdida significativa");
	return false;	
	
	}else if(document.forms["form_relacion"]["cuidado"].value==''){
		
	alert("Debe Seleccionar una opción para Dificultades en el cuidado de su(s) propio(s) hijo(s)");
	return false;	
	
	}else if(document.forms["form_relacion"]["otros"].value==''){
		
	alert("Debe Seleccionar una opción para Otros problemas");
	return false;	
	
	}else if(document.forms["form_relacion"]["evidencia"].value==''){
		
	alert("Debe Seleccionar una Evidencia");
	document.forms["form_relacion"]["evidencia"].focus();
	return false;	
	
	}
		
}


function validarAssetEducacion()
{

    var a=document.form_educacion['chkeducacion[]'];
	var sel=false;
	//alert("Tamaño del array"+a.length);
	var p=0;
	for(i=0;i<a.length;i++){
		if(a[i].checked){
			//alert(a[i].value);
			p=1;
		}
	}
	
	var c=document.form_educacion['chkinasistencia[]'];
	var sele=false;
	//alert("Tamaño del array"+a.length);
	var pi=0;
	for(i1=0;i1<c.length;i1++){
		if(c[i1].checked){
			//alert(a[i].value);
			pi=1;
		}
	}
	
	if ((p==0)&&(document.forms["form_educacion"]["chkdetalleotro"].value=='')){
		alert('Seleccione por lo menos una Opcion para alguna situacion describe mejor la situación de educación, capacitación o trabajo actual o en caso que no coincida con su Seleccion, detalle algun otro');
		document.forms["form"]["chkdetalleotro"].focus();
		return false;
	}  
	else if(document.forms["form_educacion"]["horasdedicadas"].value==''){
		
	alert("Debe Ingresar cantidades de horas ECE debería dedicar a la semana");
	document.forms["form_educacion"]["horasdedicadas"].focus();
	return false;	
	
	}else if(document.forms["form_educacion"]["horasefectivas"].value==''){
		
	alert("Debe Ingresar Cuántas horas efectivas le dedica a ECE en la semana");
	document.forms["form_educacion"]["horasefectivas"].focus();
	return false;	
	
	}else if(document.forms["form_educacion"]["complementarios"].value==''){
		
	alert("Debe Seleccionar una opción que indique que tenga algún estudio complementario a la escuela");
	return false;	
	
	}else if((document.forms["form_educacion"]["inasistencia"].value=='')&&(pi==0)){
		
	alert("Debe Seleccionar una opción para evidencia de inasistencia");
	return false;	
	
	}else if(document.forms["form_educacion"]["necesidades"].value==''){
		
	alert("Debe Seleccionar una opción que indique si Tiene necesidades educativas especiales (NNE) identificadas");
	return false;	
	
	}else if(document.forms["form_educacion"]["certificado"].value==''){
		
	alert("Debe Seleccionar una opción si tiene algo que lo acredite");
	return false;	
	
	}else if(document.forms["form_educacion"]["alfabetizacion"].value==''){
		
	alert("Debe Seleccionar una opción para seleccionar alguna dificultades de alfabetización");
	return false;	
	
	}else if(document.forms["form_educacion"]["aritmeticas"].value==''){
		
	alert("Debe Seleccionar una opción para seleccionar alguna dificultad aritmeticas");
	return false;	
	
	}else if(document.forms["form_educacion"]["evidencia1"].value==''){
		
	alert("Debe Ingresar alguna descripcion para la Primera Evidencia");
	document.forms["form_educacion"]["evidencia1"].focus();
	return false;	
	
	}else if(document.forms["form_educacion"]["actitudnegativa"].value==''){
		
	alert("Debe Seleccionar una opción para Actitudes negativas hacia la educación/ capacitación/ empleo");
	return false;	
	
	}else if(document.forms["form_educacion"]["faltaadherencia"].value==''){
		
	alert("Debe Seleccionar una opción para Falta de adherencia a la actual prestación de ECE");
	return false;	
	
	}else if(document.forms["form_educacion"]["victimabullying"].value==''){
		
	alert("Debe Seleccionar una opción para Víctima de violencia/ bullying");
	return false;	
	
	}else if(document.forms["form_educacion"]["victimariobullying"].value==''){
		
	alert("Debe Seleccionar una opción para Víctimario de violencia/ bullying");
	return false;	
	
	}else if(document.forms["form_educacion"]["relacionpobre"].value==''){
		
	alert("Debe Seleccionar una opción para Pobres relaciones con la mayoría de los profesores / tutores/ empleadores/ colegios");
	return false;	
	
	}else if(document.forms["form_educacion"]["actitudpadres"].value==''){
		
	alert("Debe Seleccionar una opción para Actitudes negativas de los padres/ cuidadores hacia la educación/ capacitación o empleo");
	return false;	
	
	}else if(document.forms["form_educacion"]["otro"].value==''){
		
	alert("Debe Seleccionar una opción para Otros problemas");
	return false;	
	
	}else if(document.forms["form_educacion"]["evidencia2"].value==''){
		
	alert("Debe Ingresar alguna descripcion para la Segunda Evidencia");
	document.forms["form_educacion"]["evidencia2"].focus();
	return false;	
	
	}
		
}

function validarAssetBarrio(){

if(document.forms["form_barrio"]["descripcion"].value==''){
		
	alert("Debe Ingresar alguna Descripciòn");
	document.forms["form_barrio"]["descripcion"].focus();
	return false;	
	
	}else if(document.forms["form_barrio"]["evidenciatrafico"].value==''){
		
	alert("Debe Seleccionar una opción para Signos evidentes de tráfico y/o consumo de drogas");
	return false;	
	
	}else if(document.forms["form_barrio"]["tensionetnica"].value==''){
		
	alert("Debe Seleccionar una opción para Tensiones étnicas o raciales");
	return false;	
	
	}else if(document.forms["form_barrio"]["localidadaislada"].value==''){
		
	alert("Debe Seleccionar una opción para Localidad aislada/ falta de accesibilidad al transporte");
	return false;	
	
	}else if(document.forms["form_barrio"]["faltainstalaciones"].value==''){
		
	alert("Debe Seleccionar una opción para Falta de instalaciones apropiadas para niños y jóvenes");
	return false;	
	
	}else if(document.forms["form_barrio"]["otro"].value==''){
		
	alert("Debe Seleccionar una opción para Otros problemas");
	return false;	
	
	}else if(document.forms["form_barrio"]["evidencia"].value==''){
		
	alert("Debe Ingresar alguna Evidencia");
	document.forms["form_barrio"]["evidencia"].focus();
	return false;	
	
	}
	
}


function validarAssetEstiloDeVida(){

if(document.forms["form_estilo"]["faltaamistad"].value==''){
		
	alert("Debe Seleccionar una opción para Falta de amistades de edad apropiada al NNA");
	return false;	
	
	}else if(document.forms["form_estilo"]["asocpredominante"].value==''){
		
	alert("Debe Seleccionar una opción para Asociación predominante con pares pro-infracción/ transgresión de normas");
	return false;	
	
	}else if(document.forms["form_estilo"]["faltaasociacion"].value==''){
		
	alert("Debe Seleccionar una opción para Falta de asociación con pares no infractores/ transgresores de norma");
	return false;	
	
	}else if(document.forms["form_estilo"]["tiempolibre"].value==''){
		
	alert("Debe Seleccionar una opción para No tiene mucho que hacer en su tiempo libre");
	return false;	
	
	}else if(document.forms["form_estilo"]["actividadriesgo"].value==''){
		
	alert("Debe Seleccionar una opción para la Participación en actividades de riesgo");
	return false;	
	
	}else if(document.forms["form_estilo"]["dineroinsuficiente"].value==''){
		
	alert("Debe Seleccionar una opción para el Insuficiente dinero obtenido de manera legítima");
	return false;	
	
	}else if(document.forms["form_estilo"]["otro"].value==''){
		
	alert("Debe Seleccionar una opción para Otros problemas");
	return false;	
	
	}else if(document.forms["form_estilo"]["evidencia"].value==''){
		
	alert("Debe Ingresar alguna Evidencia");
	document.forms["form_estilo"]["evidencia"].focus();
	return false;	
	
	}
	
}


function validarAssetSustancias(){
	
	
    if((document.forms["form_sustancias"]["tabaco"].value=='')||(document.forms["form_sustancias"]["tabaco_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion de Tabaco y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["tabaco_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["alcohol"].value=='')||(document.forms["form_sustancias"]["alcohol_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Alcohol y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["alcohol_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["solventes"].value=='')||(document.forms["form_sustancias"]["solventes_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Solvente y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["solventes_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["cannabis"].value=='')||(document.forms["form_sustancias"]["cannabis_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para la Cannabis y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["cannabis_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["pastabase"].value=='')||(document.forms["form_sustancias"]["pastabase_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para la Pasta Base y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["pastabase_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["cocaina"].value=='')||(document.forms["form_sustancias"]["cocaina_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para la Cocaina y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["cocaina_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["anfetamina"].value=='')||(document.forms["form_sustancias"]["anfetamina_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para la Cocaina y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["cocaina_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["tranquilizante"].value=='')||(document.forms["form_sustancias"]["tranquilizante_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Tranquilizante y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["tranquilizante_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["extasis"].value=='')||(document.forms["form_sustancias"]["extasis_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Extasis y en caso que este el valor en blanco, favor ingresar a que edad lo uso");
	document.forms["form_sustancias"]["extasis_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["lcd"].value=='')||(document.forms["form_sustancias"]["lcd_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Consumo de LSD y en caso que este el valor en blanco, favor ingresar a que edad lo Consumio");
	document.forms["form_sustancias"]["lcd_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["inhalantes"].value=='')||(document.forms["form_sustancias"]["inhalantes_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el consumo de Inhalantes y en caso que este el valor en blanco, favor ingresar a que edad comenzo a consumirlo");
	document.forms["form_sustancias"]["inhalantes_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["crack"].value=='')||(document.forms["form_sustancias"]["crack_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Consumo de Crack y en caso que este el valor en blanco, favor ingresar a que edad comenzo a consumirlo");
	document.forms["form_sustancias"]["crack_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["heroina"].value=='')||(document.forms["form_sustancias"]["heroina_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para la Heroina y en caso que este el valor en blanco, favor ingresar a que edad comenzo a Consumirlo");
	document.forms["form_sustancias"]["heroina_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["metadona"].value=='')||(document.forms["form_sustancias"]["metadona_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Consumo de Metadona y en caso que este el valor en blanco, favor ingresar a que edad comenzo a consumirlo");
	document.forms["form_sustancias"]["metadona_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["esteroides"].value=='')||(document.forms["form_sustancias"]["esteroides_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el consumo de Esteroides y en caso que este el valor en blanco, favor ingresar a que edad empezo a consumirlo");
	document.forms["form_sustancias"]["esteroides_edad"].focus();
	return false;	
	
	}else if((document.forms["form_sustancias"]["otros"].value=='')||(document.forms["form_sustancias"]["otros_edad"].value=='')){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para Otros y en caso que este el valor en blanco, favor ingresar a que dia su Primer Uso");
	document.forms["form_sustancias"]["otros_edad"].focus();
	return false;	
	
	}else if(document.forms["form_sustancias"]["nnariesgo"].value==''){
		
	alert("Debe Seleccionar alguna opcion para Alguna Práctica(s) que ponen al NNA en riesgo");
	return false;	
	
	}else if(document.forms["form_sustancias"]["usopositivo"].value==''){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el uso de sustancias como algo positivo y/o escencial en la vida");
	return false;	
	
	}else if(document.forms["form_sustancias"]["educacion"].value==''){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Notable efecto perjudicial en la educación, relaciones interpersonales, funcionamiento diario");
	return false;	
	
	}else if(document.forms["form_sustancias"]["infracciones"].value==''){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna opcion para el Comete infracciones para obtener dinero para consumir sustancias");
	return false;	
	
	}else if(document.forms["form_sustancias"]["otro"].value==''){
		
	alert("En Uso de Sustancias Debe Seleccionar alguna Otra Opcion en caso que no sea la que Coincida con su Opcion");
	return false;	
	
	}else if(document.forms["form_sustancias"]["evidencia"].value==''){
		
	alert("Debe Ingresar alguna evidencia");
	document.forms["form_sustancias"]["evidencia"].focus();
	return false;	
	
	}

}

function validarAssetSaludFisica(){

    if(document.forms["form_saludfisica"]["condiciones"].value==''){
		
	alert("Debe Seleccionar Alguna Condiciòn");
	return false;	
	
	} else if(document.forms["form_saludfisica"]["inmadurez"].value==''){
		
	alert("Debe Seleccionar alguna Opcion en Inmadurez");
	return false;	
	
	}else if(document.forms["form_saludfisica"]["acceso"].value==''){
		
	alert("Debe Seleccionar alguna Opcion en Falta de acceso");
	return false;	
	
	}else if(document.forms["form_saludfisica"]["riesgo"].value==''){
		
	alert("Debe Seleccionar alguna Opcion en Salud puesta en riesgo");
	return false;	
	
	}else if(document.forms["form_saludfisica"]["otro"].value==''){
		
	alert("Debe Seleccionar alguna lista en Otro");
	return false;	
	
	}else if(document.forms["form_saludfisica"]["evidencia"].value==''){
		
	alert("Debe Ingresar en evidencia");
	document.forms["form_saludfisica"]["evidencia"].focus();
	return false;	
	
	}
	
}

function validarAssetSaludMental(){

	 if(document.forms["form_saludmental"]["acontecimientos"].value==''){
		
	alert("Debe Seleccionar Algun Acontecimiento");
	return false;	
	
	} else if(document.forms["form_saludmental"]["circunstancias"].value==''){
		
	alert("Debe Seleccionar alguna Opcion en Circuntancia(s)");
	return false;	
	
	}else if(document.forms["form_saludmental"]["preocupaciones"].value==''){
		
	alert("Debe Seleccionar alguna Opcion en Precauciones");
	return false;	
	
	}else if(document.forms["form_saludmental"]["evidencia1"].value==''){
		
	alert("Debe Ingresar Una Primera Evidencia");
	document.forms["form_saludmental"]["evidencia1"].focus();
	return false;	
	
	}else if(document.forms["form_saludmental"]["diagnostico"].value==''){
		
	alert("Debe Seleccionar alguna lista en Diagnostico");
	return false;	
	
	}else if(document.forms["form_saludmental"]["derivacion"].value==''){
		
	alert("Debe Seleccionar alguna lista en Derivacion");
	return false;	
	
	}else if(document.forms["form_saludmental"]["evidencia2"].value==''){
		
	alert("Debe Ingresar Una Segunda Evidencia");
	document.forms["form_saludmental"]["evidencia2"].focus();
	return false;	
	
	}else if(document.forms["form_saludmental"]["afectado"].value==''){
		
	alert("Debe Seleccionar algun Afectado");
	return false;	
	
	}else if(document.forms["form_saludmental"]["provocadano"].value==''){
		
	alert("Debe Seleccionar algun Daño Provocado");
	return false;	
	
	}else if(document.forms["form_saludmental"]["suicidio"].value==''){
		
	alert("Debe Seleccionar algun Suicidio en Lista");
	return false;	
	
	}else if(document.forms["form_saludmental"]["evidencia3"].value==''){
		
	alert("Debe Ingresar una Tercera Evidencia");
	document.forms["form_saludmental"]["evidencia3"].focus();
	return false;	
	
	}
	
}

function validarAssetPercepcion(){
	
 if(document.forms["form_percepcion"]["identidad"].value==''){
		
	alert("Debe Seleccionar Algun Problema de Identidad");
	return false;	
	
	} else if(document.forms["form_percepcion"]["autoestima"].value==''){
		
	alert("Debe Seleccionar alguna Autoestima Inapropiada");
	return false;	
	
	}else if(document.forms["form_percepcion"]["desconfianza"].value==''){
		
	alert("Debe Seleccionar alguna Desconfianza en General");
	return false;	
	
	}else if(document.forms["form_percepcion"]["discriminado"].value==''){
		
	alert("Debe Seleccionar alguna Se ve a sí mismo como una víctima de discriminación");
	return false;	
	
	}else if(document.forms["form_percepcion"]["discriminador"].value==''){
		
	alert("Debe Seleccionar Alguna actitudes discriminatorias hacia otros");
	return false;	
	
	}else if(document.forms["form_percepcion"]["criminal"].value==''){
		
	alert("Debe Seleccionar si se percibe a sí mismo con una identidad criminal");
	return false;	
	
	}else if(document.forms["form_percepcion"]["evidencia"].value==''){
		
	alert("Debe Ingresar Una Evidencia");
	document.forms["form_percepcion"]["evidencia"].focus();
	return false;	
	
	}	
	
}


function validarAssetComportamiento(){

    if(document.forms["form_comportamiento"]["faltacomprension"].value==''){
		
	alert("Debe Seleccionar una Falta de Comprension en Lista");
	return false;	
	
   }else if(document.forms["form_comportamiento"]["impulsividad"].value==''){
		
	alert("Debe Seleccionar una Impulsividad de la Lista");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["emociones"].value==''){
		
	alert("Debe Seleccionar una Necesidad de Emociones en Linea");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["faltaasertividad"].value==''){
		
	alert("Debe Seleccionar una falta de asertividad");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["temperamental"].value==''){
		
	alert("Debe Seleccionar una Impulsividad de la Lista");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["habilidades"].value==''){
		
	alert("Debe Seleccionar un Listado de Habilidades sociales y comunicacionales inapropiadas");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["propiedad"].value==''){
		
	alert("Debe Seleccionar un Listado para Destrucción de la propiedad");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["agresion"].value==''){
		
	alert("Debe Seleccionar un Listado para Agresiónes hacia otros");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["sexual"].value==''){
		
	alert("Debe Seleccionar un Listado para el Comportamiento sexual inapropiado");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["manipulacion"].value==''){
		
	alert("Debe Seleccionar un Listado para Intentos de manipular/ controlar a otros");
	return false;	
	
	}else if(document.forms["form_comportamiento"]["evidencia"].value==''){
		
	alert("Debe Ingresar una Evidencia");
	document.forms["form_comportamiento"]["evidencia"].focus();
	return false;	
	
	}	
	
}


function validarAssetActitud(){

 if(document.forms["form_actitud"]["negacion"].value==''){
		
	alert("Debe Seleccionar para la Negación de la seriedad de su comportamiento");
	return false;	
	
   }else if(document.forms["form_actitud"]["reticente"].value==''){
		
	alert("Debe Seleccionar una Opcion para la Reticencia a aceptar cualquier responsabilidad por su participación en la infracción");
	return false;	
	
	}else if(document.forms["form_actitud"]["comprensionvictima"].value==''){
		
	alert("Debe Seleccionar una Opcion para la Falta de comprensión sobre los efectos de su comportamiento en la(s) víctima(s)");
	return false;	
	
	}else if(document.forms["form_actitud"]["faltaremordimiento"].value==''){
		
	alert("Debe Seleccionar opcion para la Falta de remordimiento");
	return false;	
	
	}else if(document.forms["form_actitud"]["comprensionimpacto"].value==''){
		
	alert("Debe Seleccionar una Opcion para la Falta de comprensión respecto al impacto de su comportamiento en la familia/ cuidadores");
	return false;	
	
	}else if(document.forms["form_actitud"]["infraccionaceptable"].value==''){
		
	alert("Debe Seleccionar un Listado para La creencia de que ciertos tipos de infracción son aceptables");
	return false;	
	
	}else if(document.forms["form_actitud"]["objetivoaceptable"].value==''){
		
	alert("Debe Seleccionar un Listado para La creencia de que ciertos grupos son objetivos alcanzables para cometer infracciones");
	return false;	
	
	}else if(document.forms["form_actitud"]["infraccioninevitable"].value==''){
		
	alert("Debe Seleccionar un Listado para El o la adolescente cree que cometer futuras infracciones es inevitable");
	return false;	
	
	}else if(document.forms["form_actitud"]["evidencia"].value==''){
		
	alert("Debe Ingresar una Evidencia");
	document.forms["form_actitud"]["evidencia"].focus();
	return false;	
	
	}
	
}


function validarAssetMotivacion(){

if(document.forms["form_motivacion"]["comprendecomportamiento"].value==''){
		
	alert("Debe Seleccionar una Opcion para la comprensión adecuada de los aspectos problemáticos de su propio comportamiento");
	return false;	
	
   }else if(document.forms["form_motivacion"]["resolverproblemas"].value==''){
		
	alert("Debe Seleccionar una Opcion para la Muestra evidencias concretas de querer resolver los problemas de su vida");
	return false;	
	
	}else if(document.forms["form_motivacion"]["comprendeconsecuencias"].value==''){
		
	alert("Debe Seleccionar una Opcion para el Comprender las consecuencias para el mismo de futuras infracciones)");
	return false;	
	
	}else if(document.forms["form_motivacion"]["identificaincentivos"].value==''){
		
	alert("Debe Seleccionar una Opcion para Identificar claramente las razones o incentivos para evitar futuras infracciones");
	return false;	
	
	}else if(document.forms["form_motivacion"]["muestraevidencia"].value==''){
		
	alert("Debe Seleccionar una Opcion para la Muestra evidencias concretas de querer dejar de incurrir en comportamientos infractores");
	return false;	
	
	}else if(document.forms["form_motivacion"]["apoyofamiliar"].value==''){
		
	alert("Debe Seleccionar un Listado para Recibir apoyo de la familia, amigos y otras personas durante la intervención");
	return false;	
	
	}else if(document.forms["form_motivacion"]["objetivoaceptable"].value==''){
		
	alert("Debe Seleccionar un Listado para La creencia de que ciertos grupos son objetivos alcanzables para cometer infracciones");
	return false;	
	
	}else if(document.forms["form_motivacion"]["cooperacion"].value==''){
		
	alert("Debe Seleccionar un Listado para Estar dispuesto a cooperar con los demás (familia, otras agencias) para lograr un cambio");
	return false;	
	
	}else if(document.forms["form_motivacion"]["evidencia"].value==''){
		
	alert("Debe Ingresar Alguna Evidencia");
	document.forms["form_motivacion"]["evidencia"].focus();
	return false;	
	
	}

}


function validaAssetResumen(){


   if((document.forms["form_evaluacion"]["an_fecinicio"].value=='')&&
   (document.forms["form_evaluacion"]["an_fectermino"].value=='')){
	   
	alert("Debe ingresar la etapa de analisis la fecha inicio y/o final de la evaluacion o Reevaluacion del ASSET");
	return false;	

   } else if((document.forms["form_evaluacion"]["ho_sin_domicilio"].value=='')||
   (document.forms["form_evaluacion"]["ho_incumplimiento"].value=='')||
   (document.forms["form_evaluacion"]["ho_hogar_deprivado"].value=='')||
   (document.forms["form_evaluacion"]["ho_vive_delincuentes"].value=='')||
   (document.forms["form_evaluacion"]["ho_situacion_calle"].value=='')||
   (document.forms["form_evaluacion"]["ho_desorganizado"].value=='')||
   (document.forms["form_evaluacion"]["ho_otros"].value=='')){
		
	alert("Debe completar la etapa de hogar de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["re_involucrado"].value=='')||
   (document.forms["form_evaluacion"]["re_alcohol"].value=='')||
   (document.forms["form_evaluacion"]["re_drogas"].value=='')||
   (document.forms["form_evaluacion"]["re_comunicacion"].value=='')||
   (document.forms["form_evaluacion"]["re_supervision"].value=='')||
   (document.forms["form_evaluacion"]["re_experiencia"].value=='')||
   (document.forms["form_evaluacion"]["re_testigo"].value=='')||
   (document.forms["form_evaluacion"]["re_duelo"].value=='')||
   (document.forms["form_evaluacion"]["re_cuidado"].value=='')||
   (document.forms["form_evaluacion"]["re_otros"].value=='')){
		
	alert("Debe completar la etapa de relaciones de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form"]["ed_complementarios"].value=='')||
   (document.forms["form_evaluacion"]["ed_necesidades"].value=='')||
   (document.forms["form_evaluacion"]["ed_certificado"].value=='')||
   (document.forms["form_evaluacion"]["ed_alfabetizacion"].value=='')||
   (document.forms["form_evaluacion"]["ed_aritmeticas"].value=='')||
   (document.forms["form_evaluacion"]["ed_actitudnegativa"].value=='')||
   (document.forms["form_evaluacion"]["ed_faltaadherencia"].value=='')||
   (document.forms["form_evaluacion"]["ed_victimabullying"].value=='')||
   (document.forms["form_evaluacion"]["ed_victimariobullying"].value=='')||
   (document.forms["form_evaluacion"]["ed_relacionpobre"].value=='')||
   (document.forms["form_evaluacion"]["ed_actitudpadres"].value=='')||
   (document.forms["form_evaluacion"]["ed_otro"].value=='')){
		
	alert("Debe completar la etapa de educacion de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["ba_evidenciatrafico"].value=='')||
   (document.forms["form_evaluacion"]["ba_tensionetnica"].value=='')||
   (document.forms["form_evaluacion"]["ba_localidadaislada"].value=='')||
   (document.forms["form_evaluacion"]["ba_faltainstalaciones"].value=='')||
   (document.forms["form_evaluacion"]["ba_otro"].value=='')){
		
	alert("Debe completar la etapa de barrio de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["es_faltaamistad"].value=='')||
   (document.forms["form_evaluacion"]["es_actividadriesgo"].value=='')||
   (document.forms["form_evaluacion"]["es_asocpredominante"].value=='')||
   (document.forms["form_evaluacion"]["es_dineroinsuficiente"].value=='')||
   (document.forms["form_evaluacion"]["es_faltaasociacion"].value=='')||
   (document.forms["form_evaluacion"]["es_tiempolibre"].value=='')||
   (document.forms["form_evaluacion"]["es_otro"].value=='')){
		
	alert("Debe completar la etapa de estilo de vida de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["su_tabaco"].value=='')||
   (document.forms["form_evaluacion"]["su_cannabis"].value=='')||
   (document.forms["form_evaluacion"]["su_anfetamina"].value=='')||
   (document.forms["form_evaluacion"]["su_lcd"].value=='')||
   (document.forms["form_evaluacion"]["su_heroina"].value=='')||
   (document.forms["form_evaluacion"]["su_alcohol"].value=='')||
   (document.forms["form_evaluacion"]["su_pastabase"].value=='')||
   (document.forms["form_evaluacion"]["su_tranquilizante"].value=='')||
   (document.forms["form_evaluacion"]["su_inhalantes"].value=='')||
   (document.forms["form_evaluacion"]["su_metadona"].value=='')||
   (document.forms["form_evaluacion"]["su_solventes"].value=='')||
   (document.forms["form_evaluacion"]["su_cocaina"].value=='')||
   (document.forms["form_evaluacion"]["su_extasis"].value=='')||
   (document.forms["form_evaluacion"]["su_crack"].value=='')||
   (document.forms["form_evaluacion"]["su_esteroides"].value=='')||
   (document.forms["form_evaluacion"]["su_otro"].value=='')||
   (document.forms["form_evaluacion"]["su_nnariesgo"].value=='')||
   (document.forms["form_evaluacion"]["su_usopositivo"].value=='')||
   (document.forms["form_evaluacion"]["su_educacion"].value=='')||
   (document.forms["form_evaluacion"]["su_infracciones"].value=='')||
   (document.forms["form_evaluacion"]["su_otros"].value=='')){
		
	alert("Debe completar la etapa de sustancias de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["sf_condiciones"].value=='')||
   (document.forms["form_evaluacion"]["sf_inmadurez"].value=='')||
   (document.forms["form_evaluacion"]["sf_acceso"].value=='')||
   (document.forms["form_evaluacion"]["sf_riesgo"].value=='')||
   (document.forms["form_evaluacion"]["sf_otro"].value=='')){
		
	alert("Debe completar la etapa de salud fisica de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["sm_acontecimientos"].value=='')||
   (document.forms["form_evaluacion"]["sm_circunstancias"].value=='')||
   (document.forms["form_evaluacion"]["sm_preocupaciones"].value=='')||
   (document.forms["form_evaluacion"]["sm_diagnostico"].value=='')||
   (document.forms["form_evaluacion"]["sm_derivacion"].value=='')||
   (document.forms["form_evaluacion"]["sm_afectado"].value=='')||
   (document.forms["form_evaluacion"]["sm_provocadano"].value=='')||
   (document.forms["form_evaluacion"]["sm_suicidio"].value=='')){
		
	alert("Debe completar la etapa de salud mental de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["pe_identidad"].value=='')||
   (document.forms["form_evaluacion"]["pe_autoestima"].value=='')||
   (document.forms["form_evaluacion"]["pe_desconfianza"].value=='')||
   (document.forms["form_evaluacion"]["pe_discriminado"].value=='')||
   (document.forms["form_evaluacion"]["pe_discriminador"].value=='')||
   (document.forms["form_evaluacion"]["pe_criminal"].value=='')){
		
	alert("Debe completar la etapa de percepcion de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["com_faltacomprension"].value=='')||
   (document.forms["form_evaluacion"]["com_impulsividad"].value=='')||
   (document.forms["form_evaluacion"]["com_emociones"].value=='')||
   (document.forms["form_evaluacion"]["com_faltaasertividad"].value=='')||
   (document.forms["form_evaluacion"]["com_temperamental"].value=='')||
   (document.forms["form_evaluacion"]["com_habilidades"].value=='')||
   (document.forms["form_evaluacion"]["com_propiedad"].value=='')||
   (document.forms["form_evaluacion"]["com_agresion"].value=='')||
   (document.forms["form_evaluacion"]["com_sexual"].value=='')||
   (document.forms["form_evaluacion"]["com_manipulacion"].value=='')){
		
	alert("Debe completar la etapa de comportamiento de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["ac_negacion"].value=='')||
   (document.forms["form_evaluacion"]["ac_reticente"].value=='')||
   (document.forms["form_evaluacion"]["ac_comprensionvictima"].value=='')||
   (document.forms["form_evaluacion"]["ac_faltaremordimiento"].value=='')||
   (document.forms["form_evaluacion"]["ac_comprensionimpacto"].value=='')||
   (document.forms["form_evaluacion"]["ac_infraccionaceptable"].value=='')||
   (document.forms["form_evaluacion"]["ac_objetivoaceptable"].value=='')||
   (document.forms["form_evaluacion"]["ac_infraccioninevitable"].value=='')){
		
	alert("Debe completar la etapa de actitud de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if((document.forms["form_evaluacion"]["mo_comprendecomportamiento"].value=='')||
   (document.forms["form_evaluacion"]["mo_resolverproblemas"].value=='')||
   (document.forms["form_evaluacion"]["mo_comprendeconsecuencias"].value=='')||
   (document.forms["form_evaluacion"]["mo_identificaincentivos"].value=='')||
   (document.forms["form_evaluacion"]["mo_muestraevidencia"].value=='')||
   (document.forms["form_evaluacion"]["mo_apoyofamiliar"].value=='')||
   (document.forms["form_evaluacion"]["mo_cooperacion"].value=='')){
		
	alert("Debe completar la etapa de motivacion de la evaluacion o Reevaluacion del ASSET");
	return false;	
	
   }else if(document.forms["form_evaluacion"]["infoadicional"].value==''){
		
	alert("Debe Ingresar almenos Una evidencia Pertenecientes a los Otros Modulos y/o Escribir en esta");
	document.forms["form_evaluacion"]["infoadicional"].focus();
	return false;	
	
   }
	
}

function validar_fecha_contactabilidad(){

var fecha_caso = document.forms["form"]["fecha_caso"].value;
var assetFechaCaso = fecha_caso.replace("-", "");
var assetFechaCaso2 = assetFechaCaso.replace("-", "");

var fecha_ingreso_contactabilidad = cambio_posicion_fecha(document.forms["form"]["fecha"].value);
var fechaIngresoCaso = fecha_ingreso_contactabilidad.replace("-", "");
var fechaIngresoCaso2 = fechaIngresoCaso.replace("-", "");

if((fechaIngresoCaso2<assetFechaCaso2)&&(fechaIngresoCaso2!='')){
    alert("Debe Ingresar una fecha Superior a la creada por el caso que fue el dia "+fecha_caso);
	document.forms["form"]["fecha"].focus();
	return false;	
}
	
}


function validarAssetRevision(){

if(document.forms["form_revision"]["comentario"].value==''){
    alert("Debe Ingresar un comentario un motivo para la aceptacion o rechazo de la Evaluacion o Reevaluacion");
	document.forms["form_revision"]["comentario"].value;
	return false;	
}
	
}
/*function validarAssetHogar() {
	
if (IsChk('chkviviendanna'))
{
//ok, hay al menos 1 elemento checkeado envía el form!
return true;
} else {
//ni siquiera uno chequeado no envía el form
alert ('Debes Seleccionar Con quién ha vivido el NNA la mayor parte del tiempo en los últimos seis meses!');
return false;
}
}

function validarAssetRelaciones() {
	
if (IsChk('chkmiembros'))
{
//ok, hay al menos 1 elemento checkeado envía el form!
return true;
} else {
//ni siquiera uno chequeado no envía el form
alert ('Debes Seleccionar Con cuáles de los miembros de la familia o cuidadores ha estado en contacto el NNA la mayor parte del tiempo en los últimos 6 meses!');
return false;
}
}


function validarAssetEducacion() {
	
if ((IsChk('chkeducacion')) && (IsChk('chkinasistencia')))
{
//ok, hay al menos 1 elemento checkeado envía el form!
return true;
} else {
//ni siquiera uno chequeado no envía el form
alert ('Debes Seleccionar Una de las Listas para la situación de educación asi como sobre la evidencia de inasistencia!');
return false;
} 
}


function IsChk(chkName)
{
var found = false;
var chk = document.getElementsByName(chkName+'[]');
for (var i=0 ; i < chk.length ; i++)
{
found = chk[i].checked ? true : found;
}
return found;
}*/
/*
a->"+'\u00e1'+"
e->"+'\u00e9'+"
i->"+'\u00ed'+"
o->"+'\u00f3'+"
u->"+'\u00fa'+"
ñ->"+'\u00f1'+"
¿->"+'\u00BF'+"
*/