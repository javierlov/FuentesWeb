function enviarForm() {
	if (validarFormObjetivos(formObjetivos))
		with (document) {
			getElementById('tableDatos').style.display = 'none';
			getElementById('formObjetivos').submit();
		}
}

function validarFormObjetivos(form) {
	if (!ValidarForm(form))
		return false;

	if ((!form.MotivoCambio[0].checked) && (!form.MotivoCambio[1].checked) && (!form.MotivoCambio[2].checked) && 
			(!form.MotivoCambio[3].checked) && (!form.MotivoCambio[4].checked)) {
		alert('Por favor, indique el motivo del cambio del objetivo.');
    form.MotivoCambio[0].focus();
    return false;
  }

	if ((form.MotivoCambio[4].checked) && (document.getElementById('MotivoCambioOtros').value == '')) {
		alert('Por favor, detalle el motivo del cambio.');
    document.getElementById('MotivoCambioOtros').focus();
    return false;
  }

	return true;
}