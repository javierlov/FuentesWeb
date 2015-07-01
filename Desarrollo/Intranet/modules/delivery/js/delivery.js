function avisarCierreLocal() {
	with (document)
		if (confirm('¿ Realmente desea avisar que el local ' + getElementById('tmpNombre').value + ' está cerrado ?'))
			iframeProcesando.location.href = '/modules/delivery/avisar_local_cerrado.php?id=' + document.getElementById('id').value + '&rnd=' + Math.random();
}

function cancelar() {
	document.getElementById('divFormAgregarLocal').style.display = 'none';
}

function darBaja() {
	if (confirm('¿ Realmente desea dar de baja este local ?'))
		iframeProcesando.location.href = '/modules/delivery/dar_baja_local.php?id=' + document.getElementById('id').value + '&rnd=' + Math.random();
}

function editarLocal() {
	iframeProcesando.location.href = '/modules/delivery/editar_local.php?id=' + document.getElementById('id').value + '&rnd=' + Math.random();
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAgregarLocal').submit();
	}
}

function mantenerBotones() {
	puedeOcultarBotonesLocal = false;
	document.getElementById('imgEditarLocal').style.display = 'block';
	document.getElementById('imgAlerta').style.display = 'block';
}

function mostrarBotones(obj) {
	puedeOcultarBotonesLocal = false;
	with (document) {
		getElementById('id').value = obj.id.substr(8);
		getElementById('tmpNombre').value = obj.childNodes[1].innerHTML;

		getElementById('imgAlerta').style.left = ((obj.offsetLeft + obj.offsetWidth) * 0.005) + 'px';
		getElementById('imgAlerta').style.top = (obj.offsetTop - obj.offsetHeight + 32) + 'px';
		getElementById('imgAlerta').style.display = 'inline';

		getElementById('imgEditarLocal').style.left = ((obj.offsetLeft + obj.offsetWidth) * 0.96) + 'px';
		getElementById('imgEditarLocal').style.top = (obj.offsetTop - obj.offsetHeight + 32) + 'px';
		getElementById('imgEditarLocal').style.display = 'inline';
	}
}

function mostrarFormularioAgregarLocal() {
	with (document) {
		getElementById('btnDarBaja').style.display = 'none';
		getElementById('divTitulo').innerHTML = 'NUEVO ESTABLECIMIENTO';
		getElementById('direccion').value = '';
		getElementById('id').value = '';
		getElementById('link').value = '';
		getElementById('nombre').value = '';
		getElementById('telefono').value = '';
		getElementById('divFormAgregarLocal').style.display = 'block';
		getElementById('nombre').focus();
	}
}

function ocultarBotonesLocal(obj) {
	function ocultar() {
		if (puedeOcultarBotonesLocal) {
			document.getElementById('imgEditarLocal').style.display = 'none';
			document.getElementById('imgAlerta').style.display = 'none';
		}
	}

	puedeOcultarBotonesLocal = true;
	setTimeout(function() {ocultar()}, 5000);
}


var puedeOcultarBotonesLocal = true;