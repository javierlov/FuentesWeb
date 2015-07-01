$(document).ready(inicializar);
var arrayPersonalExpuesto = [];
var jsonPersonalExpuesto = '';
var seleccionDatosESOP = [];
var seleccionDatosESOPdefault = [];
var cuilValido = false;
var id_PuestoTrabajo = 0;
var id_PuestoTrabajoEdicion = 0;
/*variable para la busqueda de trabajador*/
var CurrentPage = 1;
var CurrentBuscarTrabajadorNombre = '';
var CurrentBuscarTrabajadorCUIL = '';
var idControlESOP = 'id_nrIdeRie';
var columnaEdit = 0;
var inFiltroRiesgos = ''; //str separado por comas con los riesgos seleccionados al momento se usa con el check "mostrar solo seleccionados"
var EstaModoEdicion = false;
var globalvalue = 0;
var arrmensajes = [];

function inicializar(){		
	
	actualizarArrayDefaultActividad();
	
	$('#btnGuardarNomina').click( GuardarNomina );	
	
	$('#botonBuscar').click( BuscarDatosESOP );
	$('#btnBuscarTrabajador').click( FiltrarTrabajador );
	$('#txtBuscarTrabajador').keypress( FiltrarTrabajadorKey );
	
	$('#codigoBuscar').keypress( BuscarDatosESOPKey );
	$('#textoBuscar').keypress( BuscarDatosESOPKey );
	$('#soloRiesgosSelecc').click( filtarRiesgosSeleccionados );
	
	var formAccordion = $('#DatosGeneralesPersonalExpuesto').accordion({icons: null, heightStyle: "content"});  
	$('#DatosGeneralesPersonalExpuesto .ui-accordion-header').click( function() { 
		$('#DatosGeneralesPersonalExpuesto .ui-accordion-content').toggle();  });
			
	iniDialogImportarExel('', '', '');
	
	iniDialogCargaEsop();
	iniDialogDetalleRiesgo();
	
	iniDialogMostrarMsjGrid();
	iniDialogMensajeDatosDefault();
	$('#dialogMensajeValidacion').dialog({autoOpen: false});
	
	Grilla_NominaPersonalExpuesto(1, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
	
	iniDialogPersonalExpuestoNNdeA();
	
	if(param_ERRORXLS != ''){		
		MensajeValidacion('Reporte Importación excel', '', param_ERRORXLS, '', '', 'aceptar');
	}
		
}
function iniDialogMostrarMsjGrid(tipoBoton){	
		$('#dialogMostrarMsjGrid').dialog({
		autoOpen: false,
		width: 350,
		modal:true,
		open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
		buttons: [
			{
				id: "idbtnAceptarMG",
				text: "",
				click: function() {					
					$( this ).dialog( "close" );  						
					return true;
				}
			}
		]
	});
	
	if(tipoBoton == undefined || tipoBoton == 'ACEPTAR')
		JQAsignaClaseCSS("#idbtnAceptarMG", "btnAceptar");  	
	
	if(tipoBoton == 'CANCELAR')
		JQAsignaClaseCSS("#idbtnAceptarMG", "btnCancelar2");  	
	
	return true;
}
function iniDialogMensajeValidacion(funcion, parametro, botonseleccion, widthMsj){	
	var botones = []; 
	
	if(widthMsj == undefined){widthMsj = 500;}
	if(widthMsj <= 0){widthMsj = 500;}
	if(widthMsj == ''){widthMsj = 500;}
	
	if(botonseleccion == undefined){
		botonseleccion = '';
	}
	
	if(funcion == undefined){
		funcion = '';
	}
		
	if(funcion !== '' && botonseleccion == 'soloaceptar'){
		botones[0] = GetBotonMsj('idbtnAceptarMV',funcion, parametro);		
	}else if(funcion !== '' && botonseleccion == 'siguiente'){
		botones[0] = GetBotonMsj('idbtnSiguienteMV',funcion, parametro);		
	}else if(funcion !== '' && botonseleccion == 'aceptar'){
		botones[0] = GetBotonMsj('idbtnAceptarMV',funcion, parametro);
		botones[1] = GetBotonMsj('idbtnCancelarMV','', '');
	}else if(funcion !== '' && botonseleccion == ''){
		botones[0] = GetBotonMsj('idbtnSiguienteMV',funcion, parametro);
		botones[1] = GetBotonMsj('idbtnCancelarMV','', '');
	}else{
		botones[0] = GetBotonMsj('idbtnAceptarMV',funcion, parametro);		
	}
		
	$('#dialogMensajeValidacion').dialog({
		autoOpen: false,
		width: widthMsj,
		modal:true,
		buttons: botones,
		open: function(event, ui) { 
				if(botonseleccion == 'soloaceptar'){ 
					$(".ui-dialog-titlebar-close").hide(); 
				}
			}
	});
	
	if(funcion != ''){
		if ($('#idbtnSiguienteMV').length){  
			JQAsignaClaseCSS("#idbtnSiguienteMV", "btnSiguiente");  	
		}
	}
	
	if ($('#idbtnCancelarMV').length){ 
		JQAsignaClaseCSS("#idbtnCancelarMV", "btnCancelar2");  	
	}
		
	if ($('#idbtnAceptarMV').length)  {
		JQAsignaClaseCSS("#idbtnAceptarMV", "btnAceptar");  			
	}
	
}
function iniDialogDetalleRiesgo(){	
	$('#dialogDetalleRiesgo').dialog({
		autoOpen: false,
		width: 600,
		modal:true,
		buttons: [
			{				
				id: "idbtnAceptarDR",
				text: "",
				click: function() {					
					$( this ).dialog( "close" );  						
					return true;
				}
			}
		]
	});
	
	JQAsignaClaseCSS("#idbtnAceptarDR", "btnAceptar");  	
	return true;
}
function iniDialogMensajeDatosDefault(){
	$('#dialogMensajeDatosDefault').dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{
				id: "idbtnAceptarDD",
				text: "",
				click: function() {
					actualizarArrayDefault();					
					$( this ).dialog( "close" );  						
					return true;
				}
			},
			{				
				id: "idbtnCancelarDD",
				text: "",
				click: function() {
					limpiarArrayDefault();
					$( this ).dialog( "close" );  		
					return false;
				}
			}
		]
	});
	JQAsignaClaseCSS("#idbtnAceptarDD", "btnAceptar");  
	JQAsignaClaseCSS("#idbtnCancelarDD", "btnCancelar2");
	return true;
}
function showDetalleRiesgo(idRiesgo){
	BuscarDetalleESOPJSON(idRiesgo);
	$("#dialogDetalleRiesgo").dialog("open");	
	return false;
}
function questionArrayDefault(){
	$("#dialogMensajeDatosDefault").dialog("open");	
	return false;
}
function ImportarArchvioXLS(){
	if($("#archivoxls").val() == ''){
		MostrarMsjGrid('Error', '', 'Seleccione un archivo para procesar.');		
		return false;
	}	
	document.getElementById("procesandoArchivo").style.display = 'block';  
	document.formImportarXLS.submit();
	return true;
}
function showDialogImportarExel(){
	Grilla_NominaPersonalExpuesto(1, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
	var id = ValidaExistenTrabajadoresAsignados(idEstablecimiento);
	if ( parseInt(id) == 0 ){
		iniDialogImportarExel(	'', function(){  ImportarArchvioXLS(); }, '' );
		$("#dialogImportarExel").dialog("open");	
	}else{
		MostrarMsjGrid('Aviso', '', 'Esta nomina tiene registros asignados, debe estar vacía para poder importar desde xls.');	
	}
	return false;
}
function iniDialogImportarExel(encabeza, funcion, parametro){
	var botones = []; 	
	botones[0] = GetBotonMsj('IXLS_btnAceptar',funcion, parametro);				
	botones[1] = GetBotonMsj('IXLS_btnCancelar','', '');
	$( "#dialogImportarExel" ).dialog({
		autoOpen: false,
		width: 380,
		modal:true,
		buttons: botones
	});
	JQAsignaClaseCSS("#IXLS_btnAceptar", "btnAceptar");
	JQAsignaClaseCSS("#IXLS_btnCancelar", "btnCancelar2");
	
	JQDivSetValue('#encabezadoXLS', encabeza);
	
	if(encabeza == ''){
		document.getElementById("encabezadoXLS").style.display = 'none';  
	}else{
		document.getElementById("encabezadoXLS").style.display = 'block';  
	}
	
}
function iniDialogCargaEsop(){
	$('#dialogCargaEsop').dialog({
		autoOpen: false,
		width: 770,
		modal:true,
		close: function(){ Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL); },
		buttons: [
			{
				// class: "btnSI",
				id: "idbtnAceptar",
				text: "",
				click: function() {
					aceptarSeleccCodigos();		
					questionArrayDefault();					
					$( this ).dialog( "close" );  						
					return true;
				}
			},
			{
				// class: "btnNO",
				id: "idbtnCancelar2",
				text: "",
				click: function() {
					Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
					$( this ).dialog( "close" );  		
					return false;
				}
			}
		]
	});
	JQAsignaClaseCSS("#idbtnAceptar", "btnAceptar");  
	JQAsignaClaseCSS("#idbtnCancelar2", "btnCancelar2");
	return true;
}
function iniDialogPersonalExpuestoNNdeA(){
		$( "#dialogPersonalExpuestoNNdeA" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [			
			{
				// class:"btnAceptar",							
				id:"PENNA_btnAceptar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );					
					return true;
				}
			}
		]
	});
	
	JQAsignaClaseCSS("#PENNA_btnAceptar", "btnAceptar");	
}
function VerDialogPersonalExpuestoNNdeA(listaPersonal){
	
	var listaTrabajadores = $("#listaTrabajadores");  
	listaTrabajadores.empty().html( listaPersonal );   
	listaTrabajadores.show();	
	
	$("#dialogPersonalExpuestoNNdeA").dialog("open");	
	return false;
}
function verCargaESOPNuevo(){
	var NomApe = document.getElementById("id_nrNomApe").value;
	var Sector = document.getElementById("id_nrSecTra").value;	
	var CUIL = document.getElementById("input_CUIT").value; 
	var ESOPPuesto = document.getElementById("id_nrPueTra").value;
	
	verCargaESOP(NomApe, Sector, CUIL, ESOPPuesto);
}
function verCargaESOP(vNomApe, vSector, vCUIL, vEsopPuesto){
	
	var elemento = document.getElementById("soloRiesgosSelecc");	
	elemento.checked = false;
	
	CopiaArrayInicial();
	$("#textoBuscar").val('');
	$("#codigoBuscar").val('');
	
	$("#dialogCargaEsopNomApe").empty().html(vNomApe); 
	$("#dialogCargaEsopSector").empty().html(vSector); 
	$("#dialogCargaEsopCUIL").empty().html(vCUIL); 
	$("#dialogCargaEsopPuesto").empty().html(vEsopPuesto); 
	
	//BuscarDatosESOP();	
	/*al inicio solo mostrar items seleccionados*/
	$("#soloRiesgosSelecc").prop("checked", "checked");
	filtarRiesgosSeleccionados();
	
	$("#dialogCargaEsop").dialog("open");  
	return false;
}
/********************* Funciones Table Personal Expuesto ****************************/
function Print_TablePE(){
	var grilla = $("#grillaPersonalExpuesto");  
	grilla.empty().html( CreateTablePersonalExpuesto() );   
	grilla.show();
}
function autocompletePueTra(){
	
	$("#id_nrPueTra").autocomplete({
			source: arrayjsPuestosNomina,
			minLength: 2,
			delay: 400,				
			focus: function(event, ui) {					
				event.preventDefault();  
				$(this).val(ui.item.label);
				//id_PuestoTrabajo = 0;
			},
			select: function(event, ui) {					
				event.preventDefault();  
				$(this).val(ui.item.label);
				
				$("#id_nrPueTra").val(ui.item.label);  					
				id_PuestoTrabajo = ui.item.value;
			}
		}).autocomplete("widget").addClass("limit-width-height");
}
function LimpiarContolesNewRow(){
	LimpiarContolID("nrCuil");
	LimpiarContolID("nrNomApe");
	LimpiarContolID("nrFecIng");
	LimpiarContolID("nrFecIni");
	LimpiarContolID("nrSecTra");
	LimpiarContolID("nrPueTra");  
	LimpiarContolID("id_nrIdeRie");  
	columnaEdit = 0;
}
function LimpiarContolID(idcontrol){
	if( document.getElementById(idcontrol) ){
		document.getElementById(idcontrol).innerHTML = '';
		document.getElementById(idcontrol).value = '';
	}
}
function HayCuilIngresado(){	
	//limpia los mensajes de error
	ShowErrorGridCell("grillaPersExpMsj", '');  
	
	if( $('#input_CUIT').val() == '' ){ 
		//ShowErrorGridCell("grillaPersExpMsj", 'Debe ingresar un CUIL primero.');  
		MostrarMsjGrid('Aviso', '', 'Debe ingresar un CUIL primero.');
		/*
		LimpiarContolID("id_nrNomApe");
		LimpiarContolID("id_nrFecIng");
		LimpiarContolID("id_nrFecIni");
		LimpiarContolID("id_nrSecTra");
		LimpiarContolID("id_nrPueTra");
		*/
		return false;  
	}
	if( !cuilValido	){ 
		// ShowErrorGridCell("grillaPersExpMsj", 'El CUIL no es válido. Deberá corregirlo para procesar la nómina.');   
		MostrarMsjGrid('Aviso', '', 'El CUIL no es válido. Deberá corregirlo para procesar la nómina.');
		return false;
	}
	return true;
}
function PuedeEditNombApe(nombApe){
	
	if( nombApe.search('ALTA') > -1 ){ return true;}
	if( nombApe.search('NOMBRE') > -1 ){ return true;}
	if( nombApe.search('APELLIDO') > -1 ){ return true;}
	return false;
	
}
function FechaFormatoIngresoEsvalida(idFecha){
	var fechIng = $(idFecha).val();
	if(fechIng == ""){
		fechIng = $(idFecha).html();}
	if ( !IsValidDate(fechIng) ){		
		// ShowErrorGridCell("grillaPersExpMsj", 'Fecha Ingreso, No es una fecha valida. Formato debe ser dd/mm/yyyy ');   
		MostrarMsjGrid('Aviso', '', 'Fecha Ingreso, No es una fecha valida. Formato debe ser dd/mm/yyyy ');
		return false;
	}
	return true;
}
function ValidarFechaInicioExpo(idfecha, idfechaInicio){
	/* [Fecha de Inicio de la exposición]  
		Debe ser menor/igual a la fecha actual 
		y mayor/igual a la Fecha de Ingreso a la empresa. */
		
	var fechIng = $(idfecha).val();
	
	ShowErrorGridCell("grillaPersExpMsj", '');   
	
	if( fechIng == '' ){ return true;}
	if( idfechaInicio == '' ){ return false;}
	
	if( !FechaFormatoIngresoEsvalida(idfecha) ){ return false;}
	if( !FechaFormatoIngresoEsvalida(idfechaInicio) ){ return false;}
	
	var FechaHoy = GetFechaHoy();
	var parseFechaHoy = ParsearFecha(FechaHoy);
	var parseFechIng = ParsearFecha(fechIng);
	
	if (parseFechaHoy < parseFechIng) {				
		MostrarMsjGrid('Aviso', '', 'Fecha Inicio de la exposicion, Debe ser menor/igual a la fecha actual..');
		return false;
	}
	
	var fechInicio = $(idfechaInicio).val();
	if(fechInicio == ''){ fechInicio = $(idfechaInicio).html();	}
	var parseFechInicio = ParsearFecha(fechInicio);	
	
	if (parseFechInicio > parseFechIng) {				 
		MostrarMsjGrid('Aviso', '', 'Fecha Inicio de la exposicion, Debe ser mayor/igual a la fecha de Ingreso a la empresa..');				
		return false;
	}	
	return true;  
}
function ValidarFechaIngreso(idfecha){
	var fechIng = $(idfecha).val();
	
	ShowErrorGridCell("grillaPersExpMsj", '');   
	if( fechIng == '' ){ return false;}
	if( !FechaFormatoIngresoEsvalida(idfecha) ){ return false;}
	
	var FechaHoy = GetFechaHoy();
	var parseFechaHoy = ParsearFecha(FechaHoy);
	var parseFechIng = ParsearFecha(fechIng);
	
	if (parseFechaHoy < parseFechIng) {				
		MostrarMsjGrid('Aviso', '', 'Fecha Ingreso, Debe ser menor/igual a la fecha actual..');
		return false;
	}
	
	return true;  
}
function ValidarGrabarRegistroNuevo(event){
	
	if(event != undefined){
		if (event.keyCode == 27){
			Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
			return true;
		}
	}
		
	if( document.getElementById('input_CUIT').value != '' && BuscarTrabajadorNuevo() ){
		VerificaryGrabarRegistro(true); }
	return true;
}
function CreateRowEdit(){
	var RowEdit = "<td align='center' class='XLSColAlignCenter XLSText' > <input type='button' class='btnNuevoItem' id='nuevoRegistro' onclick='NewRow()' /> </td> ";
	RowEdit += "<td align='left' class='XLSColAlignRight XLSText' id='nrCellCuil' name='nrCellCuil' > <div style='width:80px;  height:auto;' id='nrCuil' ></div> </td> ";  
	RowEdit += " <td align='left' class='XLSColAlignLeft XLSText' id='nrCellNomApe' name='nrCellNomApe' > <div style='width:150px;  height:auto;' id='nrNomApe' > </div> </td> ";  
	RowEdit += " <td align='left' class='XLSColAlignRight XLSText' id='nrCellFecIng' name='nrCellFecIng' > <div style='width:90px;  height:auto;' id='nrFecIng' > </div> </td> ";
	RowEdit += " <td align='left' class='XLSColAlignRight XLSText' id='nrCellFecIni' name='nrCellFecIni' > <div style='width:90px;  height:auto;' id='nrFecIni' ></div> </td> ";
	RowEdit += " <td align='left' class='XLSColAlignLeft XLSText' id='nrCellSecTra' name='nrCellSecTra' > <div style='width:100px;  height:auto;' id='nrSecTra' > </div> </td> ";
	RowEdit += " <td align='left' class='XLSColAlignLeft XLSText' id='nrCellPueTra' name='nrCellPueTra' > <div style='width:100px;  height:auto;' id='nrPueTra' > </div> </td> ";
	
	RowEdit += " <td align='left' class='XLSColAlignLeft XLSText' id='nrCellIdeRie' name='nrCellIdeRie' > <div style='width:150px;  max-width:150px;  height:auto;' id='nrIdeRie' ></div> </td> ";
	
	return "	<tr class='gridFondoOnMouseOver gridRow1'>"+RowEdit+"</tr>	";
}
function CreateTablePersonalExpuesto(){
	var arrayFields = ['Seleccionar', 'CUIL', 'NombreApellido', 'FecIngreso', 'FecInicio', 'SectorTrabajo', 'PuestoTrabajo',  'IdentificacionRiesgo'];
	var arrayHeader = ['Seleccionar', 'C.U.I.L.', 'Nombre y Apellido', 'Fec. de ingreso a la empresa', 'Fec. de inicio de la exposicion', 'Sector de Trabajo', 'Puesto de Trabajo',  'Identificacion de riesgo segun codigo ESOP'];
		
	var table = "<table width='100%' class='GridTableCiiu'> ";
	
	table += "		<tr> 		";
	for(i=0;  i < arrayHeader.length;  i++){				
		table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none;   color:rgb(255,255,255);' >"+arrayHeader[i]+"</a></td>	";
	}	
	table += "		</tr>		";
	
	table += CreateRowEdit();
		
	table += "</table>";
	return table;
}
/********************* GetTemp Table Personal Expuesto ****************************/
function Print_PersonalExpuesto(jsonPersonalExpuesto){
	/*busca los datos en la Base de Datos.... */
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=GetTempTablePersonalExpuesto";
	
	strparametros = strparametros+"&jsonPersonalExpuesto="+encodeURIComponent(jsonPersonalExpuesto);  
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'grillaPersonalExpuesto');  
	document.getElementById("grillaPersonalExpuesto").style.display = 'block';  
}
function Insert_ArrayPersonalExpuesto(Seleccionar, CUIL, NombreApellido, FecIngreso, FecInicio, SectorTrabajo, PuestoTrabajo, IdentificacionRiesgo){
	
	var jsonRow = '';  
	var arrayLength = arrayPersonalExpuesto.length;
		
	//jsonRow += '{"id":"'+(parseInt(arrayLength)+1)+'",';
	jsonRow += ' { "Seleccionar":"'+Seleccionar+'",';
	jsonRow += ' "CUIL":"'+CUIL+'",';
	jsonRow += ' "NombreApellido":"'+NombreApellido+'",';
	jsonRow += ' "FecIngreso":"'+FecIngreso+'",';
	jsonRow += ' "FecInicio":"'+FecInicio+'",';
	jsonRow += ' "SectorTrabajo":"'+SectorTrabajo+'",';
	jsonRow += ' "PuestoTrabajo":"'+PuestoTrabajo+'",';  
	jsonRow += ' "IdentificacionRiesgo":"'+IdentificacionRiesgo+'" } ';  
	
	arrayPersonalExpuesto.push(jsonRow);
			
	return GetJson_PersonalExpuesto();
}
function GetJson_PersonalExpuesto(){
	var arrayLength = arrayPersonalExpuesto.length;
	var jsonRowGroup = '';  
	
	for (var i = 0;  i < arrayLength;  i++) {		
		var row = arrayPersonalExpuesto[i];
		if(i > 0){ jsonRowGroup += ',';}
		jsonRowGroup += row;
	}
	jsonPersonalExpuesto = '[ '+jsonRowGroup+' ]';
			
	return jsonPersonalExpuesto;
}
/****************************************************************/
function BuscarTrabajadorKey(event){
	var tecla = event.keyCode;
	
	if(tecla == undefined){ 
		tecla = event.which;}
	
	if (tecla == 13){ 
		BuscarTrabajador('nuevo');
		ValidarGrabarRegistroNuevo();
		event.preventDefault();
		event.stopPropagation();
		//return true;
	}
	
	if (tecla == 27){ 
		Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);}
}
function BuscarTrabajadorNuevo(){	
		return BuscarTrabajador('nuevo');
}
function ValidoTrabajadorNomina(cuilTrabajador, cuitEmpresa, establecimiento){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=ValidoTrabajadorNomina";
	
	strparametros = strparametros+"&cuilTrabajador="+encodeURIComponent(cuilTrabajador);  
	strparametros = strparametros+"&cuitEmpresa="+encodeURIComponent(cuitEmpresa);  
	strparametros = strparametros+"&establecimiento="+encodeURIComponent(establecimiento);
	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'resultadoProceso'); 	
	
	return resultado;		
}
function ValidaExistenTrabajadoresAsignados(ID_Establecimiento){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=ValidaExistenTrabajadoresAsignados";
	
	strparametros = strparametros+"&id="+encodeURIComponent(ID_Establecimiento);  	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'resultadoProceso'); 	
	
	return resultado;		
}
function BuscarTrabajador(accion){
	
	ShowErrorGridCell("grillaPersExpMsj", '');   
	var inputCUIT = document.getElementById("input_CUIT").value;
	if (inputCUIT == ''){ return false;}
	
	if ( !ValidarCuit(inputCUIT) ){		
		MostrarMsjGrid('Aviso', '', 'Por favor, ingrese un cuil/cuit válido!');
		cuilValido = false;
		return false;
	} 
	
	cuilTrabajador = inputCUIT;
	cuitEmpresa = param_CUILEMPRESA;
	establecimiento = idEstablecimiento;
	
	var resValido = ValidoTrabajadorNomina(cuilTrabajador, cuitEmpresa, establecimiento);
	var titulo = 'Informacion';
	var encabezado = '';
	var mensaje = '';
		
	if( resValido > 0){
		if(resValido == 1){  mensaje = 'Este cuil/cuit ya se ingreso en una Nomina web. '; }
		if(resValido == 2){  mensaje = 'Este cuil/cuit ya se ingresado en una Nomina aprobada. ';}
		if(resValido == 3){  mensaje = 'Este cuil/cuit ya se ingreso en una Nomina actual. ';}
		cuilValido = false;		
	} 
	
	if( resValido > 0){		
		MostrarMsjGrid('Aviso', '', mensaje);
		return false;
	}
	cuilValido = true;
	
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=BuscarTrabajadorJSON";
	var input_CUIT = document.getElementById("input_CUIT").value;
	
	strparametros = strparametros+"&CONTRATO="+encodeURIComponent(param_CONTRATO);  
	strparametros = strparametros+"&CUILEMPRESA="+encodeURIComponent(param_CUILEMPRESA);  
	strparametros = strparametros+"&CUIT="+encodeURIComponent(input_CUIT);
		
	var resultado = ProcesarDatosJSON(pagefunciones, encodeURI(strparametros), 'resultadoProceso');
	if( resultado ){			
		var resultadoProceso = document.getElementById("resultadoProceso").innerHTML;
		var resultParse = JSON.parse(resultadoProceso);
		
		document.getElementById("resultadoProceso").style.display = 'none';  
		
		if(resultParse.CUIL == '' || resultParse.CUIL == '0' ){
			
			LimpiarContolID("id_nrNomApe");
			LimpiarContolID("id_nrFecIng");
			LimpiarContolID("id_nrFecIni");
			LimpiarContolID("id_nrSecTra");
			LimpiarContolID("id_nrPueTra");
			
			mensaje = 'El CUIL <b>'+input_CUIT+'</b> no se encuentra en afiliaciones. Si desea dar de alta el mismo haga click <a  href="/nomina-trabajadores/alta-trabajador" target="_blank" style="color:blue;" >aquí.</a>';
			
			iniDialogMostrarMsjGrid('CANCELAR');
			MostrarMsjGrid('Aviso', '', mensaje);
			
		}else{			
			document.getElementById("input_CUIT").value = resultParse.CUIL;
			document.getElementById("id_nrNomApe").value = resultParse.NOMBRE;			
			document.getElementById("id_nrFecIng").value = resultParse.FECHA_INGRESO;
			//document.getElementById("id_nrFecIni").value = resultParse.FECHA_INI; // NO SE DEBE CARGAR EN ESTA PARTE.
			document.getElementById("id_nrSecTra").value = resultParse.SECTOR;
			document.getElementById("id_nrPueTra").value = resultParse.PUESTO;		
		}
		
		if(accion == 'nuevo'){
			document.getElementById("id_nrIdeRie").innerHTML = SepararArrayaString(seleccionDatosESOP,", ");  
		}else{
			document.getElementById("id_nrIdeRie").innerHTML = resultParse.PUESTO;}
		
		JQAsignaClaseCSS('#nrCellCuil', 'XLSText');
		
		return true;
	}	
	return false;  
}
function ShowErrorGridCell(iddivError, msj){
	//grillaPersExpMsj
	document.getElementById(iddivError).style.display = 'none';  
	LimpiarContolID(iddivError);
	if(msj != ''){
		document.getElementById(iddivError).innerHTML = msj;
		document.getElementById(iddivError).style.display = 'block';  
	}
}
/************************************************************************/
function FiltrarTrabajadorKey(event){		
	if (event.keyCode == 13) {
		FiltrarTrabajador();
		event.preventDefault();
		event.stopPropagation();
	}
}
function FiltrarTrabajador(){		
	var option = JQradioButtonSelect('radioBuscarPor');
	var txtBuscarTrabajador = document.getElementById("txtBuscarTrabajador").value;
	
	CurrentBuscarTrabajadorNombre = '';
	CurrentBuscarTrabajadorCUIL = '';
	
	if(option == 0){	
		CurrentBuscarTrabajadorNombre = escape(txtBuscarTrabajador);}
		
	if(option == 1){	
		CurrentBuscarTrabajadorCUIL = txtBuscarTrabajador;}
		
	CurrentPage = 1;
	Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
}
function BuscarDatosESOP(){		
	var elemento = document.getElementById("soloRiesgosSelecc");	
	elemento.checked = false; //se deschequea el check "mostar solo seleccionados..."
	inFiltroRiesgos = '';	 //se limpia el str con los items seleccionados
	
	BuscarGridDatosESOP(1);
}
function BuscarDatosESOPKey(){
	if (event.keyCode == 13){ 
		BuscarDatosESOP();}
}
function BuscarGridDatosESOP(pagina){
	
	var codigo = $("#codigoBuscar").val();
	var descripcion = $("#textoBuscar").val();
			
	var IDdivResultado = 'idGridDatosESOP'; 	
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  
			
	var strparametros = "funcion=GridDatosESOP";  
	strparametros = strparametros+"&pagina="+encodeURIComponent(pagina);  
	strparametros = strparametros+"&codactividad="+encodeURIComponent(codigoactividad);  
	strparametros = strparametros+"&codigo="+encodeURIComponent(codigo);  
	strparametros = strparametros+"&descripcion="+encodeURIComponent(escape(descripcion));  
	strparametros = strparametros+"&inFiltroRiesgos="+encodeURIComponent(inFiltroRiesgos);  
	
	strparametros = strparametros+AgregarParametroHHMMSS();		
	ProcesarDatos(pagefunciones, strparametros, IDdivResultado, 'loadingESOP');	
	descheckAll();  
		
	return true;
}
function descheckAll(){
	var form = document.formularioGrid;
	var isdefault = (seleccionDatosESOP.length == 0);
	
	for (i=0;  i< form.elements.length;  i++){
		if (form.elements[i].type == "checkbox"){						
			var idcheck = trim(form.elements[i].id); //le quito los espacios en blanco						
			if(isdefault){
				form.elements[i].checked = (ArrayindexOf(seleccionDatosESOPdefault, idcheck) != -1);
				//(seleccionDatosESOPdefault.indexOf(idcheck) != -1);
			}else{
				form.elements[i].checked = (ArrayindexOf(seleccionDatosESOP, idcheck) != -1);
				// (seleccionDatosESOP.indexOf(idcheck) != -1); 
			}
			
		}
	}
	
}
function CheckOption(id){
	var index = ArrayindexOf(seleccionDatosESOP, id);
		//seleccionDatosESOP.indexOf(id);
	if(index == -1){
		seleccionDatosESOP.push(id);
	}else{
		seleccionDatosESOP.splice(index, 1);
	}
}
function VerificaryGrabarRegistro(validate){
	if(validate){
		// validacion para registros nuevos
		var cuil = document.getElementById("input_CUIT").value; 		
		var nombre = document.getElementById("id_nrNomApe").value;
		var fechaingreso = document.getElementById("id_nrFecIng").value;
		var fechainiexpo = document.getElementById("id_nrFecIni").value;	
		var sectortrab = document.getElementById("id_nrSecTra").value;	
		var arrayRiesgos = seleccionDatosESOP;
		
		/*estos valores deben venir de la base*/
		if(cuil == ''){ return false; }
		if(nombre == ''){ return false; }
		if(fechaingreso == ''){ return false; }
		/*
		if(fechainiexpo == '') return false;
		if(sectortrab == '') return false;
		if(arrayRiesgos.length == 0) return false;
		if(id_PuestoTrabajo == 0) return false;
		*/
		// ShowErrorGridCell("grillaPersExpMsj", 'Se van a guardar los datos');			
		GrabarRegistroNomina(0, cuil, nombre, fechaingreso, fechainiexpo, sectortrab, id_PuestoTrabajo, arrayRiesgos);			
		Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
		
	}else{
		// validacion para registros editados
		GrabarItemModificado(columnaEdit, 'id_EditIdeRie'); 				
	}
	
	return true;
}
/**********************************************************/
function aceptarSeleccCodigos(){		
	document.getElementById(idControlESOP).value = SepararArrayaString(seleccionDatosESOP,", ");  	
	VerificaryGrabarRegistro( columnaEdit == 0 );
	return true;
}
/**********************************************************/
function filtarRiesgosSeleccionados(){
	var elemento = document.getElementById("soloRiesgosSelecc");
	var arrayfiltro = '';
	inFiltroRiesgos = '';
	
	document.getElementById("codigoBuscar").value = '';
	document.getElementById("textoBuscar").value = '';
	
	if(elemento.checked){	
		for(var x=0; x < seleccionDatosESOP.length; x++){ 
			if(arrayfiltro != ''){ arrayfiltro += ", "; }
			arrayfiltro += " '"+seleccionDatosESOP[x]+"' ";
		}
		
		inFiltroRiesgos = encodeURI(seleccionDatosESOP);
		BuscarGridDatosESOP(1);
		//filtar datos
	}else{
		BuscarGridDatosESOP(1);	
		$("#soloRiesgosSelecc").prop("checked", "");
	}
}
function HabilitarEdicion(){
	//LimpiarContolesNewRow();
	LimpiarContolID("input_CUIT");
	document.getElementById("input_CUIT").style.display = 'block';  
	document.getElementById("idbtnActualizar").style.display = 'block';  
	
	id_PuestoTrabajo = 0;
	id_PuestoTrabajoEdicion = 0;
	seleccionDatosESOP.length = 0;
	
	actualizarArrayDefaultActividad();	
	
	Asigna_EventoDblClick();
	
	$('#input_CUIT').bind('keydown',function(e){	
		if ( e.which == 27 ) {		   
			   Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
			}
		}
	);		
	
	autocompleteEmpleados();		
	///////// MostrarListadoEmpleados('id_nrNomApe');
	$("#input_CUIT").focus();
	
}
function HabilitarControlesEdicion(){
	if(document.getElementById("id_nrNomApe").value == ''){ document.getElementById("id_nrNomApe").style.display = 'block';  }
	if(document.getElementById("id_nrFecIng").value == ''){ document.getElementById("id_nrFecIng").style.display = 'block';  }
	document.getElementById("id_nrFecIni").style.display = 'block';  
	document.getElementById("id_nrSecTra").style.display = 'block';  
	document.getElementById("id_nrPueTra").style.display = 'block';  
	document.getElementById("idbtnActualizar").style.display = 'block';  
}
function Grilla_NominaPersonalExpuesto(page, buscaNombre, buscaCuil){
	/*busca los datos en la Base de Datos.... */
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=getGridDatosNominaWeb";
	
	ShowErrorGridCell("grillaPersExpMsj", '');   
	CurrentPage = page;
	EstaModoEdicion = false;
	
	strparametros = strparametros+"&page="+encodeURIComponent(page);  
	strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(idEstablecimiento);  
	
	if (buscaNombre === undefined){ buscaNombre = '';}
	if (buscaCuil === undefined){  buscaCuil = '';}
	
	strparametros = strparametros+"&buscaNombre="+buscaNombre;  	
	strparametros = strparametros+"&buscaCuil="+encodeURIComponent(buscaCuil);  
	
	//document.getElementById("loadingTrabajador").style.display = 'block';  
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'grillaPersonalExpuesto', 'loadingTrabajador');
	document.getElementById("grillaPersonalExpuesto").style.display = 'block';  
	
	OcultarContolesRow();
}
	
