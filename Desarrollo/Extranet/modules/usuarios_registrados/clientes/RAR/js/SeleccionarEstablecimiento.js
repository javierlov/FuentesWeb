//SeleccionarEstablecimiento
var columnOB = '';
var arrayParametrosNuevaPres = [];
$(document).ready(inicializar);

function inicializar(){
	arrayParametrosNuevaPres.length = 0;
	InicalizaDialogoRechazo();
	InicalizaDialogoYaPresentada();
	InicalizaDialogoMensaje('', '', '');

	$("#btnBuscar").button();
	$("#btnBuscar").click(BuscarGrillaEstab);

	$("#idEstablecimiento").keypress(buscarGrilla);
	$("#EstablecimientoNombre").keypress(buscarGrilla);
	$("#calle").keypress(buscarGrilla);
	$("#CPostal").keypress(buscarGrilla);
	$("#Localidad").keypress(buscarGrilla);
	$("#Provincia").keypress(buscarGrilla);

	BuscarGrillaEstab();
}

function InicalizaDialogoYaPresentada(){

	$('#dialogYaPresentada').dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{
				// class: "idbtnAceptarYP", //esto lo declaro abajo por que IE 9 No lo toma
				id: "idbtnAceptarYP",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					return true;
				}
			}
		]
	});

	JQAsignaClaseCSS("#idbtnAceptarYP", "btnAceptar");

}

function InicalizaDialogoMensaje(funcion, parametro, funcionCancel){
	var botones = [
			{
				id:"IDM_btnAceptar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					if(funcion != '') funcion(parametro);
					return true;
				}
			},
			{
				id: "IDM_btnCancelar",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					if(funcionCancel != '') funcionCancel();
					return false;
				}
			}
		];
		$( "#dialogSeleccionMensajes" ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: botones
	});

	if ($('#IDM_btnAceptar').length)  JQAsignaClaseCSS("#IDM_btnAceptar", "btnAceptar");
	if ($('#IDM_btnCancelar').length)  JQAsignaClaseCSS("#IDM_btnCancelar", "btnCancelar2");
}

function InicalizaDialogoRechazo(){

	$('#dialogRechazado').dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: [
			{
				// class: "btnAceptar", //esto lo declaro abajo por que IE 9 No lo toma
				id: "idbtnAceptarR",
				text: "",
				click: function() {
					$( this ).dialog( "close" );
					return true;
				}
			}
		]
	});

	JQAsignaClaseCSS("#idbtnAceptarR", "btnAceptar");

}

function buscarGrilla(event){
  if ( event.which == 13 ) {
	 JQMostrarElemento('#divProcesando');
	 BuscarGrillaEstab();
	 JQOcultarElemento('#divProcesando');
	 this.focus();
     event.preventDefault();
  }
}

function NominaYaPresentadaMsj(){
	$("#dialogYaPresentada").dialog("open");
	return true;
}

function mostrarMensajeSeleccion(titulo, encabezado, mensaje){
	JQDivSetValue('#ui-id-2', titulo);

	JQDivSetValue('#YaPresentadaTitulo', encabezado);
	JQDivSetValue('#motivoYaPresentada', mensaje);

	$("#dialogYaPresentada").dialog("open");
	return true;
}

function AsignaAccion_NominaActual( boton, idNomina,  status){
	// status = 'ACTUAL'
	// status = 'ANTERIOR'

	if(boton == 'RECHAZADA_LINK_'){
		NominaActualEstatus( boton, idNomina );
	}

	if( boton == 'KEYBTNPDF'){
		if(idNomina == 0)
			imprimeListadoExpuestosPDF(idNomina, true);
		else
			imprimeListadoAnnoAnterior('key', idNomina, status, 0, 0, 'SI');
	}

}

function NominaActualEstatus( status, idNomina ){

	if(status == 'RECHAZADA_LINK_'){

		var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";
		var strparametros = "funcion=NominaWEBRechazoMotivo";

		strparametros = strparametros+"&idNomina="+encodeURIComponent(idNomina);

		document.getElementById("msgError").style.display = 'block';
		var resultJson = ProcesarDatosJSON(pagefunciones, encodeURI(strparametros), 'msgError');

		var resultado = JSON.parse(resultJson);

		JQDivSetValue("#motivoRechazo", "<div style='text-align: center;'>"+unescape(resultado.MR_DESCRIPCION)+"</div>" );
		JQDivSetValue("#observacionRechazo", "<div style='text-align: center;'>"+unescape(resultado.EW_OBSERVACIONESRECHAZO)+"</div>"  );
		$("#dialogRechazado").dialog("open");
	}

	return true;
}

function MostrarMensaje( msj ){
	alert('Mostrar Mensaje '+msj);
	return true;
}

function RedirectFormularioNomina(){
	window.location.href = '/FormulariosNomina';
}

function BuscarGrillaEstab(){
	BuscarGrillaEstablecimientos(1);
}

function BuscarGrillaEstabOrderBy(column){
	columnOB = column;
	BuscarGrillaEstablecimientos(1);
}

