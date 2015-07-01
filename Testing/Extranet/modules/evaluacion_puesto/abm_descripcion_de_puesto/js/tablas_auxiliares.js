function aceptar(tipotabla) {
	with (document) {
		if (!validarItem())
			return;

		getElementById('btnAgregar').style.display = 'block';
		getElementById('btnModificar').style.display = 'block';
		getElementById('btnEliminar').style.display = 'block';
		getElementById('btnAceptar').style.display = 'none';
		getElementById('btnCancelar').style.display = 'none';
		getElementById('item').style.display = 'none';

		window.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/tablas_auxiliares.php?tipotabla=' + tipotabla + '&id=' + getElementById('tipos').value + '&tipoop=' + getElementById('tipoOp').value + '&item=' + getElementById('item').value;
	}
}

function agregar() {
	with (document) {
		getElementById('btnAgregar').style.display = 'none';
		getElementById('btnModificar').style.display = 'none';
		getElementById('btnEliminar').style.display = 'none';
		getElementById('btnAceptar').style.display = 'block';
		getElementById('btnCancelar').style.display = 'block';
		getElementById('item').style.display = 'block';
		getElementById('tipoOp').value = 'A';

		getElementById('item').value = '';
		getElementById('item').focus();
	}
}

function cancelar() {
	with (document) {
		getElementById('btnAgregar').style.display = 'block';
		getElementById('btnModificar').style.display = 'block';
		getElementById('btnEliminar').style.display = 'block';
		getElementById('btnAceptar').style.display = 'none';
		getElementById('btnCancelar').style.display = 'none';
		getElementById('item').style.display = 'none';
	}
}

function eliminar(tipotabla) {
	with (document) {
		if (!validarTipo())
			return;

		if (!confirm('¿ Realmente desea eliminar este item ?'))
			return;

		window.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/tablas_auxiliares.php?tipotabla=' + tipotabla + '&id=' + getElementById('tipos').value + '&tipoop=B';
	}
}

function modificar() {
	with (document) {
		if (!validarTipo())
			return;

		getElementById('btnAgregar').style.display = 'none';
		getElementById('btnModificar').style.display = 'none';
		getElementById('btnEliminar').style.display = 'none';
		getElementById('btnAceptar').style.display = 'block';
		getElementById('btnCancelar').style.display = 'block';
		getElementById('item').style.display = 'block';
		getElementById('tipoOp').value = 'M';

		getElementById('item').value = getElementById('tipos').options[getElementById('tipos').selectedIndex].text;
		getElementById('item').focus();
	}
}

function validarItem() {
	with (document) {
		if (getElementById('item').value == '') {
			alert('Por favor, escriba un texto.');
			getElementById('item').focus();
			return false;
		}
		else
			return true;
	}
}

function validarTipo() {
	with (document) {
		if (getElementById('tipos').value == '') {
			alert('Por favor, seleccione un item.');
			getElementById('tipos').focus();
			return false;
		}
		else
			return true;
	}
}