function Asigna_EventoDblClick(){
	OcultarContolesRow();
	ReadAndColors("input_CUIT");
	ShowErrorGridCell("grillaPersExpMsj", '');   
	//CopiaArrayInicial();
	
	var fcn_nrNomApe = function(){
		if(document.getElementById('id_nrNomApe')){						
			if( PuedeEditNombApe(document.getElementById('id_nrNomApe').innerHTML) ){
				OcultarContolesRow();  
				if( !HayCuilIngresado() ){ return false;  }	
				
				OcultarContolesRow();
				ReadAndColors("id_nrNomApe");
				
			}else{					
				MostrarMsjGrid('Aviso', '', 'No puede editar esta celda.');
			}
			$('#id_nrNomApe').focus();
		}
		return true;
	};
	
	var fcn_nrFecIng = function(){
		if(document.getElementById('id_nrFecIng')){			
			if( !HayCuilIngresado() ){return false;  	}
						
			OcultarContolesRow();
			ReadAndColors("id_nrFecIng");
									
			$("#id_nrFecIng").datepicker( { dateFormat: 'dd/mm/yy' } );						
			$('#id_nrFecIng').change( ValidarFechaIngreso('#id_nrFecIng') );
			$('#id_nrFecIng').blur( ValidarFechaIngreso('#id_nrFecIng') );			
			$('#id_nrFecIng').focus();  
		}
		return true;
	};
	var fcn_nrFecIni = function(){
		if(document.getElementById('id_nrFecIni')){
			OcultarContolesRow();
			if( !HayCuilIngresado() ){return false;  }
			
			OcultarContolesRow();
			ReadAndColors("id_nrFecIni");
			
			$("#id_nrFecIni").datepicker({ dateFormat: 'dd/mm/yy' });
			$('#id_nrFecIni').focus();  
		}
		return true;
	};
	var fcn_nrSecTra = function(){
		OcultarContolesRow();
		if( !HayCuilIngresado() ){return false;  }
		OcultarContolesRow();
		ReadAndColors("id_nrSecTra");
		return true;
	};
	
	var fcn_nrPueTra = function(){
		var textLista = 'Ingese texto, seleccione de la lista';
		OcultarContolesRow();
		if( !HayCuilIngresado() ){return false;  }
		
		OcultarContolesRow();
		ReadAndColors("id_nrPueTra");				
		autocompletePueTra();
		$('#id_nrPueTra').focus();
		return true;		
	};
		
	// JQRemoveClaseCSS('#nrCellCuil', 'XLSText');		
	JQElementSetEventdblClick('#id_nrNomApe', fcn_nrNomApe);  
	JQElementSetEventdblClick('#id_nrFecIng', fcn_nrFecIng);  
	JQElementSetEventdblClick('#id_nrFecIni', fcn_nrFecIni);  
	JQElementSetEventdblClick('#id_nrSecTra', fcn_nrSecTra);  
	JQElementSetEventdblClick('#id_nrPueTra', fcn_nrPueTra);
		
	document.getElementById("id_nrIdeRie").innerHTML = SepararArrayaString(seleccionDatosESOP,", ");  
	
	document.getElementById("idbtnActualizar").style.display = 'block';  				
	$("#id_nrPueTra").val('');  					
	id_PuestoTrabajo = 0;
	
	// document.getElementById("nuevoRegistro").style.display = 'none';  			
	// document.getElementById("saveRegistro").style.display = 'block';  				
				
}
function ReadOnlyAndColors(idControl){
	if(document.getElementById(idControl)) {				
		JQReadOnlyInput("#"+idControl, true);			
		document.getElementById(idControl).style.background = '#666';
		document.getElementById(idControl).style.color = '#FFF';
	}
}
function ReadAndColors(idControl){
	if(document.getElementById(idControl)) {				
		JQReadOnlyInput("#"+idControl, false);			
		document.getElementById(idControl).style.background = '#FFF';
		document.getElementById(idControl).style.color = '#000';
	}
}
function OcultarContolesRow(){
	
	ReadOnlyAndColors("input_CUIT");
	ReadOnlyAndColors("id_nrNomApe");
	ReadOnlyAndColors("id_nrFecIng");
	ReadOnlyAndColors("id_nrFecIni");
	ReadOnlyAndColors("id_nrSecTra");
	ReadOnlyAndColors("id_nrPueTra");
	
}
/********************************************/
function ValidarDatosNewItem(){
	if( document.getElementById("input_CUIT").value == ''){ return false;}
	/*
	if( document.getElementById("id_nrNomApe").value == '') return false;
	if( document.getElementById("id_nrFecIng").value == '') return false;
	if( document.getElementById("id_nrFecIni").value == '') return false;
	if( document.getElementById("id_nrSecTra").value == '') return false;
	if( document.getElementById("id_nrPueTra").value == '') return false;
	if( seleccionDatosESOP.length == 0) return false;
	*/
	return true;
}
function ConverArraytoJson(arrayJS){
	var jsonArray = ' { '; 
	for(var x=0; x < arrayJS.length; x++) {
		if(x > 0){ jsonArray += ', '; }
		jsonArray += ' "'+x+'":"'+arrayJS[x]+'" ';
	}
	
	jsonArray += ' } '; 
	return jsonArray;	
}
function GrabarEstadoNomina(){
	/*confirma la nomina para el establecimiento.... */
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=GrabarNominaConfirmada";
	strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(idEstablecimiento);  	
	
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'grillaPersonalExpuesto');  
	document.getElementById("grillaPersonalExpuesto").style.display = 'block';  			
	return true;
}
function BuscarPuestoTrab(id){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=BuscarPuestoTrab";
	
	strparametros = strparametros+"&id="+encodeURIComponent(id);  
	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'grillaPersExpMsj');
	document.getElementById("grillaPersExpMsj").style.display = 'block';  	
	return resultado; 
}
function GrabarRegistroNomina(idRow, cuil, nombre, fechaingreso, fechainiexpo, sectortrab, puestotrab, arrayRiesgos){	
	if(idRow == 0){
		if( !ValidarDatosNewItem() ){
			alert('Complete todos los campos...');
			return false;
		}
	}	
	/*busca los datos en la Base de Datos.... */
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=GrabarRegistroNomina";
	
	strparametros = strparametros+"&idRow="+encodeURIComponent(idRow);  	
	strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(idEstablecimiento);  	
	strparametros = strparametros+"&cuil="+encodeURIComponent(cuil);  
	strparametros = strparametros+"&nombre="+encodeURIComponent(nombre);  
	strparametros = strparametros+"&fechaingreso="+encodeURIComponent(fechaingreso);  
	strparametros = strparametros+"&fechainiexpo="+encodeURIComponent(fechainiexpo);  
	strparametros = strparametros+"&sectortrab="+encodeURIComponent(sectortrab);  
	strparametros = strparametros+"&puestotrab="+puestotrab;  
	
	var jsonArray = ConverArraytoJson(arrayRiesgos);
	strparametros = strparametros+"&arrayRiesgos="+encodeURIComponent(jsonArray);  
	
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'grillaPersonalExpuesto');  
	document.getElementById("grillaPersonalExpuesto").style.display = 'block';  			
	return true;
}
function eliminarItem(id){
	var titulo = 'Elimina Trabajador';
	var encabezado = '';
	var mensaje = '¿Está seguro que desea eliminar el trabajador?';
		
	MensajeValidacion(titulo, encabezado, mensaje, ConfirmaEliminarItem, id, 'aceptar', 400);
}
function ConfirmaEliminarItem(id){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=EliminarNominaWEB";
	
	strparametros = strparametros+"&idNomina="+encodeURIComponent(id);  
	
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'grillaPersExpMsj');
	document.getElementById("grillaPersExpMsj").style.display = 'block';  
		
	Grilla_NominaPersonalExpuesto(1, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
	return true;
}
function mensajenomodificar(x, y){
	var titulo = 'Aviso';
	encabezado = '';
	mensaje = 'No puede modificar este valor';
	MostrarMsjGrid(titulo, encabezado, mensaje);
}
function MensajeValidacion(titulo, encabezado, mensaje, funcion, parametro, botones, widthMsj){			
	JQDivSetValue('#ui-id-8', titulo);
	
	if(encabezado == ''){
		document.getElementById("encabezadoAV").style.display = 'none';  
	}else{
		document.getElementById("encabezadoAV").style.display = 'block'; 
		JQDivSetValue('#encabezadoAV', encabezado);	
	}	
		
	//JQDivSetValue('#msjAValid', mensaje);
	document.getElementById('msjAValid').innerHTML = mensaje;
		
	iniDialogMensajeValidacion(funcion, parametro, botones, widthMsj);
	$("#dialogMensajeValidacion").dialog("open");  
	return true;
}
function verCargaESOPEdicion(idRow){
	
	var CUIL = JQDivGetValue("#CELDA_2_"+idRow); 
	var NomApe = JQDivGetValue("#CELDA_3_"+idRow);
	var Sector = JQDivGetValue("#CELDA_6_"+idRow);	
	var EsopPuesto = JQDivGetValue("#CELDA_7_"+idRow);	
	
	verCargaESOP(NomApe, Sector, CUIL, EsopPuesto);
}
function GrabarItemModificado(idRow, controlName){
	
	var cuil = JQDivGetValue("#CELDA_2_"+idRow); 
	var nombre = JQDivGetValue("#CELDA_3_"+idRow);
	var fechaingreso = JQDivGetValue("#CELDA_4_"+idRow);
	var fechainiexpo = JQDivGetValue("#CELDA_5_"+idRow);	
	var sectortrab = JQDivGetValue("#CELDA_6_"+idRow);	
	//var puestotrab = JQDivGetValue("#CELDA_7_"+idRow);	
	var arrayRiesgos = seleccionDatosESOP;
	
	
	if(controlName == 'EditNomApe'){nombre = document.getElementById("EditNomApe").value;}
	if(controlName == 'EditFecIng'){fechaingreso = document.getElementById("EditFecIng").value;}
	if(controlName == 'EditFecIni'){fechainiexpo = document.getElementById("EditFecIni").value;}
	if(controlName == 'EditSecTra'){sectortrab = document.getElementById("EditSecTra").value;}
	
	nombre = escape(nombre);
	
	if(id_PuestoTrabajoEdicion == 0){
		id_PuestoTrabajoEdicion =  BuscarPuestoTrab(idRow);
		puestotrab = id_PuestoTrabajoEdicion;
	}else{
		if(controlName == 'EditPueTra'){
			puestotrab = id_PuestoTrabajoEdicion;
		}
	}	
	id_PuestoTrabajoEdicion = 0;
	
	GrabarRegistroNomina(idRow, cuil, nombre, fechaingreso, fechainiexpo, sectortrab, puestotrab, arrayRiesgos);
	Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
}
function ValidaColAnterior(NumColumna, idColumnaActual){
	var nombreDiv = '#CELDA_'+(NumColumna-1)+'_'+idColumnaActual;
	var valorDiv = JQDivGetValue(nombreDiv);	
	if(valorDiv == ''){ return false; }
	return true; 
}
function HabilitaColumnaSector(NumColumna, IDColumna){
	
	if( !HabilitaControl("EditSecTra") ){
		return true;}
	/*Se elimina esta validacion
	if( !ValidaColAnterior(NumColumna, IDColumna) ){		
		var titulo = 'Aviso';
		var encabezado = '';
		var mensaje = 'Debe completar Fecha de inicio de la exposicion primero';
		MostrarMsjGrid(titulo, encabezado, mensaje);
		return false;
	}
	*/
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
		
	var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
	eliminaEventoOnDblClick(nombreDivJS);
	
	
	JQDivSetValue(nombreDiv, "<input id='EditSecTra' type='text' style='border:0;margin:0;padding:0; display:block; width:150px; height:auto; text-transform: uppercase;' value='"+valorDiv+"' class='txt-enabled' maxlength='100' keypress=' ' />");	
	$('#EditSecTra').focus();	
	
	$("#EditSecTra").change( function(){ makeUppercase("EditSecTra"); } );
	
	$('#EditSecTra').keypress( function(event){		
									if (event.keyCode == 13) {
										if((CurrentValue != valorDiv)  ){ 
											GrabarItemModificado(IDColumna, 'EditSecTra'); 
										}else{
											Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
										}
										$(nombreDiv).focus();			
										event.preventDefault();
										event.stopPropagation();
									}
		} );	
	var CurrentValue = valorDiv;
	
	$('#EditSecTra').change( function(){ CurrentValue = $('#EditSecTra').val(); } );
	
	$('#EditSecTra').blur( function(){ 		 
		if((CurrentValue != valorDiv)  ){ 
			GrabarItemModificado(IDColumna, 'EditSecTra'); 
		}else{
			Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);}
			
		$(nombreDiv).focus();			
	} );
	
	document.getElementById("originalGrid").style.borderspacing = '0';  		
	EditArrayEsop(IDColumna);
		
	document.getElementById("nuevoRegistro").style.display = 'none';  			
	document.getElementById("idbtnActualizar").style.display = 'none';  			
	
	return true;
}
function TeclasGrilla(idControlJQ){
	$(idControlJQ).datepicker("hide"); 
	Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);
		
	return true;
}
function HabilitaColumnaFecIni(NumColumna, IDColumna){
		
	if( !HabilitaControl("EditFecIni") ){
		return true;}
	
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
	
	var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
	eliminaEventoOnDblClick(nombreDivJS);
	
	JQDivSetValue(nombreDiv, "<input type='text' id='EditFecIni' style='display:block; width:80px; height:auto;' value='"+valorDiv+"' class='txt-enabled' maxlength='12' row='1'  /> ");	
	
	$("#EditFecIni").datepicker( { dateFormat: 'dd/mm/yy',
		onClose: function(){ TeclasGrilla('#EditFecIni');}
	} );
	
	$('#EditFecIni').focus();	
	
	//var fechIngInicial = $('#EditFecIni').val();
	
	$('#EditFecIni').change( function(){ 			
		var idfechaIniCelda = '#CELDA_4_'+IDColumna;
		if( !ValidarFechaInicioExpo('#EditFecIni', idfechaIniCelda) ){
			$('#EditFecIni').focus();  						
		}else{	
			GrabarItemModificado(IDColumna, 'EditFecIni'); 
			$( "#EditFecIni" ).datepicker("hide"); 
		}		
	} );	
	
	//$('#EditFecIni').blur( function(){ Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL); } );	
	
	document.getElementById("nuevoRegistro").style.display = 'none';  			
	document.getElementById("idbtnActualizar").style.display = 'none'; 
	return true;
}
function HabilitaColumnaFecIng(NumColumna, IDColumna){
	
	if( !HabilitaControl("EditFecIng") ){
		return true;}
	
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
	
	if(valorDiv == ''){
		var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
		eliminaEventoOnDblClick(nombreDivJS);
		
		JQDivSetValue(nombreDiv, "<input type='text' id='EditFecIng' style='display:block; width:80px; height:auto;' value='"+valorDiv+"' class='txt-enabled' maxlength='12' row='1'  /> ");	
		
		$("#EditFecIng").datepicker( { dateFormat: 'dd/mm/yy',
			onClose: function(){TeclasGrilla('#EditFecIng');}
		} );
			
		$('#EditFecIng').focus();	
		
		var fechIngInicial = $('#EditFecIng').val();	
			
		$('#EditFecIng').change( function(){ 					
			if( !ValidarFechaIngreso('#EditFecIng') ){
				$('#EditFecIng').focus();  						
			}else{	
				GrabarItemModificado(IDColumna, 'EditFecIng'); 
				$( "#EditFecIng" ).datepicker("hide"); 
			}		
		} );	
		
		document.getElementById("nuevoRegistro").style.display = 'none';  			
		document.getElementById("idbtnActualizar").style.display = 'none';  			
	}else{
		var titulo = 'Informacion';
		encabezado = '';
		mensaje = valorDiv+' No se puede modificar.';
		MostrarMsjGrid(titulo, encabezado, mensaje);
	}	
	return true;
}
function HabilitaControl(idControl){
	EstaModoEdicion = true;
	
	if( document.getElementById(idControl) ){
		return false;
	}else{
		if(EstaModoEdicion){
			Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL); 
			EstaModoEdicion = true;
		}
		return true;
	}
}
function MostrarMsjGrid(titulo, encabeza, msj){				
	JQDivSetValue('#ui-id-6', titulo);
	
	if(encabeza == ''){
		document.getElementById("grillaPersExpMsj").style.display = 'none';  
	}else{
		document.getElementById("grillaPersExpMsj").style.display = 'block';  
	}
	
	JQDivSetValue('#MostrarMsjGridTit', encabeza);
	JQDivSetValue('#MostrarMsjGridText', msj);
	$("#dialogMostrarMsjGrid").dialog("open"); 
}
function HabilitaColumnaPuesto(NumColumna, IDColumna){
		
	if( !HabilitaControl("EditPueTra") ){
		return false;}
	/*Se elimina esta validacion
	if( !ValidaColAnterior(NumColumna, IDColumna) ){
		var titulo = 'Aviso';
		var encabezado = '';
		var mensaje = 'Debe completar Sector de Trabajo primero';		
		MostrarMsjGrid(titulo, encabezado, mensaje);
		return false;
	}		
	*/
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
	
	var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
	eliminaEventoOnDblClick(nombreDivJS);
	
	var textLista = 'Ingese texto, seleccione de la lista';	
	JQDivSetValue(nombreDiv, "<input id='EditPueTra' type='text' style='display:block; width:120px; height:auto;' value='"+valorDiv+"' class='txt-enabled' maxlength='120' placeholder='"+textLista+"' title='"+textLista+"' /> ");	
	
	//Limpio la variable global
	id_PuestoTrabajoEdicion = 0;
	
	autocompletePueTraEdicion("#EditPueTra");
	$('#EditPueTra').focus();	
	
	$('#EditPueTra').keypress( function(event){ 		
		
		if (event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 27){ 		
			//Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);		
			GrabarItemModificado(IDColumna, 'EditPueTra'); 		
			
			$( "#EditPueTra" ).autocomplete( "close" );
			
			event.preventDefault();
			event.stopPropagation();
		}		
	});
	
	
	$('#EditPueTra').blur( function(event){ 					
		var valorEdit = $('#EditPueTra').val();
		
		if( valorEdit == '' || id_PuestoTrabajoEdicion == 0 ){			
			// MostrarMsjGrid('Aviso', '', 'Debe seleccionar un valor de la lista...');
			// $('#EditPueTra').focus();  			
			JQDivSetValue("#CELDA_7_"+IDColumna, '');	
			$('#EditPueTra').val('');
			id_PuestoTrabajoEdicion = 0;			
			GrabarItemModificado(IDColumna, 'EditPueTra'); 					
		}else{	
			GrabarItemModificado(IDColumna, 'EditPueTra'); 		
		}
		$(nombreDiv).focus();			
	} );	
	
	EditArrayEsop(IDColumna);
	
	$( "#EditPueTra" ).autocomplete( "close" );
	document.getElementById("nuevoRegistro").style.display = 'none';  			
	document.getElementById("idbtnActualizar").style.display = 'none';  	
	return true;
}
function autocompletePueTraEdicion(idControl){
	
	$(idControl).autocomplete({
			source: arrayjsPuestosNomina,
			minLength: 2,
			delay: 400,	
/*			
			open: function( event, ui ) {
					$(idControl).val('');  					
					id_PuestoTrabajoEdicion = 0; },
*/					
			focus: function(event, ui) {					
				event.preventDefault();  
				$(this).val(ui.item.label);
				//id_PuestoTrabajoEdicion = 0;
			},
			select: function(event, ui) {					
				event.preventDefault();  
				$(this).val(ui.item.label);
				
				$(idControl).val(ui.item.label);  					
				id_PuestoTrabajoEdicion = ui.item.value;
			}
		}).autocomplete("widget").addClass("limit-width-height");
}
function HabilitaColumnaESOP(NumColumna, IDColumna){
	
	if( !HabilitaControl("id_EditIdeRie") ){
		Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL); 
		//return true;
	}
	/*Se elimina esta validacion
	if( !ValidaColAnterior(NumColumna, IDColumna) ){
		var titulo = 'Aviso';
		var encabezado = '';
		var mensaje = 'Debe completar Puesto de Trabajo primero';		
		MostrarMsjGrid(titulo, encabezado, mensaje);
		return false;
	}		
	*/
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
	
	var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
	eliminaEventoOnDblClick(nombreDivJS);
		
	JQDivSetValue(nombreDiv, "<div style='width:150px; max-width:100px; height:auto;' id='EditIdeRie' >  <input id='id_EditIdeRie' type='text' style='height:auto; display: block; width:120px; height: auto; ' readonly='readonly' class='txt-disabled XLSfloatLeft' value='"+valorDiv+"' /> </div> <input type='button' class='btnEditar XLSfloatRight'  onclick='verCargaESOPEdicion("+IDColumna+")' id='idbtnActualizarEDIT' />");	
	
	$('#id_EditIdeRie').focus();		
	idControlESOP = 'id_EditIdeRie';
	columnaEdit = IDColumna;
