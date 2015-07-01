function cambiarDia(dia) {
	with (document) {
		getElementById('diaCumple').value = parseInt(getElementById('diaCumple').value) + dia;
		getElementById('iframeCambiarDia').src = '/modules/portada/cambiar_dia_cumple.php?d=' + getElementById('diaCumple').value;
	}
}

function cambiarPeriodoCalendario(ano, mes, vistaPrevia) {
	vistaPrevia = (vistaPrevia)?vistaPrevia:false;

	cerrarMenuCalendario = true;
	ocultarPeriodos();

	with (document) {
		getElementById('anoCalendario').value = parseInt(getElementById('anoCalendario').value) + ano;
		getElementById('mesCalendario').value = parseInt(getElementById('mesCalendario').value) + mes;

		if (parseInt(getElementById('mesCalendario').value) < 1) {
			getElementById('anoCalendario').value = parseInt(getElementById('anoCalendario').value) - 1;
			getElementById('mesCalendario').value = 12;
		}

		if (parseInt(getElementById('mesCalendario').value) > 12) {
			getElementById('anoCalendario').value = parseInt(getElementById('anoCalendario').value) + 1;
			getElementById('mesCalendario').value = 1;
		}

		getElementById('iframeCalendario').src = '/modules/portada/cambiar_periodo_calendario.php?a=' + getElementById('anoCalendario').value + '&m=' + getElementById('mesCalendario').value + '&vp=' + iif(vistaPrevia, 't', 'f');
	}
}

function cargarImagen(pos) {
	with (document) {
		getElementById('divFotoPersonalFecha').innerHTML = fechasCumpleanos[pos];

		getElementById('imgFotoPersonal').src = listaImagenes[pos].src;
		getElementById('divFotoPersonal').style.display = 'block';
	}
}

function clicBotonBanner(idImg) {
	// Oculto las imágenes..
	var elements = document.getElementsByClassName('imgBanner');
	for (var i=0; i<elements.length; ++i)
		elements[i].style.display = 'none';

	// Deselecciono los botones..
	var elements = document.getElementsByClassName('divBannerBoton');
	for (var i=0; i<elements.length; ++i)
		elements[i].style.backgroundPosition = '';

	document.getElementById('imgBanner_' + idImg).style.display = 'block';
	document.getElementById('divBannerBoton_' + idImg).style.backgroundPosition = '0 -11px';

	ajustarAltoMenu();
}

function clicBotonNacimiento(idImg) {
	// Oculto las imágenes..
	var elements = document.getElementsByClassName('divNacimientosDatos');
	for (var i=0; i<elements.length; ++i)
		elements[i].style.display = 'none';

	// Deselecciono los botones..
	var elements = document.getElementsByClassName('divNacimientosBoton');
	for (var i=0; i<elements.length; ++i)
		elements[i].style.backgroundPosition = '';

	document.getElementById('divNacimientos_' + idImg).style.display = 'block';
	document.getElementById('divNacimientosBoton_' + idImg).style.backgroundPosition = '0 -11px';

	// Si es menor, ajusto el alto del texto con respecto a la imagen..
//	if ((document.getElementById('divNacimientosLeft_' + idImg).offsetHeight + 32) < document.getElementById('divNacimientosImagen_' + idImg).offsetHeight)
//		document.getElementById('divNacimientosLeft_' + idImg).style.height = (document.getElementById('divNacimientosImagen_' + idImg).offsetHeight + 32) + 'px';

	ajustarAltoMenu();
}

function continuarVotando() {
	with (document) {
		getElementById('divEncuestasContenidoValidacion').style.display = 'none';
		getElementById('divEncuestasFondoValidacion').style.display = 'none';
	}
}

function editar() {
	if (document.getElementById('editarTipo').value == 'a')
		window.location.href = '/articulos-abm/' + document.getElementById('editarId').value;
	if (document.getElementById('editarTipo').value == 'b')
		window.location.href = '/banners-abm/' + document.getElementById('editarId').value;
	if (document.getElementById('editarTipo').value == 'n')
		window.location.href = '/nacimientos-abm/' + document.getElementById('editarId').value;
}

function elegirDiaCumple() {
	with (document) {
		getElementById('diaCumple').value = getNumeroDiasentreFechas(getElementById('fechaActual').value, getElementById('fechaCumple').value);
		getElementById('iframeCambiarDia').src = '/modules/portada/cambiar_dia_cumple.php?d=' + getElementById('diaCumple').value;
	}
}

function limpiarVistaPrevia() {
	if (confirm('¿ Realmente desea limpiar toda la vista previa ?'))
		window.location.href = '/modules/portada/limpiar_vista_previa.php';
}

