$(document).ready(inicializar);
var arrayTelefonos = [];
var jsonTelefonos = '';
var opcTelefono = '';
var IDopcTelefono = -1;
var CargoRespHYS = '0';
var CSSsilver = "#C0C0C0";
var CSSfondoBco = "#fff";
function inicializar(){			
	FN_IniAsteriscos();
		
	if(TipoEstabSeleccionado != 'O'){			
		DesactivaTipoNomina(false, CSSsilver, true);	
	}
	if(TipoEstabSeleccionado == 'O')		
			ConfigIniTipoNomina();
	if(ArrayJsonTelEdit != ''){
		arrayTelefonos = [ArrayJsonTelEdit];
		ConertJsonToArray(ArrayJsonTelEdit);
		//arrayTelefonos = stringToArray(ArrayJsonTelEdit);
		//stringToArray
		Print_Telefonos( GetJson_Telefonos() );
		$("#hiddenArrayTelefonos").val( GetJson_Telefonos() );
		CODIGOCIUU = $("#hiddenCODIGOCIUU").val();
		CargoRespHYS = $("#hiddenCargoRespHYS").val();
		OcultarDiv('SinTelefonos');
	}
	
	$("#idTexActividad").change( function(){ makeUppercase("idTexActividad"); } );
	$("#RespNombre").change( function(){ makeUppercase("RespNombre"); } );
	$("#RespApellido").change( function(){ makeUppercase("RespApellido"); } );
	
	$("#RespEMail").change( function(){ makeUppercase("RespEMail"); } );
	$("#ContactoNombre").change( function(){ makeUppercase("ContactoNombre"); } );
	$("#ContactoApellido").change( function(){ makeUppercase("ContactoApellido"); } );
	$("#ContactoEMail").change( function(){ makeUppercase("ContactoEMail"); } );
	$("#ContactoCodArea").change( function(){ makeUppercase("ContactoCodArea"); } );
	$("#ContactoTelefono").change( function(){ makeUppercase("ContactoTelefono"); } );
	CopiaRespEmpaContacto();
	$("#RespEmpNombre").change( function(){ makeUppercase("RespEmpNombre"); CopiaRespEmpaContacto(); } );
	$("#RespEmpApellido").change( function(){ makeUppercase("RespEmpApellido"); CopiaRespEmpaContacto(); } );
	$("#RespEmpCodArea").change( function(){ makeUppercase("RespEmpCodArea"); CopiaRespEmpaContacto(); } );
	$("#RespEmpTelefono").change( function(){ makeUppercase("RespEmpTelefono"); CopiaRespEmpaContacto(); } );
	$("#ResptipoTelefono").change( function(){ makeUppercase("ResptipoTelefono"); CopiaRespEmpaContacto(); } );
	$("#RespEmpEMail").change( function(){ makeUppercase("RespEmpEMail"); CopiaRespEmpaContacto(); } );
	$("#TipoNominaCE").click( function(){DesactivaTipoNomina(true, CSSfondoBco, false); } );	
	$("#TipoNominaSE").click( 
		function(){
			ValidarNominaExistente();
			//DesactivaTipoNomina(false, CSSsilver, false);		
		} );	
	$("#ContactoIgualaResp").click( CopiaRespEmpaContacto );	
	
	AsignaValControlesRespHYS();
	AsignaValControlesRespEmp();
	AsignaValControlesRespContacto();
	
	var formDatosGenerales = AccordionFactory("#DatosGenerales");		
	var formEstablecimientos = AccordionFactory("#Establecimientos");	
	var formResponsableHYS = AccordionFactory("#ResponsableHYS");
	var formResponsableEmpresa = AccordionFactory("#ResponsableEmpresa");
	var formContacto = AccordionFactory("#Contacto");
	$("#Siguiente").click(boton_siguiente);	
	$("#idVolverDExp").click(cerrarVentana);	
	$("#radioAdmin").click( ValidarNominaExistente );	
	$("#radioComMino").click( ValidarNominaExistente );	
	$("#radioObraConst").click( ValidarNominaExistente );	
	$("#radioVehi").click( ValidarNominaExistente );	
	$("#radioOtros").click( ValidarNominaExistente );	
	$( "#redirectNomina" ).dialog({ autoOpen: false});
	iniDialogNominaMensajes('', '', '');
	iniDialogNominaEnProceso();
	iniDialogNuevaNomina();
	iniDialogDatosIncomp();
	iniDialogObraConstruccion();
	iniDialogExpuesto();
	iniDialogAltaTelefono();
	iniDialogSiguienteNominadeAnterior();
	IniDialogSinPersonalExpuesto('ACTUAL');
	if( showDialogExp )	showDialogSinPersonalExpuesto();	
	var btnAgregaTelefono = $("#AgregaTelefono").button();	
	btnAgregaTelefono.addClass("btnAgregarTelefono");
	btnAgregaTelefono.click(function( event ) {	
		LimpiarCampos_Telefono();		
		opcTelefono = 'Insert';
		$("#dialogAltaTelefono").dialog( "open" );
			false;		//event.preventDefault();
	});
	refreshButtonAddPhone();
	var btnVolveratras = $("#Volveratras").button();
	btnVolveratras.click(volverAtras);
	btnVolveratras.addClass("btnVolver");
	/////////////////////////////////////////////////////////////////		
	$("#autocompleteCIUU").change(function(){  limpiarCIIU(); });
	$("#autocompleteCIUU").blur(function(){  limpiarCIIU(); });
	$("#codigoCIUU").change(function(){  limpiarCIIU(); });
	$("#codigoCIUU").blur(function(){  limpiarCIIU(); });
	$("#autocompleteCIUU").autocomplete({
				source: listaciuu,
				minLength: 4,
				delay: 200,				
				open: function(event, ui) {	 CODIGOCIUU = 0; },				
				focus: function(event, ui) {					
					event.preventDefault();					
					$(this).val(ui.item.label);
				},				
				select: function(event, ui) {										
					event.preventDefault();					
					$(this).val(ui.item.label);
					$("#codigoCIUU").val(ui.item.value);					
					CODIGOCIUU = ui.item.codigo;
					$("#hiddenCODIGOCIUU").val(CODIGOCIUU);					
				}
				
			}).autocomplete("widget").addClass("limit-width-height");
			
	$("#codigoCIUU").autocomplete({				
				source: listaciuuCodigos,
				minLength: 1,
				delay: 400,	
				open: function(event, ui) {	 CODIGOCIUU = 0; },				
				focus: function(event, ui) {					
					event.preventDefault();					
					$(this).val(ui.item.label);
				},
				select: function(event, ui) {					
					event.preventDefault();					
					$(this).val(ui.item.label);
					$("#autocompleteCIUU").val(ui.item.value);
					//$("#labelcodigoCIUU").text(ui.item.codigo);
					CODIGOCIUU = ui.item.codigo;
					$("#hiddenCODIGOCIUU").val(CODIGOCIUU);
				}
			}).autocomplete("widget").addClass("limit-width-height").blur(function(){
					if(!select){
						$("#tags").val($('ul.ui-autocomplete li:first a').text());
					}
				});
							
	FN_Control_ResplistaCargos();
}
function FN_Control_ResplistaCargos(){
	var ResplistaCargos = $("#ResplistaCargos");
	
	ResplistaCargos.change(
		function(){ 
			FN_LimpiaResponsable(); 
			$("#hiddenCargoRespHYS").val('0');  
			AsignaAsteriscoRespHYS();  
			makeUppercase("ResplistaCargos"); 
	} );	
	ResplistaCargos.blur( 
		function(){ 
			FN_LimpiaResponsable(); 
			//AsignaAsteriscoRespHYS();  
		} );
	ResplistaCargos.autocomplete({
				source: listaCargos,
				minLength: 3,
				delay: 500,				
				open: function(event, ui) {	 CargoRespHYS = 0; },				
				focus: function(event, ui) {					
					event.preventDefault();					
					$(this).val(ui.item.label);
				},
				select: function(event, ui) {					
					event.preventDefault();					
					$(this).val(ui.item.label);					
					CargoRespHYS = ui.item.value;
					$("#hiddenCargoRespHYS").val(CargoRespHYS);
				},
				close: function( event, ui ) { FN_LimpiaResponsable(); }
			}).autocomplete("widget").addClass("limit-width-height");
}
function FN_ObligAsteriscos(nameControls, mandatory){
	var elem = document.getElementsByName(nameControls);
	for (i = 0; i < elem.length; i++) {
		if(mandatory){
			elem[i].style.display = 'inline-block';  		
		}else{
			elem[i].style.display = 'none';  		
		}
	}
}
function FN_IniAsteriscos(){
	var elem = document.getElementsByName("RespHYSOblig");
	for (i = 0; i < elem.length; i++) {		
		elem[i].style.display = 'none';  
	}
	elem = document.getElementsByName("RespEmpOblig");
	for (i = 0; i < elem.length; i++) {
		elem[i].style.display = 'none';  
	}
	elem = document.getElementsByName("ContactoOblig");
	for (i = 0; i < elem.length; i++) {		
		elem[i].style.display = 'none';  
	}
	elem = document.getElementsByName("TipoEstOblig");
	for (i = 0; i < elem.length; i++) {		
		elem[i].style.display = 'none';  
	}	
	/* En IE 8 falla getElementsByName... */
	elem = document.getElementById("RespHYSOblig");
	elem.style.display = 'none';  
	elem = document.getElementById("RespEmpOblig");
	elem.style.display = 'none';  
	elem = document.getElementById("ContactoOblig");
	elem.style.display = 'none';  
	elem = document.getElementById("TipoEstOblig");
	elem.style.display = 'none';  
	
}
function FN_LimpiaResponsable(){
	if(CargoRespHYS == '0'){ 
		$("#hiddenCargoRespHYS").val('0'); 
		$("#ResplistaCargos").val(''); 
	}	
	return true;
}
function iniDialogSiguienteNominadeAnterior(){
	var dialogSiguiente = $("#dialogSiguienteNominaExiste");		
	dialogSiguiente.dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{				
				id: "DS_btnSI",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					/* dialogo siguiente*/
					InsertNominaActualdeNominaAnterior();
					return true;
				}
			},
			{
				id: "DS_btnNO",
				text: "",
				click: function() {
					VarMsjNuevaNomina();
					$( this ).dialog( "close" );							
					return false;
				}
			}
		]
	});	
	JQAsignaClaseCSS("#DS_btnSI", "btnSI");	
	JQAsignaClaseCSS("#DS_btnNO", "btnNO");	
}
function iniDialogAltaTelefono(){
	var dialogAltaTelefono = $("#dialogAltaTelefono");	
	dialogAltaTelefono.dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{				
				id: "DAT_btnGrabar",
				text: "",
				click: function() {
					if( opcTelefono == 'Insert'){
						if( Insertar_Telefono() ){						
							$( this ).dialog( "close" );							
							return true;
						}
					}
					if( opcTelefono == 'Edit'){		
						if( Update_ArrayTelefonos() ){
							$( this ).dialog( "close" );
							return true;
						}
					}
					return false;
				}
			},
			{
				// class: "btnCancelar2",
				id: "DAT_btnCancelar2",
				text: "",
				click: function() {										
					$( this ).dialog( "close" );
					return true;					
				}
			}
		]
	});
	JQAsignaClaseCSS("#DAT_btnGrabar", "btnGrabar");	
	JQAsignaClaseCSS("#DAT_btnCancelar2", "btnCancelar2");	
}
function iniDialogExpuesto(){
	var dialogExpuesto = $("#dialogExpuesto");		
	dialogExpuesto.dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{
				// class: "btnSiguiente",
				id: "DE_btnSiguiente",				
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					return redirectNominaPersonalExpuesto();
				}
			}
		]
	});	
	JQAsignaClaseCSS("#DE_btnSiguiente", "btnSiguiente");	
}
function iniDialogObraConstruccion(){
	var dialogObraConstruccion = $("#dialogObraConstruccion");			
	dialogObraConstruccion.dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [			
			{
				// class:"btnVolver",							
				id:"DOC_btnVolver",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	JQAsignaClaseCSS("#DOC_btnVolver", "btnVolver");	
}
function iniDialogDatosIncomp(){
	$( "#dialogDatosIncomp" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [			
			{
				// class:"btnAceptar",							
				id:"DDI_btnAceptar",											
				text: "",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	JQAsignaClaseCSS("#DDI_btnAceptar", "btnAceptar");	
}
function iniDialogNuevaNomina(){
	$( "#dialogNuevaNomina" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [			
			{
				// class:"btnAceptar",							
				id:"DNN_btnAceptar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );						
						redirectNominaPersonal_StepTwo();
					return true;
				}
			}
		]
	});
	JQAsignaClaseCSS("#DNN_btnAceptar", "btnAceptar");	
}
function iniDialogNominaMensajes(funcion, parametro, funcionCancel){
		var botones = [
			{
				id:"DNM_btnAceptar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );					
					if(funcion != '') funcion(parametro);
					return true;
				}
			},
			{
				id: "DNM_btnCancelar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );  
					if(funcionCancel != '') funcionCancel();					
					return false;
				}
			}
		]; 
		$( "#dialogNominaMensajes" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: botones
	});
	if ($('#DNM_btnAceptar').length)  JQAsignaClaseCSS("#DNM_btnAceptar", "btnAceptar");	
	if ($('#DNM_btnCancelar').length)  JQAsignaClaseCSS("#DNM_btnCancelar", "btnCancelar2");	
}
function MuestrNominaMensajes(titulo, encabezado, mensaje, funcion, parametro, funcionCancel){			
	JQDivSetValue('#ui-id-12', titulo);
	if(encabezado == '')
		document.getElementById("TituloMensaje").style.display = 'none';  
	else
		document.getElementById("TituloMensaje").style.display = 'block';  		
	JQDivSetValue('#TituloMensaje', encabezado);
	JQDivSetValue('#TextoMensaje', mensaje);
	if( funcion == undefined ) funcion = '';
	if( parametro == undefined ) parametro = '';
	
	iniDialogNominaMensajes(funcion, parametro, funcionCancel);
	 $("#dialogNominaMensajes").dialog("open"); 
	return true;	
}
function iniDialogNominaEnProceso(){
		$( "#dialogNominaEnProceso" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [			
			{
				// class:"btnAceptar",							
				id:"NEP_btnAceptar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					redirectNominaPersonal_StepTwo();										
					return true;
				}
			}
		]
	});
	JQAsignaClaseCSS("#NEP_btnAceptar", "btnAceptar");	
}
function showDialogSinPersonalExpuesto(){	
	IniDialogSinPersonalExpuesto('ACTUAL');
	
	$("#dialogSinPersonalExpuesto").dialog("open");  
	showDialogExp = false;
	return true;
}	