//-------------------------------------------------------------
	$('#id_EditIdeRie').click( function(){ 		 
		Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);			
		$(nombreDiv).focus();			
	} );
//-------------------------------------------------------------	
	if(valorDiv != ''){ 
		ActualizarArrayInicial(valorDiv);	
	}else{
		ActualizarArrayInicial('');	}
	
	document.getElementById("nuevoRegistro").style.display = 'none';  			
	document.getElementById("idbtnActualizar").style.display = 'none';  	
	return true;
}
function EditArrayEsop(IDColumna){
	/*Actuliza el array de ESOP (columna 8)*/		
	//var nombreDiv = '#CELDA_8_'+IDColumna; 		//JQUERY
	
	var nombreDiv = 'CELDA_8_'+IDColumna; 			//JS
	var valorDiv = document.getElementById(nombreDiv).innerHTML; //JQDivGetValue(nombreDiv);	
		
	if(valorDiv != ''){ 
		ActualizarArrayInicial(valorDiv);	
	}else{
		ActualizarArrayInicial('');	}
}
function HabilitaColumnaNombApe(NumColumna, IDColumna){
		
	if( !HabilitaControl("EditNomApe") ){
		return true;}
	
	var nombreDiv = '#CELDA_'+NumColumna+'_'+IDColumna;
	var valorDiv = JQDivGetValue(nombreDiv);	
	
	var nombreDivJS = 'CELDA_'+NumColumna+'_'+IDColumna;
	eliminaEventoOnDblClick(nombreDivJS);
	
	if( !PuedeEditNombApe(valorDiv)){
		var titulo = 'Informacion';
		encabezado = '';
		mensaje = valorDiv+' No se puede modificar';
		MostrarMsjGrid(titulo, encabezado, mensaje);
	}else{
		JQDivSetValue(nombreDiv, "<input id='EditNomApe' type='text' style='display:block; width:150px; height:auto;' value='"+valorDiv+"' class='txt-enabled' maxlength='100' />");	
		$('#EditNomApe').focus();	
		var CurrentValue = valorDiv;
		
		$('#EditNomApe').change( function(){ CurrentValue = $('#EditNomApe').val(); } );
		
		$('#EditNomApe').blur( function(){ 		 
			if((CurrentValue != valorDiv) && (CurrentValue != '') ){ 
				GrabarItemModificado(IDColumna, 'EditNomApe'); 
			}else{
				Grilla_NominaPersonalExpuesto(CurrentPage, CurrentBuscarTrabajadorNombre, CurrentBuscarTrabajadorCUIL);}
				
			$(nombreDiv).focus();			
		} );
	
		document.getElementById("nuevoRegistro").style.display = 'none';  			
		document.getElementById("idbtnActualizar").style.display = 'none';  		
	}
	return true;
}
function ValidarNomina(){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=ValidarNomina";
		
	strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(idEstablecimiento);  	
	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'grillaPersExpMsj');
	document.getElementById("grillaPersExpMsj").style.display = 'block';  
	
	if(resultado == 0){ return false; }
	return true;
}
function GuardarNomina(event){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=GuardarNominaEstablecimiento";
	
	strparametros = strparametros+"&IDESTABLECIWEB="+encodeURIComponent(idEstablecimiento);  	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'grillaPersExpMsj');
	
	if(resultado != ''){
		MensajeValidacion("Aviso", "<div style='text-align:left; color:red;'>Debe cargar todos los datos en la nómina, para que la misma pueda generarse</div>",resultado, 	'',  '');
		event.preventDefault();
		event.stopPropagation();
		return true;
	}else{		
		 ValidarMensajeConfirmacion();					
	}	
}
function ValidarMensajeConfirmacion(event){
	/*Se van a mostrar los mensajes de validacion */
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=MensajesNominaEstablecimiento";
	
	strparametros = strparametros+"&IDESTABLECIWEB="+encodeURIComponent(idEstablecimiento);  
		
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'grillaPersExpMsj');
	var resultParse = JSON.parse(resultado);				
	
	if(resultParse != ''){						
		MensajeESOP_Validacion(resultParse);
				
		//event.preventDefault();
		//event.stopPropagation();
		return true;
	}else{	
		MensajeESOP_Validacion(resultParse);
		
		//event.preventDefault();
		//event.stopPropagation();
		return true;
	}
	
}
function MensajeESOP_Validacion(resultParse){
	
	var esRestrictivo = false;
	
	$.each(resultParse, function(i, item) {
			
		if(i == 'RESTRICTIVO'){								
			MensajeValidacion('Restrictivo', '', '<div style="color:red;">'+item+'</div>', '', '');
			
			esRestrictivo = true;
			event.preventDefault();
			event.stopPropagation();
			return false;
		}else{				
			arrmensajes.push(item);
		}
		return true;
	});
	if( !esRestrictivo ){
		globalvalue = 0;
		MensajeESOP_ValRecorre();
	}
}
function MensajeESOP_ValRecorre(){
	
	var titulo = 'Informativo';
	var encabezado = '';
	var mensaje = '';
	
	if(arrmensajes.length == 0){
			MensajeValidacion('Informativo', '', 'No hay mensajes para mostrar. Por favor Imprima el PDF con la Nómina de Personal Expuesto', redirectSeleccionEstablecimiento, '', 'soloaceptar');
	}
	
	for (var i = 0; i < arrmensajes.length; i++) {		
		
		var entrar = false;
		
		if(globalvalue == arrmensajes.length){			
			MensajeValidacion('Informativo', '', 'Por favor Imprima el PDF con la Nómina de Personal Expuesto', redirectSeleccionEstablecimiento, '', 'soloaceptar');		
			return true;
		}
		
		if(globalvalue == i){
			entrar = true;			
		}		
		
		if(entrar){
			globalvalue = i+1;
			titulo = 'Informativo';
			mensaje = arrmensajes[i];
			MensajeValidacion(titulo, encabezado, mensaje, MensajeESOP_ValRecorre, '');
			
			return true;			
		}		
	}
	return true;
}
function MensajeESOP80006(resultParse){
	if( resultParse.ESOP80006 > 0){
		var titulo = 'Alerta';
		var encabezado = "<div style='color:red;' >El sistema no procesará la nómina, volverá a la pantalla de carga de puestos para que el usuario cambie el mismo</div>";
		var mensaje = "Se han declarado ESOP 80006 tuberculosis. Il Santo Padre ha disposto che il presente decreto generale esecutivo sia promulgato mediante la pubblicazione su L'Osservatore Romano, entrando in vigore l’11 novembre 2014, e successivamente nel commentario ufficiale Acta Apostolicae Sedis.";		
		
		MensajeValidacion(titulo, encabezado, mensaje, '', '');		
		return false;
	}else{
		return true;		
	}
}
function redirectSeleccionEstablecimiento(){
	imprimeListadoExpuestosPDF(idEstablecimiento, true);		
	window.location.assign('/SeleccionarEstablecimiento');
}
function BuscarDetalleESOPJSON(idRiesgo){
	
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=BuscarDetalleESOPJSON";	
	
	strparametros = strparametros+"&RiesgoESOP="+encodeURIComponent(idRiesgo);  
		
	var resultado = ProcesarDatosJSON(pagefunciones, encodeURI(strparametros), 'resultadoProceso');
	
	if(resultado){
		var resultadoProceso = document.getElementById("resultadoProceso").innerHTML;
		var resultParse = JSON.parse(resultadoProceso);
	
		LimpiarContolID("detaESOP_Codigo");
		LimpiarContolID("detaESOP_AgenteRiesgo");
		LimpiarContolID("detaESOP_Grupo");
		LimpiarContolID("detaESOP_Criterio1");
		LimpiarContolID("detaESOP_Criterio2");
		LimpiarContolID("detaESOP_Observaciones");
		LimpiarContolID("detaESOP_Limite");
		LimpiarContolID("detaESOP_ACGIH");		
	
		if( resultParse.RIESGOESOP != '0' ){			
			document.getElementById("detaESOP_Codigo").innerHTML = resultParse.RIESGOESOP;
			document.getElementById("detaESOP_AgenteRiesgo").innerHTML = resultParse.AGENTERIESGO;			
			document.getElementById("detaESOP_Grupo").innerHTML = resultParse.GRUPO;
			document.getElementById("detaESOP_Criterio1").innerHTML = resultParse.CRITERIO1;
			document.getElementById("detaESOP_Criterio2").innerHTML = resultParse.CRITERIO2;
			document.getElementById("detaESOP_Observaciones").innerHTML = resultParse.OBSERVACIONES;		
			document.getElementById("detaESOP_Limite").innerHTML = resultParse.LIMIT;		
			document.getElementById("detaESOP_ACGIH").innerHTML = resultParse.ACGIH;		
		}
	}	
	return true;
}
/******************* ESOP COPY************************/
function ActualizarArrayInicial(valores){
	
	var arrayVal = stringToArray(valores, ",");	
	seleccionDatosESOP.length=0;
	
	if(valores != ''){
		for(var x=0; x < arrayVal.length; x++){
			var valArr = trim(arrayVal[x]);
			seleccionDatosESOP.push(valArr);			
		}
	}	
	return true;
}
  