function mantenerBotonEdicion() {
	ocultarBotonEditar = false;
	document.getElementById('divEditar').style.display = 'block';
}

function mostrarBotonEdicion(obj, tipo, id) {
	var tmp = obj;
	var left = tmp.offsetLeft;
	var top = tmp.offsetTop;

	while (tmp = tmp.offsetParent)
		left+= tmp.offsetLeft;

	tmp = obj;
	while (tmp = tmp.offsetParent)
		top+= tmp.offsetTop;

	document.getElementById('editarTipo').value = tipo;
	document.getElementById('editarId').value = id;

	with (document.getElementById('divEditar')) {
		style.left = left + 'px';
		style.top = top + 'px';
		style.display = 'block';
	}
}

function mostrarBusquedas(mostrar) {
	if (mostrar)
		document.getElementById('divBusquedas').style.display = 'block';
}

function mostrarIngresos(mostrar) {
	if (mostrar)
		document.getElementById('divIngresos').style.display = 'block';
}

function mostrarPasesDeSector(mostrar) {
	if (mostrar)
		document.getElementById('divPasesSector').style.display = 'block';
}

function mostrarPeriodos(mes, ano) {
	with (document)
		getElementById('iframeCalendario').src = '/modules/portada/mostrar_periodos.php?a=' + ano + '&m=' + mes;
}

function moverImagen(obj) {
	var pos = getAbsoluteElementPosition(obj);

	with (document.getElementById('divFotoPersonal').style) {
//		left = (pos.left + 8) + 'px';
//		top = (pos.top + 8) + 'px';
		left = (pos.left) + 'px';
		top = (pos.top + obj.offsetHeight - 4) + 'px';
	}
}

function ocultarBotonEdicion() {
	function ocultar() {
		if (ocultarBotonEditar)
			document.getElementById('divEditar').style.display = 'none';
	}

	ocultarBotonEditar = true;
	setTimeout(function() {ocultar()}, 5000);
}

function ocultarImagen() {
	document.getElementById('divFotoPersonal').style.display = 'none';
}

function ocultarMenuPeriodos() {
	if (cerrarMenuCalendario)
		with (document) {
			getElementById('divPeriodos').style.display = 'none';
			getElementById('divBusquedaEmpleadoCampo').style.zIndex = '99';
			getElementById('divBusquedaEmpleadoFondo').style.zIndex = '99';
		}
}

function ocultarPeriodos() {
	setTimeout('ocultarMenuPeriodos()', 300);
}

function resaltarEvento(dia, resaltar) {
	with (document) {
		getElementById('evento' + dia).style.backgroundColor = iif(resaltar, '#fff', '');
//		getElementById('evento' + dia).style.color = iif(resaltar, '#fff', '');
	}
}

function resaltarFeriado(dia, resaltar) {
	with (document) {
		getElementById('feriado' + dia).style.backgroundColor = iif(resaltar, '#fff', '');
//		getElementById('feriado' + dia).style.color = iif(resaltar, '#fff', '');
	}
}

function setBanners(idImg) {
	var hay = (idImg > -1);
	document.getElementById('divBanner').style.display = (hay)?'block':'none';

	if (hay) {
		clicBotonBanner(idImg);
//		document.getElementById('divBannerBotonera').style.display = 'block';
	}
}

function setEncuestas(cantPreguntas) {
	var hay = (cantPreguntas > 0);
	document.getElementById('divEncuesta').style.display = (hay)?'block':'none';
}

function setImagenesGrandes(w, vistaPrevia) {
	vistaPrevia = (vistaPrevia)?vistaPrevia:false;

	document.getElementById('iframeImagenesGrandes').src = '/modules/portada/cargar_imagenes_grandes.php?w=' + w + '&vp=' + iif(vistaPrevia, 't', 'f');
}

function setNacimientos(idImg) {
	var hay = (idImg > -1);
	document.getElementById('divNacimientos').style.display = (hay)?'block':'none';

	if (hay) {
		clicBotonNacimiento(idImg);
		document.getElementById('divNacimientosBotonera').style.display = 'block';
	}
}

function verResultadosEncuesta() {
	var elements = document.getElementsByClassName('spanEncuestasCantidadVotos');
	for (var i=0; i<elements.length; ++i)
		elements[i].style.display = 'inline';
}


var cerrarMenuCalendario = false;
var dia = '<?= $dia?>';
var fechasCumpleanos = new Array();
var listaImagenes = new Array();
var ocultarBotonEditar = true;