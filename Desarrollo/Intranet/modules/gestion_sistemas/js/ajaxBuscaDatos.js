//cambio jlovatto 17/06/2015
function objetoAjax() {
	var xmlhttp = false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {

		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}

	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

//--------------------------------------------------------------------------------
function TienePermiso(IDdivResultado, usuario, TicketDetalle) {

	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";
	var strparametros = "funcion=TienePermisoTicket" + "&usuario=" + usuario + "&TicketDetalle=" + TicketDetalle;

	var resultado = ProcesarDatosResultado(pagefunciones, strparametros, IDdivResultado);	
	return document.getElementById(IDdivResultado).innerHTML;
	
}

//--------------------------------------------------------------------------------
function ActualizarPermisos(UsuarioAlta, DetallePedido, USUARIOSLISTA, USUARIOSLISTABAJA) {

	var IDdivResultado = 'DivAreaMensajes';
	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";

	var strparametros = "funcion=ActualizarPermisos" + "&UsuarioAlta=" + UsuarioAlta + "&DetallePedido=" + DetallePedido + "&USUARIOSLISTA=" + USUARIOSLISTA + "&USUARIOSLISTABAJA=" + USUARIOSLISTABAJA;

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
	return true;
}

//--------------------------------------------------------------------------------
function AgregarParametroHHMMSS() {
	var f = new Date();
	return "&check=" + f.getHours() + f.getMinutes() + f.getSeconds();
}

//--------------------------------------------------------------------------------
function EliminarPermisoGrupo(grupoIDs) {

	var IDdivResultado = 'grillaColaboradoresPermisos'
	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";
	/*
	 $IDMOTIVOSOLICITUD = GetParametroDecode("IDMOTIVOSOLICITUD");
	 $USUARIO = GetParametroDecode("USUARIO");
	 */
	var strparametros = "funcion=EliminarPermisoGrupo" + "&IDgroup='" + grupoIDs + "'" + AgregarParametroHHMMSS();

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
}

//--------------------------------------------------------------------------------
function BuscarGrillaPermisos(IDdivResultado, UsuarioSolicitud, idPadre, idItem, sistema, pagina) {

	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";

	var strparametros = "funcion=GrillaPermisosGenerales" + "&UsuarioSolicitud=" + encodeURIComponent(UsuarioSolicitud) + "&idPadre=" + encodeURIComponent(idPadre) + "&idItem=" + encodeURIComponent(idItem) + "&sistema=" + encodeURIComponent(sistema) + "&pagina=" + encodeURIComponent(pagina) + AgregarParametroHHMMSS();

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
	return true;
}

//--------------------------------------------------------------------------------
function BuscarDatosUsuarioGrid(IDdivResultado, UsuarioSolicitud, sistema, motivos, idsolicitud, ArraySeleccion, pagina) {
	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";

	var strparametros = "funcion=DatosUsuarioGrid" + "&UsuarioSolicitud=" + encodeURIComponent(UsuarioSolicitud) + "&sistema=" + encodeURIComponent(sistema) + "&motivos=" + encodeURIComponent(motivos) + "&idsolicitud=" + encodeURIComponent(idsolicitud) + "&ArraySeleccion=" + encodeURIComponent(ArraySeleccion) + "&pagina=" + encodeURIComponent(pagina) + AgregarParametroHHMMSS();

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
}

//--------------------------------------------------------------------------------
function BuscarGrillaPedidos(IDdivResultado, all_tickets, pending_tickets, pending_moreinfo_tickets, pending_auth_tickets, numeroTicket, fechaDesde, fechaHasta, ss_notas, PlanAccion, TipoPedido, DetallePedido, employees, sistema, back_button, close_button, pagina, MNUselect) {

	var pagefunciones = "/modules/gestion_sistemas/ticket_funciones.php";

	var strparametros = "funcion=GrillaPedidos" + "&all_tickets=" + encodeURIComponent(all_tickets) + "&pending_tickets=" + encodeURIComponent(pending_tickets) + "&pending_moreinfo_tickets=" + encodeURIComponent(pending_moreinfo_tickets) + "&pending_auth_tickets=" + encodeURIComponent(pending_auth_tickets) + "&numeroTicket=" + encodeURIComponent(numeroTicket) + "&fechaDesde=" + encodeURIComponent(fechaDesde) + "&fechaHasta=" + encodeURIComponent(fechaHasta) + "&ss_notas=" + encodeURIComponent(ss_notas) + "&PlanAccion=" + encodeURIComponent(PlanAccion) + "&TipoPedido=" + encodeURIComponent(TipoPedido) + "&DetallePedido=" + encodeURIComponent(DetallePedido) + "&employees=" + encodeURIComponent(employees) + "&sistema=" + sistema + "&back_button=" + encodeURIComponent(back_button) + "&close_button=" + encodeURIComponent(close_button) + "&pagina=" + encodeURIComponent(pagina) + "&MNUselect=" + encodeURIComponent(MNUselect) + AgregarParametroHHMMSS();

	ProcesarDatos(pagefunciones, strparametros, IDdivResultado);
	return true;
}

//--------------------------------------------------------------------------------
function ProcesarDatos(pagefunciones, strparametros, IDdivResultado) {
	//ActivaGif();
	/*
	 console.log('ProcesarDatos');
	 console.log(pagefunciones+'?'+strparametros);
	 console.log(strparametros);
	 */

	var divResultado = document.getElementById(IDdivResultado);
	var resultadoDatos = 'FALLO. Intente nuevamente. (ProcesarDatos)';
	divResultado.innerHTML = 'Iniciando... ';
	ajax = objetoAjax();
	try {
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				if (ajax.status == 200) {
					var grillahtml = ajax.responseText;
					resultadoDatos = 'OK';
					divResultado.innerHTML = grillahtml;
				} else {
					divResultado.innerHTML = 'Error ' + ajax.statusText;
				}
			} else {
				divResultado.innerHTML = "<div style='font: 16px Tahoma;font-weight: bold;	color:gray; padding: 28px 5px;' >Procesando... <div>";
			}
		}
		/*
		 ajax.open("GET", pagefunciones+'?'+strparametros,false);
		 ajax.send(null);
		 */
		ajax.open("GET", pagefunciones + '?' + strparametros);
		ajax.send();
	} catch(e) {
		divResultado.innerHTML = "Error. " + e.message + " ";
		;
		return false;
	}

	if (resultadoDatos.trim() == 'OK') {
		//divResultado.innerHTML = '';
		return true;
	} else {
		// divResultado.innerHTML = resultadoDatos.trim();
		//MostrarError('', '', $('#'+IDdivResultado), resultadoDatos);
		return false;
	}
}

