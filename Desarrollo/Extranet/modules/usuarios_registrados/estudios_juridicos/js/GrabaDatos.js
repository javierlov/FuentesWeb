function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
 
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}
 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	  xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

/*
function crearXMLHttpRequest() 
{
  var xmlHttp=null;
  if (window.ActiveXObject) 
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  else 
    if (window.XMLHttpRequest) 
      xmlHttp = new XMLHttpRequest();
  return xmlHttp;
}
 
//Función para recoger los datos del formulario y enviarlos por post  
function enviarDatosEmpleado(Domicilio, Telefonos, Fax, Email, usuario, idJuicio){
 
  //div donde se mostrará lo resultados
  divResultado = document.getElementById('lblErrores');
  //recogemos los valores de los inputs  
  //instanciamos el objetoAjax
  ajax=objetoAjax();
   
  //ObtenerDatosJuiciosParteDemandada.php
  var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php";
  
  //false = modo sincrono...
  ajax.open("POST", pagefunciones,false); 
 
  ajax.onreadystatechange=function() {
	  //la función responseText tiene todos los datos pedidos al servidor
  	if (ajax.readyState==4) {
  		//mostrar resultados en esta capa
		divResultado.innerHTML = ajax.responseText
  		//llamar a funcion para limpiar los inputs
		LimpiarCampos();
	}	

 }
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores a *.php para que inserte los datos
	//UpdateMasDatosJuicios($Domicilio, $Telefonos, $Fax, $Email, $usuario, $idJuicio);		
	ajax.send("FUNCION=UpdateMasDatosJuicios&Domicilio="+Domicilio+
				"&Telefonos="+encodeURIComponent(Telefonos)+
				"&Fax="+encodeURIComponent(Fax)+
				"&Email="+encodeURIComponent(Email)+
				"&usuario="+usuario+
				"&idJuicio="+idJuicio);
				
	if(divResultado.innerHTML.trim() == ''){
		return true;
	}
	return false;
}
*/ 
function LimpiarCampos(){  
	//función para limpiar los campos sin implementar
}

