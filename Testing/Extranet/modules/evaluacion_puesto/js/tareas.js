function agregarTarea() {
	with (document) {
		if (getElementById('queHace').value == "") {
			alert('El campo Acciones es obligatorio.');
			getElementById('queHace').focus();
			return;
		}

		getElementById('tmpAccion').value = 'A';
		getElementById('tmpId').value = -1;
		getElementById('tmpQueHace').value = getElementById('queHace').value;
		getElementById('tmpParaQueLoHace').value = getElementById('paraQueLoHace').value;
		getElementById('tmpComoSeSabeLoQueHizo').value = getElementById('comoSeSabeLoQueHizo').value;
		getElementById('formTarea').submit();

		ocultarBotones();
	}
}

function ajustarTamanoIframeTareas(iFrame) {
	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('GridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('GridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = 72;
	else
		iFrame.height = 48 + (cant * 20);
}

function cancelarTarea() {
	ocultarBotones();
}

function eliminarTarea() {
	if (confirm('¿ Realmente desea eliminar esta tarea ?'))
		with (document) {
			getElementById('tmpAccion').value = 'B';
			getElementById('formTarea').submit();

			ocultarBotones();
		}
}

function modificarTarea() {
	with (document) {
		if (getElementById('queHace').value == "") {
			alert('El campo Acciones es obligatorio.');
			getElementById('queHace').focus();
			return;
		}

		getElementById('tmpAccion').value = 'M';
		getElementById('tmpQueHace').value = getElementById('queHace').value;
		getElementById('tmpParaQueLoHace').value = getElementById('paraQueLoHace').value;
		getElementById('tmpComoSeSabeLoQueHizo').value = getElementById('comoSeSabeLoQueHizo').value;
		getElementById('formTarea').submit();

		ocultarBotones();
	}
}

function ocultarBotones() {
	with (document) {
		getElementById('btnAgregarAccion').style.display = 'inline';
		getElementById('btnModificarAccion').style.display = 'none';
		getElementById('btnCancelarModificacion').style.display = 'none';
		getElementById('btnEliminarRegistro').style.display = 'none';
		
		getElementById('queHace').value = '';
		getElementById('paraQueLoHace').value = '';
		getElementById('comoSeSabeLoQueHizo').value = '';
	}
}