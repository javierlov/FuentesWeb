var limpTicket = true;

function InicializaformTicket() {
	//console.log('InicializaformTicket');
	BuscarPedidos(1);
	limpTicket = true;
	$("#numeroTicket").change(limpiarGrilla);
	$("#fechaDesde").change(limpiarGrilla);
	$("#fechaHasta").change(limpiarGrilla);
	$("#textoLibre").change(limpiarGrilla);
	$("#TipoPedido").change(limpiarGrilla);
	$("#DetallePedido").change(limpiarGrilla);

	$("#numeroTicket").keypress(function(e) {
		if (e.which == 13) {
			limpTicket = false;
			BuscarPedidosPage();
		}
	});

	/*
	 if(recordCount == 1)
	 window.location.href = "/modules/gestion_sistemas/index.php"+"?sistema=1&MNU=3&ticket_detail=yes&all_tickets=no&pending_tickets=no&back_button=yes&close_button=yes&id=49805";
	 */
}

//------------ BEGIN LINEA TIEMPO -------------------------------------
function verLineaTiempo(sistema, mnu, nro_ticket) {
	var idReferencia = document.getElementById('idticket').value;
	/*
	 window.location.href = '/modules/gestion_sistemas/index.php?LineaTiempo=yes&idReferencia='+idReferencia;
	 */
	var parametros = new Array();
	parametros['LineaTiempo'] = 'yes';
	parametros['idReferencia'] = idReferencia;
	parametros['nro_ticket'] = nro_ticket;
	parametros['MNU'] = mnu;

	urlpag = '/modules/gestion_sistemas/index.php?sistema=' + sistema;
	SendPost(urlpag, parametros);
}

//------------ END LINEA TIEMPO -------------------------------------
function BuscarPedidosPage() {
	var paginaactual = 1;

	if (document.getElementById('paginaactual'))
		paginaactual = document.getElementById('paginaactual').value;

	BuscarPedidos(paginaactual);

}

function ValidaSolicitud() {
	limpiarGrilla();
	ValidarFormTicketPermiso();
}

function limpiarGrilla() {
	if (document.getElementById('grillaPedidosActuales'))
		document.getElementById('grillaPedidosActuales').style.display = 'none';

	if (document.getElementById('grillaColaboradores')) {
		if (limpTicket)
			document.getElementById('grillaColaboradores').style.display = 'none';
		else
			limpTicket = true;
	}
}

function LimpiaPermisoUsuarios() {
	$("#numeroTicket").val('');
	$("#fechaDesde").val('');
	$("#fechaHasta").val('');
	$("#textoLibre").val('');

	ResetCombo('TipoPedido', false);
	ResetCombo('DetallePedido', true);
	document.getElementById('numeroTicket').focus();

	limpiarGrilla();
}

function ResetCombo(id, limpiar) {
	if (document.getElementById(id)) {
		document.getElementById(id).value = -1;
		if (limpiar)
			document.getElementById(id).innerHTML = "<SELECT><option value='-1' >- SELECCIONAR -</option></SELECT>";
	}
}

function DetallePedidoChange() {
	LimpiarGrid();
	CambioDetallePedido();
}

function LimpiarGrid() {
	if (document.getElementById('grillaColaboradores')) {
		document.getElementById('grillaColaboradores').style.display = 'none';
	}
}

function ActivarGif() {
	if (document.getElementById('imgProcesando'))
		document.getElementById('imgProcesando').style.display = 'block';
}

function DesactivarGif() {
	if (document.getElementById('imgProcesando'))
		document.getElementById('imgProcesando').style.display = 'none';
}

function GetValueControl(idcontrol) {
	var valorcontrol = '';
	if (document.getElementById(idcontrol))
		valorcontrol = document.getElementById(idcontrol).value;

	return valorcontrol;
}

//----------------------------
function ActualizaPanelGrilla() {
	var IdDivPanel = '';
	if (document.getElementById('grillaColaboradores')) {
		document.getElementById('grillaColaboradores').style.display = 'block';
		IdDivPanel = 'grillaColaboradores';
	}

	if (document.getElementById('grillaPedidosActuales')) {
		document.getElementById('grillaPedidosActuales').style.display = 'block';
		IdDivPanel = 'grillaPedidosActuales';
	}
	return IdDivPanel;
}