//--------------------------------------------------------------------------------
function SalvarPeritoABM(Accion, nombre, apellido, cuil, tipoperito, parteoficio, usuario, direccion, email, telefono, idperito){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	if(Accion  == 'EDIT'){	var funcionEjecuta = 'UpdatePerito';	}
	if(Accion  == 'ALTA'){	var funcionEjecuta = 'InsertarPeritoNuevo';	}
	
	var strparametros = "FUNCION="+funcionEjecuta+
				"&id="+encodeURIComponent(idperito)+
				"&nombre="+encodeURIComponent(nombre)+
				"&apellido="+encodeURIComponent(apellido)+
				"&cuil="+encodeURIComponent(cuil)+
				"&tipoperito="+encodeURIComponent(tipoperito)+
				"&parteoficio="+encodeURIComponent(parteoficio)+
				"&usuario="+encodeURIComponent(usuario)+
				"&direccion="+encodeURIComponent(direccion) +
				"&email="+encodeURIComponent(email)+
				"&telefono="+encodeURIComponent(telefono);
	
	var IDdivresult = 'lblErrores';	
	if(Accion  == 'EDIT') return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);	
	
	if(Accion  == 'ALTA') {
		var resuljson = SalvarDatosJSON(pagefunciones, strparametros, IDdivresult);	
		var datos = JSON.parse(resuljson);
		
		for (var i=0; i < datos.length; i++){				
			result = datos[i].ID;		
			
		}
		return result;
	}
	
}
//--------------------------------------------------------------------------------
function SalvarModificacionCYQ(Accion, txtsindico, txtdireccion, txtlocaclidad, txtfuero,	txttelefono, txtjurisdiccion, txtjuzgado, txtsecretaria, fechaconcurso, fechaquiebra, fechaart32, fechaart200, fverificacioncredito, usuario, nroorden, montoprivilegio, montoquirografario){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	if(Accion  == 'EDIT'){	
		var funcionEjecuta = 'UpdateConcursoyquiebras';
	}
/*
UpdateConcursoyquiebras($txtsindico, $txtdireccion, $txtlocaclidad, $txtfuero, 
		$txttelefono, $txtjurisdiccion, $txtjuzgado, 
		$txtsecretaria, $fechaconcurso, $fechaquiebra, 
		$fechaart32, $fechaart200, $fverificacioncredito, 
		$usuario, $nroorden, $montoprivilegio, $montoquirografario)
		*/	
	var strparametros = "FUNCION="+funcionEjecuta+
				"&txtsindico="+encodeURIComponent(txtsindico)+
				"&txtdireccion="+encodeURIComponent(txtdireccion)+
				"&txtlocaclidad="+encodeURIComponent(txtlocaclidad)+
				"&txtfuero="+encodeURIComponent(txtfuero)+
				"&txttelefono="+encodeURIComponent(txttelefono)+
				"&txtjurisdiccion="+encodeURIComponent(txtjurisdiccion)+
				"&txtjuzgado="+encodeURIComponent(txtjuzgado)+
				"&txtsecretaria="+encodeURIComponent(txtsecretaria)+
				"&fechaconcurso="+encodeURIComponent(fechaconcurso)+
				"&fechaquiebra="+encodeURIComponent(fechaquiebra)+
				"&fechaart32="+encodeURIComponent(fechaart32)+
				"&fechaart200="+encodeURIComponent(fechaart200)+
				"&fverificacioncredito="+encodeURIComponent(fverificacioncredito)+
				"&usuario="+encodeURIComponent(usuario)+
				"&nroorden="+encodeURIComponent(nroorden)+
				"&montoprivilegio="+encodeURIComponent(montoprivilegio)+
				"&montoquirografario="+encodeURIComponent(montoquirografario);
				
	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);	
	
}
//--------------------------------------------------------------------------------
function SalvarCuotas(Accion, txtFecha, cantcuota, periodicidadCuotas, txtMonto, usuario, nroorden, cmbTipo){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";	
	if(Accion  == 'ALTA'){var funcionEjecuta = 'InsertarCuotas'; }
	
	var strparametros = "FUNCION="+funcionEjecuta+
				"&txtFecha="+encodeURIComponent(txtFecha)+
				"&cantcuota="+encodeURIComponent(cantcuota)+
				"&periodicidadCuotas="+encodeURIComponent(periodicidadCuotas)+
				"&txtMonto="+encodeURIComponent(txtMonto)+
				"&usuario="+encodeURIComponent(usuario)+
				"&nroorden="+encodeURIComponent(nroorden)+
				"&cmbTipo="+encodeURIComponent(cmbTipo);				

	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);	
}
//--------------------------------------------------------------------------------
function SalvarAcuerdosABM(Accion, txtfechavenc, txtMonto, txtfechapago, txtobservaciones, usuario, nroorden, NroPago, txtFechaExtincion, cmbTipo){
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	if(Accion  == 'EDIT'){
		var funcionEjecuta = 'UpdateAcuerdosABM';		
	}
	if(Accion  == 'ALTA'){
		var funcionEjecuta = 'InsertarAcuerdoNuevo';		
	}
	
	var strparametros = "FUNCION="+funcionEjecuta+
				"&txtfechavenc="+encodeURIComponent(txtfechavenc)+
				"&txtMonto="+encodeURIComponent(txtMonto)+
				"&txtfechapago="+encodeURIComponent(txtfechapago)+
				"&txtobservaciones="+encodeURIComponent(txtobservaciones)+
				"&usuario="+encodeURIComponent(usuario)+
				"&nroorden="+encodeURIComponent(nroorden)+
				"&NroPago="+encodeURIComponent(NroPago)+
				"&txtFechaExtincion="+encodeURIComponent(txtFechaExtincion)+
				"&cmbTipo="+encodeURIComponent(cmbTipo);
				

	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);		
}
//--------------------------------------------------------------------------------
function SalvarEventosCYQABM(Accion, txtfecha, txtobservaciones, usuario, cmbEventos, nroorden, nroevento){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	if(Accion  == 'EDIT'){
		var funcionEjecuta = 'UpdateEventosCYQABM';		
	}
	if(Accion  == 'ALTA'){
		var funcionEjecuta = 'InsertarEventoCYQNuevo';		
	}
			
	var strparametros = "FUNCION="+funcionEjecuta+
				"&txtfecha="+encodeURIComponent(txtfecha)+
				"&txtobservaciones="+encodeURIComponent(txtobservaciones)+
				"&usuario="+encodeURIComponent(usuario)+
				"&cmbEventos="+encodeURIComponent(cmbEventos)+
				"&nroorden="+encodeURIComponent(nroorden)+
				"&nroevento="+encodeURIComponent(nroevento);

	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
	
}	
//--------------------------------------------------------------------------------
function SalvarAdmin(Accion, txtResProbable, cmbEstado, usuario,
	NroJuicio, cmbJurisdiccion, cmbFuero, cmbJuzgadoNro, cmbSecretaria, txtNroExp, txtAnioExp){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	if(Accion  == 'EDIT'){
		var funcionEjecuta = 'UpdateAdmin';		
	}
			
	var strparametros = "FUNCION="+funcionEjecuta+
		"&NroJuicio="+encodeURIComponent(NroJuicio)+
		"&cmbJurisdiccion="+encodeURIComponent(cmbJurisdiccion)+
		"&cmbFuero="+encodeURIComponent(cmbFuero)+
		"&cmbJuzgadoNro="+encodeURIComponent(cmbJuzgadoNro)+
		"&cmbSecretaria="+encodeURIComponent(cmbSecretaria)+
		"&txtNroExp="+encodeURIComponent(txtNroExp)+
		"&txtAnioExp="+encodeURIComponent(txtAnioExp)+		
		"&txtResProbable="+encodeURIComponent(txtResProbable)+		
		"&cmbEstado="+encodeURIComponent(cmbEstado)+
		"&usuario="+encodeURIComponent(usuario);
	
	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
		
}
//--------------------------------------------------------------------------------
function SalvarSentencia(Accion, txtfechasentencia, txtfecharecep, jt_sentencia, cmbsentencia,  usuario, jt_id, txtimportehonorarios, 
			txtimporteintereses, txtimportetasajusticia, instancia, txtMontoCondena, txtPorcentajeIncapacidad){
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	if(Accion  == 'EDIT'){
		var funcionEjecuta = 'UpdateSentencia';		
	}
			
	var strparametros = "FUNCION="+funcionEjecuta+
		"&txtfechasentencia="+encodeURIComponent(txtfechasentencia)+
		"&txtfecharecep="+encodeURIComponent(txtfecharecep)+
		"&jt_sentencia="+encodeURIComponent(jt_sentencia)+
		"&cmbsentencia="+encodeURIComponent(cmbsentencia)+
		"&usuario="+encodeURIComponent(usuario)+
		"&jt_id="+encodeURIComponent(jt_id)+
		"&txtimportehonorarios="+encodeURIComponent(txtimportehonorarios)+
		"&txtimporteintereses="+encodeURIComponent(txtimporteintereses)+
		"&txtimportetasajusticia="+encodeURIComponent(txtimportetasajusticia)+
		"&instancia="+encodeURIComponent(instancia)+
		"&txtMontoCondena="+encodeURIComponent(txtMontoCondena)+
		"&txtPorcentajeIncapacidad="+encodeURIComponent(txtPorcentajeIncapacidad);
		
	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
	
}
//--------------------------------------------------------------------------------
function SalvarEventoABM(Accion, txtfecha, txtfechavencimiento, txtobservaciones , nrojuicio, usuario, cmbEventos){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	if(Accion  == 'EDIT'){
		var funcionEjecuta = 'UpdateEventosABM';
		var parametroID = "&EventoID="+encodeURIComponent(nrojuicio);
	}
	if(Accion == 'ALTA'){
		var funcionEjecuta = 'InsertarEventoNuevo';
		var parametroID = "&nrojuicio="+encodeURIComponent(nrojuicio);
	}
			
	var strparametros = "FUNCION="+funcionEjecuta+
		"&txtfecha="+encodeURIComponent(txtfecha)+
		"&txtfechavencimiento="+encodeURIComponent(txtfechavencimiento)+
		"&txtobservaciones="+encodeURIComponent(txtobservaciones)+
		parametroID +
		"&usuario="+encodeURIComponent(usuario)+
		"&cmbEventos="+encodeURIComponent(cmbEventos);
		
	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
}
//--------------------------------------------------------------------------------
function DeletePeritaje(id){
	//FALTA IMPLEMENTAR ESTE METODO .....
	var r = confirm('¿Está seguro de que desea eliminar la pericia?.');								
	if(r == true){
		var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/redirect.php";
		var funcionEjecuta = 'DeletePeritajes';
		var strparametros = "FUNCION="+funcionEjecuta+"&id="+id;
		var IDdivresult = 'lblErrores';	
		
		return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
	}
	return false;
	
}
//--------------------------------------------------------------------------------
function SalvarPeritajeABM(accion, txtFechaAsignacion, txtFechaPericia,txtFVencImpug, cmbPericia, 
			txtResultados, nrojuicio, usuario,incapacidadDemanda, incapacidadPeritoMedico, 
			ibmArt, ibmPericial, impugnacion, idperito){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	if(accion == 'EDIT'){
		var funcionEjecuta = 'UpdatePeritajesABM';
	}
	if(accion == 'ALTA'){
		var funcionEjecuta = 'InsertarPeritajeNuevo';
	}
	
	var strparametros = "FUNCION="+funcionEjecuta+
		"&txtFechaAsignacion="+encodeURIComponent(txtFechaAsignacion)+
		"&txtFechaPericia="+encodeURIComponent(txtFechaPericia)+
		"&txtFVencImpug="+encodeURIComponent(txtFVencImpug)+
		"&cmbPericia="+encodeURIComponent(cmbPericia)+
		"&txtResultados="+encodeURIComponent(txtResultados)+
		"&nrojuicio="+encodeURIComponent(nrojuicio)+
		"&usuario="+encodeURIComponent(usuario)+
		"&incapacidadDemanda="+encodeURIComponent(incapacidadDemanda)+
		"&incapacidadPeritoMedico="+encodeURIComponent(incapacidadPeritoMedico)+
		"&ibmArt="+encodeURIComponent(ibmArt)+
		"&ibmPericial="+encodeURIComponent(ibmPericial)+
		"&impugnacion="+encodeURIComponent(impugnacion)+
		"&idperito="+encodeURIComponent(idperito);
		
	var IDdivresult = 'lblErrores';	
	
	SalvarDatosSicroParametro(pagefunciones, strparametros, IDdivresult);
	var result = document.getElementById(IDdivresult).innerHTML;
	return result;
		
}
//--------------------------------------------------------------------------------
function SalvarInstanciaABM(accion, nroInstancia, NroJuicio, cmbJurisdiccion, cmbFuero,
	cmbJuzgadoNro, cmbSecretaria, txtNroExp, txtAnioExp, cmbMotivo, txtDetalle, usuario, txtFecha){

	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	if(accion == 'EDIT'){
		var funcionEjecuta = 'UpdateInstanciaAbmMod';
	}
	if(accion == 'ALTA'){
		var funcionEjecuta = 'UpdateInstanciaABMAlta';
	}
		
	var strparametros = "FUNCION="+funcionEjecuta+
		"&NroJuicio="+NroJuicio+
		"&cmbJurisdiccion="+encodeURIComponent(cmbJurisdiccion)+				
		"&cmbFuero="+encodeURIComponent(cmbFuero)+				
		"&cmbJuzgadoNro="+encodeURIComponent(cmbJuzgadoNro)+				
		"&cmbSecretaria="+encodeURIComponent(cmbSecretaria)+				
		"&txtNroExp="+encodeURIComponent(txtNroExp)+				
		"&txtAnioExp="+encodeURIComponent(txtAnioExp)+				
		"&cmbMotivo="+encodeURIComponent(cmbMotivo)+				
		"&txtDetalle="+encodeURIComponent(txtDetalle)+				
		"&usuario="+encodeURIComponent(usuario)+				
		"&txtFecha="+encodeURIComponent(txtFecha)+
		"&nroInstancia="+encodeURIComponent(nroInstancia);
	var IDdivresult = 'lblErrores';	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);
	
}
//--------------------------------------------------------------------------------
function SalvarMasDatosJuicio(Domicilio, Telefonos, Fax, Email, usuario, idJuicio){
	//var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php";
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/FuncionesSalvarDatos.php";
	
	var strparametros = "FUNCION=UpdateMasDatosJuicios&Domicilio="+Domicilio+
				"&Telefonos="+encodeURIComponent(Telefonos)+
				"&Fax="+encodeURIComponent(Fax)+
				"&Email="+encodeURIComponent(Email)+
				"&usuario="+usuario+
				"&idJuicio="+idJuicio;
				
	var IDdivresult = 'lblErrores';
	
	return SalvarDatosSicro(pagefunciones, strparametros, IDdivresult);	
}
//--------------------------------------------------------------------------------
function ActivaGif(){
		if(document.getElementById('imgAplicandoCambios'))
		document.getElementById('imgAplicandoCambios').style.display = 'block';	
}

