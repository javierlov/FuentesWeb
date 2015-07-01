function blurInput() {
	cancelarEnter = false;
	ocultarListaEmpleados();
}

function buscarEnListaEmpleados(empleado) {
	if ((empleado != '') && (!ocultarLista))
		with (document)
			getElementById('iframeBusquedaEmpleados').src = '/functions/busqueda_empleados/buscar_empleado.php?e=' + empleado + '&t=' + getElementById('mostrarTelefono').value;
	else
		ocultarListaEmpleados();
}

function focus() {
	cancelarEnter = true;
	buscarEnListaEmpleados(document.getElementById('empleadoLista').value);
}

function keyUp() {
	var keyCode = event.which;
	if (keyCode == undefined)
		keyCode = event.keyCode;

	if (keyCode == 13) {
		itemPosicionado.click();
		ocultarListaEmpleados();
		return false;
	}

	if ((keyCode == 9) || (keyCode == 27))
		ocultarLista = true;

	if ((keyCode == 8) || ((keyCode >= 48) && (keyCode <= 57)) || ((keyCode >= 65) && (keyCode <= 90)) || ((keyCode >= 97) && (keyCode <= 122)))
		ocultarLista = false;


	if (document.getElementById('divBusquedaListaEmpleados').style.display == 'block') {		// Solo le doy bola a las flechas, si se está mostrando la lista..
		if ((keyCode == 39) || (keyCode == 40)) {		// Siguiente..
			if (itemPosicionado == null)
				itemPosicionado = document.getElementById('divBusquedaListaEmpleados').firstChild;
			else {
				itemPosicionado.style.backgroundColor = '';
				itemPosicionado = itemPosicionado.nextSibling.nextSibling;
			}
			itemPosicionado.style.backgroundColor = '#888';
		}
		if ((keyCode == 37) || (keyCode == 38)) {		// Anterior..
			if (itemPosicionado == null)
				itemPosicionado = document.getElementById('divBusquedaListaEmpleados').lastChild.previousSibling;
			else {
				itemPosicionado.style.backgroundColor = '';
				itemPosicionado = itemPosicionado.previousSibling.previousSibling;
			}
			itemPosicionado.style.backgroundColor = '#888';
		}


		if ((keyCode >= 37) && (keyCode <= 40))		// Si apretó alguna flecha, salgo..
			return;
	}

	buscarEnListaEmpleados(document.getElementById('empleadoLista').value);
}

function ocultarListaEmpleados() {
	setTimeout(function(){
		with (document.getElementById('divBusquedaListaEmpleados')) {
			style.display = 'none';

			// Lamentablemente tengo que harcodear lo de abajo para que en la portada se vean bien las cosas que quedan abajo..
			if (parentNode.id == 'divBusquedaEmpleadoCampo')		// Si la búsqueda se hace desde la portada..
				parentNode.style.height = '36px';
		}
	}, 100);
}

function seleccionarEnListaEmpleados(obj) {
	with (document) {
		if (getElementById('urlOnClick').value != '')
			window.parent.location.href = getElementById('urlOnClick').value.replace('<<idusuario>>', obj.nextSibling.value);

		getElementById('empleadoLista').value = obj.innerHTML;
		ocultarListaEmpleados();
	}
}


var cancelarEnter = true;
var cancelKeypress = false;
var itemPosicionado = null;
var ocultarLista = false;

document.onkeydown = function(evt) {
	if (!cancelarEnter)
		return true;

	evt = evt || window.event;
	cancelKeypress = /^(13)$/.test("" + evt.keyCode);
	if (cancelKeypress)
		return false;
};

/* For Opera */
document.onkeypress = function(evt) {
	if (!cancelarEnter)
		return true;

	if (cancelKeypress)
		return false;
};