//----------------------------
function BuscaPermisoUsuarios(pagina) {
	ActivarGif();
	document.getElementById('PaginaActual').value = pagina;
	
	if(document.getElementById('DivAreaMensajes')){
		document.getElementById('DivAreaMensajes').innerHTML = '';
	}

	var IdDivPanel = ActualizaPanelGrilla();

	var ArraySeleccion = ArraysUsuariosPermisos;
	BuscarDatosUsuarioGrid(IdDivPanel, UsuarioNombre, sistema, motivoid, idsolicitud, ArraySeleccion, pagina);
	/*
	 $('#DetallePedido').change(DetallePedidoChange);
	 */
	DesactivarGif();
	return true;
}

//---------------------------- ----------------------------
function redirectxx(rediret) {
	if (rediret != '') {
		window.location.href = rediret;
		return true;
	}

	window.location.href = 'index.php?sistema=1&MNU=3&ticket_detail=yes&all_tickets=no&pending_tickets=no&back_button=yes&close_button=yes&id=70222';

}

function BuscarPedidos(pagina) {
	BuscarPedidoDetalle(pagina);
}

function ExisteSoloUno() {

	if (document.getElementById('66286')) {
		var urlredireccion = document.getElementById('66286').innerHTML;
		document.location.href = urlredireccion;
		//redirectxx(urlredireccion);
	}

}

function BuscarPedidoDetalle(pagina) {

	ActivarGif();
	var IdDivPanel = ActualizaPanelGrilla();

	document.getElementById('PaginaActual').value = pagina;
	document.getElementById('DivAreaMensajes').innerHTML = '';

	ss_notas = GetValueControl('textoLibre');
	numeroTicket = GetValueControl('numeroTicket');

	fechaDesde = GetValueControl('fechaDesde');
	fechaHasta = GetValueControl('fechaHasta');

	TipoPedido = GetValueControl('TipoPedido');
	DetallePedido = GetValueControl('DetallePedido');
	MNUselect = MNU;

	var urlredireccion = BuscarGrillaPedidos(IdDivPanel, all_tickets, pending_tickets, pending_moreinfo_tickets, pending_auth_tickets, numeroTicket, fechaDesde, fechaHasta, ss_notas, PlanAccion, TipoPedido, DetallePedido, employees, sistema, back_button, close_button, pagina, MNUselect);

	DesactivarGif();
	return true;
}

//---------------------------- ----------------------------
function LoadGrillaPermisos(valor) {
	//CheckOption(valor)

	if (recordCount > 0) {
		CheckOption(valor)
	} else {
		if (document.getElementById('grillaColaboradores'))
			document.getElementById('grillaColaboradores').style.display = 'none';

		if (document.getElementById('originalGrid'))
			document.getElementById('originalGrid').style.display = 'none';

		if (document.getElementById('btnGuardar'))
			document.getElementById('btnGuardar').style.display = 'none';

		if (document.getElementById('btnCancelar'))
			document.getElementById('btnCancelar').innerHTML = 'Volver';

		if (document.getElementById('DivAreaMensajes')) {
			document.getElementById('DivAreaMensajes').innerHTML = '<div style="text-align:center"><p><b style="font: italic normal 16px Neo Sans; color:Red;" >Los colaboradores fueron autorizados por un usuario con mayor privilegio.</b></div><p>';
			document.getElementById('DivAreaMensajes').style.display = 'block';
		}
	}

}

//------------------------ ------------------------------------
function NuevosPermisos() {
	if (!ValidarFormPermisos())
		return false;

	var motivoid = document.getElementById('DetallePedido').value;

	var idpadre = ReturnValueCombo('TipoPedido');
	var idmotivo = ReturnValueCombo('DetallePedido');

	var parametros = new Array();
	parametros['Permisos'] = 'yes';
	parametros['motivoid'] = motivoid;
	parametros['idpadre'] = idpadre;
	parametros['idmotivo'] = idmotivo;
	parametros['MNU'] = 6;
	parametros['check'] = AgregarParametroHHMMSS();

	urlpag = '/modules/gestion_sistemas/index.php';
	SendPost(urlpag, parametros);

	return true;
}

function ReturnTextCombo(idcombo) {
	/*Retorna el text del item seleccionado del combo*/
	if (!document.getElementById(idcombo))
		return '';
	var posicion = document.getElementById(idcombo).options.selectedIndex;
	return document.getElementById(idcombo).options[posicion].text
}

function ReturnValueCombo(idcombo) {
	/*Retorna el value del item seleccionado del combo*/
	if (!document.getElementById(idcombo))
		return -1;

	return document.getElementById(idcombo).value;
}