function IniDialogSinPersonalExpuesto(AnnoProc){
	$('#dialogSinPersonalExpuesto').dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{				
				id: "btnSinPExpOk",
				text: "",
				click: function() {
					SetResponsableDefault(CODIGOEWID, 'resultadoProceso');
					imprimeListadoAnnoAnterior('key', 0, AnnoProc, empresaESTABLECI, empresaCUITSINGUION, 'NO');
					$( this ).dialog( "close" );														
					return true;
				}
			}
		]
	});	
	JQAsignaClaseCSS("#btnSinPExpOk", "btnAceptar");	
}
function CopiaRespEmpaContacto(){
	if( $("#ContactoIgualaResp").is(':checked') ){
		$("#ContactoNombre").val( $("#RespEmpNombre").val() );
		$("#ContactoApellido").val( $("#RespEmpApellido").val() );
		$("#ContactoCodArea").val( $("#RespEmpCodArea").val() );
		$("#ContactoTelefono").val( $("#RespEmpTelefono").val() );
		$("#ContactoTipoTelefono").val( $("#ResptipoTelefono").val() );
		$("#ContactoEMail").val( $("#RespEmpEMail").val() );
		JQBloqueaControl("#ContactoNombre", "txt-disabled", "txt-enabled");
		JQBloqueaControl("#ContactoApellido", "txt-disabled", "txt-enabled");
		JQBloqueaControl("#ContactoCodArea", "txt-disabled", "txt-enabled");
		JQBloqueaControl("#ContactoTelefono", "txt-disabled", "txt-enabled");
		JQBloqueaControl("#ContactoTipoTelefono", "txt-disabled", "txt-enabled");
		JQBloqueaControl("#ContactoEMail", "txt-disabled", "txt-enabled");		
	}else{
		JQDesBloqueaControl("#ContactoNombre", "txt-enabled", "txt-disabled");
		JQDesBloqueaControl("#ContactoApellido", "txt-enabled", "txt-disabled");
		JQDesBloqueaControl("#ContactoCodArea", "txt-enabled", "txt-disabled");
		JQDesBloqueaControl("#ContactoTelefono", "txt-enabled", "txt-disabled");
		JQDesBloqueaControl("#ContactoTipoTelefono", "txt-enabled", "txt-disabled");
		JQDesBloqueaControl("#ContactoEMail", "txt-enabled", "txt-disabled");	
		document.getElementById("ContactoNombre").disabled = false;		
	}
}
function VarMsjNuevaNominadeAnterior(){	
	//iniDialogSiguienteNominadeAnterior
	$("#dialogSiguienteNominaExiste").dialog("open");		
	return false;
}
function VarMsjNuevaNomina(){
	$("#dialogNuevaNomina").dialog("open");	
	return false;
}
function VerMsjEnProceso(){
	$("#dialogNominaEnProceso").dialog("open");	
	return false;
}
function showDialogNomina(){
		if($("#radioOtros").is(':checked') ){
			if($("#TipoNominaCE").is(':checked') ){
				//Si el establecimiento es Otros / Con Expuesto			
				if( ExisteNominaEnProceso > 0 )
					return VerMsjEnProceso();					
				else{
					if( ExisteNominaAnnoAnterior ){					
						return VarMsjNuevaNominadeAnterior();					
					}else{
						return VarMsjNuevaNomina();										
					}	
				}
			}
		}
		if($("#radioObraConst").is(':checked') ){
			var dialogObraConstruccion = $("#dialogObraConstruccion");					
			dialogObraConstruccion.dialog("open");			
			return false;
		}		
		showDialogSinPersonalExpuesto();		
}
function BotonValidarForm(){
		if( !ValidarNominaDatosGenerales() ) return false;				
		$("#hiddenArrayTelefonos").val( GetJson_Telefonos() );		
		return true;
}
function AccordionFactory(idAccordion){
	var formAccordion = $(idAccordion).accordion({icons: null, heightStyle: "content"});	
	$(idAccordion+' .ui-accordion-header').click( function() { $(idAccordion+' .ui-accordion-content').toggle(); });		
	return formAccordion;
}
function ValidarTelefono(){
	var tipoTelefono = $("#ATtipoTelefono option:selected").text();
	var area = document.getElementById("ATarea").value;
	var numero = document.getElementById("ATnumero").value;
	var arrayLength = arrayTelefonos.length;
	$('#ATerrores').empty();
	var conterror = 0;
	if( (opcTelefono == 'Insert') &&( arrayLength == 3)) {
			$('#ATerrores').append('Como máximo podrá cargar 3 teléfonos<p>'); conterror++;}
	if( tipoTelefono == '') {$('#ATerrores').append('Complete el tipo de telefono<p>'); conterror++;}
	if( $.trim(area) == '') {$('#ATerrores').append('Complete el area<p>'); conterror++;}
	if( $.trim(numero) == '') {$('#ATerrores').append('Complete el numero de telefono'); conterror++;}
	
	if( tipoTelefono != ''){
		var tipoTelefSeleccion = document.getElementById("ATtipoTelefono").value;	
		for (i = 0; i < arrayTelefonos.length; i++) { 
			var jsonrow = "["+arrayTelefonos+"]";
			var telefonosList = JSON.parse(jsonrow);
			var telefonos = telefonosList[i];
			console.log("telefono " + i + " = " + arrayTelefonos[i]);
			if(telefonos.tipoTelefono == tipoTelefSeleccion){
				$('#ATerrores').append('Este Tipo Teléfonos ya se ingreso. puede modificarlo desde la lista.<p>'); 
				conterror++;
			}
		}
	}
	
	if(conterror > 0) return false;		
	return true;
}
function limpiarCIIU(){		
	if( CODIGOCIUU != 0) return this;	
	$("#autocompleteCIUU").val('');
	$("#codigoCIUU").val('');
	$("#labelcodigoCIUU").text('');
	CODIGOCIUU = 0;
	$("#hiddencodigoCIUU").val('0');	
	return this;
}
function cerrarVentana(){	
	$("#dialogExpuesto").dialog( "close" );
	return false;
}
function volverAtras(){	
	window.location.assign('/SeleccionarEstablecimiento');
	return true;
}
function LimpiarCampos_Telefono(){
	$('#ATerrores').empty();
	$('#ATtipoTelefono').val('');
	$('#ATarea').val('');
	$('#ATnumero').val('');
	$('#ATinterno').val('');
	$("#ATprincipal").prop("checked", "");
	$('#ATobservaciones').val('');
}
function Edit_Telefono(id){
	var jsonrow = "["+arrayTelefonos+"]";
	var telefonosList = JSON.parse(jsonrow);
	var telefonos = telefonosList[id];
	opcTelefono = 'Edit';	
	IDopcTelefono = id;
	LimpiarCampos_Telefono();
	$('#ATerrores').empty();
	$('#ATtipoTelefono').val(telefonos.tipoTelefono);
	$('#ATarea').val(telefonos.area);
	$('#ATnumero').val(telefonos.numero);
	$('#ATinterno').val(telefonos.interno);
	if(telefonos.principal == 'Y')		$("#ATprincipal").prop("checked", "checked");
	else								$("#ATprincipal").prop("checked", "");
	$('#ATobservaciones').val(telefonos.observaciones);
	$("#dialogAltaTelefono").dialog( "open" );
	event.preventDefault();
}
function Update_ArrayTelefonos(){
	if( !ValidarTelefono() ) return false;
	var jsonrowCurrent = "["+arrayTelefonos+"]";
	var telefono = JSON.parse(jsonrowCurrent);
	var jsonRow = '';	
	var ArrTelefono = Get_Values();
	jsonRow += '{ "e":"'+IDopcTelefono+'",';
	jsonRow += ' "ID":"'+telefono[IDopcTelefono].ID+'",';
	jsonRow += ' "TIPORESP":"H",';
	jsonRow += ' "tipoTelefono":"'+ArrTelefono.tipoTelefono+'",';
	jsonRow += ' "tipoTelDescrip":"'+ArrTelefono.tipoTelDescrip+'",';
	jsonRow += ' "area":"'+ArrTelefono.area+'",';
	jsonRow += ' "numero":"'+ArrTelefono.numero+'",';
	jsonRow += ' "interno":"'+ArrTelefono.interno+'",';
	jsonRow += ' "principal":"'+ArrTelefono.principal+'",';
	jsonRow += ' "observaciones":"'+ArrTelefono.observaciones+'"}';	
	arrayTelefonos[IDopcTelefono] = jsonRow;
	Print_Telefonos( GetJson_Telefonos() );
	AsignaAsteriscoRespHYS();
	return true;
}
function Insert_ArrayTelefonos(tipoTelefono, tipoTelDescrip, area, numero, interno, principal, observaciones){
	var jsonRow = '';	
	var arrayLength = arrayTelefonos.length;
	LimpiarCampos_Telefono();
	jsonRow += '{ "e":"'+arrayLength+'",';
	jsonRow += ' "ID":"0",';
	jsonRow += ' "TIPORESP":"H",';
	jsonRow += ' "tipoTelefono":"'+tipoTelefono+'",';
	jsonRow += ' "tipoTelDescrip":"'+tipoTelDescrip+'",';
	jsonRow += ' "area":"'+area+'",';
	jsonRow += ' "numero":"'+numero+'",';
	jsonRow += ' "interno":"'+interno+'",';
	jsonRow += ' "principal":"'+principal+'",';
	jsonRow += ' "observaciones":"'+observaciones+'"}';	
	arrayTelefonos.push(jsonRow);
	return GetJson_Telefonos();
}
function GetJson_Telefonos(){
	var arrayLength = arrayTelefonos.length;
	var jsonRowGroup = '';	
	if(arrayLength == 0) return '';
	for (var i = 0; i < arrayLength; i++) {		
		var row = arrayTelefonos[i];		
		if(i > 0) jsonRowGroup += ',';
		jsonRowGroup += row;		
	}
	jsonTelefonos = '[ '+jsonRowGroup+' ]';
	return jsonTelefonos;
}
function Get_Values(){
	var tipoTelefono = document.getElementById("ATtipoTelefono").value;	
	var tipoTelDescrip = ComboValorTexto("ATtipoTelefono");
	var area = document.getElementById("ATarea").value;
	var numero = document.getElementById("ATnumero").value;
	var interno = document.getElementById("ATinterno").value;
	var principal = document.getElementById("ATprincipal").value;
	var observaciones = document.getElementById("ATobservaciones").value;
	var DataTelefono = new Array();
	DataTelefono["tipoTelefono"] = tipoTelefono;
	DataTelefono["tipoTelDescrip"] = tipoTelDescrip;
	DataTelefono["area"] = area;
	DataTelefono["numero"] = numero;
	DataTelefono["interno"] = interno;
	DataTelefono["principal"] = principal;
	DataTelefono["observaciones"] = observaciones;	
	return DataTelefono;
}
function refreshButtonAddPhone(){
	if(arrayTelefonos.length == 3)		
		JQOcultarElemento("#AgregaTelefono");
	else		
		JQMostrarElemento("#AgregaTelefono");
}
function Insertar_Telefono(){
	if( !ValidarTelefono() ) return false;
	var ArrTelefono = Get_Values();
	var jsTelefonos = Insert_ArrayTelefonos(ArrTelefono.tipoTelefono, 
											ArrTelefono.tipoTelDescrip, 
											ArrTelefono.area, 
											ArrTelefono.numero, 
											ArrTelefono.interno, 
											ArrTelefono.principal, 
											ArrTelefono.observaciones);
	Print_Telefonos(jsTelefonos);
	OcultarDiv('SinTelefonos');
	refreshButtonAddPhone();
	AsignaAsteriscoRespHYS();
	return true;
}
function Print_Telefonos(jsTelefonos){
	if( arrayTelefonos == '' ) return "<b style='color:red; font: Arial 14;' >No se encontraron Telefonos.<b>";
	if( arrayTelefonos.length == 0 ) return "<b style='color:red; font: Arial 14;' >No se encontraron Telefonos.<b>";
	var table = "<table width='100%' class='GridTableCiiu'> ";
	table += "<tr> ";
	table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >Edit</a></td>";
	table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >Tipo de Telefono</a></td>";
	table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >Area</a></td>";
	table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >Numero</a></td>";
	table += "<td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >Interno</a></td>";
	table += "</tr>";
	var jsontelef = "["+arrayTelefonos+"]";		
	var telefonos = JSON.parse(jsontelef);
	for(i = 0; telefonos.length > i; i++){			
		table += "<tr class='gridFondoOnMouseOver gridRow1' >	";		
		table += "<td align='center' class='gridColAlignLeft gridText' ><div><input type='button' class='btnEditar' onclick='Edit_Telefono("+i+")' /></div></td>";
		table += "<td align='left' class='gridColAlignLeft gridText' ><div>"+telefonos[i].tipoTelDescrip+"</div></td>";
		table += "<td align='left' class='gridColAlignLeft gridText' ><div>"+telefonos[i].area+"</div></td>";
		table += "<td align='left' class='gridColAlignLeft gridText' ><div>"+telefonos[i].numero+"</div></td>";
		table += "<td align='left' class='gridColAlignLeft gridText' ><div>"+telefonos[i].interno+"</div></td>";
		table += "</tr>";		
	}
	table += "</table>";
	if(document.getElementById("listaTelefonos")){
		JQDivSetValue('#listaTelefonos', table);
		document.getElementById("listaTelefonos").style.display = 'block';
	}
}
function Print_TelefonosAjax(jsTelefonos){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";				
	var strparametros = "funcion=GetTempTableTelefonos";
	strparametros = strparametros+"&jsonTelefonos="+encodeURIComponent(jsTelefonos);	
	ProcesarDatos(pagefunciones, encodeURI(strparametros), 'listaTelefonos');	
	document.getElementById("listaTelefonos").style.display = 'block';
}
function LimpiarCtrlSelecEstablec(){
	/*limpio los controles de la seccion Seleccion Establecimiento*/	
	JQDivSetValue("#msjradioAdmin", false);
	JQDivSetValue("#msjradioComMino", false);
	JQDivSetValue("#msjradioObraConst", false);
	JQDivSetValue("#msjradioVehi", false);
}
function muestraMensaje(){
	var msj = 'El tipo de establecimiento seleccionado no presenta personal expuesto';
	var msjObraConst = 'Para completar la nómina de personal expuesto para el personal asignado al establecimiento, comunicarse al teléfono 4335-5100 Int. 5199';
	LimpiarCtrlSelecEstablec();
	/*Escribo msj en pantalla*/
	if(JQIsChecked("#radioAdmin") ){ $("#msjradioAdmin").text(msj); }
	if(JQIsChecked("#radioComMino") ){ $("#msjradioComMino").text(msj); }
	if(JQIsChecked("#radioObraConst") ){ $("#msjradioObraConst").text(msjObraConst); }	
	if(JQIsChecked("#radioVehi") ){ $("#msjradioVehi").text(msj); }
	if(JQIsChecked("#radioOtros") ){ 
		DesactivaTipoNomina( true, CSSsilver, true ); 
	}else{
		DesactivaTipoNomina( false, CSSsilver, true );	
		JQCheckedControl("#TipoNominaCE", false);
		JQCheckedControl("#TipoNominaSE", false);
	}
	if( !JQIsChecked("#radioOtros") && !JQIsChecked("#TipoNominaCE") ) 
		ExisteNominaEnProceso = 0;
	return true;
}
function SetTipoNominaSeleccionado(){
	if(JQIsChecked("#radioAdmin") ){ TipoEstabSeleccionado = $("#radioAdmin").val(); }
	if(JQIsChecked("#radioComMino") ){ TipoEstabSeleccionado = $("#radioComMino").val(); }
	if(JQIsChecked("#radioObraConst") ){ TipoEstabSeleccionado = $("#radioObraConst").val(); }
	if(JQIsChecked("#radioVehi") ){ TipoEstabSeleccionado = $("#radioVehi").val(); }
	TipoNominaSeleccExp  = '';
	if(JQIsChecked("#radioOtros") ){ 
		TipoEstabSeleccionado = $("#radioOtros").val(); 
		if(JQIsChecked("#TipoNominaCE") ){ TipoNominaSeleccExp = $("#TipoNominaCE").val();  } 
		if(JQIsChecked("#TipoNominaSE") ){ TipoNominaSeleccExp = $("#TipoNominaSE").val();  } 	
	}
}
function CancelRadioOtros(){
	$("#radioOtros").prop("checked", "checked");
	$("#TipoNominaCE").prop("checked", "checked");
	event.preventDefault();
	event.stopPropagation();
}
function ValidarNominaExistente(){
	SetTipoNominaSeleccionado();
	if(ExisteNominaEnProceso > 0 ){
		var mensaje = 'El establecimiento tiene cargado una nómina con personal expuesto, si cambia el tipo de nómina se perderán todos los datos. Está seguro de realizar dicho cambio?';		
		if( !MuestrNominaMensajes('Nomina Expuestos', '', mensaje, muestraMensaje, '', CancelRadioOtros) );		
		return true;						
	}else{
		muestraMensaje();		
	}	
	return true;
}
function DesactivaTipoNominaSeleccion(){	
	desbloquear = true; 
	styleColor = CSSfondoBco;	
	DesactivaTipoNomina(desbloquear, styleColor, true);		
}
function ConfigIniTipoNomina(){
	var checked = false;
	var styleColor = CSSsilver;
	/*
	DesactivaControl('codigoCIUU', checked, styleColor );		
	DesactivaControl('autocompleteCIUU', checked, styleColor );		
	DesactivaControl('idTexActividad', checked, styleColor );		
	JQInputSetValue('#codigoCIUU', '');
	JQInputSetValue('#autocompleteCIUU', '');
	JQInputSetValue('#idTexActividad', '');
	*/
	//-----------------------------------------------------
	var desbloquear = true; 
	styleColor = CSSfondoBco; 	
	if(TipoNominaSeleccExp == 'S'){		
		JQCheckedControl("#TipoNominaCE", true);
		DesactivaControl('codigoCIUU', true, styleColor );		
		DesactivaControl('autocompleteCIUU', true, styleColor );		
		DesactivaControl('idTexActividad', true, styleColor );
	}
	if(TipoNominaSeleccExp == 'N')		
		JQCheckedControl("#TipoNominaSE", true);
}
function DesactivaTipoNomina(checked, styleColor, actTipoNomina){
	FN_ObligAsteriscos('TipoEstOblig', false); 
	if(actTipoNomina){
		DesactivaControl('TipoNominaCE', checked, styleColor );
		DesactivaControl('TipoNominaSE', checked, styleColor );
	}
	if( JQIsChecked("#TipoNominaCE") ){ 
		checked = true;
		FN_ObligAsteriscos('TipoEstOblig', true); 
	}else{
		checked = false;
		styleColor = CSSsilver;
	}
	DesactivaControl('codigoCIUU', checked, styleColor );		
	DesactivaControl('autocompleteCIUU', checked, styleColor );		
	DesactivaControl('idTexActividad', checked, styleColor );		
	JQInputSetValue('#codigoCIUU', '');
	JQInputSetValue('#autocompleteCIUU', '');
	JQInputSetValue('#idTexActividad', '');
}
/****************** Validacion de datos **************************/
function ValidarContacto(){
	var msjErrores = '';
	var contErrores = 0;	
	if( JQInputGetValue('#ContactoNombre') == ''){//1
		contErrores++;
		msjErrores = msjErrores + "<dd>Nombre</dd>";
	}
	if( JQInputGetValue('#ContactoApellido') == ''){//2
		contErrores++;
		msjErrores = msjErrores + "<dd>Apellido</dd>";
	}	
	if( JQInputGetValue('#ContactoCodArea') == ''){//3
		contErrores++;
		msjErrores = msjErrores + "<dd>Codigo de Area</dd>";
	}
	if( JQInputGetValue('#ContactoTelefono') == ''){//4
		contErrores++;
		msjErrores = msjErrores + "<dd>Telefono</dd>";
	}
	if( ComboIDSeleccionado('ContactoTipoTelefono') == 0){//5
		contErrores++;
		msjErrores = msjErrores + "<dd>Tipo de Telefono</dd>";
	}	
	if( JQInputGetValue('#ContactoEMail') == ''){//6
		contErrores++;
		msjErrores = msjErrores + "<dd>E-Mail </dd>";
	}
	/*
		no validar este check...
	if( !JQIsChecked("#ContactoIgualaResp") ){//7
		contErrores++;
		//msjErrores = msjErrores + "<dd>Complete check Contacto Resp. de la empresa</dd>";
	}
	*/	
	if(contErrores == 0 || contErrores == 6)
		return '';
	else{
		// return "<dl><dt>Seccion: Contacto</dt>"+msjErrores+"</ul></dt></dl>";	
		return "<dl><dt>Seccion: Contacto</dt> Debe completar todos los campos de esta seccion </ul></dt></dl>";			
	}
}
function ValidarResponsableEmpresa(validarSinExpuesto){
	var msjErrores = '';
	var contErrores = 0;	
	if(ComboIDSeleccionado('TipoDocRespEmpresa') == 0){//1
		contErrores++;
		msjErrores = msjErrores + "<dd>Tipo de Documento</dd>";
	}
	if( JQInputGetValue('#RespEmpNumDoc') == ''){//2
		contErrores++;
		msjErrores = msjErrores + "<dd>Numero Documento</dd>";
	}
	if( ComboIDSeleccionado('RespEmpTiposexo') == 0){//3
		contErrores++;
		msjErrores = msjErrores + "<dd>Sexo</dd>";
	}
	if( JQInputGetValue('#RespEmpNombre') == ''){//4
		contErrores++;
		msjErrores = msjErrores + "<dd>Nombre</dd>";
	}
	if( JQInputGetValue('#RespEmpApellido') == ''){//5
		contErrores++;
		msjErrores = msjErrores + "<dd>Apellido</dd>";
	}
	if( JQInputGetValue('#RespEmpCodArea') == ''){//6
		contErrores++;
		msjErrores = msjErrores + "<dd>Codigo de Area</dd>";
	}
	if( JQInputGetValue('#RespEmpTelefono') == ''){//7
		contErrores++;
		msjErrores = msjErrores + "<dd>Telefono</dd>";
	}
	if(ComboIDSeleccionado('ResptipoTelefono') == 0){//8
		contErrores++;
		msjErrores = msjErrores + "<dd>Tipo de Telefono</dd>";
	}
	if( JQInputGetValue('#RespEmpEMail') == ''){//9
		contErrores++;
		msjErrores = msjErrores + "<dd>E-Mail</dd>";
	}
	if( validarSinExpuesto) {
		if(contErrores == 0) return 'C';
		if(contErrores == 9) return 'V';
		// Si la nomina es sin expuesto siempre debe completar esta seccion
		// return "<dl><dt>Seccion: Responsable Empresa</dt>"+msjErrores+"</ul></dt></dl>";
		return "<dl><dt>Seccion: Responsable de la Empresa</dt>  Debe completar todos los campos de esta seccion  </ul></dt></dl>";				
	}else{
		if(contErrores == 0 || contErrores == 9)
			return '';
		else
			//return "<dl><dt>Seccion: Responsable de la Empresa</dt>"+msjErrores+"</ul></dt></dl>";	
			return "<dl><dt>Seccion: Responsable de la Empresa</dt>  Debe completar todos los campos de esta seccion  </ul></dt></dl>";				
	}
	return '';
}
//----------------------------------------------------------------------
//----------------------------------------------------------------------
function AsignaValControlesRespContacto(){
	$("#ContactoNombre").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoApellido").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoCodArea").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoTelefono").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoTipoTelefono").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoEMail").change( function(){ AsignaAsteriscoRespContacto(); } );
	$("#ContactoIgualaResp").change( function(){ AsignaAsteriscoRespContacto(); } );	
}
function AsignaAsteriscoRespContacto(){	
	var contElem = 0;	
	FN_ObligAsteriscos('ContactoOblig', false);
	if( JQInputGetValue('#ContactoNombre') != ''){		contElem++;			}
	if( JQInputGetValue('#ContactoApellido') != ''){		contElem++;			}	
	if( JQInputGetValue('#ContactoCodArea') != ''){		contElem++;			}
	if( JQInputGetValue('#ContactoTelefono') != ''){		contElem++;	}
	if( ComboIDSeleccionado('ContactoTipoTelefono') != 0){		contElem++;	}	
	if( JQInputGetValue('#ContactoEMail') != ''){		contElem++;	}
	
	if(contElem > 0){		FN_ObligAsteriscos('ContactoOblig', true); }
}
function AsignaValControlesRespEmp(){
	$("#TipoDocRespEmpresa").change( function(){ AsignaAsteriscoRespEmp(); } );
	$("#RespEmpNumDoc").change( function(){ AsignaAsteriscoRespEmp(); } );
	$("#RespEmpTiposexo").change( function(){ AsignaAsteriscoRespEmp(); } );	
	$("#RespEmpNombre").change( function(){ AsignaAsteriscoRespEmp(); } );
	
	$("#RespEmpApellido").change( function(){ AsignaAsteriscoRespEmp(); } );
	$("#RespEmpCodArea").change( function(){ AsignaAsteriscoRespEmp(); } );
	$("#RespEmpTelefono").change( function(){ AsignaAsteriscoRespEmp(); } );
	$("#ResptipoTelefono").change( function(){ AsignaAsteriscoRespEmp(); } );
	
	$("#RespEmpEMail").change( function(){ AsignaAsteriscoRespEmp(); } );
}
function AsignaAsteriscoRespEmp(){	
	var contElem = 0;	
	FN_ObligAsteriscos('RespEmpOblig', false);
	if(ComboIDSeleccionado('TipoDocRespEmpresa') != 0){	contElem++;	}	
	if( JQInputGetValue('#RespEmpNumDoc') != ''){		contElem++;	}
	if( ComboIDSeleccionado('RespEmpTiposexo') != 0){	contElem++;	}	
	if( JQInputGetValue('#RespEmpNombre') != ''){		contElem++;	}	
	if( JQInputGetValue('#RespEmpApellido') != ''){		contElem++;	}	
	if( JQInputGetValue('#RespEmpCodArea') != ''){		contElem++;	}	
	if( JQInputGetValue('#RespEmpTelefono') != ''){		contElem++;	}	
	if(ComboIDSeleccionado('ResptipoTelefono') != 0){	contElem++;	}	
	if( JQInputGetValue('#RespEmpEMail') != ''){		contElem++;	}
	if(contElem > 0){		FN_ObligAsteriscos('RespEmpOblig', true); }
}
function AsignaValControlesRespHYS(){
	$("#tipoDocRespHYS").change( function(){ AsignaAsteriscoRespHYS(); } );
	$("#RespNumDoc").change( function(){ AsignaAsteriscoRespHYS(); } );
	$("#RespTiposexo").change( function(){ AsignaAsteriscoRespHYS(); } );
	$("#RespNombre").change( function(){ AsignaAsteriscoRespHYS(); } );
	$("#RespApellido").change( function(){ AsignaAsteriscoRespHYS(); } );
	/*
	$("#ResplistaCargos").change( 
		function(){ 
			AsignaAsteriscoRespHYS();  
			makeUppercase("ResplistaCargos"); 
	} );
	*/
	$("#RespEMail").change( function(){ AsignaAsteriscoRespHYS(); } );	
}
function AsignaAsteriscoRespHYS(){
	var contElem = 0;
	//RespHYSOblig
	FN_ObligAsteriscos('RespHYSOblig', false); 
	if(ComboIDSeleccionado('tipoDocRespHYS') != 0){		contElem++;			}
	if( JQInputGetValue('#RespNumDoc') != ''){		contElem++;			}
	if( ComboIDSeleccionado('RespTiposexo') != ''){		contElem++;			}
	if( JQInputGetValue('#RespNombre') != ''){		contElem++;			}	
	if( JQInputGetValue('#RespApellido') != ''){		contElem++;			}
	if( JQInputGetValue('#ResplistaCargos') != '' || CargoRespHYS != '0'){		contElem++;			}
	if( JQInputGetValue('#RespEMail') != ''){		contElem++;		}
	if( arrayTelefonos.length > 0){		contElem++;		}
	if(contElem > 0)
		FN_ObligAsteriscos('RespHYSOblig', true); 
}
//----------------------------------------------------------------------
//----------------------------------------------------------------------
function ValidarResponsableHYS(validarSinExpuesto){
	var msjErrores = '';
	var contErrores = 0;	
	var arrayLength = arrayTelefonos.length;
	if(ComboIDSeleccionado('tipoDocRespHYS') == 0){
		contErrores++;
		msjErrores = msjErrores + "<dd>Documento</dd>";
	}
	if( JQInputGetValue('#RespNumDoc') == ''){
		contErrores++;
		msjErrores = msjErrores + "<dd>Numero de Documento</dd>";
	}
	if( ComboIDSeleccionado('RespTiposexo') == ''){
		contErrores++;
		msjErrores = msjErrores + "<dd>Sexo</dd>";
	}
	if( JQInputGetValue('#RespNombre') == ''){
		contErrores++;
		msjErrores = msjErrores + "<dd>Nombre</dd>";
	}
	if( JQInputGetValue('#RespApellido') == ''){
		contErrores++;//5
		msjErrores = msjErrores + "<dd>Apellido</dd>";
	}
	if( JQInputGetValue('#ResplistaCargos') == '' || CargoRespHYS == '0'){
		contErrores++;
		msjErrores = msjErrores + "<dd>Cargo: Debe seleccionar uno de la lista, complete al menos 3 caracteres.</dd>";
	}
	if( JQInputGetValue('#RespEMail') == ''){
		contErrores++;
		msjErrores = msjErrores + "<dd>E-Mail</dd>";
	}
	if( arrayLength == 0){
		contErrores++;
		msjErrores = msjErrores + "<dd>Telefonos: Debe agregar al menos un Telefono</dd>";
	}
	if( validarSinExpuesto ) {		
		if(contErrores == 0) return 'C';
		if(contErrores == 8) return 'V';
		// Si la nomina es sin expuesto siempre debe completar esta seccion
		// return "<dl><dt>Seccion: Responsable HYS</dt>"+msjErrores+"</ul></dt></dl>";
		return "<dl><dt>Seccion: Responsable HYS</dt> Debe completar todos los campos de esta seccion </ul></dt></dl>";			
	}else{
		if(contErrores == 0 || contErrores == 8){
		
			if(contErrores == 0) return '';
			if(contErrores == 8) return 'V';
			return '';
		}	
		else{
			// return "<dl><dt>Seccion: Responsable HYS</dt>"+msjErrores+"</ul></dt></dl>";
			return "<dl><dt>Seccion: Responsable HYS</dt> Debe completar todos los campos de esta seccion </ul></dt></dl>";			
		}
	}
	return '';
}
function ValidarSeleccTipoEstab(){
	var msjErrores = '';
	var contErrores = 0;
	if( JQIsChecked("#radioOtros") ){
		if( JQIsChecked("#TipoNominaCE") ){
			if( JQInputGetValue("#codigoCIUU") == '' ){
				contErrores++;
				msjErrores = msjErrores + "<dd>Codigo CIUU: Seleccione uno de la lista</dd>";
			}
			if( JQInputGetValue("#autocompleteCIUU") == '' ){
				contErrores++;
				msjErrores = msjErrores + "<dd>Descripcion CIUU: Seleccione uno de la lista complete al menos 4 caracteres</dd>";
			}
			if( JQInputGetValue("#idTexActividad") == '' ){
				contErrores++;
				msjErrores = msjErrores + "<dd>Descripcion Actividad CIUU</dd>";
			}
			if( CODIGOCIUU == 0 && contErrores == 0){
				contErrores++;
				msjErrores = msjErrores + "<dd>Codigo CIUU: Seleccione uno de la lista</dd>";
			}			
		}else{
			if( !JQIsChecked("#TipoNominaSE") ){
				contErrores++;
				msjErrores = msjErrores + "<dd>Seleccione opcion Tipo nomina</dd>";
			}
		}
	}else{
		if( !JQIsChecked("#radioAdmin") )
			if( !JQIsChecked("#radioComMino") )
				if( !JQIsChecked("#radioObraConst") )
					if( !JQIsChecked("#radioVehi") ){
						contErrores++;
						msjErrores = msjErrores + "<dd>Seleccione opcion Tipo Establecimiento</dd>";
					}
	}
	if(msjErrores == '')
		return msjErrores;
	else{
		// return "<dt>Seccion: Seleccion Tipo Establecimiento</dt>"+msjErrores+"</ul></dt>";
		return "<dt>Seccion: Seleccion Tipo Establecimiento</dt> Debe completar todos los campos de esta seccion </ul></dt>";
	}
}
function ValidarNominaDatosGenerales(){
	var validarSinExpuesto = false;	
	if( !(JQIsChecked("#radioOtros") && JQIsChecked("#TipoNominaCE")) ) {
		validarSinExpuesto = true;
	}
	var msjError1 = ValidarSeleccTipoEstab();	
	var msjError2 = ValidarResponsableHYS(validarSinExpuesto);
	
	if( !validarSinExpuesto && msjError2 == 'V' ){ 
		msjError2 = 'Debe completar Responsable HyS.';
	}
	
	var msjError3 = ValidarResponsableEmpresa(validarSinExpuesto);
	var msjError4 = ValidarContacto();
	var msjError = msjError1;
	if(validarSinExpuesto){
		if(msjError2 == 'V' && msjError3 == 'V'){
			msjError += '<p>Para nóminas sin Expuestos: es obligatorio cargar el responsable HyS o el Responsable de la Empresa. complete todos los campos de al menos una de estas secciones';	
		}else{
			if(msjError2 != 'C' && msjError2 != 'V'){
				msjError += msjError2;				
			}
			if(msjError3 != 'C' && msjError3 != 'V'){
				msjError += msjError3;				
			}
		}			
	}else{
		msjError += msjError2 + msjError3;		
	}
	msjError += msjError4;
	if(msjError != ''){		
		msjError = "<dl style='color:red;' >"+msjError+"</dl>";
		JQDivSetValue('#dialogListaErrores', msjError);
		$("#dialogDatosIncomp").dialog("open");
		return false;			
	}
	return true;	
}
function redirectNominaPersonal_StepTwo(){				
	window.location.assign('/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php?funcion=CODIGOACTIVIDAD&CODIGOACTIVIDAD='+CODIGOCIUU);			 
	return true;
}
function redirectNominaPersonal_NuevadeAnterior(){			
	// document.getElementById('FormulariosNomina').submit();		
	window.location.assign('/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php?funcion=InsertNominaNuevadeAnterior&InsertNominaNuevadeAnterior=true&CODIGOACTIVIDAD='+CODIGOCIUU);			 
}
function redirectNominaPersonalExpuesto(){
	window.location.assign('/NominaPersonalExpuesto');
	return true;
}
/****************************************************************/
function boton_siguiente(){	
	// ValidarNominaExistente();
	boton_siguienteOK();
	return true;
}
function boton_siguienteOK(){	
	if( !BotonValidarForm() ) return false;
	if( Save_NominaPrimerosDatos() ){	
		showDialogNomina();
	} 
}
function Save_NominaPrimerosDatos(){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";				
	var strparametros = "funcion=SaveNominaPrimerosDatos";
	var jsonPrimerosDatos = GrabarPersonalExpuesto();
	strparametros = strparametros+"&jsonPrimerosDatos="+encodeURIComponent(jsonPrimerosDatos);	
	if( ProcesarDatosJSON(pagefunciones, encodeURI(strparametros), 'resultadoProceso') == false ){			
		document.getElementById("resultadoProceso").style.display = 'block';
		document.getElementById("resultadoProceso").style.color = 'red';		
		return false; 
	}else{
		document.getElementById("resultadoProceso").style.display = 'none';
		document.getElementById("resultadoProceso").style.color = 'black';
		if( TipoEstabSeleccionado != 'O' || TipoNominaSeleccExp != 'S'){
			ExisteNominaEnProceso = false;
		}
		return true;
	}	
}
function InsertNominaActualdeNominaAnterior(){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";				
	var strparametros = "funcion=InsertNominaActualdeNominaAnterior";
	var jsonPrimerosDatos = GrabarPersonalExpuesto();
	// CODIGOEWID es el id de la tabla establecimiento
	// empresaESTABLECI es el id de la tabla AFI.AES_ESTABLECIMIENTO
	// empresaESTABLECI
	strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(empresaESTABLECI);	
	strparametros = strparametros+"&cuitEmpresa="+encodeURIComponent(empresaCUIT);	
	strparametros = strparametros+"&usualta="+encodeURIComponent(empresaUSUARIO);	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), 'resultadoProceso');
	if( resultado == false &&  resultado != ''){	
		document.getElementById("resultadoProceso").style.display = 'block';
		document.getElementById("resultadoProceso").style.color = 'red';
		return false; 
	}else{
		document.getElementById("resultadoProceso").style.display = 'none';
		document.getElementById("resultadoProceso").style.color = 'black';
		if( resultado != ''){
			var mensaje = 'Personal Expuesto No importado';
			MuestrNominaMensajes('Motivo Personal No Importado', '', resultado, 
				function(){ redirectNominaPersonalExpuesto(); return true; } , 
				'', 
				function(){return false;} );
			/*
			iniDialogMensajeValidJQ('#redirectNomina', redirectNominaPersonalExpuesto, resultado, 1);
			MuestraMsjNoImportado(resultado);
			*/
		}
		else{	
			redirectNominaPersonalExpuesto();			
		}
		return true;
	}	
}
function MuestraMsjNoImportado(resultado){
	/*
	document.getElementById("motivoNoImporado").innerHTML = resultado;
	$("#redirectNomina").dialog( "open" );
	*/
	//(titulo, encabezado, mensaje, funcion, parametro, funcionCancel){			
	MuestrNominaMensajes('Motivo Personal No Importado', '', resultado, 
		function(){ window.location.assign('/NominaPersonalExpuesto'); return true; } , 
		'', 
		function(){return false;} );
	//window.location.assign('/NominaPersonalExpuesto');			 	
}
function GrabarPersonalExpuesto(){
	var resultado = '';
	var jsonValue = '';
// HYS.HEW_ESTABLECIMIENTOWEB
/* 
	EW_TIPOESTAB = Tipo de establecimiento A: Administrativo - CM: Comercio Minorista - OC: Obras en Construcción - V: Vehículo - O: Otros	
	EW_TipoNomina = S: CON EXPUESTO - N: SIN EXPUESTO
	EW_ESTADO = NULL: no generada - C: Cargada - A: Aprobada - R: Rechazada
*/
	var TipoEstablecimiento = JQradioButtonSelect("TipoEstablecimiento");
	var TipoNomina = JQradioButtonSelect("TipoNomina");
	var idTexActividad = JQInputGetValue("#idTexActividad");
	if(TipoEstablecimiento == undefined) TipoEstablecimiento = '';
	if(TipoNomina == undefined) TipoNomina = '';
	jsonValue += ' "USUARIO":"'+empresaUSUARIO+'", ';
	jsonValue += ' "CUIT":"'+empresaCUIT+'", ';
	jsonValue += ' "ESTABLECI":"'+empresaESTABLECI+'", ';
	jsonValue += ' "TipoEstablecimiento":"'+TipoEstablecimiento+'", ';
	jsonValue += ' "TipoNomina":"'+TipoNomina+'", ';
	jsonValue += ' "CODIGOCIUU":"'+CODIGOCIUU+'", ';	
	jsonValue += ' "idTexActividad":"'+encodeURIComponent(idTexActividad)+'", ';	
	jsonValue += ' "CODIGOEWID":"'+encodeURIComponent(CODIGOEWID)+'" ';	
	resultado += ' "Establecimiento": { '+jsonValue+' } ';	
	/****************** PANEL RESPONSABLE HYS *********************/
	var tipoDocRespHYS = JQInputGetValue("#tipoDocRespHYS");		
	var RespNumDoc = JQInputGetValue("#RespNumDoc");	
	var RespTiposexo = JQInputGetValue("#RespTiposexo");	
	var RespNombre = JQInputGetValue("#RespNombre");	
	var RespApellido = JQInputGetValue("#RespApellido");	
	var ResplistaCargos = JQInputGetValue("#ResplistaCargos");	
	var RespEMail = JQInputGetValue("#RespEMail");
	var RespCargo = CargoRespHYS;
	//CargoRespHYS	
	var checksum = RespNumDoc+RespNombre+RespApellido+ResplistaCargos; 
	if( checksum != '' ){
		jsonValue = ' "tipoDocRespHYS" : "'+tipoDocRespHYS+'", ';
		jsonValue += ' "RespNumDoc" : "'+RespNumDoc+'", ';
		jsonValue += ' "RespTiposexo" : "'+RespTiposexo+'", ';
		jsonValue += ' "RespNombre" : "'+RespNombre+'", ';
		jsonValue += ' "RespApellido" : "'+RespApellido+'", ';
		jsonValue += ' "ResplistaCargos" : "'+ResplistaCargos+'", ';
		jsonValue += ' "RespCargo" : "'+RespCargo+'", ';
		jsonValue += ' "RespEMail" : "'+RespEMail+'", ';
		var arrayLength = arrayTelefonos.length;
		var jsonRowGroup = '';	
		for (var i = 0; i < arrayLength; i++) {		
			var row = arrayTelefonos[i];		
			if(i > 0) jsonRowGroup += ',';
			jsonRowGroup += row;		
		}
		jsonValue += ' "Telefonos": [ '+jsonRowGroup+' ] ';
		if( resultado != '' ) resultado += ','
		resultado += ' "ResponsableHYS": { '+jsonValue+' } ';	
	}
	/****************** PANEL RESPONSABLE DE LA EMPRESA *********************/
	var TipoDocRespEmpresa = JQInputGetValue("#TipoDocRespEmpresa");	
	var RespEmpNumDoc= JQInputGetValue("#RespEmpNumDoc");	
	var RespEmpTiposexo= JQInputGetValue("#RespEmpTiposexo");	
	var RespEmpNombre= JQInputGetValue("#RespEmpNombre");	
	var RespEmpApellido= JQInputGetValue("#RespEmpApellido");	
	var RespEmpCodArea= JQInputGetValue("#RespEmpCodArea");	
	var RespEmpTelefono= JQInputGetValue("#RespEmpTelefono");	
	var ResptipoTelefono= JQInputGetValue("#ResptipoTelefono");	
	var RespEmpEMail= JQInputGetValue("#RespEmpEMail");	
	checksum = RespEmpNombre+RespEmpApellido+RespEmpTelefono+RespEmpEMail; 
	if( checksum != '' ){		
		jsonValue = '  "TipoDocRespEmpresa" : "'+TipoDocRespEmpresa+'", ';
		jsonValue += ' "RespEmpNumDoc" : "'+RespEmpNumDoc+'", ';
		jsonValue += ' "RespEmpTiposexo" : "'+RespEmpTiposexo+'", ';
		jsonValue += ' "RespEmpNombre" : "'+unescape(RespEmpNombre)+'", ';
		jsonValue += ' "RespEmpApellido" : "'+unescape(RespEmpApellido)+'", ';
		jsonValue += ' "RespEmpCodArea" : "'+RespEmpCodArea+'", ';
		jsonValue += ' "RespEmpTelefono" : "'+RespEmpTelefono+'", ';
		jsonValue += ' "ResptipoTelefono" : "'+ResptipoTelefono+'", ';	
		jsonValue += ' "RespEmpEMail" : "'+RespEmpEMail+'"  ';	
		if( resultado != '' ) resultado += ','
		resultado += ' "ResponsableEmpresa": { '+jsonValue+' } ';	
	}
	/****************** PANEL CONTACTO *********************/
	var ContactoNombre = JQInputGetValue("#ContactoNombre");	
	var ContactoApellido = JQInputGetValue("#ContactoApellido");	
	var ContactoCodArea = JQInputGetValue("#ContactoCodArea");	
	var ContactoTelefono = JQInputGetValue("#ContactoTelefono");	
	var ContactoTipoTelefono = JQInputGetValue("#ContactoTipoTelefono");	
	var ContactoEMail = JQInputGetValue("#ContactoEMail");	
	var ContactoIgualaResp = 'N';
	if( JQIsChecked("#ContactoIgualaResp") ) ContactoIgualaResp = 'Y';
	var checksum = ContactoNombre+ContactoApellido+ContactoCodArea+ContactoTelefono+ContactoEMail; 
	if( checksum != '' ){		
		jsonValue = ' "ContactoNombre" : "'+ContactoNombre+'", ';
		jsonValue += ' "ContactoApellido" : "'+ContactoApellido+'", ';
		jsonValue += ' "ContactoCodArea" : "'+ContactoCodArea+'", ';
		jsonValue += ' "ContactoTelefono" : "'+ContactoTelefono+'", ';
		jsonValue += ' "ContactoTipoTelefono" : "'+ContactoTipoTelefono+'", ';
		jsonValue += ' "ContactoEMail" : "'+ContactoEMail+'", ';
		jsonValue += ' "ContactoIgualaResp" : "'+ContactoIgualaResp+'" ';	
		if( resultado != '' ) resultado += ','	
		resultado += ' "Contacto" : { '+jsonValue+' } ';
	}
	return "[ { "+resultado+" } ]";
}
function ConertJsonToArray(arrayValues){
	arrayTelefonos.length = 0;
	var resStr = '';	
	if(arrayValues != ''){
		resStr = arrayValues.replace(/},/g, "};");			
		arrayTelefonos = resStr.split(';');	
	}
	return true;
}
