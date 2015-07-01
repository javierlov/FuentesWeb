function enviarForm() {
	if (validarFormEvento(formEvento))
		with (document) {
			getElementById('tableTipoEvento').style.display = 'none';
			getElementById('tableDescripcion').style.display = 'none';
			getElementById('formEvento').submit();
		}
}

function validarFormEvento(form) {
	if (!ValidarForm(form))
		return false;

	if ((!form.TipoEvento[0].checked) && (!form.TipoEvento[1].checked)) {
		alert('Por favor, indique el tipo de evento.');
    form.TipoEvento[0].focus();
    return false;
  }

	return true;
}