//--------------------------------------------------------------------------------
function ProcesarDatosResult(pagefunciones, strparametros, IDdivResultado) {

	var divResultado = document.getElementById(IDdivResultado);
	var resultadoDatos = 'FALLO. Intente nuevamente. (ProcesarDatosResult)';
	divResultado.innerHTML = 'Iniciando... ';
	ajax = objetoAjax();
	try {
		ajax.onreadystatechange = function() {

			divResultado.innerHTML = 'Procesando... ';

			if (ajax.readyState == 0) {
				divResultado.innerHTML = 'No inicializado... ';
			}
			if (ajax.readyState == 1) {
				divResultado.innerHTML = 'Cargando ... ';
			}
			if (ajax.readyState == 2) {
				divResultado.innerHTML = 'Cargado  ... ';
			}
			if (ajax.readyState == 3) {
				divResultado.innerHTML = 'Interactivo   ... ';
			}

			if (ajax.readyState == 4) {
				if (ajax.status == 200) {
					var grillahtml = ajax.responseText;
					resultadoDatos = grillahtml;
					divResultado.innerHTML = grillahtml;
					return true;
					
				} else {
					divResultado.innerHTML = 'Error ' + ajax.statusText;
					return false;
				}
			}
		}
		
		ajax.open("GET", pagefunciones + '?' + strparametros);
		ajax.send();
		
	} catch(e) {
		divResultado.innerHTML = "Error. " + e.message + " ";		
		return false;
	}
	/*
	 if(resultadoDatos.trim() == 'OK'){
		return true;
	 }else{	 
		divResultado.innerHTML = '';
		return false;
	 }
	*/
}


function ProcesarDatosResultado(pagefunciones, strparametros, IDdivResultado) {

	var divResultado = document.getElementById(IDdivResultado);
	var resultadoDatos = 'FALLO. Intente nuevamente. (ProcesarDatosResult)';
	divResultado.innerHTML = 'Iniciando... ';
	ajax = objetoAjax();
	try {
		ajax.onreadystatechange = function() {

			divResultado.innerHTML = 'Procesando... ';

			if (ajax.readyState == 0) {
				divResultado.innerHTML = 'No inicializado... ';
			}
			if (ajax.readyState == 1) {
				divResultado.innerHTML = 'Cargando ... ';
			}
			if (ajax.readyState == 2) {
				divResultado.innerHTML = 'Cargado  ... ';
			}
			if (ajax.readyState == 3) {
				divResultado.innerHTML = 'Interactivo   ... ';
			}

			if (ajax.readyState == 4) {
				if (ajax.status == 200) {
					var grillahtml = ajax.responseText;
					resultadoDatos = grillahtml;
					divResultado.innerHTML = grillahtml;
					return 'OK';
					
				} else {
					divResultado.innerHTML = 'Error ' + ajax.statusText;
					return 'FALLO';
				}
			}
		}
		
		ajax.open("POST", pagefunciones + '?' + strparametros, false);
		ajax.send();
		
	} catch(e) {
		divResultado.innerHTML = "Error. " + e.message + " ";		
		return 'FALLO';
	}
	
}