function actualizarArrayDefault(){
	/*COPIA LOS ESOP SELECCIONADOS EN EL GRUPO DE ESOP DEFAULT*/	
	seleccionDatosESOPdefault.length=0;	
	for(var x=0; x < seleccionDatosESOP.length; x++) {
		seleccionDatosESOPdefault.push(seleccionDatosESOP[x]);
	}
	return true;
}
function actualizarArrayDefaultActividad(){
	/*COPIA LOS ESOP CORRESPONDIENTES A ESTA ACTIVIDAD A LOS ESOP POR DEFAULT*/		
	for(var x=0; x < seleccionDatosESOPActividad.length; x++) {
		seleccionDatosESOPdefault.push(seleccionDatosESOPActividad[x]);
	}
	return true;
}
function limpiarArrayDefault(){
	
	seleccionDatosESOPdefault.length=0;	
	actualizarArrayDefaultActividad();
	return true;
}
function CopiaArrayInicial(){
	//seleccionDatosESOP.length=0;	
	if(seleccionDatosESOP.length === 0){
		//si hay esop seteados como default se usan estos
		if(seleccionDatosESOPdefault.length > 0){		
			for(var x=0; x < seleccionDatosESOPdefault.length; x++) {
				seleccionDatosESOP.push(seleccionDatosESOPdefault[x]);
			}
		}else{
			//si no hay default se utilizan los de la actividad seleccionada
			for(var x=0; x < seleccionDatosESOPActividad.length; x++) {
				seleccionDatosESOP.push(seleccionDatosESOPActividad[x]);
				seleccionDatosESOPdefault.push(seleccionDatosESOPActividad[x]);
			}
		}
	}
}
function autocompleteEmpleados(){
	$( "#input_CUIT" ).autocomplete({
		minLength: 1,
		delay: 400,		
		source: arrayjsEmpleados
    }).autocomplete("widget").addClass("limit-width-heightEmp");

/*
	$("#input_CUIT").autocomplete({
			minLength: 1,
			delay: 400,		
			source: arrayjsEmpleados
			
			falla en IE
			source: function( request, response ) {
				  var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
				  response( $.grep( arrayjsEmpleados, function( item ){
					  return matcher.test( item.value );
				  }) );
			  }
			  
		}).autocomplete("widget").addClass("limit-width-heightEmp");
	*/
}
function autocompEmpNombre(){	
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "?funcion=GetJSArrayEmpleados";
	strparametros += "&ArrayName="+encodeURIComponent('arrayJsonNombre');  
	strparametros += "&EMPRESA="+param_idEmpresa;  
	strparametros += "&NOMBRE="+$('#id_nrNomApe').val();  
	var urljson = window.location.origin + pagefunciones + strparametros;
	
	$( "#id_nrNomApe" ).autocomplete({
		source: urljson,		
		minLength: 3,		
		select: function( event, ui ) {
			$( "#input_CUIT" ).val(ui.item.value);
			$( "#id_nrNomApe" ).val(ui.item.label);
		},
		open: function( event, ui ) {
			strparametros = "?funcion=GetJSArrayEmpleados";
			strparametros += "&ArrayName="+encodeURIComponent('arrayJsonNombre');  
			strparametros += "&EMPRESA="+param_idEmpresa;  
			strparametros += "&NOMBRE="+$('#id_nrNomApe').val();  
			urljson = window.location.origin + pagefunciones + strparametros;;
		}
    }).autocomplete("widget").addClass("limit-width-heightEmp");
}
//---------------------------------------------------------------------------
function MostrarListadoEmpleados(idControl) {
	/*Funcion muestra un listado para la busqueda de empleados por nombre*/	
	ReadAndColors("id_nrNomApe");	
	document.getElementById("id_nrNomApe").style.display = 'block';  
	
	if( !document.getElementById("listadoEmpleados")){		
		$("#DatosGeneralesPersonalExpuesto").append( "<div id='listadoEmpleados' style='position: absolute; width: auto; display: block;  z-index: 101;' >lista empleados vacia</div>");
		
		var elemento = $("#id_nrNomApe");
		var posicion = elemento.position();
		
		$("#listadoEmpleados").css("left", posicion.left);
		$("#listadoEmpleados").css("top", posicion.top +15);
		
		//$("#listadoEmpleados").addClass("limit-width-heightEmp");
		$("#listadoEmpleados").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable");			
	}else{
		var elemento = $("#id_nrNomApe");
		var posicion = elemento.position();
		
		$("#listadoEmpleados").css("left", posicion.left);
		$("#listadoEmpleados").css("top", posicion.top +15);
		document.getElementById("listadoEmpleados").style.display = 'block';  
	}
	
	var controlJQ = "#"+idControl;
		$(controlJQ).keypress( 
			function(event){ 
				if ( event.which == 13 ){ 
					event.preventDefault(); 
					BuscarListadoEmpleados('listadoEmpleados', mostrarEmpleado ); 
					if( document.getElementById("listadoResultados") ){
						document.getElementById("listadoResultados").style.display = 'block';  	
						document.getElementById("listadoResultados").focus();  
					}else{
						if( document.getElementById("listadoEmpleados") )
							document.getElementById("listadoEmpleados").style.display = 'none';  						
					}
				} 
			} );	
		 
}

function mostrarEmpleado(){
	$('#input_CUIT').val( ComboIDSeleccionado('listadoResultados') );
	$('#id_nrNomApe').val( ComboValorTexto('listadoResultados') );	
	document.getElementById("listadoEmpleados").style.display = 'none';  
	ValidarGrabarRegistroNuevo();
	JQEliminarElemento("#listadoEmpleados");		
}
