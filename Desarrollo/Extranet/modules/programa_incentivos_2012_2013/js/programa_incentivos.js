function calcularSaldo() {
	var frm = document.getElementById('formPuntos');
	var puntos = 0;

	for (i=0; i<frm.elements.length; i++)
		if (frm.elements[i].type == 'text')
			puntos+= Number(frm.elements[i].value);
	document.getElementById('divSaldo').innerText = Number(document.getElementById('divPuntos').innerText) - puntos;

	if (puntos > Number(document.getElementById('divPuntos').innerText))
		document.getElementById('divSaldo').style.color = '#f00';
	else
		document.getElementById('divSaldo').style.color = '';
}

function guardar(accion) {
	if (accion == 'c') {
		if (!confirm('Está a punto de cerrar su canje de puntos, una vez hecha esta acción usted no podrá reasignar sus puntos.\n\n¿ Confirma la acción ?'))
			return false;
	}

	with (document) {
		getElementById('accion').value = accion;
		getElementById('formPuntos').submit();
	}
}

function validarCaracter(obj, e) {
	opc = false;
	tecla = (document.all)?event.keyCode:e.which;

	if (tecla == 8)
		opc = true;		// tecla backspace
	if (tecla >= 48 && tecla <= 57)
		opc = true;		// sólo numeros

	if (opc)
		setTimeout('calcularSaldo()', 1000)

	return opc;
}