function ValidarFormPermisos() {
	document.getElementById('DivAreaMensajes').style.display = 'none';
	document.getElementById('grillaColaboradoresPermisos').style.display = 'none';

	if (document.getElementById('TipoPedido').value == -1) {
		document.getElementById('TipoPedido').focus();
		document.getElementById('DivAreaMensajes').style.display = 'block';
		document.getElementById('DivAreaMensajes').innerHTML = 'Debe seleccionar el tipo de pedido.';
		return false;
	}

	if (document.getElementById('DetallePedido').value == -1) {
		document.getElementById('DetallePedido').focus();
		document.getElementById('DivAreaMensajes').style.display = 'block';
		document.getElementById('DivAreaMensajes').innerHTML = 'Debe seleccionar el Detalle del pedido.';
		return false;
	}
	return true;
}

//---------------------------------------
function SendPost(urlpage, params) {
	var FormuPost = document.createElement("form");
	FormuPost.method = "post";
	FormuPost.action = urlpage;

	for (var namek in params) {
		var InputPostValue = document.createElement("input");
		InputPostValue.setAttribute("name", namek);

		var valorencode = params[namek];

		InputPostValue.setAttribute("value", valorencode);
		FormuPost.appendChild(InputPostValue);
	}
	document.body.appendChild(FormuPost);
	FormuPost.submit();
	document.body.removeChild(FormuPost);
}

//---------------------------------------
function ValidarFormTicketPermiso() {
	if (!ValidarFormTicket())
		return false;
	return true;
}



//--------------------------------------------------------
function CargarPaginaHome(sistema) {
	var parametros = {
		"sistema" : sistema,
		"MNU" : "1"
	};
	CargarPagina("home.php", parametros);
}

function CargarPaginaRealizarPedido(sistema) {
	var parametros = {
		"sistema" : sistema,
		"MNU" : "2",
		"newticket" : "yes"
	};
	CargarPagina("ticket_new.php", parametros);
}

function CargarPagina(Pagina, parametros) {
	$.ajax({
		data : parametros,
		url : Pagina,
		type : 'post',
		beforeSend : function() {
			$("#resultado").html("Procesando, espere por favor...");
		},
		success : function(response) {
			$("#resultado").html(response);
		}
	});
}

/*--------------------------------------------------------------------*/
function BuscaColaboradores(pagina) {
	ActivarGif();
	//if(!ValidarFormTicket())	return false;
	document.getElementById('paginaactual').value = pagina;

	//document.getElementById('btnCancelar').disabled = false;
	document.getElementById('DivAreaMensajes').innerHTML = '';

	document.getElementById('grillaColaboradoresPermisos').style.display = 'block';

	var idPadre = document.getElementById('TipoPedido').value;
	var idItem = document.getElementById('DetallePedido').value;

	if (idItem == -1)
		idItem = 0;
	if (idPadre == -1) {
		idPadre = 0;
		idItem = 0;
	}

	BuscarGrillaPermisos('grillaColaboradoresPermisos', UsuarioNombre, idPadre, idItem, sistema, pagina);

	$('#DetallePedido').change(DetallePedidoChange);
	DesactivarGif();
	return true;
}

function EventEditaPermiso(grupoids, motivoid, descpadre, descmotivo) {
	var parametros = '';
	parametros = '?sistema=1';
	parametros += '&Permisos=yes';
	parametros += '&grupoids='+grupoids;
	parametros += '&motivoid='+motivoid;

	parametros += '&idpadre='+descpadre;
	parametros += '&idmotivo='+descmotivo;
	
	parametros += '&MNU=6';
	/*
	urlpag = 'index.php';	
	window.location.href = urlpag+parametros;
	*/
	window.location.href="index.php"+parametros;
}

function IniciaResponsive750(){
	var navigation = responsiveNav(".nav-collapse", {
		animate : true, // Boolean: Use CSS3 transitions, true or false
		transition : 284, // Integer: Speed of the transition, in milliseconds
		label : "Menu", // String: Label for the navigation toggle
		insert : "after", // String: Insert the toggle before or after the navigation
		customToggle : "", // Selector: Specify the ID of a custom toggle
		closeOnNavClick : false, // Boolean: Close the navigation when one of the links are clicked
		openPos : "relative", // String: Position of the opened nav, relative or static
		navClass : "nav-collapse", // String: Default CSS class. If changed, you need to edit the CSS too!
		navActiveClass : "js-nav-active", // String: Class that is added to <html> element when nav is active
		jsClass : "js", // String: 'JS enabled' class which is added to <html> element
		init : function() {
		}, // Function: Init callback
		open : function() {
		}, // Function: Open callback
		close : function() {
		} // Function: Close callback
	});

}