function ValidaDatosBusqueda(){
	var errores = 0;
	var EstablecimientoNombre = document.getElementById("EstablecimientoNombre").value;
	var idEstablecimiento = document.getElementById("idEstablecimiento").value;
	$("#divlistaerrores").hide();
	$("#idGridSeleccionaEstablecimieto").hide();
	$("#listaerrores").empty();

	if( (!$.isNumeric(idEstablecimiento)) && (idEstablecimiento.length > 0) ){
		$("#listaerrores").append('<i>Colocar en el primer campo un valor numerico</i><p>');
		$("#listaerrores").focus();
		errores++;
	}

	if( ($.trim(EstablecimientoNombre) != '') && (EstablecimientoNombre.length < 3) ){
		$("#listaerrores").append('<i>Colocar en el segundo campo de texto  como mínimo 3 caracteres o la palabra completa</i><p>');
		$("#listaerrores").focus();
		errores++;
	}

	if(errores > 0){
		$("#divlistaerrores").show();
		return false;
	}

	$("#idGridSeleccionaEstablecimieto").show();
	return true;
}

function BuscarGrillaEstablecimientos(pagina){
//IDdivResultado, UsuarioSolicitud, idPadre, idItem, sistema, pagina
	if(!ValidaDatosBusqueda()) return false;

	var IDdivResultado = 'idGridSeleccionaEstablecimieto';
	var contrato = contratoSession; //ejemplo 143138
	var idEstablecimiento = document.getElementById("idEstablecimiento").value;
	var EstablecimientoNombre = document.getElementById("EstablecimientoNombre").value;
	var calle = document.getElementById("calle").value;
	var CPostal = document.getElementById("CPostal").value;
	var Localidad = document.getElementById("Localidad").value;
	var Provincia = document.getElementById("Provincia").value;

	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";
	var strparametros = "funcion=GrillaEstablecimientos";

	strparametros = strparametros+"&contrato="+encodeURIComponent(contrato);
	strparametros = strparametros+"&pagina="+encodeURIComponent(pagina);
	strparametros = strparametros+"&ob="+encodeURIComponent(columnOB);

	if(idEstablecimiento != '')
		strparametros = strparametros+"&idEstablecimiento="+encodeURIComponent(idEstablecimiento);

	if(EstablecimientoNombre != '')
		strparametros = strparametros+"&EstablecimientoNombre="+encodeURIComponent(EstablecimientoNombre);

	if(calle != '')
		strparametros = strparametros+"&calle="+encodeURIComponent(calle);

	if(CPostal != '')
		strparametros = strparametros+"&CPostal="+encodeURIComponent(CPostal);

	if(Localidad != '')
		strparametros = strparametros+"&Localidad="+encodeURIComponent(Localidad);

	if(Provincia != '')
		strparametros = strparametros+"&Provincia="+encodeURIComponent(Provincia);

	strparametros = strparametros+AgregarParametroHHMMSS();

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
	return true;
}

function redirectNuevaPresentacion(pageid, id, NROESTABLECI, ESTADONOMINA, CODIGOEWID, CODIGO ){
	  var titulo = 'Nomina Aprobada';
	  var encabezado = '';
	  var mensaje = 'Usted ya presentó una Nómina de expuestos en el año. Por favor comuníquese al teléfono 4335-5100 Int. 5199';

	if(ESTADONOMINA > 0){
	  mostrarMensajeSeleccion(titulo, encabezado, mensaje);
	  return true;
	}

	arrayParametrosNuevaPres = [pageid, id, NROESTABLECI, ESTADONOMINA, CODIGOEWID, CODIGO];

	if(showDialogResponsable == 'SI' && CODIGO == 'NOGENERADA'){
		UsarResponsabelDefault();
	}else{
		RedirectNuevaNomina();
	}
	return true;
}

function UsarResponsabelDefault(){
	var titulo = 'Responsable por Defecto';
	var encabezado = '';
	var mensaje = '¿Desea utilizar el Responsable cargadado en la ultima nomina?';

	InicalizaDialogoMensaje(RedirectSiDefault, '', RedirectNoDefault);
	mostrarSeleccionMensajes(titulo, encabezado, mensaje);
}

function RedirectSiDefault(){
	arrayParametrosNuevaPres[6] = 'SI';
	RedirectNuevaNomina();
}
function RedirectNoDefault(){
	arrayParametrosNuevaPres[6] = 'NO';
	RedirectNuevaNomina();
}

function mostrarSeleccionMensajes(titulo, encabezado, mensaje){
	JQDivSetValue('#ui-id-3', titulo);

	JQDivSetValue('#TituloSMensaje', encabezado);
	JQDivSetValue('#TextoSMensaje', mensaje);

	$("#dialogSeleccionMensajes").dialog("open");
	return true;
}

function RedirectNuevaNomina(){
	var PAGEID =  arrayParametrosNuevaPres[0];
	var ID =  arrayParametrosNuevaPres[1];
	var NROESTABLECI =  arrayParametrosNuevaPres[2];
	var ESTADONOMINA =  arrayParametrosNuevaPres[3];
	var CODIGOEWID =  arrayParametrosNuevaPres[4];
	var CODIGO =  arrayParametrosNuevaPres[5];
	var RESPONSABLEDEFAULT =  arrayParametrosNuevaPres[6];

	if(CODIGO != 'NOGENERADA'){
		RESPONSABLEDEFAULT = 'NO';
	}
	window.location.assign('/modules/usuarios_registrados/clientes/RAR/redirect.php?pageid=' + PAGEID + '&ID=' + ID + '&NROESTABLECI=' + NROESTABLECI + '&CODIGOEWID=' + CODIGOEWID + '&CODIGO=' + CODIGO + '&RESPONSABLEDEFAULT=' + RESPONSABLEDEFAULT);
}