function DesctivaGif(){
		if(document.getElementById('imgAplicandoCambios'))
		document.getElementById('imgAplicandoCambios').style.display = 'none';	
}

function SalvarDatosSicro(pagefunciones, strparametros, IDdivresult){	
	ActivaGif();
	
	var divResultado = document.getElementById(IDdivresult);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';					
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){									
					var xml = ajax.responseXML;												
					var valor = 'Fallo';				
					var resultXML = xml.getElementsByTagName('result');
					
					valor = resultXML[0].firstChild.nodeValue;				
					resultadoDatos = valor;				
					divResultado.innerHTML = valor;												
				}
				else{					
					resultadoDatos = "Error (1) "+ ajax.statusText;
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		
		ajax.open("GET", pagefunciones+'?'+strparametros,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesctivaGif();
		return false;
	}
	
	if(	trimString(resultadoDatos) == 'OK'){
		divResultado.innerHTML = '';												
		DesctivaGif();
		return true;
	}else{
		divResultado.innerHTML = trimString(resultadoDatos);
		MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
		DesctivaGif();
		return false;
	}
}
//--------------------------------------------------------------------------------
function SalvarDatosSicroParametro(pagefunciones, strparametros, IDdivresult){
	
	ActivaGif();
	
	var divResultado = document.getElementById(IDdivresult);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';					
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){									
					var xml = ajax.responseXML;												
					var valor = 'Fallo';				
					var resultXMLResult = xml.getElementsByTagName('result');
					// var resultXML = xml.getElementsByTagName('ID');
					
					valor = resultXMLResult[0].firstChild.nodeValue;				
					resultadoDatos = 'OK';				
					divResultado.innerHTML = valor;												
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		//console.log(pagefunciones);
		//console.log(strparametros);
		ajax.open("GET", pagefunciones+'?'+strparametros,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesctivaGif();
		return false;
	}
	
	if( trimString(resultadoDatos) == 'OK'){
		//divResultado.innerHTML = '';												
		DesctivaGif();
		return true;
	}else{
		divResultado.innerHTML = trimString(resultadoDatos);
		MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
		DesctivaGif();
		return false;
	}
}
//--------------------------------------------------------------------------------
function SalvarDatosJSON(pagefunciones, strparametros, IDdivresult){	
	ActivaGif();
	
	var divResultado = document.getElementById(IDdivresult);
	var resultadoDatos = 'FALLO. Intente nuevamente.';	
	var valor = '';
	divResultado.innerHTML = 'Iniciando... ';		
	
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){									
					valor = ajax.responseText;																						
					resultadoDatos = 'OK';				
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		//console.log(pagefunciones);
		//console.log(strparametros);
		ajax.open("GET", pagefunciones+'?'+strparametros,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesctivaGif();
		return '';
	}
	
	if(trimString(resultadoDatos) == 'OK'){
		//divResultado.innerHTML = '';												
		DesctivaGif();
		return valor;
	}else{
		divResultado.innerHTML = trimString(resultadoDatos);
		MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
		DesctivaGif();
		return '';
	}
}
//--------------------------------------------------------------------------------